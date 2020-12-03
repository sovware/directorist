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
            add_action( 'wp_ajax_atbdp_authenticate_the_customer', array($this, 'authenticate_the_customer') );
            add_action( 'wp_ajax_atbdp_get_customers_purchased', array($this, 'get_customers_purchased') );
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

            $status[ 'success' ] = true;
            $status[ 'log' ]['login_successful'] = [
                'type'    => 'success',
                'message' => 'Login is successful',
            ];

            wp_send_json([ 'status' => $status, 'license_data' => $license_data ]);
        }

        // get_customers_purchased
        public function get_customers_purchased() {
            $status       = [ 'success' => true ];
            $license_data = ( isset( $_POST['license_data'] ) ) ? $_POST['license_data'] : '';

            // Validate
            if ( empty( $license_data ) ) {
                $status[ 'success' ] = false;
                $status[ 'message' ] = __( 'License data is missing', 'directorist' );
                wp_send_json( $status );
            }

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

            $status['purchased_extensions'] = $purchased_extensions;
            $status['invalid_purchased_extensions'] = $invalid_purchased_extensions;

            $status['purchased_themes'] = $purchased_themes;
            $status['invalid_purchased_themes'] = $invalid_purchased_themes;

            wp_send_json( $status );
        }

        // download_the_customers_purchased
        public function download_the_customers_purchased() {

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
            // Get Extensions
            $all_plugins_list = get_plugins();
            $directorist_extensions = [];

            foreach ( $all_plugins_list as $plugin_base => $plugin_data ) {
                if ( preg_match( '/^directorist-/', $plugin_base ) ) {
                    $directorist_extensions[ $plugin_base ] = $plugin_data;
                }
            }

            // Get Themes
            $all_sovware_themes = [
                'direo',
                'dlist',
                'dservice',
            ];

            $all_themes = wp_get_themes();
            $sovware_themes = [];

            foreach ( $all_themes as $theme_base => $theme_data ) {
                if ( in_array( $theme_base, $all_sovware_themes ) ) {
                    $sovware_themes[ $theme_base ] = $theme_data;
                }
            }


            ATBDP()->load_template('theme-extensions/theme-extension');
        }
    }


}