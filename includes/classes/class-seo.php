<?php
defined('ABSPATH') || die( 'Direct access is not allowed.' );
if ( !class_exists('ATBDP_SEO') ):
    /**
     * Class ATBDP_Email
     */
    class ATBDP_SEO {


        public function __construct()
        {
            add_filter('pre_get_document_title', array($this, 'atbdp_custom_page_title'), 100);
            add_filter('wp_title', array($this, 'atbdp_custom_page_title'), 100, 2);
            add_filter('the_title', array($this, 'atbdp_custom_page_header_title'), 99);
            add_action('wp_head', array($this, 'atbdp_add_meta_keywords'), 100, 2);
        }


        public function atbdp_custom_page_header_title($title){
            if( ! in_the_loop() || ! is_main_query() ) {
                return $title;
            }
            global $post;

            $CAT_page_ID = get_directorist_option('single_category_page');
            $LOC_page_ID = get_directorist_option('single_location_page');
            // Change Location page title
            if( $post->ID == $LOC_page_ID ) {

                if( $slug = get_query_var( 'atbdp_location' ) ) {
                    $term = get_term_by( 'slug', $slug, ATBDP_LOCATION );
                    $title = $term->name;
                }
            }
             // Change Location page title
            if( $post->ID == $CAT_page_ID ) {

                if( $slug = get_query_var( 'atbdp_category' ) ) {
                    $term = get_term_by( 'slug', $slug, ATBDP_CATEGORY );
                    $title = !empty($term)?$term->name:'';
                }
            }

                return $title;

            }

        public function atbdp_add_meta_keywords(){
            global $wp, $post, $wp_query, $wpdb;
            $meta_desc = '';
            $disable_yoast_seo_metas = get_directorist_option('overwrite_by_yoast', 1);
            if (!$disable_yoast_seo_metas) {
                return true;

            }


            $atbdp_page = '';
            if(atbdp_is_page('home')){
                $atbdp_page = 'home';
                $meta_desc = (get_directorist_option('homepage_meta_desc')) ? get_directorist_option('homepage_meta_desc') : $meta_desc;
            }
            elseif(atbdp_is_page('search-result')){
                $atbdp_page = 'search-result';

                $meta_desc = (get_directorist_option('search_result_meta_desc')) ? get_directorist_option('search_result_meta_desc') : $meta_desc;
            } elseif(atbdp_is_page('add-listing')){
                $atbdp_page = 'add-listing';

                $meta_desc = (get_directorist_option('add_listing_page_meta_desc')) ? get_directorist_option('add_listing_page_meta_desc') : $meta_desc;
            }
            elseif(atbdp_is_page('all-listing')){
                $atbdp_page = 'all-listing';
                $meta_desc = (get_directorist_option('all_listing_meta_desc')) ? get_directorist_option('all_listing_meta_desc') : $meta_desc;
            }elseif(atbdp_is_page('dashboard')){
                $atbdp_page = 'dashboard';
                $meta_desc = (get_directorist_option('dashboard_meta_desc')) ? get_directorist_option('dashboard_meta_desc') : $meta_desc;
            }elseif(atbdp_is_page('author')){
                $atbdp_page = 'author';
                $meta_desc = (get_directorist_option('author_page_meta_desc')) ? get_directorist_option('author_page_meta_desc') : $meta_desc;
            }elseif(atbdp_is_page('category')){
                $atbdp_page = 'category';
                $meta_desc = (get_directorist_option('category_meta_desc')) ? get_directorist_option('category_meta_desc') : $meta_desc;
            }elseif(atbdp_is_page('single_category')){
                $atbdp_page = 'single_category';
                $meta_desc = (get_directorist_option('single_category_meta_desc')) ? get_directorist_option('single_category_meta_desc') : $meta_desc;
            }elseif(atbdp_is_page('all_locations')){
                $atbdp_page = 'all_locations';
                $meta_desc = (get_directorist_option('all_locations_meta_desc')) ? get_directorist_option('all_locations_meta_desc') : $meta_desc;
            }elseif(atbdp_is_page('single_location')){
                $atbdp_page = 'single_location';
                $meta_desc = (get_directorist_option('single_locations_meta_desc')) ? get_directorist_option('single_locations_meta_desc') : $meta_desc;
            }elseif(atbdp_is_page('registration')){
                $atbdp_page = 'registration';
                $meta_desc = (get_directorist_option('registration_meta_desc')) ? get_directorist_option('registration_meta_desc') : $meta_desc;
            }elseif(atbdp_is_page('login')){
                $atbdp_page = 'login';
                $meta_desc = (get_directorist_option('login_meta_desc')) ? get_directorist_option('login_meta_desc') : $meta_desc;
            }



            if ( $meta_desc ) {
                $meta_desc = stripslashes_deep( $meta_desc );
                /**
                 * Filter page description to replace variables.
                 *
                 * @since 1.5.4
                 *
                 * @param string $meta_desc   The page description including variables.
                 * @param string $gd_page The GeoDirectory page type if any.
                 */
                $meta_desc = apply_filters( 'atbdp_seo_meta_description_pre', __( $meta_desc, ATBDP_TEXTDOMAIN ), $atbdp_page, '' );

                /**
                 * Filter SEO meta description.
                 *
                 * @since 1.0.0
                 *
                 * @param string $meta_desc Meta description content.
                 */
                echo apply_filters( 'atbdp_seo_meta_description', '<meta name="description" content="' . $meta_desc . '" />', $meta_desc );
            }
        }


        /**
         * Set custom page title.
         *
         * @since 1.0.0
         * @since 1.6.18 Option added to disable overwrite by Yoast SEO titles & metas on GD pages.
         * @package GeoDirectory
         * @global object $wp WordPress object.
         * @param string $title Old title.
         * @param string $sep Title separator.
         * @return string Modified title.
         */
        public function atbdp_custom_page_title($title = '', $sep = '')
        {

            global $wp;
            $disable_yoast_seo_metas = get_directorist_option('overwrite_by_yoast', 1);
            if (!$disable_yoast_seo_metas) {
                return $title;
            }

            if ($sep == '') {
                /**
                 * Filter the page title separator.
                 *
                 * @since 4.6.0
                 * @package Directorist
                 * @param string $sep The separator, default: `|`.
                 */
                $sep = apply_filters('atbdp_page_title_separator', '|');
            }


            $atbdp_page = '';
            if(atbdp_is_page('home')){
                $atbdp_page = 'home';
                $title = (get_directorist_option('homepage_meta_title')) ? get_directorist_option('homepage_meta_title') : $title;
            }
            elseif(atbdp_is_page('search-result')){
                $atbdp_page = 'search-result';

                $query = (isset($_GET['q']) && ('' !== $_GET['q']))?ucfirst($_GET['q']):'';
                $category = (isset($_GET['in_cat']) && ('' !== $_GET['in_cat']))?ucfirst($_GET['in_cat']):'';
                $location = (isset($_GET['in_loc']) && ('' !== $_GET['in_loc']))?ucfirst($_GET['in_loc']):'';
                $category =  get_term_by('id',$category,ATBDP_CATEGORY);
                $location =  get_term_by('id',$location,ATBDP_LOCATION);


                $in_s_string_text       = !empty($query) ? sprintf(__('%s', ATBDP_TEXTDOMAIN), $query) : '';
                $in_cat_text            = !empty($category) ? sprintf(__(' %s %s ', ATBDP_TEXTDOMAIN), !empty($query)?'from':'', $category) : '';
                $in_loc_text            = !empty($location) ? sprintf(__('%s %s', ATBDP_TEXTDOMAIN), !empty($query)?'in':'', $location) : '';

                $how_to = get_directorist_option('meta_title_for_search_result', 'searched_value');
                if ('searched_value' === $how_to){
                    if (!empty($query) || !empty($category) || !empty($location)){
                        $title = $in_s_string_text. $in_cat_text. $in_loc_text;
                    }
                }else{
                    $title = (get_directorist_option('search_result_meta_title')) ? get_directorist_option('search_result_meta_title') : $title;
                }

            } elseif(atbdp_is_page('add-listing')){
                $atbdp_page = 'add-listing';

                $title = (get_directorist_option('add_listing_page_meta_title')) ? get_directorist_option('add_listing_page_meta_title') : $title;
            }
            elseif(atbdp_is_page('all-listing')){
                $atbdp_page = 'all-listing';
                $title = (get_directorist_option('all_listing_meta_title')) ? get_directorist_option('all_listing_meta_title') : $title;
            }elseif(atbdp_is_page('dashboard')){
                $atbdp_page = 'dashboard';
                $title = (get_directorist_option('dashboard_meta_title')) ? get_directorist_option('dashboard_meta_title') : $title;
            }elseif(atbdp_is_page('author')){
                $atbdp_page = 'author';
                $title = (get_directorist_option('author_profile_meta_title')) ? get_directorist_option('author_profile_meta_title') : $title;
            }elseif(atbdp_is_page('category')){
                $atbdp_page = 'category';
                $title = (get_directorist_option('category_meta_title')) ? get_directorist_option('category_meta_title') : $title;
            }elseif(atbdp_is_page('single_category')){
                $atbdp_page = 'single_category';
                $slug = get_query_var( 'atbdp_category' );
                $term = get_term_by( 'slug', $slug, 'at_biz_dir-category' );
                $title = (get_directorist_option('single_category_meta_title')) ? get_directorist_option('single_category_meta_title') : (!empty($term) ? $term->name : '');
            }elseif(atbdp_is_page('all_locations')){
                $atbdp_page = 'all_locations';
                $title = (get_directorist_option('all_locations_meta_title')) ? get_directorist_option('all_locations_meta_title') : $title;
            }elseif(atbdp_is_page('single_location')){
                $atbdp_page = 'single_location';
                $slug = get_query_var( 'atbdp_location' );
                $term = get_term_by( 'slug', $slug, 'at_biz_dir-location' );
                $title = (get_directorist_option('single_locations_meta_title')) ? get_directorist_option('single_locations_meta_title') : (!empty($term) ? $term->name : '');
            }elseif(atbdp_is_page('registration')){
                $atbdp_page = 'registration';
                $title = (get_directorist_option('registration_meta_title')) ? get_directorist_option('registration_meta_title') : $title;
            }elseif(atbdp_is_page('login')){
                $atbdp_page = 'login';
                $title = (get_directorist_option('login_meta_title')) ? get_directorist_option('login_meta_title') : $title;
            }



            /**
             * Filter page meta title to replace variables.
             *
             * @since 1.5.4
             * @param string $title The page title including variables.
             * @param string $atbdp_page The GeoDirectory page type if any.
             * @param string $sep The title separator symbol.
             */
            return apply_filters('atbdp_seo_meta_title', __($title, ATBDP_TEXTDOMAIN), $atbdp_page, $sep);

        }



    } // ends class
endif;
