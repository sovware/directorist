<?php

namespace Directorist\Utils;

\defined('ABSPATH') || exit;
use Exception;

class Helpers {
    /**
     * Retrieves the version of a specified plugin by its slug.
     *
     * @param string $plugin_slug The slug of the plugin whose version is to be retrieved.
     *
     * @return string|null The plugin version if found, or null if the plugin file is missing or version is not specified.
     */
    public static function get_plugin_version(string $plugin_slug)
    {
        // Define the path to the plugins directory
        $plugin_dir = WP_PLUGIN_DIR . '/' . $plugin_slug;
        // Construct the path to the main plugin file
        $main_file = $plugin_dir . '/' . $plugin_slug . '.php';
        // Check if the file exists
        if (!\file_exists($main_file)) {
            return null;
            // Plugin main file not found
        }
        // Read the file contents
        $file_contents = \file_get_contents($main_file);
        // Use a regex to find the version
        if (\preg_match('/^\\s*\\* Version:\\s*(.+)$/mi', $file_contents, $matches)) {
            return \trim($matches[1]);
        }
        return null;
        // Version not found
    }
    /**
     * Uploads a file to the WordPress media library and optionally creates an attachment.
     *
     * @param array $file              The file array, typically from $_FILES, containing 'name', 'type', 'tmp_name', 'error', and 'size'.
     * @param bool  $create_attachment Optional. Whether to create a WordPress attachment for the uploaded file. Default true.
     *
     * @return array|int The uploaded file's details if $create_attachment is false, or the attachment ID if true.
     * 
     * @throws Exception If the file upload fails.
     */
    public static function upload_file(array $file, bool $create_attachment = \true)
    {
        // Use WordPress function to handle file upload.
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $upload_overrides = ['test_form' => \false];
        // Skip "form" field check.
        $move_file = wp_handle_upload($file, $upload_overrides);
        //if error throw exception
        if (isset($move_file['error'])) {
            throw new Exception($move_file['error'], 500);
        }
        if (!$create_attachment) {
            return $move_file;
        }
        // File upload successful. Create an attachment.
        $attachment = ['guid' => $move_file['url'], 'post_mime_type' => $move_file['type'], 'post_title' => sanitize_file_name($file['name']), 'post_content' => '', 'post_status' => 'inherit'];
        // Insert the attachment into the WordPress database.
        $attachment_id = wp_insert_attachment($attachment, $move_file['file']);
        // Generate attachment metadata and update.
        $attachment_metadata = wp_generate_attachment_metadata($attachment_id, $move_file['file']);
        wp_update_attachment_metadata($attachment_id, $attachment_metadata);
        return $attachment_id;
    }
    /**
     * Deletes attachments from the WordPress media library by their IDs.
     *
     * @param int|int[] $attachment_ids The ID or an array of IDs of the attachments to delete.
     *
     * @return int[] An array of IDs of successfully deleted attachments.
     */
    public static function delete_attachments_by_ids($attachment_ids)
    {
        if (!\is_array($attachment_ids)) {
            $attachment_ids = [$attachment_ids];
        }
        $deleted_attachments = [];
        foreach ($attachment_ids as $attachment_id) {
            if (wp_delete_attachment($attachment_id, \true)) {
                $deleted_attachments[] = $attachment_id;
            }
        }
        return $deleted_attachments;
    }
    /**
     * Create and populate a new WP_REST_Request instance.
     *
     * This method sets up a new REST API request, populating it with data from
     * global variables like `$_GET`, `$_POST`, `$_FILES`, and `$_SERVER`.
     *
     * @return \WP_REST_Request A new request object populated with the relevant data.
     */
    public static function request()
    {
        $request = new \WP_REST_Request('POST', '/');
        $server = new \WP_REST_Server();
        // Populate query parameters from GET data.
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $request->set_query_params(wp_unslash($_GET));
        // Populate body parameters from POST data.
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $request->set_body_params(wp_unslash($_POST));
        // Set file parameters.
        $request->set_file_params($_FILES);
        // Populate headers from server data.
        $request->set_headers($server->get_headers(wp_unslash($_SERVER)));
        // Set raw body data.
        $request->set_body($server->get_raw_data());
        return $request;
    }
    /**
     * Decodes a JSON string if possible, otherwise returns the original value.
     *
     * @param mixed $value The value to decode. Typically a JSON string or other data type.
     *
     * @return mixed The decoded value if the input was valid JSON, or the original value otherwise.
     */
    public static function maybe_json_decode($value)
    {
        // Check if the input is a string; JSON must be a string
        if (\is_string($value)) {
            // Try to decode the JSON string
            $decoded = \json_decode($value, \true);
            // Check if json_decode succeeded and the result is not null unless the value is explicitly "null"
            if (\json_last_error() === \JSON_ERROR_NONE) {
                return $decoded;
            }
        }
        // Return the original value if it's not JSON or not a string
        return $value;
    }
    /**
     * Check if the given array is a valid one-level array.
     *
     * A valid one-level array means that all elements in the array are not arrays themselves.
     *
     * @param array $array The array to check.
     *
     * @return bool True if it is a one-level array, false otherwise.
     */
    public static function is_one_level_array(array $array)
    {
        foreach ($array as $value) {
            if (\is_array($value)) {
                return \false;
                // Found an inner array, not a one-level array
            }
        }
        return \true;
        // All values are not arrays, it is a valid one-level array
    }
    /**
     * Recursively merges two arrays. If values in both arrays have the same key and are arrays themselves, 
     * they are merged recursively. Otherwise, the value from the second array overwrites the value from the first.
     *
     * @param array $array1 The first array to merge.
     * @param array $array2 The second array to merge. Values from this array will overwrite those in the first array.
     *
     * @return array The merged array.
     */
    public static function array_merge_deep($array1, $array2)
    {
        foreach ($array2 as $key => $value) {
            if (\is_array($value) && isset($array1[$key]) && \is_array($array1[$key])) {
                $array1[$key] = Helpers::array_merge_deep($array1[$key], $value);
            } else {
                $array1[$key] = $value;
            }
        }
        return $array1;
    }
    /**
     * Remove all elements with null values from the array.
     *
     * This function filters out all keys with null values from the given array.
     *
     * @param array $array The array to filter.
     *
     * @return array The array with null values removed.
     */
    public static function remove_null_values(array $array)
    {
        foreach ($array as $key => $value) {
            if (\is_null($value)) {
                unset($array[$key]);
                // Remove element with null value
            }
        }
        return $array;
    }
    /**
     * Get the user's IP address from server variables.
     *
     * This function checks various server variables such as `HTTP_CLIENT_IP`, `HTTP_X_FORWARDED_FOR`, and `REMOTE_ADDR`
     * to retrieve the most accurate client IP address, accounting for proxies and forwarded IPs.
     *
     * @return string|null The user's IP address or null if it can't be determined.
     */
    public static function get_user_ip_address()
    {
        // Check for IP from shared Internet/ISP (HTTP_CLIENT_IP)
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && \filter_var($_SERVER['HTTP_CLIENT_IP'], \FILTER_VALIDATE_IP)) {
            // Sanitize and return the IP address
            return sanitize_text_field(wp_unslash($_SERVER['HTTP_CLIENT_IP']));
        }
        // Check for IP addresses passed through proxies (HTTP_X_FORWARDED_FOR)
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Sanitize and explode the list of IPs from the forwarded header
            $ip_addresses = \explode(',', sanitize_text_field(wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR'])));
            // Loop through each IP address and return the first valid one
            foreach ($ip_addresses as $ip) {
                $ip = \trim($ip);
                if (\filter_var($ip, \FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        // Check for the remote IP address (REMOTE_ADDR)
        if (!empty($_SERVER['REMOTE_ADDR']) && \filter_var($_SERVER['REMOTE_ADDR'], \FILTER_VALIDATE_IP)) {
            // Sanitize and return the remote IP address
            return sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
        }
        // Return null if no valid IP address is found
        return null;
    }
}
