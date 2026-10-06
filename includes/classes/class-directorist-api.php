<?php
/**
 * Directorist API class.
 */
namespace Directorist\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class API {

    const URL = 'https://app.directorist.com/wp-json/directorist/';
    const PROMO_CACHE_TTL = HOUR_IN_SECONDS;
    const PROMO_VERSION_CHECK_TTL = 5 * MINUTE_IN_SECONDS;

    /**
     * @return object
     */
    public static function get_promotion() {
        return static::get_cached_promo(
            'directorist_promotion',
            'directorist_promotion_version_check',
            'v1/get-promo-two'
        );
    }

    public static function get_dashboard_promo() {
        return static::get_cached_promo(
            'directorist_dashboard_promo',
            'directorist_dashboard_promo_version_check',
            'v1/get-dashboard-notice'
        );
    }

    protected static function get_cached_promo( $cache_key, $version_check_key, $endpoint ) {
        $promotion = get_transient( $cache_key );

        if ( empty( $promotion ) ) {
            $promotion = static::get_remote_promo( $endpoint );
            static::cache_promo_response( $cache_key, $version_check_key, $endpoint, $promotion );

            return $promotion;
        }

        if ( false !== get_transient( $version_check_key ) ) {
            return $promotion;
        }

        $remote_promotion = static::get_remote_promo( $endpoint );

        if ( empty( $remote_promotion ) ) {
            set_transient( $version_check_key, static::get_promo_version( $promotion ), static::get_promo_version_check_ttl( $endpoint ) );

            return $promotion;
        }

        if ( static::has_promo_version_changed( $promotion, $remote_promotion ) ) {
            static::cache_promo_response( $cache_key, $version_check_key, $endpoint, $remote_promotion );

            return $remote_promotion;
        }

        set_transient( $version_check_key, static::get_promo_version( $promotion ), static::get_promo_version_check_ttl( $endpoint ) );

        return $promotion;
    }

    protected static function get_remote_promo( $endpoint ) {
        $promotion = static::get( $endpoint );

        return json_decode( $promotion );
    }

    protected static function cache_promo_response( $cache_key, $version_check_key, $endpoint, $promotion ) {
        if ( empty( $promotion ) ) {
            return;
        }

        set_transient( $cache_key, $promotion, static::get_promo_cache_ttl( $endpoint ) );
        set_transient( $version_check_key, static::get_promo_version( $promotion ), static::get_promo_version_check_ttl( $endpoint ) );
    }

    protected static function get_promo_cache_ttl( $endpoint ) {
        return (int) apply_filters( 'directorist_promo_cache_ttl', static::PROMO_CACHE_TTL, $endpoint );
    }

    protected static function get_promo_version_check_ttl( $endpoint ) {
        return (int) apply_filters( 'directorist_promo_version_check_ttl', static::PROMO_VERSION_CHECK_TTL, $endpoint );
    }

    protected static function get_promo_version( $promotion ) {
        if ( is_object( $promotion ) && isset( $promotion->promo_version ) ) {
            return (string) $promotion->promo_version;
        }

        if ( is_array( $promotion ) && isset( $promotion['promo_version'] ) ) {
            return (string) $promotion['promo_version'];
        }

        return '';
    }

    protected static function has_promo_version_changed( $cached_promotion, $remote_promotion ) {
        $remote_version = static::get_promo_version( $remote_promotion );

        if ( '' === $remote_version ) {
            return false;
        }

        return $remote_version !== static::get_promo_version( $cached_promotion );
    }

    /**
     * @return object
     */
    public static function get_products() {
        $products = get_transient( 'directorist_products' );

        if ( ! empty( $products ) ) {
            return $products;
        }

        $products = static::get( 'v1/get-remote-products' );

        if ( empty( $products ) ) {
            return [
                'themes' => [],
                'extensions' => [],
            ];
        }

        $products = json_decode( $products, true );

        set_transient( 'directorist_products', $products, 30 * DAY_IN_SECONDS );

        return $products;
    }

    /**
     * Get current product names and badges without relying on the long-lived catalog cache.
     *
     * @return array Product display data grouped by extension and theme slug.
     */
    public static function get_product_display_data() {
        $cached_data = get_transient( 'directorist_product_display_data' );

        if ( false !== $cached_data && is_array( $cached_data ) ) {
            return $cached_data;
        }

        $request_args            = static::get_request_args();
        $request_args['timeout'] = 3;
        $response                = wp_remote_get( static::URL . 'v1/get-remote-products', $request_args );

        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            set_transient( 'directorist_product_display_data', [], 5 * MINUTE_IN_SECONDS );
            return [];
        }

        $products = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( ! is_array( $products ) ) {
            set_transient( 'directorist_product_display_data', [], 5 * MINUTE_IN_SECONDS );
            return [];
        }

        $display_data = [
            'extensions' => [],
            'themes'     => [],
        ];

        foreach ( array_keys( $display_data ) as $group ) {
            if ( empty( $products[ $group ] ) || ! is_array( $products[ $group ] ) ) {
                continue;
            }

            foreach ( $products[ $group ] as $slug => $product ) {
                if ( ! is_string( $slug ) || ! is_array( $product ) ) {
                    continue;
                }

                $display_data[ $group ][ sanitize_key( $slug ) ] = [
                    'badges' => $product['badges'] ?? $product['badge'] ?? [],
                ];

                if ( isset( $product['name'] ) && is_string( $product['name'] ) && '' !== trim( $product['name'] ) ) {
                    $display_data[ $group ][ sanitize_key( $slug ) ]['name'] = sanitize_text_field( $product['name'] );
                }
            }
        }

        set_transient( 'directorist_product_display_data', $display_data, HOUR_IN_SECONDS );

        return $display_data;
    }

    /**
     * Get current product badges.
     *
     * @return array Product badges grouped by extension and theme slug.
     */
    public static function get_product_badges() {
        $display_data   = static::get_product_display_data();
        $product_badges = [
            'extensions' => [],
            'themes'     => [],
        ];

        foreach ( array_keys( $product_badges ) as $group ) {
            foreach ( $display_data[ $group ] ?? [] as $slug => $product ) {
                if ( ! empty( $product['badges'] ) ) {
                    $product_badges[ $group ][ $slug ] = $product['badges'];
                }
            }
        }

        return $product_badges;
    }

    /**
     * @return array
     */
    protected static function get_request_args() {
        return [
            'method'      => 'GET',
            'timeout'     => 30,
            'redirection' => 5,
            'headers'     => [
                'user-agent' => 'Directorist/' . ATBDP_VERSION,
                'Accept'     => 'application/json',
            ],
            'cookies'     => [],
        ];
    }

    /**
     *
     * @return string
     */
    public static function get( $endpoint = '' ) {
        $url = static::URL . $endpoint;
        $response = wp_remote_get( $url, static::get_request_args() );

        return wp_remote_retrieve_body( $response );
    }
}
