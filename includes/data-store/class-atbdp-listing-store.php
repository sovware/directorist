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

        // get_listings_query
        public static function get_listings_query( array $args = [] ) {
            return ATBDP_Cache_Helper::get_the_transient([
                'group'      => 'atbdp_listings_query',
                'name'       => 'atbdp_listings_query',
                'query_args' => $args,
                'cache'      => apply_filters( 'atbdp_cache_listings', true ),
                'value'      => function( $data ) {
                    return new WP_Query( $data['query_args'] );
                }
            ]);
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

        // get_listings_by_user
        public static function get_listings_by_user( $user_id = 0 ) {
            $pagination        = get_directorist_option('user_listings_pagination',1);
            $listings_per_page = get_directorist_option('user_listings_per_page',9);

            //for pagination
            $paged = atbdp_get_paged_num();
            $query_args = array(
                'author'         => !empty($user_id) ? absint($user_id) :  get_current_user_id(),
                'post_type'      => ATBDP_POST_TYPE,
                'posts_per_page' => (int) $listings_per_page,
                'order'          => 'DESC',
                'orderby'        => 'date',
                'post_status'    => array('publish', 'pending', 'private'),
                'fields'         => 'ids',
            );

            if ( ! empty($pagination)) {
                $query_args['paged'] = $paged;
            } else {
                $query_args['no_found_rows'] = false;
            }

            $query_args = apply_filters('atbdp_user_dashboard_query_arguments', $query_args);
            return self::get_archive_listings_query( $query_args );
        }

        // get_favourite_listings
        public static function get_favourite_listings( $user_id = 0 ) {
			$user_id = absint( $user_id );
			if ( ! $user_id ) {
				$user_id = get_current_user_id();
			}

			$favorites = directorist_get_user_favorites( $user_id );
			$action    = ! empty( $_GET['atbdp_action'] ) ? $_GET['atbdp_action'] : '';
			$listing_id  = ! empty( $_GET['atbdp_listing'] ) ? absint( $_GET['atbdp_listing'] ) : 0;

            if ( ! empty( $action ) && ! empty( $listing_id ) ) {
                if ( in_array( $listing_id, $favorites ) ) {
					directorist_delete_user_favorites( $user_id, $listing_id );
                } else {
					directorist_add_user_favorites( $user_id, $listing_id );
                }

				$favorites = directorist_get_user_favorites( $user_id );
            }

            if ( ! empty( $favorites ) ) {
                $query_args = array(
                    'post_type'      => ATBDP_POST_TYPE,
                    'posts_per_page' => -1,                //@todo; Add pagination in future.
                    'order'          => 'DESC',
                    'post__in'       => $favorites,
                    'orderby'        => 'date'
                );
            } else {
                $query_args = array();
            }

            return self::get_archive_listings_query( $query_args );
        }
    }
endif;
