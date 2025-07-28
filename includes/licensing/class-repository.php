<?php
/**
 * Licensing Repository
 *
 * @package Directorist Licensing
 * @version  1.0.0
 */

namespace Directorist\Licensing;

use Directorist\Licensing\Utils\Http;

defined( 'ABSPATH' ) || exit;

class Repository {
    private static function get_endpoint( string $endpoint ) {
        return 'https://directorist.com/wp-json/directorist-license-manager/' . $endpoint;
    }

    private static function remote_request( $endpoint = '' ) {
        $args = [
            'timeout'     => 30,
            'redirection' => 5,
            'headers'     => [
                'user-agent' => 'Directorist/' . ATBDP_VERSION,
                'Accept'     => 'application/json',
            ],
            'cookies'     => [],
            'version'     => ATBDP_VERSION,
        ];

        $url = self::get_endpoint( $endpoint );

        $response = wp_remote_post( $url, $args );

        return wp_remote_retrieve_body( $response );
    }

    public static function get_promotional_content() {
        $content = get_transient( 'directorist_promotional_content' );

        if ( ! empty( $content ) ) {
            return $content;
        }

        $content = self::remote_request( 'promotional-content' );

        if ( empty( $content ) ) {
            return [
                'templates'  => [],
                'extensions' => [],
            ];
        }

        $content = json_decode( $content, true );

        set_transient( 'directorist_promotional_content', $content, 30 * DAY_IN_SECONDS );

        return $content;
    }

    public function login_with_access_key( string $access_key ) {
        try {
            $http = new Http(
                self::get_endpoint( 'user-connect' ),
                [
                    'access_key' => $access_key,
                ]
            );

            $response = $http->post()->response();
            $raw_body = wp_remote_retrieve_body( $response );
            $data     = json_decode( $raw_body, true );

            if ( ! isset( $data['account_data']['user_id'] ) ) {
                throw new \Exception( __( 'Invalid Access Key', 'directorist' ) );
            }

            update_option( 'directorist_licensing_account_data', $data );

            if ( isset( $data['account_data']['templatiq_token'] ) && ! empty( $data['account_data']['templatiq_token'] ) ) {
                update_option( '_templatiq_token', $data['account_data']['templatiq_token'] );
            }

            $extensions = $data['plan_data']['downloads']['legacy_array'] ?? [];
            add_user_meta( 1, '_plugins_available_in_subscriptions', $extensions );

            return true;

        } catch ( \Throwable $th ) {
            throw $th;
        }
    }

    public function login_with_account( string $email, string $pass ) {
        try {
            $http = new Http(
                self::get_endpoint( 'user-login' ),
                [
                    'email' => $email,
                    'pass'  => $pass,
                ]
            );

            $response = $http->post()->response();
            $raw_body = wp_remote_retrieve_body( $response );
            $data     = json_decode( $raw_body, true );

            if ( ! isset( $data['account_data']['user_id'] ) ) {
                throw new \Exception( __( 'Invalid Email or Password', 'directorist' ) );
            }

            update_option( 'directorist_licensing_account_data', $data );

            if ( isset( $data['account_data']['templatiq_token'] ) && ! empty( $data['account_data']['templatiq_token'] ) ) {
                update_option( '_templatiq_token', $data['account_data']['templatiq_token'] );
            }

            $extensions = $data['plan_data']['downloads']['legacy_array'] ?? [];
            add_user_meta( 1, '_plugins_available_in_subscriptions', $extensions );

            return true;

        } catch ( \Throwable $th ) {
            throw $th;
        }
    }
}