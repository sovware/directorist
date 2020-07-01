<?php
defined('ABSPATH') || die('Direct access is not allowed.');
/**
 * @since 4.7.2
 * @package Directorist
 */
if (!class_exists('ATBDP_Tools')) :

    class ATBDP_Tools
    {


        /**
         * The path to the current file.
         *
         * @var string
         */
        protected $file = '';

        /**
         * Whether to skip existing products.
         *
         * @var bool
         */
        protected $update_existing = false;

        /**
         * The current delimiter for the file being read.
         *
         * @var string
         */
        protected $delimiter = ',';


        public function __construct()
        {
            add_action('admin_menu', array($this, 'add_tools_submenu'), 10);
            add_action('admin_init', array($this, 'atbdp_csv_import_controller'));
            $this->file            = isset($_REQUEST['file']) ? wp_unslash($_REQUEST['file']) : '';
            $this->update_existing = isset($_REQUEST['update_existing']) ? (bool) $_REQUEST['update_existing'] : false;
            $this->delimiter       = !empty($_REQUEST['delimiter']) ? wp_unslash($_REQUEST['delimiter']) : ',';
            add_action('wp_ajax_atbdp_import_listing', array($this, 'atbdp_import_listing'));
        }


        public function atbdp_import_listing()
        {
            $data = array();
            // Get the data from all those CSVs!
            $posts = $this->csv_get_data($this->file, true);
            // get the mapped data
            $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
            $description = isset($_POST['description']) ? sanitize_text_field($_POST['description']) : '';
            $metas = isset($_POST['meta']) ? atbdp_sanitize_array($_POST['meta']) : array();
            $imported = 1;
            $failed = 1;
            $skipped = 1;
            foreach ($posts as $post) {
                $post_id = wp_insert_post(array(
                    "post_title"   => $post[$title],
                    "post_content" => $post[$description],
                    "post_type"    => 'at_biz_dir',
                    "post_status"  => "publish"
                ));
                foreach ($metas as $index => $value) {
                    $meta_value = $post[$index] ? $post[$index] : '';
                    update_post_meta($post_id, $value, $meta_value);
                }
                $exp_dt = calc_listing_expiry_date();
                update_post_meta($post_id, '_expiry_date', $exp_dt);
                update_post_meta($post_id, '_featured', 0);
                update_post_meta($post_id, '_listing_status', 'post_status');
               // update_post_meta($post_id, '_admin_category_select', $admin_category_select);
                if (!is_wp_error($post_id)) {
                    $data['imported'] = $imported++;
                } else {
                    $data['failed'] = $failed++;
                }
            }
            wp_send_json($data);
            die();
        }


        public function atbdp_csv_import_controller()
        {
            // Displaying this page triggers Ajax action to run the import with a valid nonce,
            // therefore this page needs to be nonce protected as well.
            // step one

            // $post = new WP_Query(array(
            //     'post_type' => ATBDP_POST_TYPE,
            //     'posts_per_page' => -1
            // ));
            // foreach($post->posts as $post){
            //     wp_delete_post($post->ID, true);
            // }
            // var_dump(admin_url());
            if (isset($_POST['atbdp_save_csv_step'])) {
                check_admin_referer('directorist-csv-importer');
                // redirect to step two || data mapping
                $file = wp_import_handle_upload();
                $file = $file['file'];
                $url = admin_url() . "edit.php?post_type=at_biz_dir&page=tools&step=2";

                $params = array(
                    'step'            => 2,
                    'file'            => str_replace(DIRECTORY_SEPARATOR, '/', $file),
                    'delimiter'       => $this->delimiter,
                    'update_existing' => $this->update_existing,
                    // 'map_preferences' => $this->map_preferences,
                    // '_wpnonce'        => wp_create_nonce( 'directorist-csv-importer' ), // wp_nonce_url() escapes & to &amp; breaking redirects.
                );
                wp_safe_redirect(add_query_arg($params, $url));
            }
        }

        private function insert_post($post)
        {
            $post["id"] = wp_insert_post(array(
                "post_title"   => $post["title"],
                "post_content" => $post["description"],
                "post_type"    => 'at_biz_dir',
                "post_status"  => "publish"
            ));

            // // Get uploads dir
            // $uploads_dir = wp_upload_dir();

            // // Set attachment meta
            // $attachment = array();
            // $attachment["path"] = "{$uploads_dir["baseurl"]}/sitepoint-attachments/{$post["attachment"]}";
            // $attachment["file"] = wp_check_filetype($attachment["path"]);
            // $attachment["name"] = basename($attachment["path"], ".{$attachment["file"]["ext"]}");

            // // Replace post attachment data
            // $post["attachment"] = $attachment;

            // // Insert attachment into media library
            // $post["attachment"]["id"] = wp_insert_attachment(array(
            //     "guid"           => $post["attachment"]["path"],
            //     "post_mime_type" => $post["attachment"]["file"]["type"],
            //     "post_title"     => $post["attachment"]["name"],
            //     "post_content"   => "",
            //     "post_status"    => "inherit"
            // ));
        }

        private function csv_get_data($default_file = null, $multiple = null)
        {
            $data = $multiple ? array() : '';
            $errors = array();
            // Get array of CSV files
            $file = $default_file ? $default_file : $this->file;
            if (!$file) return;

            // Attempt to change permissions if not readable
            if (!is_readable($file)) {
                chmod($file, 0744);
            }

            // Check if file is writable, then open it in 'read only' mode
            if (is_readable($file) && $_file = fopen($file, "r")) {

                // To sum this part up, all it really does is go row by
                //  row, column by column, saving all the data
                $post = array();

                // Get first row in CSV, which is of course the headers
                $header = fgetcsv($_file);

                while ($row = fgetcsv($_file)) {

                    foreach ($header as $i => $key) {
                        $post[$key] = $row[$i];
                    }

                    if ($multiple) {
                        $data[] = $post;
                    } else {
                        $data = $post;
                    }
                }

                fclose($_file);
            } else {
                $errors[] = "File '$file' could not be opened. Check the file's permissions to make sure it's readable by your server.";
            }
            if (!empty($errors)) {
                // ... do stuff with the errors
            }
            return $data;
        }

        private function importable_fields()
        {
            return apply_filters('atbdp_csv_listing_import_mapping_default_columns', array(
                'id'                => __('ID', 'directorist'),
                'title'             => __('Title', 'directorist'),
                'description'       => __('Description', 'directorist'),
                '_never_expire'       => __('Never Expire', 'directorist'),
                '_expiry_date'       => __('Expiration Date', 'directorist'),
                '_tagline'           => __('Tagline', 'directorist'),
                '_price'             => __('Price', 'directorist'),
                '_price_range'       => __('Price Range', 'directorist'),
                '_atbdp_post_views_count'        => __('View Count', 'directorist'),
                '_excerpt'           => __('Excerpt', 'directorist'),
                'location'          => __('Location', 'directorist'),
                'tag'               => __('Tag', 'directorist'),
                'category'          => __('Category', 'directorist'),
                '_hide_contact_info' => __('Hide Contact Info', 'directorist'),
                '_address'           => __('Address', 'directorist'),
                '_manual_lat'          => __('Latitude', 'directorist'),
                '_manual_lng'         => __('Longitude', 'directorist'),
                '_hide_map'         => __('Hide Map', 'directorist'),
                '_zip'         => __('Zip/Post Code', 'directorist'),
                '_phone'             => __('Phone', 'directorist'),
                '_phone2'         => __('Phone Two', 'directorist'),
                '_fax'               => __('Fax', 'directorist'),
                '_email'             => __('Email', 'directorist'),
                '_website'           => __('Website', 'directorist'),
                '_listing_prv_img'     => __('Preview Image', 'directorist'),
                '_videourl'             => __('Video', 'directorist'),
                '_fm_plans'      => __('Pricing Plan (Requires Pricing Plan Extension)', 'directorist'),
                '_claimed_by_admin'        => __('Claimed (Requires Claim Listing Extension)', 'directorist'),
            ));
        }

        /**
         * It adds a submenu for showing all the Tools and details support
         */
        public function add_tools_submenu()
        {
            add_submenu_page('edit.php?post_type=at_biz_dir', __('Tools', 'directorist'), __('Tools', 'directorist'), 'manage_options', 'tools', array($this, 'render_tools_submenu_page'));
        }

        public function render_tools_submenu_page()
        {
            ATBDP()->load_template('tools',  array('data' => $this->csv_get_data(), 'fields' => $this->importable_fields()));
        }
    }

endif;
