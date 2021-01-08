<?php

if ( ! class_exists( 'ATBDP_Cache_Helper' ) ) :
class ATBDP_Cache_Helper {
    /**
     * Get transient version.
     *
     * When using transients with unpredictable names, e.g. those containing an md5
     * hash in the name, we need a way to invalidate them all at once.
     *
     * When using default WP transients we're able to do this with a DB query to
     * delete transients manually.
     *
     * With external cache however, this isn't possible. Instead, this function is used
     * to append a unique string (based on time()) to each transient. When transients
     * are invalidated, the transient version will increment and data will be regenerated.
     *
     * @param  string  $group   Name for the group of transients we need to invalidate.
     * @param  boolean $refresh true to force a new version.
     * @return string transient version based on time(), 10 digits.
     */

    public static function get_transient_version( $group, $refresh = false ) {
        $transient_name  = $group . '-transient-version';
        $transient_value = get_transient( $transient_name );

        if ( false === $transient_value || true === $refresh ) {
            $transient_value = (string) time();
            set_transient( $transient_name, $transient_value );
            // self::log([ 
            //     'title' => $transient_name, 
            //     'content' => $transient_value 
            // ]);
        }

        return $transient_value;
    }

    // get_transient_name
    public static function get_transient_name( $prefix = '' , $args = '' ) {
        $args = ( is_array( $args ) ) ? wp_json_encode( $args ) : $args;
        $name = "{$prefix}_". md5( $args );

        return $name;
    }

    // get_the_transient
    public static function get_the_transient( $args = [] ) {
        $defaults = [
            'group'      => '',
            'name'       => '',
            'query_args' => '',
            'data'       => [],
            'update'     => false,
            'expiration' => 0,
            'cache'      => true,
            'value'      => null,
        ];
        $args = array_merge( $defaults, $args );

        $enable_cahce = get_directorist_option( 'atbdp_enable_cache', true ); 
        $args['cache'] = ( $enable_cahce ) ? $args['cache'] : false;

        $reset_cahce = get_directorist_option( 'atbdp_reset_cache', false ); 
        $args['update'] = ( $reset_cahce ) ? true : $args['update'];

        $transient_name    = self::get_transient_name( $args['name'], $args['query_args'] );
        $transient_version = self::get_transient_version( $args['group'], $args['update'] );
        $transient_value   = $args['cache'] ? get_transient( $transient_name ) : false;
        
        $has_transient         = isset( $transient_value['value'], $transient_value['version'] ) ? true : false;
        $transient_not_updated = ( $has_transient && $transient_value['version'] === $transient_version ) ? true : false;
       
        if ( $has_transient && $transient_not_updated ) { 
            $value = $transient_value['value'];
        } else {
            if ( is_callable( $args['value'] ) ) {
                $cb_data               = $args['data'];
                $cb_data['query_args'] = $args['query_args'];

                if ( isset( $args['args'] ) ) {
                    $cb_data['args'] = $args['args'];
                }

                $value = $args['value']( $cb_data );
            } else {
                $value = $args['value'];
            }

            if ( $args['cache'] ) {
                $transient_value = [
                    'version' => $transient_version,
                    'value'   => $value,
                ];

                set_transient( $transient_name, $transient_value, $args['expiration'] );
            } 

        }

        return $value;
    }

    // reset_cache
    public static function reset_cache() {
        add_action( 'save_post', array( __CLASS__, 'reset_query_cache' ), 10, 3 );
        add_action( 'delete_post', array( __CLASS__, 'reset_query_cache' ) );
        
        // add_action( 'created_' . ATBDP_CATEGORY, array( __CLASS__, 'reset_category_cache' ), 10, 2 );
        // add_action( 'delete_' . ATBDP_CATEGORY, array( __CLASS__, 'reset_category_cache' ), 10, 2 );

        // add_action( 'created_' . ATBDP_LOCATION, array( __CLASS__, 'reset_location_cache' ), 10, 2 );
        // add_action( 'delete_' . ATBDP_LOCATION, array( __CLASS__, 'reset_location_cache' ), 10, 2 );

        // add_action( 'created_' . ATBDP_TAGS, array( __CLASS__, 'reset_tag_cache' ), 10, 2 );
        // add_action( 'delete_' . ATBDP_TAGS, array( __CLASS__, 'reset_tag_cache' ), 10, 2 );
    }

    // reset_query_cache
    public static function reset_query_cache( $post_ID = 0, $post = null, $update = true ) {

        if ( empty( $post_ID ) && empty( $post ) ) { return; }
        if ( empty( $post ) ) { $post = get_post( $post_ID ); }
        if ( empty( $post ) ) { return; }

        // Reset Cache for Listings Query
        if ( ATBDP_POST_TYPE === $post->post_type  ) {
            self::get_transient_version('atbdp_listings_query', true);
        }

        // Reset Cache for Custom Field Query
        if ( ATBDP_CUSTOM_FIELD_POST_TYPE === $post->post_type ) {
            self::get_transient_version('atbdp_custom_field_query', true);
        }
        
        // Reset Cache for Review Table
        /* if ( 'atbdp_listing_review' === $post->post_type ) {
            $approve_immediately = get_directorist_option('approve_immediately', 1);

            if ( $update || ! empty( $approve_immediately ) ) {
                self::get_transient_version( 'atbdp_ratings_query', true );
            }
        } */
    }

    // reset_category_cache
    public static function reset_category_cache(  int $term_id, int $tt_id ) {
        self::get_transient_version( 'atbdp_category_terms', true );
    }

    // reset_location_cache
    public static function reset_location_cache(  int $term_id, int $tt_id ) {
        self::get_transient_version( 'atbdp_location_terms', true );
    }

    // reset_tag_cache
    public static function reset_tag_cache(  int $term_id, int $tt_id ) {
        self::get_transient_version( 'atbdp_tag_terms', true );
    }

    // log
    private static function log( array $log = [] ) {
        $title   = ( ! empty( $log['title'] ) ) ? $log['title'] : '';
        $content = ( ! empty( $log['content'] ) ) ? $log['content'] : '';
        
        wp_insert_post([
            'post_title' => 'Cache Alert | ' .  $title,
            'post_content' => $content,
        ]);
    }
}
endif;