<?php
/**
 * Rest Licensing Controller
 *
 * @package Directorist Licensing
 * @version  1.0.0
 */

namespace Directorist\Licensing;

defined( 'ABSPATH' ) || exit;

class Controllers {
    public function login_with_access_key( \WP_REST_Request $request ) {

        $access_key = (string) $request->get_param( 'access_key' );

        if ( empty( $access_key ) ) {
            return rest_ensure_response( ['success' => false, 'message' => __( 'Access key is required', 'directorist' )] );
        }

        try {
            $repo = new Repository();
            $repo->login_with_access_key( $access_key );

            return rest_ensure_response(
                [
                    'success' => true,
                    'message' => __( 'Connected successfully', 'directorist' ),
                ] 
            );

        } catch ( \Throwable $th ) {
            return rest_ensure_response(
                [
                    'success' => false,
                    'message' => $th->getMessage(),
                ] 
            );
        }
    }

    public function login_with_account( \WP_REST_Request $request ) {
        $email = (string) $request->get_param( 'email' );
        $pass  = (string) $request->get_param( 'pass' );

        if ( empty( $email ) || empty( $pass ) ) {
            return rest_ensure_response( ['success' => false, 'message' => __( 'Email and Password is required', 'directorist' )] );
        }

        try {
            $repo = new Repository();
            $repo->login_with_account( $email, $pass );

            return rest_ensure_response(
                [
                    'success' => true,
                    'message' => __( 'Connected successfully', 'directorist' ),
                ] 
            );

        } catch ( \Throwable $th ) {
            return rest_ensure_response(
                [
                    'success' => false,
                    'message' => $th->getMessage(),
                ] 
            );
        }
    }

    public function logout_account() {
        delete_option( 'directorist_licensing_account_data' );

        // Remove 'logout' from the URL
        $redirect_url = remove_query_arg( 'logout' );

        // Redirect to the new URL without 'logout' parameter
        wp_safe_redirect( $redirect_url );
        exit;
    }

    public function install_extension( \WP_REST_Request $request ) {
        $slug     = (string) $request->get_param( 'slug' );
        $theme_id = (int) $request->get_param( 'theme_id' );

        if ( empty( $slug ) ) {
            return rest_ensure_response( ['success' => false, 'message' => __( 'Valid extension slug missing', 'directorist' )] );
        }

        if ( $theme_id ) {
            $template_id = directorist_get_template_by_theme( $theme_id );
            add_option( '_templatiq_redirect_to_template', $template_id );
        }

        try {
            $repo = new Extension_Handler();
            $repo->install( $slug );

            return rest_ensure_response(
                [
                    'success' => true,
                    'message' => __( 'Installed successfully', 'directorist' ),
                ] 
            );

        } catch ( \Throwable $th ) {
            return rest_ensure_response(
                [
                    'success' => false,
                    'message' => $th->getMessage(),
                ] 
            );
        }
    }

    public function activate_extension( \WP_REST_Request $request ) {
        $slug = (string) $request->get_param( 'slug' );

        if ( empty( $slug ) ) {
            return rest_ensure_response( ['success' => false, 'message' => __( 'Valid extension slug missing', 'directorist' )] );
        }

        try {
            $repo = new Extension_Handler();
            $repo->activate( $slug );

            return rest_ensure_response(
                [
                    'success' => true,
                    'message' => __( 'Activated successfully', 'directorist' ),
                ] 
            );

        } catch ( \Throwable $th ) {
            return rest_ensure_response(
                [
                    'success' => false,
                    'message' => $th->getMessage(),
                ] 
            );
        }
    }

    public function deactivate_extension( \WP_REST_Request $request ) {
        $slug = (string) $request->get_param( 'slug' );

        if ( empty( $slug ) ) {
            return rest_ensure_response( ['success' => false, 'message' => __( 'Valid extension slug missing', 'directorist' )] );
        }

        try {
            $repo = new Extension_Handler();
            $repo->deactivate( $slug );

            return rest_ensure_response(
                [
                    'success' => true,
                    'message' => __( 'Deactivated successfully', 'directorist' ),
                ] 
            );

        } catch ( \Throwable $th ) {
            return rest_ensure_response(
                [
                    'success' => false,
                    'message' => $th->getMessage(),
                ] 
            );
        }
    }

    public function update_extension( \WP_REST_Request $request ) {
        $slug = (string) $request->get_param( 'slug' );

        if ( empty( $slug ) ) {
            return rest_ensure_response( ['success' => false, 'message' => __( 'Valid extension slug missing', 'directorist' )] );
        }

        try {
            $repo = new Extension_Handler();

            return $repo->update( $slug );

        } catch ( \Throwable $th ) {
            return rest_ensure_response(
                [
                    'success' => false,
                    'message' => $th->getMessage(),
                ] 
            );
        }
    }

    public function install_theme( \WP_REST_Request $request ) {
        $slug          = (string) $request->get_param( 'slug' );
        $download_link = (string) $request->get_param( 'download_link' );

        if ( empty( $slug ) ) {
            return rest_ensure_response( [ 'success' => false, 'message' => __( 'Valid theme slug missing', 'directorist' ) ] );
        }

        if ( empty( $download_link ) ) {
            return rest_ensure_response( [ 'success' => false, 'message' => __( 'Valid download link missing', 'directorist' ) ] );
        }

        try {
            if ( ! function_exists( 'wp_get_themes' ) ) {
                require_once ABSPATH . 'wp-includes/theme.php';
            }

            if ( ! function_exists( 'request_filesystem_credentials' ) ) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }

            if ( ! class_exists( 'WP_Ajax_Upgrader_Skin' ) ) {
                require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            }

            if ( ! class_exists( 'Theme_Upgrader' ) ) {
                require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            }

            $skin     = new \WP_Ajax_Upgrader_Skin();
            $upgrader = new \Theme_Upgrader( $skin );

            $result = $upgrader->install( $download_link );

            if ( is_wp_error( $result ) ) {
                return rest_ensure_response( [ 'success' => false, 'message' => $result->get_error_message() ] );
            }

            // Clear theme cache to detect newly installed theme
            if ( function_exists( 'wp_clean_themes_cache' ) ) {
                wp_clean_themes_cache();
            }

            return rest_ensure_response( [
                'success' => true,
                'message' => __( 'Theme installed successfully', 'directorist' ),
            ] );
        } catch ( \Throwable $th ) {
            return rest_ensure_response( [ 'success' => false, 'message' => $th->getMessage() ] );
        }
    }

    public function activate_theme( \WP_REST_Request $request ) {
        $slug = (string) $request->get_param( 'slug' );

        if ( empty( $slug ) ) {
            return rest_ensure_response( [ 'success' => false, 'message' => __( 'Valid theme slug missing', 'directorist' ) ] );
        }

        try {
            if ( ! function_exists( 'wp_get_theme' ) ) {
                require_once ABSPATH . 'wp-includes/theme.php';
            }

            if ( ! function_exists( 'switch_theme' ) ) {
                require_once ABSPATH . 'wp-includes/theme.php';
            }

            // Ensure the theme exists before switching
            $themes = wp_get_themes();
            if ( empty( $themes[ $slug ] ) ) {
                return rest_ensure_response( [ 'success' => false, 'message' => __( 'Theme is not installed', 'directorist' ) ] );
            }

            switch_theme( $slug );

            return rest_ensure_response( [
                'success' => true,
                'message' => __( 'Theme activated successfully', 'directorist' ),
            ] );
        } catch ( \Throwable $th ) {
            return rest_ensure_response( [ 'success' => false, 'message' => $th->getMessage() ] );
        }
    }
}