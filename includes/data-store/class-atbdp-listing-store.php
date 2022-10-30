<?php
if ( ! class_exists( 'ATBDP_Listings_Data_Store' ) ) :
    class ATBDP_Listings_Data_Store {
        // get_archive_listings_query
        public static function get_archive_listings_query( array $query_args = [], array $custom_option = [] ) {
            $default_option = [
                'cache' => true, 'update' => false,
            ];
            $default_option = array_merge( $default_option, $custom_option );
            $fixed_option = [
                'group'      => 'atbdp_listings_query',
                'name'       => 'atbdp_listings_archive',
                'query_args' => $query_args,
                'expiration' => DAY_IN_SECONDS * 30,
                'value'      => function( $data ) {
                    $data['query_args']['fields'] = 'ids';
                    $query                        = new \WP_Query( $data['query_args'] );
                    $paginated                    = ! $query->get( 'no_found_rows' );

                    $results = (object) [
                        'ids'          => wp_parse_id_list( $query->posts ),
                        'total'        => $paginated ? (int) $query->found_posts : count( $query->posts ),
                        'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
                        'per_page'     => (int) $query->get( 'posts_per_page' ),
                        'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
                    ];

                    return $results;
                }
            ];
            $transient_args = array_merge( $default_option, $fixed_option );

            return ATBDP_Cache_Helper::get_the_transient( $transient_args );
        }

        // get_listings
        public static function get_listings( array $args = [] ) {
            return ATBDP_Cache_Helper::get_the_transient([
                'group'      => 'atbdp_listings_query',
                'name'       => 'atbdp_listings',
                'query_args' => $args,
                'cache'      => apply_filters( 'atbdp_cache_listings', true ),
                'value'      => function( $data ) {
                    return get_posts( $data['query_args'] );
                }
            ]);
        }

		/**
		 * Unused method
		 *
		 * @return object WP_Query
		 */
        public static function get_listings_query( array $args = [] ) {
			_deprecated_function( __METHOD__, '7.4.3' );
			return new WP_Query();
        }

        // get_listings_ids
        public static function get_listings_ids( array $query_args = [] ) {
            $default = [
                'post_type'      => ATBDP_POST_TYPE,
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'fields'         => 'ids'
            ];
            $query_args = array_merge( $default, $query_args );

            return ATBDP_Cache_Helper::get_the_transient([
                'group'      => 'atbdp_listings_query',
                'name'       => 'atbdp_cache_listings',
                'query_args' => $query_args,
                'expiration' => DAY_IN_SECONDS * 30,
                'value'      => function( $data ) {
                    return get_posts( $data['query_args'] );
                }
            ]);
        }

		/**
		 * Unused method
		 *
		 * @return object WP_Query
		 */
        public static function get_listings_by_user( $user_id = 0 ) {
			_deprecated_function( __METHOD__, '7.4.3' );
			return new WP_Query();
        }

		/**
		 * Unused method
		 *
		 * @return object WP_Query
		 */
        public static function get_favourite_listings( $user_id = 0 ) {
			_deprecated_function( __METHOD__, '7.4.3' );
			return new WP_Query();
        }
    }
endif;
