<?php
/**
 * ATBDP Extensions class
 *
 * This class is for interacting with Extensions eg. showing extensions lists
 *
 * @package     ATBDP
 * @subpackage  inlcudes/classes Extensions
 * @copyright   Copyright (c) 2018, AazzTech
 * @since       1.0
 */


// Exit if accessed directly
if ( ! defined('ABSPATH') ) { die( 'Direct access is not allowed.' ); }

if ( ! class_exists('ATBDP_Extensions') ) {

    /**
     * Class ATBDP_Extensions
     */
    class ATBDP_Extensions
    {
        public function __construct()
        {
            add_action( 'admin_menu', array($this, 'admin_menu'), 100 );
            
            // Ajax
            add_action( 'wp_ajax_atbdp_authenticate_the_customer', array($this, 'authenticate_the_customer') );
            add_action( 'wp_ajax_atbdp_download_purchased_items', array($this, 'download_purchased_items') );
            add_action( 'wp_ajax_atbdp_plugins_bulk_action', array($this, 'plugins_bulk_action') );
            add_action( 'wp_ajax_atbdp_update_plugins', array($this, 'update_plugins') );
        }

        // update_plugins
        public function update_plugins() {
            $status = [ 'success' => true ];
            $plugin_item = ( isset( $_POST['plugin_item'] ) ) ? $_POST['plugin_item'] : '';

            $plugin_updates       = get_site_transient( 'update_plugins' );
            $outdated_plugins     = $plugin_updates->response;
            $outdated_plugins_key = array_keys( $outdated_plugins );

            if ( empty( $outdated_plugins_key ) ) { 
                $status['massage'] = __( 'All plugins are up to date', 'directorist' );
                wp_send_json( [ 'status' => $status ] );
            }

            if ( ! empty( $plugin_item ) && ! in_array( $plugin_item, $outdated_plugins_key )  ) {
                $status['massage'] = __( 'The plugin is up to date', 'directorist' );
                wp_send_json( [ 'status' => $status ] );
            }

            if ( ! empty( $plugin_item ) ) {
                $outdated_plugin = $outdated_plugins[ $plugin_item ];
                $this->download_plugin( [ 'url' => $outdated_plugin->package ] );

                $status['massage'] = __( 'The plugin has been updated successfully', 'directorist' );
                wp_send_json( [ 'status' => $status ] );
            }

            // Update all
            foreach ( $outdated_plugins as $plugin_base => $plugin ) {
                $this->download_plugin( [ 'url' => $plugin->package ] );
            }

            $status['massage'] = __( 'All the plugins are updated successfully', 'directorist' );
            wp_send_json( [ 'status' => $status ] );
        }

        // plugins_bulk_action
        public function plugins_bulk_action() {
            $status = [ 'success' => true ];

            $task = ( isset( $_POST['task'] ) ) ? $_POST['task'] : '';
            $plugin_items = ( isset( $_POST['plugin_items'] ) ) ? $_POST['plugin_items'] : '';

            // Validation
            if ( empty( $task ) ) {
                $status['success'] = false;
                $status['message'] = 'No task found';
                wp_send_json([ 'status' => $status ]);
            }

            if ( empty( $plugin_items ) ) {
                $status['success'] = false;
                $status['message'] = 'No plugin items found';
                wp_send_json([ 'status' => $status ]);
            }

            // Activate
            if ( 'activate' === $task ) {
                foreach ( $plugin_items as $plugin ) {
                    activate_plugin( $plugin );
                }
            }

            // Deactivate
            if ( 'deactivate' === $task ) {
                deactivate_plugins( $plugin_items );
            }

            // Uninstall
            if ( 'uninstall' === $task ) {
                delete_plugins( $plugin_items );
            }

            wp_send_json([ 'status' => $status,  ]);
        }

        // get_license_authentication
        public function get_license_authentication() {
            $status = [ 'success' => true, 'log' => [] ];
            
            // Get form data
            $username = ( isset( $_POST['username'] ) ) ? $_POST['username'] : '';
            $password = ( isset( $_POST['password'] ) ) ? $_POST['password'] : '';

            // Validate username
            if ( empty( $username ) ) {
                $status['success'] = false;
                $status[ 'log' ]['username_missing'] = [
                    'type'    => 'error',
                    'message' => 'Username is required',
                ];
            }

            // Validate password
            if ( empty( $password ) ) {
                $status['success'] = false;
                $status[ 'log' ]['password_missing'] = [
                    'type'    => 'error',
                    'message' => 'Password is required',
                ];
            }

            if ( ! $status['success'] ) {
                wp_send_json( $status );
            }

            // Get licencing data
            $url_base = 'https://directorist.com/wp-json/directorist/v1/licencing';
            $args     = '?user=' . $username;
            $args    .= '&password=' . $password;
            $url      = $url_base . $args;

            $response = wp_remote_get( $url);
            $response_body = ( 'string' === gettype( $response['body'] ) ) ? json_decode( $response['body'], true ) : $response['body'];

            // Validate response
            if ( ! $response_body['success'] ) {
                $status['success'] = false;
                $status['massage'] = $response_body['massage'];

                wp_send_json([ 'status' => $status, 'response_body' => $response_body ]);
            }

            $license_data = $response_body['license_data'];
            // update_user_meta( get_current_user_id(), '_atbdp_license_data', $license_data );

            // Activate the licenses
            $activation_base_url = 'https://directorist.com?edd_action=activate_license&url=' . home_url();
            
            // Activate the Extensions
            $purchased_extensions = [];
            $invalid_purchased_extensions = [];

            if ( ! empty( $license_data[ 'plugins' ] ) ) {
                foreach( $license_data[ 'plugins' ] as $extension ) {
                    $item_id        = $extension['item_id'];
                    $license        = ( ! empty( $response_body['all_access'] ) ) ? $response_body['active_licenses'][0] : $extension['license'];
                    $query_args     = "&item_id={$item_id}&license={$license}";
                    $activation_url = $activation_base_url . $query_args;

                    $activation_response = wp_remote_get( $activation_url );

                    if ( empty( $activation_response['success'] ) ) {
                        $invalid_purchased_extensions[] = $extension;
                        continue;
                    }

                    $purchased_extensions[] = $extension;
                }
            }

            // Activate the Themes
            $purchased_themes = [];
            $invalid_purchased_themes = [];

            if ( ! empty( $license_data[ 'themes' ] ) ) {
                foreach( $license_data[ 'themes' ] as $theme ) {
                    $item_id        = $theme['item_id'];
                    $license        = ( ! empty( $response_body['all_access'] ) ) ? $response_body['active_licenses'][0] : $theme['license'];
                    $query_args     = "&item_id={$item_id}&license={$license}";
                    $activation_url = $activation_base_url . $query_args;

                    $activation_response = wp_remote_get( $activation_url );

                    if ( empty( $activation_response['success'] ) ) {
                        $invalid_purchased_themes[] = $theme;
                        continue;
                    }

                    $purchased_themes[] = $theme;
                }
            }

            $status[ 'success' ] = true;
            wp_send_json([ 
                'status'       => $status,
                'license_data' => $license_data,
                'response'     => $response_body,
            ]);
        }

        // authenticate_the_customer
        public function authenticate_the_customer() {
            $status = [ 'success' => true, 'log' => [] ];
            
            // Get form data
            $username = ( isset( $_POST['username'] ) ) ? $_POST['username'] : '';
            $password = ( isset( $_POST['password'] ) ) ? $_POST['password'] : '';

            // Validate username
            if ( empty( $username ) ) {
                $status['success'] = false;
                $status[ 'log' ]['username_missing'] = [
                    'type'    => 'error',
                    'message' => 'Username is required',
                ];
            }

            // Validate password
            if ( empty( $password ) ) {
                $status['success'] = false;
                $status[ 'log' ]['password_missing'] = [
                    'type'    => 'error',
                    'message' => 'Password is required',
                ];
            }

            if ( ! $status['success'] ) {
                wp_send_json( [ 'status' => $status ] );
            }

            // Get licencing data
            $url_base = 'https://directorist.com/wp-json/directorist/v1/licencing';
            $args     = '?user=' . $username;
            $args    .= '&password=' . $password;
            $url      = $url_base . $args;

            $response = wp_remote_get( $url);
            $response_body = ( 'string' === gettype( $response['body'] ) ) ? json_decode( $response['body'], true ) : $response['body'];

            // Validate response
            if ( ! $response_body['success'] ) {
                $status['success'] = false;
                $status['massage'] = $response_body['massage'];
                $status[ 'log' ]['unknown_error'] = [
                    'type'    => 'error',
                    'message' => $response_body['massage'],
                ];

                wp_send_json([ 'status' => $status, 'response_body' => $response_body ]);
            }

            $license_data = $response_body['license_data'];
            update_user_meta( get_current_user_id(), '_atbdp_customer_id', $username );

            $customers_purchased = $this->get_customers_purchased( $license_data );

            $status[ 'success' ] = true;
            $status[ 'log' ]['login_successful'] = [
                'type'    => 'success',
                'message' => 'Login is successful',
            ];

            wp_send_json([ 'status' => $status, 'customers_purchased' => $customers_purchased ]);
        }

        // get_customers_purchased
        public function get_customers_purchased( $license_data ) {
            // Activate the licenses
            // $activation_base_url = 'https://directorist.com?edd_action=activate_license&url=' . home_url();
            $activation_base_url = 'https://directorist.com?edd_action=activate_license';
            
            // Activate the Extensions
            $purchased_extensions = [];
            $invalid_purchased_extensions = [];

            if ( ! empty( $license_data[ 'plugins' ] ) ) {
                foreach( $license_data[ 'plugins' ] as $extension ) {
                    $item_id        = $extension['item_id'];
                    $license        = ( ! empty( $response_body['all_access'] ) ) ? $response_body['active_licenses'][0] : $extension['license'];
                    $query_args     = "&item_id={$item_id}&license={$license}";
                    $activation_url = $activation_base_url . $query_args;

                    $response        = wp_remote_get( $activation_url );
                    $response_status = json_decode( $response['body'], true );

                    if ( empty( $response_status['success'] ) ) {
                        $invalid_purchased_extensions[] = [ 'extension' => $extension, 'response' => $response_status ];
                        continue;
                    }

                    $purchased_extensions[] = $extension;
                }
            }

            // Activate the Themes
            $purchased_themes = [];
            $invalid_purchased_themes = [];

            if ( ! empty( $license_data[ 'themes' ] ) ) {
                foreach( $license_data[ 'themes' ] as $theme ) {
                    $item_id        = $theme['item_id'];
                    $license        = ( ! empty( $response_body['all_access'] ) ) ? $response_body['active_licenses'][0] : $theme['license'];
                    $query_args     = "&item_id={$item_id}&license={$license}";
                    $activation_url = $activation_base_url . $query_args;

                    $response        = wp_remote_get( $activation_url );
                    $response_status = json_decode( $response['body'], true );

                    if ( empty( $response_status['success'] ) ) {
                        $invalid_purchased_themes[] = $theme;
                        $invalid_purchased_themes[] = [ 'extension' => $theme, 'response' => $response_status ];
                        continue;
                    }

                    $purchased_themes[] = $theme;
                }
            }

            $status['purchased_extensions'] = $purchased_extensions;
            $status['invalid_purchased_extensions'] = $invalid_purchased_extensions;

            $status['purchased_themes'] = $purchased_themes;
            $status['invalid_purchased_themes'] = $invalid_purchased_themes;

            return $status;
        }

        // download_purchased_items
        public function download_purchased_items() {
            $status = [ 'success' => true, 'log' => [] ];

            $cart = ( isset( $_POST['customers_purchased'] ) ) ? $_POST['customers_purchased'] : '';

            if ( empty( $cart ) ) {
                $status['success'] = false;
                $status['log']['no_purchased_data_found'] = [
                    'type'    => 'error',
                    'message' => 'No purchased data found',
                ];
                wp_send_json([ 'status' => $status ]);
            }

            // Download the extensions
            // Download Extenstions
            if ( ! empty( $cart['purchased_extensions'] ) ) {
                foreach ( $cart['purchased_extensions'] as $extension ) {
                    $paths = $extension['links'];
                    if ( empty( $paths ) ) { continue; }

                    foreach ( $paths as $path ) {
                        $this->download_plugin( [ 'url' => $path ] );
                    }
                }
            }

            // Download Themes
            if ( ! empty( $cart['purchased_themes'] ) ) {
                foreach ( $cart['purchased_themes'] as $theme ) {
                    $paths = $theme['links'];
                    if ( empty( $paths ) ) { continue; }

                    foreach ( $paths as $path ) {
                        $this->download_theme( [ 'url' => $path ] );
                    }
                }
            }

            $status['message'] = 'Download has been completed, redirecting...';

            wp_send_json([ 'status' => $status ]);
            
        }

        // download_plugin
        public function download_plugin( array $args = [] ) {
            $default = [ 'url' => '' ];
            $args = array_merge( $default, $args );

            if ( empty( $default ) ) { return; }

            WP_Filesystem();

            $installation_path = ABSPATH . 'wp-content/plugins';
            $file_url          = $args['url'];
            $file_name         = basename( $file_url );
            $file_dir_name     = preg_replace( '/[.].+$/', '', $file_name );
            $installation_dir  = $installation_path . '/' . $file_dir_name;
            $tmp_file          = download_url( $file_url );

            // Sets file final destination.
            $filepath = "{$installation_path}/{$file_name}";

            // Copies the file to the final destination and deletes temporary file.
            copy( $tmp_file, $filepath );
            @unlink( $tmp_file );

            global $wp_filesystem;
            $wp_filesystem->rmdir( $installation_dir, true );
            
            unzip_file( $filepath, $installation_path );
            @unlink( $filepath );
        }
        
        // download_theme
        public function download_theme( array $args = [] ) {
            $default = [ 'url' => '' ];
            $args = array_merge( $default, $args );

            if ( empty( $default ) ) { return; }

            $installation_path = ABSPATH . 'wp-content/themes';
            $file_url          = $args['url'];
            $file_name         = basename( $file_url );
            $file_dir_name     = preg_replace( '/[.].+$/', '', $file_name );
            $installation_dir  = $installation_path . '/' . $file_dir_name;
            $tmp_file          = download_url( $file_url );

            // Sets file final destination.
            $filepath = "{$installation_path}/{$file_name}";
            
            // Copies the file to the final destination and deletes temporary file.
            copy( $tmp_file, $filepath );
            @unlink( $tmp_file );

            global $wp_filesystem;
            $wp_filesystem->rmdir( $installation_dir, true );
            
            unzip_file( $filepath, $installation_path );
            @unlink( $filepath );

            // If is child theme
            // $theme_dir_path = "{$theme_path}/$file_dir_name";
            // $main_theme_file = "{$theme_dir_path}/";
        }

        /**
         * It Adds menu item
         */
        public function admin_menu()
        {
            add_submenu_page('edit.php?post_type=at_biz_dir',
                __('Get Extensions', 'directorist'),
                __('<span>Themes & Extensions</span>', 'directorist'),
                'manage_options',
                'atbdp-extension',
                array($this, 'show_extension_view')
            );

        }

        /**
         * It Loads Extension view
         */
        public function show_extension_view()
        {
            // Get Extensions Details
            $plugin_updates       = get_site_transient( 'update_plugins' );
            $outdated_plugins     = $plugin_updates->response;
            $outdated_plugins_key = array_keys( $outdated_plugins );
            
            $all_plugins_list     = get_plugins();
            $installed_extensions = [];
            $active_extensions    = 0;
            $outdated_extensions  = 0;
            
            foreach ( $all_plugins_list as $plugin_base => $plugin_data ) {
                if ( preg_match( '/^directorist-/', $plugin_base ) ) {
                    $installed_extensions[ $plugin_base ] = $plugin_data;

                    if ( is_plugin_active( $plugin_base ) ) {
                        $active_extensions++;
                    }

                    if ( in_array( $plugin_base, $outdated_plugins_key ) ) {
                        $outdated_extensions++;
                    }
                }
            }

            // Get Themes Informations
            $sovware_themes = [
                'direo',
                'dlist',
                'dservice',
            ];

            $theme_updates       = get_site_transient( 'update_themes' );
            $outdated_themes     = $theme_updates->response;
            $outdated_themes_key = array_keys( $outdated_themes );

            $all_themes         = wp_get_themes();
            $active_theme_slug  = get_option('stylesheet');
            $installed_themes   = [];
            $my_active_themes   = 0;
            $my_outdated_themes = 0;

            foreach ( $all_themes as $theme_base => $theme_data ) {
                if ( in_array( $theme_base, $sovware_themes ) ) {
                    $installed_themes[ $theme_base ] = $theme_data;

                    if ( $active_theme_slug === $theme_base ) {
                        $my_active_themes++;
                    }

                    if ( in_array( $plugin_base, $outdated_themes_key ) ) {
                        $my_outdated_themes++;
                    }
                }
            }

            $settings_url = admin_url( 'edit.php?post_type=at_biz_dir&page=aazztech_settings#_extensions_switch' );

            $data = [
                'installed_extensions' => $installed_extensions,
                'outdated_plugins'     => $outdated_plugins,
                'active_extensions'    => $active_extensions,
                'outdated_extensions'  => $outdated_extensions,
                'installed_themes'     => $installed_themes,
                'active_themes'        => $my_active_themes,
                'outdated_themes'      => $my_outdated_themes,
                'settings_url'         => $settings_url,
            ];

            ATBDP()->load_template('theme-extensions/theme-extension', $data );
        }
    }


}