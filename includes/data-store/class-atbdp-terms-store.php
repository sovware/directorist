<?php
if ( ! class_exists( 'ATBDP_Terms_Data_Store' ) ) :
    class ATBDP_Terms_Data_Store {
        // get_categories_term
        public static function get_categories_term( array $args = [] ) {
            return get_terms( ATBDP_CATEGORY, $args );

            /* return ATBDP_Cache_Helper::get_the_transient([
                'group' => 'atbdp_category_terms',
                'name'  => 'atbdp_term_categories',
                'args'  => $args,
                'cache' => apply_filters( 'atbdp_cache_term_categories', true ),
                'value' => function( $data ) {
                    return get_terms( ATBDP_CATEGORY, $data['args'] );
                }
            ]); */
        }

        // get_categories_taxanomy
        public static function get_categories_taxanomy( array $args = [] ) {
            return get_categories( $args );

            /* return ATBDP_Cache_Helper::get_the_transient([
                'group'  => 'atbdp_category_terms',
                'name'   => 'atbdp_taxonomy_categories',
                'args'   => $args,
                'cache'  => apply_filters( 'atbdp_cache_taxonomy_categories', true ),
                'value'  => function( $data ) {
                    return get_categories( $data['args'] );
                }
            ]); */
        }

        // get_locations_term
        public static function get_locations_term( array $args = [] ) {
            return get_terms( ATBDP_LOCATION, $args );

          /*   return ATBDP_Cache_Helper::get_the_transient([
                'group' => 'atbdp_location_terms',
                'name'  => 'atbdp_locations',
                'args'  => $args,
                'cache' => apply_filters( 'atbdp_cache_locations', true ),
                'value' => function( $data ) {
                    return get_terms( ATBDP_LOCATION, $data['args'] );
                }
            ]); */
        }
        
        // get_tags_term
        public static function get_tags_term( array $args = [] ) {
            return get_terms( ATBDP_TAGS, $args );

            /* return ATBDP_Cache_Helper::get_the_transient([
                'group' => 'atbdp_tag_terms',
                'name'  => 'atbdp_listings_tags',
                'args'  => $args,
                'cache' => apply_filters( 'atbdp_cache_listings_tags', true ),
                'value' => function( $data ) {
                    return get_terms( ATBDP_TAGS, $data['args'] );
                }
            ]); */
        }
    }
endif;