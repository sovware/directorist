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
                'name'       => 'atbdp_listings_ids',
                'args'       => $args,
                'expiration' => DAY_IN_SECONDS * 30,
                'value'      => function( $data ) {
                    var_dump( get_posts( $data['args'] ) );
                    return get_posts( $data['args'] );
                }
            ]);
        }
    }
endif;
    