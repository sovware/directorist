<?php
defined('ABSPATH') || die('Direct access is not allowed.');
if (!class_exists('ATBDP_SEO')) :
    /**
     * Class ATBDP_SEO
     */
    class ATBDP_SEO {
        public function __construct()
        {
            if ( empty( get_directorist_option( 'atbdp_enable_seo' ) ) ) { return; }

            if ( atbdp_yoast_is_active() ) {
                add_filter('wpseo_title', array($this, 'wpseo_title'));
                add_filter('wpseo_metadesc', array($this, 'wpseo_metadesc'));
                add_filter('wpseo_canonical', array($this, 'wpseo_canonical'));
                add_filter('wpseo_opengraph_url', array($this, 'wpseo_canonical'));
                add_filter('wpseo_opengraph_title', array($this, 'wpseo_opengraph_title'));
                //add_filter('wpseo_opengraph_image', array($this, 'wpseo_opengraph_image'));

                remove_action('wp_head', 'rel_canonical');

                add_filter('pre_get_document_title', array($this, 'atbdp_custom_page_title'), 100);
                add_filter('wp_title', array($this, 'atbdp_custom_page_title'), 100, 2);
                add_action('wp_head', array($this, 'atbdp_add_meta_keywords'), 100, 2);
                add_action('wp_head', array($this, 'atbdp_add_og_meta'), 100, 2);

                /* Exclude Multiple Taxonomies From Yoast SEO Sitemap */
                add_filter( 'wpseo_sitemap_exclude_taxonomy', [ $this, 'yoast_sitemap_exclude_taxonomy'], 10, 2 );
            } else {
                add_action('wp_head', array($this, 'atbdp_texonomy_canonical'));
            }

            add_filter( 'the_title', array( $this, 'atbdp_title_update' ), 10, 2 );

            // Rank Math Integration
            // --------------------------------------------
            // Meta Title
            add_filter( 'rank_math/frontend/title', function( $title ) {
                $seo_data = $this->get_seo_meta_data();

                return $seo_data['title'];
            });

            // Meta Description
            add_filter( 'rank_math/frontend/description', function( $description ) {
                $seo_data = $this->get_seo_meta_data();

                return $seo_data['description'];
            });
        }

        // yoast_sitemap_exclude_taxonomy
        public function yoast_sitemap_exclude_taxonomy( $value, $taxonomy ) {
            $taxonomy_to_exclude = [ ATBDP_CATEGORY, ATBDP_LOCATION, ATBDP_TAGS, ATBDP_DIRECTORY_TYPE ];
            if ( in_array( $taxonomy, $taxonomy_to_exclude ) ) return true;
        }

        // get_taxonomy_term
        public function get_taxonomy_term($id = null)
        {
            $id = ($id) ?  $id : get_the_ID();
            $post = get_post($id);
            $taxonomy = null;

            if ($post instanceof WP_Post && ($post->post_type == 'post' || $post->post_type == 'page')) {
                $CAT_page_ID = get_directorist_option('single_category_page');
                $LOC_page_ID = get_directorist_option('single_location_page');
                $Tag_page_ID = get_directorist_option('single_tag_page');
                // Change Location page title
                if ($post->ID == $LOC_page_ID) {
                    if ($slug = get_query_var('atbdp_location')) {
                        $term = get_term_by('slug', $slug, ATBDP_LOCATION);
                        $taxonomy = $term;
                    }
                }
                // Change Category page title
                if ($post->ID == $CAT_page_ID) {
                    if ($slug = get_query_var('atbdp_category')) {
                        $term = get_term_by('slug', $slug, ATBDP_CATEGORY);
                        $taxonomy = $term;
                    }
                }
                // Change Tag page title
                if ($post->ID == $Tag_page_ID) {
                    if ($slug = get_query_var('atbdp_tag')) {
                        $term = get_term_by('slug', $slug, ATBDP_TAGS);
                        $taxonomy = $term;
                    }
                }
            }

            return $taxonomy;
        }


        // wpseo_opengraph_title
        public function wpseo_opengraph_title($title)
        {
            $page_title = get_bloginfo('name');
            $custom_title = false;

            $term = $this->get_taxonomy_term();
            $custom_title = ($term) ? $term->name : false;

            $title = ($custom_title) ? "$custom_title | $page_title" : $title;
            return $title;
        }

        public function atbdp_title_update($title, $id = null)
        {
            $category_page_id = get_directorist_option( 'single_category_page', 0 );
            $location_page_id = get_directorist_option( 'single_location_page', 0 );

            if ( ! ( $category_page_id == $id || $location_page_id == $id ) ) {
                return $title;
            }

            // global $post;
            if (!is_admin() && !is_null($id)) {
                $term = $this->get_taxonomy_term();
                $title = (!empty($term)) ? $term->name : $title;
            }

            return $title;
        }

        public function wpseo_metadesc($desc)
        {
            global $post;
            $overwrite_yoast = get_directorist_option('overwrite_by_yoast');
            if (!isset($post)) return $desc;


            $CAT_page_ID = get_directorist_option('single_category_page');
            $LOC_page_ID = get_directorist_option('single_location_page');
            $Tag_page_ID = get_directorist_option('single_tag_page');

            if (($post->ID != $CAT_page_ID) && ($post->ID != $LOC_page_ID) && ($post->ID != $Tag_page_ID) && (!is_singular('at_biz_dir'))) {
                return $desc;
            }

            $wpseo_titles = get_option('wpseo_titles');

            $sep_options = WPSEO_Option_Titles::get_instance()->get_separator_options();

            if (isset($wpseo_titles['separator']) && isset($sep_options[$wpseo_titles['separator']])) {
                $sep = $sep_options[$wpseo_titles['separator']];
            } else {
                $sep = '-'; // Setting default separator if Admin didn't set it from backed
            }

            $replacements = array(
                '%%sep%%'              => $sep,
                '%%page%%'             => '',
                '%%primary_category%%' => '',
                '%%sitename%%'         => get_bloginfo('name')
            );

            $desc_template = '';
            if (is_singular('at_biz_dir')) {
                if (!empty($overwrite_yoast)) {
                    return '';
                }
            }

            // Category page
            if ($post->ID == $CAT_page_ID) {
                if (!empty($overwrite_yoast)) {
                    return '';
                }
                if ($slug = get_query_var('atbdp_category')) {

                    $term = get_term_by('slug', $slug, 'at_biz_dir-category');
                    $replacements['%%term_title%%'] = $term->name;

                    // Get Archive SEO desc
                    if (array_key_exists('metadesc-tax-at_biz_dir-category', $wpseo_titles)) {
                        $desc_template = $wpseo_titles['metadesc-tax-at_biz_dir-category'];
                    }

                    // Get Term SEO desc
                    $meta = get_option('wpseo_taxonomy_meta');

                    if (array_key_exists('at_biz_dir-category', $meta)) {

                        if (array_key_exists($term->term_id, $meta['at_biz_dir-category'])) {

                            if (array_key_exists('wpseo_desc', $meta['at_biz_dir-category'][$term->term_id])) {
                                $desc_template = $meta['at_biz_dir-category'][$term->term_id]['wpseo_desc'];
                            }
                        }
                    }
                }
            }

            // Location page
            if ($post->ID == $LOC_page_ID) {
                if (!empty($overwrite_yoast)) {
                    return '';
                }
                if ($slug = get_query_var('atbdp_location')) {

                    $term = get_term_by('slug', $slug, 'at_biz_dir-location');
                    $replacements['%%term_title%%'] = $term->name;

                    // Get Archive SEO desc
                    if (array_key_exists('metadesc-tax-at_biz_dir-location', $wpseo_titles)) {
                        $desc_template = $wpseo_titles['metadesc-tax-at_biz_dir-location'];
                    }

                    // Get Term SEO desc
                    $meta = get_option('wpseo_taxonomy_meta');

                    if (array_key_exists('at_biz_dir-location', $meta)) {

                        if (array_key_exists($term->term_id, $meta['at_biz_dir-location'])) {

                            if (array_key_exists('wpseo_desc', $meta['at_biz_dir-location'][$term->term_id])) {
                                $desc_template = $meta['at_biz_dir-location'][$term->term_id]['wpseo_desc'];
                            }
                        }
                    }
                }
            }

            // Tag page
            if ($post->ID == $Tag_page_ID) {
                if (!empty($overwrite_yoast)) {
                    return '';
                }
                if ($slug = get_query_var('atbdp_tag')) {

                    $term = get_term_by('slug', $slug, 'at_biz_dir-tags');
                    $replacements['%%term_title%%'] = $term->name;

                    // Get Archive SEO desc
                    if (array_key_exists('metadesc-tax-at_biz_dir-tags', $wpseo_titles)) {
                        $desc_template = $wpseo_titles['metadesc-tax-at_biz_dir-tags'];
                    }

                    // Get Term SEO desc
                    $meta = get_option('wpseo_taxonomy_meta');

                    if (array_key_exists('at_biz_dir-tags', $meta)) {

                        if (array_key_exists($term->term_id, $meta['at_biz_dir-tags'])) {

                            if (array_key_exists('wpseo_desc', $meta['at_biz_dir-tags'][$term->term_id])) {
                                $desc_template = $meta['at_biz_dir-tags'][$term->term_id]['wpseo_desc'];
                            }
                        }
                    }
                }
            }


            // Return
            if (!empty($desc_template)) {
                $desc = strtr($desc_template, $replacements);
            }

            return $desc;
        }


        public function wpseo_title($title, $id = null)
        {
            global $post;
            $overwrite_yoast = get_directorist_option('overwrite_by_yoast');
            if (!isset($post)) return $title;

            $CAT_page_ID = get_directorist_option('single_category_page');
            $LOC_page_ID = get_directorist_option('single_location_page');
            $Tag_page_ID = get_directorist_option('single_tag_page');

            if (($post->ID != $CAT_page_ID) && ($post->ID != $LOC_page_ID) && ($post->ID != $Tag_page_ID) && (!is_singular('at_biz_dir'))) {
                return $title;
            }

            $wpseo_titles = get_option('wpseo_titles');

            $sep_options = WPSEO_Option_Titles::get_instance()->get_separator_options();

            if (isset($wpseo_titles['separator']) && isset($sep_options[$wpseo_titles['separator']])) {
                $sep = $sep_options[$wpseo_titles['separator']];
            } else {
                $sep = '-'; // Setting default separator if Admin didn't set it from backed
            }

            $replacements = array(
                '%%sep%%'              => $sep,
                '%%page%%'             => '',
                '%%primary_category%%' => '',
                '%%sitename%%'         => get_bloginfo('name')
            );

            $title_template = '';
            if (is_singular('at_biz_dir')) {
                if (!empty($overwrite_yoast)) {
                    return '';
                }
            }
            // Category page
            if ($post->ID == $CAT_page_ID) {
                if (!empty($overwrite_yoast)) {
                    return '';
                }
                if ($slug = get_query_var('atbdp_category')) {

                    $term = get_term_by('slug', $slug, 'at_biz_dir-category');
                    $replacements['%%term_title%%'] = $term->name;

                    // Get Archive SEO title
                    if (array_key_exists('title-tax-at_biz_dir-category', $wpseo_titles)) {
                        $title_template = $wpseo_titles['title-tax-at_biz_dir-category'];
                    }

                    // Get Term SEO title
                    $meta = get_option('wpseo_taxonomy_meta');

                    if (array_key_exists('at_biz_dir-category', $meta)) {

                        if (array_key_exists($term->term_id, $meta['at_biz_dir-category'])) {

                            if (array_key_exists('wpseo_title', $meta['at_biz_dir-category'][$term->term_id])) {
                                $title_template = $meta['at_biz_dir-category'][$term->term_id]['wpseo_title'];
                            }
                        }
                    }
                }
            }

            // Location page
            if ($post->ID == $LOC_page_ID) {
                if (!empty($overwrite_yoast)) {
                    return '';
                }

                $slug = get_query_var('atbdp_location');
                if ( ! empty( $slug ) ) {
                    $term = get_term_by('slug', $slug, 'at_biz_dir-location');
                    $replacements['%%term_title%%'] = $term->name;

                    // Get Archive SEO title
                    if (array_key_exists('title-tax-at_biz_dir-location', $wpseo_titles)) {
                        $title_template = $wpseo_titles['title-tax-at_biz_dir-location'];
                    }

                    // Get Term SEO title
                    $meta = get_option('wpseo_taxonomy_meta');

                    if (array_key_exists('at_biz_dir-location', $meta)) {

                        if (array_key_exists($term->term_id, $meta['at_biz_dir-location'])) {

                            if (array_key_exists('wpseo_title', $meta['at_biz_dir-location'][$term->term_id])) {
                                $title_template = $meta['at_biz_dir-location'][$term->term_id]['wpseo_title'];
                            }
                        }
                    }
                }
            }

            // Tag page
            if ($post->ID == $Tag_page_ID) {
                if (!empty($overwrite_yoast)) {
                    return '';
                }
                if ($slug = get_query_var('atbdp_tag')) {

                    $term = get_term_by('slug', $slug, 'at_biz_dir-tags');
                    $replacements['%%term_title%%'] = $term->name;

                    // Get Archive SEO title
                    if (array_key_exists('title-tax-at_biz_dir-tags', $wpseo_titles)) {
                        $title_template = $wpseo_titles['title-tax-at_biz_dir-tags'];
                    }

                    // Get Term SEO title
                    $meta = get_option('wpseo_taxonomy_meta');

                    if (array_key_exists('at_biz_dir-tags', $meta)) {

                        if (array_key_exists($term->term_id, $meta['at_biz_dir-tags'])) {

                            if (array_key_exists('wpseo_title', $meta['at_biz_dir-tags'][$term->term_id])) {
                                $title_template = $meta['at_biz_dir-tags'][$term->term_id]['wpseo_title'];
                            }
                        }
                    }
                }
            }

            // Return
            if (!empty($title_template)) {
                $title = strtr($title_template, $replacements);
            }

            return $title;
        }

        public function atbdp_texonomy_canonical()
        {
            global $post;

            if (!isset($post)) return;

            $CAT_page_ID = get_directorist_option('single_category_page');
            $LOC_page_ID = get_directorist_option('single_location_page');
            $Tag_page_ID = get_directorist_option('single_tag_page');
            $url = get_permalink($post->ID);
            // Location page
            if ($post->ID == $LOC_page_ID) {
                if ($slug = get_query_var('atbdp_location')) {
                    $term = get_term_by('slug', $slug, ATBDP_LOCATION);
                    $url = ATBDP_Permalink::atbdp_get_location_page($term);
                }
            }

            // Category page
            if ($post->ID == $CAT_page_ID) {
                if ($slug = get_query_var('atbdp_category')) {
                    $term = get_term_by('slug', $slug, ATBDP_CATEGORY);
                    $url = ATBDP_Permalink::atbdp_get_category_page($term);
                }
            }

            // User listings page
            if ($post->ID == $Tag_page_ID) {

                if ($slug = get_query_var('atbdp_tag')) {
                    $term = get_term_by('slug', $slug, ATBDP_TAGS);
                    $url = ATBDP_Permalink::atbdp_get_tag_page($term);
                }
            }
            echo "<link rel='canonical' href='" . esc_url($url) . "' />\n";
        }


        /**
         * Override the Yoast SEO canonical URL on our category, location & user_listings pages.
         *
         * @since     1.6.1
         * @param     array    $url    The Yoast canonical URL.
         * @return                     Modified canonical URL.
         */
        public function wpseo_canonical($url)
        {
            global $post;
            if (!isset($post)) return $url;

            $CAT_page_ID = get_directorist_option('single_category_page');
            $LOC_page_ID = get_directorist_option('single_location_page');
            $Tag_page_ID = get_directorist_option('single_tag_page');

            // Location page
            if ($post->ID == $LOC_page_ID) {

                if ($slug = get_query_var('atbdp_location')) {
                    $term = get_term_by('slug', $slug, ATBDP_LOCATION);
                    $url = ATBDP_Permalink::atbdp_get_location_page($term);
                }
            }

            // Category page
            if ($post->ID == $CAT_page_ID) {

                if ($slug = get_query_var('atbdp_category')) {
                    $term = get_term_by('slug', $slug, ATBDP_CATEGORY);
                    $url = ATBDP_Permalink::atbdp_get_category_page($term);
                }
            }

            // User listings page
            if ($post->ID == $Tag_page_ID) {

                if ($slug = get_query_var('atbdp_tag')) {
                    $term = get_term_by('slug', $slug, ATBDP_TAGS);
                    $url = ATBDP_Permalink::atbdp_get_tag_page($term);
                }
            }

            return $url;
        }

        public function atbdp_add_meta_keywords()
        {   
            $seo_meta = $this->get_seo_meta_data();
            $meta_desc = $seo_meta['description'];
            if ( ! empty( $meta_desc ) ) {
                /**
                 * Filter SEO meta description.
                 *
                 * @since 1.0.0
                 *
                 * @param string $meta_desc Meta description content.
                 */
                echo apply_filters('atbdp_seo_meta_description', '<meta name="description" content="' . $meta_desc . '" />', $meta_desc) . "\n";
            }
        }


        /**
         * Set custom page title.
         *
         * @since 1.0.0
         * @since 1.6.18 Option added to disable overwrite by Yoast SEO titles & metas on GD pages.
         * @package atbdpectory
         * @global object $wp WordPress object.
         * @param string $title Old title.
         * @param string $sep Title separator.
         * @return string Modified title.
         */
        public function atbdp_custom_page_title($title = '', $sep = '')
        {
            // global $wp;
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
            
            $seo_meta = $this->get_seo_meta_data();
            $page = $seo_meta['page'];
            if ( ! empty( $seo_meta['title'] ) ) {
                $title = $seo_meta['title'];
            }
            
            /**
             * Filter page meta title to replace variables.
             *
             * @since 1.5.4
             * @param string $title The page title including variables.
             * @param string $atbdp_page The atbdpectory page type if any.
             * @param string $sep The title separator symbol.
             */
            return apply_filters('atbdp_seo_meta_title', __($title, 'directorist'), $page, $sep);
        }

        // atbdp_add_og_meta
        public function atbdp_add_og_meta() {
            $seo_meta_data       = $this->get_seo_meta_data();
            $og_metas = [
                'site_name'   => [
                    'property' => 'og:site_name',
                    'content'  => '',
                ],
                'title' => [
                    'property' => 'og:title',
                    'content'  =>  '',
                ],
                'description' => [
                    'property' => 'og:description',
                    'content'  => '',
                ],
                'url' => [
                    'property' => 'og:url',
                    'content'  => '',
                ],
                'image' => [
                    'property' => 'og:image',
                    'content'  => '',
                ],
                'twitter_card' => [
                    'name'    => 'twitter:card',
                    'content' => '',
                ],
                'twitter_title' => [
                    'property' => 'og:twitter_title',
                    'content'  => '',
                ],
                'twitter_description' => [
                    'property' => 'og:twitter_description',
                    'content'  => '',
                ],
                'twitter_image' => [
                    'property' => 'og:twitter_image',
                    'content'  => '',
                ]
            ];

            // Sync the data
            foreach ( $seo_meta_data as $meta_key => $meta_value ) {
                if ( ! empty( $og_metas[ $meta_key ] ) ) {
                    $og_metas[ $meta_key ]['content'] = $seo_meta_data[ $meta_key ];
                }
            }

            if ( ! empty( $seo_meta_data['site_name'] ) && ! empty( $og_metas['title'] ) ) {
                $site_name           = $seo_meta_data['site_name'];
                $title               = $og_metas['title']['content'];

                $title_has_site_name = preg_match( '/'. $site_name .'/', $title ) ;
                $og_metas['title']['content'] = ( $title_has_site_name ) ? $title : $title . ' | ' . $site_name;
            }

            $og_metas = apply_filters( 'atbdp_og_metas', $og_metas );

            if ( empty( $og_metas ) || ! is_array( $og_metas ) ) {
                return;
            }

            foreach ( $og_metas as $meta_key => $meta_attributes ) {
                $props = '';
                foreach ( $meta_attributes as $attr => $value ) {
                    if ( ! empty( $meta_attributes['content'] ) ) {
                        $props .= "{$attr}='{$value}' ";
                    }
                }

                $props = trim( $props );
                if ( ! empty( $props ) ) {
                    echo "<meta {$props} />\n";
                }
            }
        }

        // get_seo_meta_data
        public function get_seo_meta_data()
        {
            global $wp, $post;
            
            $desc      = esc_html( get_the_excerpt() );
            $meta_desc = ( strlen( $desc ) > 200 ) ? substr( $desc, 0, 200 ) . "..." : $desc;

            $seo_meta = [
                'site_name'    => get_bloginfo('name'),
                'title'        => get_the_title(),
                'description'  => $meta_desc,
                'page'         => '',
                'current_page' => home_url( add_query_arg( array(), $wp->request ) ) . '/',
                'image'        => '',
            ];

            $meta_desc         = '';
            $CAT_page_ID       = get_directorist_option('single_category_page');
            $LOC_page_ID       = get_directorist_option('single_location_page');
            $TAG_page_ID       = get_directorist_option('single_tag_page');
            $home_page_id      = get_option('page_on_front');
            $default_thumbnail = get_the_post_thumbnail_url();
            $default_thumbnail = ! empty( $default_thumbnail ) ? $default_thumbnail : get_the_post_thumbnail_url( $home_page_id );

            $seo_meta['image'] = $default_thumbnail;

            if (is_singular('at_biz_dir')) {
                $desc      = esc_html( get_the_excerpt() );
                $meta_desc = ( strlen( $desc ) > 200 ) ? substr( $desc, 0, 200 ) . "..." : $desc;

                $seo_meta['title']         = get_the_title();
                $seo_meta['desc']          = esc_html( get_the_excerpt() );
                $seo_meta['description']     = $meta_desc;
                $seo_meta['image'] = get_the_post_thumbnail_url();
                
                if ( empty( $seo_meta['image'] ) ) {
                    $listing_img_id = get_post_meta( get_the_ID(), '_listing_prv_img', true);
                    
                    if ( empty( $listing_img_id ) || ! is_string( $listing_img_id ) || ! is_numeric( $listing_img_id ) ) {
                        $listing_img_id = get_post_meta( get_the_ID(), '_listing_img', true );
                        $listing_img_id = ( ! empty( $listing_img_id ) ) ? $listing_img_id[0] : null;
                    }
                    
                    $seo_meta['image'] = ( ! empty($listing_img_id) || is_string( $listing_img_id ) || is_int( $listing_img_id )) ? wp_get_attachment_url($listing_img_id) : '';
                }
            }

            if (atbdp_is_page('home')) {
                $seo_meta['page']      = 'home';
                $seo_meta['title']     = (get_directorist_option('homepage_meta_title')) ? get_directorist_option('homepage_meta_title') : $seo_meta['title'];
                $seo_meta['description'] = (get_directorist_option('homepage_meta_desc')) ? get_directorist_option('homepage_meta_desc') : $seo_meta['description'];
            } elseif (atbdp_is_page('search-result')) {
                $seo_meta['page'] = 'search-result';

                $query = (isset($_GET['q']) && ('' !== $_GET['q'])) ? ucfirst($_GET['q']) : '';
                $category = (isset($_GET['in_cat']) && ('' !== $_GET['in_cat'])) ? ucfirst($_GET['in_cat']) : '';
                $location = (isset($_GET['in_loc']) && ('' !== $_GET['in_loc'])) ? ucfirst($_GET['in_loc']) : '';
                $category =  get_term_by('id', $category, ATBDP_CATEGORY);
                $location =  get_term_by('id', $location, ATBDP_LOCATION);


                $in_s_string_text = !empty($query) ? sprintf(__('%s', 'directorist'), $query) : '';
                $in_cat_text      = !empty($category) ? sprintf(__(' %s %s ', 'directorist'), !empty($query) ? 'from' : '', $category->name) : '';
                $in_loc_text      = !empty($location) ? sprintf(__('%s %s', 'directorist'), !empty($category) ? 'in' : '', $location->name) : '';

                $how_to = get_directorist_option('meta_title_for_search_result', 'searched_value');
                if ('searched_value' === $how_to) {
                    if (!empty($query) || !empty($category) || !empty($location)) {
                        $seo_meta['title'] = $in_s_string_text . $in_cat_text . $in_loc_text;
                    }
                } else {
                    $seo_meta['title'] = (get_directorist_option('search_result_meta_title')) ? get_directorist_option('search_result_meta_title') : $seo_meta['title'];
                }

                $seo_meta['description'] = (get_directorist_option('search_result_meta_desc')) ? get_directorist_option('search_result_meta_desc') : $seo_meta['description'];
            } elseif (atbdp_is_page('add-listing')) {
                $seo_meta['page'] = 'add-listing';
                $seo_meta['title'] = (get_directorist_option('add_listing_page_meta_title')) ? get_directorist_option('add_listing_page_meta_title') : $seo_meta['title'];
                $seo_meta['description'] = (get_directorist_option('add_listing_page_meta_desc')) ? get_directorist_option('add_listing_page_meta_desc') : $seo_meta['description'];
            } elseif (atbdp_is_page('all-listing')) {
                $seo_meta['page'] = 'all-listing';
                $seo_meta['title'] = (get_directorist_option('all_listing_meta_title')) ? get_directorist_option('all_listing_meta_title') : $seo_meta['title'];
                $seo_meta['description'] = (get_directorist_option('all_listing_meta_desc')) ? get_directorist_option('all_listing_meta_desc') : $seo_meta['description'];
            } elseif (atbdp_is_page('dashboard')) {
                $seo_meta['page'] = 'dashboard';
                $seo_meta['title'] = (get_directorist_option('dashboard_meta_title')) ? get_directorist_option('dashboard_meta_title') : $seo_meta['title'];
                $seo_meta['description'] = (get_directorist_option('dashboard_meta_desc')) ? get_directorist_option('dashboard_meta_desc') : $seo_meta['description'];
            } elseif (atbdp_is_page('author')) {
                $seo_meta['page'] = 'author';
                $seo_meta['title'] = (get_directorist_option('author_profile_meta_title')) ? get_directorist_option('author_profile_meta_title') : $seo_meta['title'];
                $seo_meta['description'] = (get_directorist_option('author_page_meta_desc')) ? get_directorist_option('author_page_meta_desc') : $seo_meta['description'];
            } elseif (atbdp_is_page('category')) {
                $seo_meta['page'] = 'category';
                $seo_meta['title'] = (get_directorist_option('category_meta_title')) ? get_directorist_option('category_meta_title') : $seo_meta['title'];
                $seo_meta['description'] = (get_directorist_option('category_meta_desc')) ? get_directorist_option('category_meta_desc') : $seo_meta['description'];
            } elseif (atbdp_is_page('single_category')) {
                $seo_meta['page'] = 'single_category';
                $slug = get_query_var('atbdp_category');
                $term = get_term_by('slug', $slug, ATBDP_CATEGORY);
                $seo_meta['title'] = (get_directorist_option('single_category_meta_title')) ? get_directorist_option('single_category_meta_title') : (!empty($term) ? $term->name : $seo_meta['title']);
                $seo_meta['description'] = (get_directorist_option('single_category_meta_desc')) ? get_directorist_option('single_category_meta_desc') : $seo_meta['description'];
                
                // show term description as meta description first
                if ( $post->ID == $CAT_page_ID && $slug && ! empty( $term ) ) {
                    $seo_meta['description'] = ! empty( $term->description ) ? $term->description : $seo_meta['description'];
                    $thumb_id = get_term_meta( $term->term_id, 'image', true );
                    $seo_meta['image'] = wp_get_attachment_url( $thumb_id );
                }

                if ( atbdp_yoast_is_active() ) {
                    $seo_meta = $this->sync_with_yoast_seo_meta([
                        'url'      => get_term_link( $slug, ATBDP_CATEGORY ),
                        'seo_meta' => $seo_meta,
                    ]);
                }

            } elseif (atbdp_is_page('all_locations')) {
                $seo_meta['page'] = 'all_locations';
                $seo_meta['title'] = (get_directorist_option('all_locations_meta_title')) ? get_directorist_option('all_locations_meta_title') : $seo_meta['title'];
                $seo_meta['description'] = (get_directorist_option('all_locations_meta_desc')) ? get_directorist_option('all_locations_meta_desc') : $seo_meta['description'];
            } elseif (atbdp_is_page('single_location')) {
                $atbdp_page = 'single_location';
                $slug = get_query_var('atbdp_location');
                $term = get_term_by('slug', $slug, ATBDP_LOCATION);
                $seo_meta['title'] = (get_directorist_option('single_locations_meta_title')) ? get_directorist_option('single_locations_meta_title') : (!empty($term) ? $term->name : $seo_meta['title']);
                $seo_meta['description'] = (get_directorist_option('single_locations_meta_desc')) ? get_directorist_option('single_locations_meta_desc') : $seo_meta['description'];
                
                // show term description as meta description first
                if ($post->ID == $LOC_page_ID && $slug && ! empty( $term )) {
                    $seo_meta['description'] = ! empty( $term->description ) ? $term->description : $seo_meta['description'];
                    $thumb_id = get_term_meta( $term->term_id, 'image', true );
                    $seo_meta['image'] = wp_get_attachment_url( $thumb_id );
                }

                if ( atbdp_yoast_is_active() ) {
                    $seo_meta = $this->sync_with_yoast_seo_meta([
                        'url'      => get_term_link( $slug, ATBDP_LOCATION ),
                        'seo_meta' => $seo_meta,
                    ]);
                }

            } elseif (atbdp_is_page('single_tag')) {
                $seo_meta['page'] = 'single_tag';
                $slug = get_query_var('atbdp_tag');
                $term = get_term_by('slug', $slug, ATBDP_TAGS);
                $seo_meta['title'] = !empty($term) ? $term->name : '';

                // show term description as meta description first
                if ($post->ID == $TAG_page_ID && $slug && ! empty( $term )) {
                    $seo_meta['description'] = ! empty( $term->description ) ? $term->description : $seo_meta['description'];
                    $thumb_id = get_term_meta( $term->term_id, 'image', true );
                    $seo_meta['image'] = wp_get_attachment_url( $thumb_id );
                }

                if ( atbdp_yoast_is_active() ) {
                    $seo_meta = $this->sync_with_yoast_seo_meta([
                        'url'      => get_term_link( $slug, ATBDP_TAGS ),
                        'seo_meta' => $seo_meta,
                    ]);
                }
                
            } elseif (atbdp_is_page('registration')) {
                $seo_meta['page'] = 'registration';
                $seo_meta['title'] = (get_directorist_option('registration_meta_title')) ? get_directorist_option('registration_meta_title') : $seo_meta['title'] ;
                $seo_meta['description'] = (get_directorist_option('registration_meta_desc')) ? get_directorist_option('registration_meta_desc') : $seo_meta['description'];
            } elseif (atbdp_is_page('login')) {
                $seo_meta['page'] = 'login';
                $seo_meta['title'] = (get_directorist_option('login_meta_title')) ? get_directorist_option('login_meta_title') : $seo_meta['title'];
                $seo_meta['description'] = (get_directorist_option('login_meta_desc')) ? get_directorist_option('login_meta_desc') : $seo_meta['description'];
            }

            if ( $seo_meta['description'] ) {
                $seo_meta['description'] = stripslashes_deep( $seo_meta['description'] );
                /**
                 * Filter page description to replace variables.
                 *
                 * @since 1.5.4
                 *
                 * @param string $meta_desc   The page description including variables.
                 * @param string $gd_page The atbdpectory page type if any.
                 */
                $seo_meta['description'] = apply_filters('atbdp_seo_meta_description_pre', __($seo_meta['description'], 'directorist'), $seo_meta['page'], '');
            }
            
            return $seo_meta;
        }

        // sync_with_yoast_seo_meta
        public function sync_with_yoast_seo_meta( array $args = [] ) {
            $default = [
                'url' => '', 'seo_meta' => [],
            ];

            $args       = array_merge( $default, $args );
            $url        = $args['url'];
            $seo_meta   = $args['seo_meta'];

            if ( function_exists( 'YoastSEO' ) ) {
                try {
                    $yoast_meta = YoastSEO()->meta->for_url( $url );
                } catch ( Exception $e ) {
                    $yoast_meta = '';
                }
            }

            if ( empty( $yoast_meta ) ) {
                return $seo_meta;
            }

            // Image
            $og_images = $yoast_meta->open_graph_images;
            $og_image  = ( ! empty( $og_images ) && is_array( $og_images ) ) ? reset( $og_images )['url'] : '';

            $yoast_seo_meta = [
                'title'               => $yoast_meta->open_graph_title,
                'description'         => $yoast_meta->open_graph_description,
                'image'               => $og_image,
                'twitter_title'       => $yoast_meta->twitter_title,
                'twitter_description' => $yoast_meta->twitter_description,
                'twitter_image'       => $yoast_meta->twitter_image,
            ];

            foreach ( $yoast_seo_meta as $yoast_meta_key => $yoast_meta_value  ) {
                $seo_meta[ $yoast_meta_key ] = ( ! empty( $yoast_meta_value ) ) ? $yoast_meta_value : $seo_meta[ $yoast_meta_key ];
            }
            
            return $seo_meta;
        }

    } // ends class
endif;
