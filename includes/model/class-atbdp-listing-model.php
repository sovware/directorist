<?php
if ( ! class_exists( 'ATBDP_Listings_Model' ) ) :
    class ATBDP_Listings_Model {
        // get_archive_listings_query
        public static function get_archive_listings_query( array $args = [] ) {
            return ATBDP_Cache_Helper::get_the_transient([
                'group'      => 'atbdp_listings_query',
                'name'       => 'atbdp_listings_archive',
                'args'       => $args,
                'expiration' => DAY_IN_SECONDS * 30,
                'value'      => function( $data ) {
                    $data['args']['fields'] = 'ids';
                    $query     = new \WP_Query( $data['args'] );
                    $paginated = ! $query->get( 'no_found_rows' );
                    
                    $results = (object) [
                        'ids'          => wp_parse_id_list( $query->posts ),
                        'total'        => $paginated ? (int) $query->found_posts : count( $query->posts ),
                        'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
                        'per_page'     => (int) $query->get( 'posts_per_page' ),
                        'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
                    ];

                    return $results;
                }
            ]);
        }

        // get_listings
        public static function get_listings( array $args = [] ) {
            return ATBDP_Cache_Helper::get_the_transient([
                'group' => 'atbdp_listings_query',
                'name'  => 'atbdp_listings',
                'args'  => $args,
                'cache' => apply_filters( 'atbdp_cache_listings', true ),
                'value' => function( $args ) {
                    return get_posts( $args['args'] );
                }
            ]);
        }

        // get_listings_query
        public static function get_listings_query( array $args = [] ) {
            return ATBDP_Cache_Helper::get_the_transient([
                'group' => 'atbdp_listings_query',
                'name'  => 'atbdp_listings_query',
                'args'  => $args,
                'cache' => apply_filters( 'atbdp_cache_listings', true ),
                'value' => function( $args ) {
                    return new WP_Query( $args['args'] );
                }
            ]);
        }

        // get_listings_ids
        public static function get_listings_ids( array $args = [] ) {
            $default = [
                'post_type'      => ATBDP_POST_TYPE,
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'fields'         => 'ids'
            ];
            $args = array_merge( $default, $args );

            return ATBDP_Cache_Helper::get_the_transient([
                'group'      => 'atbdp_listings_query',
                'name'       => 'atbdp_cache_listings',
                'args'       => $args,
                'expiration' => DAY_IN_SECONDS * 30,
                'value'      => function( $data ) {
                    return get_posts( $data['args'] );
                }
            ]);
        }

        // get_listings_by_user
        public static function get_listings_by_user( $user_id = 0 ) {
            $pagination        = get_directorist_option('user_listings_pagination',1);
            $listings_per_page = get_directorist_option('user_listings_per_page',9);

            //for pagination
            $paged = atbdp_get_paged_num();
            $args  = array(
                'author'         => !empty($user_id) ? absint($user_id) :  get_current_user_id(),
                'post_type'      => ATBDP_POST_TYPE,
                'posts_per_page' => (int) $listings_per_page,
                'order'          => 'DESC',
                'orderby'        => 'date',
                'post_status'    => array('publish', 'pending', 'private'),
                'fields'         => 'ids',
            );

            if ( ! empty($pagination)) {
                $args['paged'] = $paged;
            } else {
                $args['no_found_rows'] = false;
            }

            $args = apply_filters('atbdp_user_dashboard_query_arguments', $args);
            return self::get_archive_listings_query( $args );
        }

        // get_favourite_listings
        public static function get_favourite_listings( $user_id = 0 ) {
            $user_id     = ! empty( $user_id ) ? absint( $user_id ) :  get_current_user_id();
            $fav_listing = get_user_meta( $user_id, 'atbdp_favourites', true );
            $fav_listing = ( ! empty( $fav_listing ) ) ? $fav_listing : [];
            $fav_listing = ( is_string( $fav_listing ) ) ? [ $fav_listing ] : $fav_listing;
            $action      = ! empty( $_GET['atbdp_action'] ) ? $_GET['atbdp_action'] : '';

            if ( ! empty( $action ) ) {
                $remove_id   = ! empty( $_GET['atbdp_listing'] ) ? $_GET['atbdp_listing'] : array();

                if ( is_array( $fav_listing ) && in_array( $remove_id, $fav_listing ) ) {
                    if ( ( $key = array_search( $remove_id, $fav_listing ) ) !== false ) {
                        unset( $fav_listing[ $key ] );
                    }
                } else {
                    $fav_listing[] = $remove_id;
                }

                $fav_listing = array_filter( $fav_listing );
                $fav_listing = array_values( $fav_listing );

                delete_user_meta( get_current_user_id(), 'atbdp_favourites' );
                update_user_meta( get_current_user_id(), 'atbdp_favourites', $fav_listing );
            }

            if ( ! empty( $fav_listing ) ) {
                $args = array(
                    'post_type'      => ATBDP_POST_TYPE,
                    'posts_per_page' => -1,                //@todo; Add pagination in future.
                    'order'          => 'DESC',
                    'post__in'       => $fav_listing,
                    'orderby'        => 'date'

                );
            } else {
                $args = array();
            }

            return self::get_archive_listings_query( $args );
        }
    }
endif;
    