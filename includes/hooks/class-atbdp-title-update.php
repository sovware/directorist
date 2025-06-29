<?php
if ( ! class_exists( 'ATBDP_Title_Update' ) ) :

    class ATBDP_Title_Update {
        // run
        public static function run( $title, $id = null ) {
            if ( ! in_the_loop() || ! is_main_query() ) {
                return $title;
            }
            
            // global $post;
            if ( ! is_admin() && ! is_null( $id ) ) {
                $post = get_post( $id );
                if ( $post instanceof WP_Post && ( $post->post_type == 'post' || $post->post_type == 'page' ) ) {
                    $CAT_page_ID = get_directorist_option('single_category_page');
                    $LOC_page_ID = get_directorist_option('single_location_page');
                    $Tag_page_ID = get_directorist_option('single_tag_page');
                    // Change Location page title
                    if( $post->ID == $LOC_page_ID ) {
                        if( $slug = get_query_var( 'atbdp_location' ) ) {
                            $term = get_term_by( 'slug', $slug, ATBDP_LOCATION );
                            $title = !empty($term)?$term->name:'';
                        }
                    }
                    // Change Category page title
                    if( $post->ID == $CAT_page_ID ) {
                        if( $slug = get_query_var( 'atbdp_category' ) ) {
                            $term = get_term_by( 'slug', $slug, ATBDP_CATEGORY );
                            $title = !empty($term)?$term->name:'';
                        }
                    }
                    // Change Tag page title
                    if( $post->ID == $Tag_page_ID ) {
                        if( $slug = get_query_var( 'atbdp_tag' ) ) {
                            $term = get_term_by( 'slug', $slug, ATBDP_TAGS );
                            $title = !empty($term)?$term->name:'';
                        }
                    }
                }
            }

            return $title;
        }
    }

endif;