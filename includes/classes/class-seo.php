<?php
defined('ABSPATH') || die('Direct access is not allowed.');
if (!class_exists('ATBDP_SEO')) :
    /**
     * Class ATBDP_SEO
     */
    class ATBDP_SEO {
        public function __construct()
        {
            if ( empty( get_directorist_option( 'atbdp_enable_seo' ) ) ) return;

            add_filter( 'the_title', array( $this, 'update_taxonomy_page_title' ), 10, 2 );
            add_filter( 'single_post_title', array( $this, 'update_taxonomy_single_page_title' ), 10, 2 );
            add_filter('pre_get_document_title', array($this, 'atbdp_custom_page_title'), 10);
            add_filter('wp_title', array($this, 'atbdp_custom_page_title'), 10, 2);
            add_action('wp_head', array($this, 'atbdp_add_meta_keywords'), 10, 2);
            add_action('wp_head', array($this, 'add_opengraph_meta'), 10, 2);

            if ( atbdp_yoast_is_active() ) {
                add_filter('wpseo_title', array($this, 'wpseo_title'));
                add_filter('wpseo_metadesc', array($this, 'wpseo_metadesc'));
                add_filter('wpseo_canonical', array($this, 'wpseo_canonical'));
                add_filter('wpseo_opengraph_url', array($this, 'wpseo_canonical'));
                add_filter('wpseo_opengraph_title', array($this, 'wpseo_opengraph_title'));
                //add_filter('wpseo_opengraph_image', array($this, 'wpseo_opengraph_image'));

                remove_action('wp_head', 'rel_canonical');
                /* Exclude Multiple Taxonomies From Yoast SEO Sitemap */
                add_filter( 'wpseo_sitemap_exclude_taxonomy', [ $this, 'yoast_sitemap_exclude_taxonomy'], 10, 2 );
            } else {
                add_action('wp_head', array($this, 'add_texonomy_canonical'));
                add_action( 'wp', [ $this, 'remove_duplicate_canonical' ] );
            }

            /**
             * Removes Rank Math SEO meta data
             * 
             * Rank math SEO meta data has been integrated
             * to add_opengraph_meta function. So we are removing
             * meta data's from Rank Math to prevent printing 
             * duplicate meta data on frontend
             */
            $this->disable_rankmath_for_directorist_pages();
        }


        // Disable rankmath for directorist pages
        public function disable_rankmath_for_directorist_pages() {
            // Meta Title
            add_filter( 'rank_math/frontend/title', [ $this, 'disable_rankmath_frontend_meta_for_directorist_pages' ], 20, 1 );
            add_filter( 'rank_math/frontend/description', [ $this, 'disable_rankmath_frontend_meta_for_directorist_pages' ], 20, 1);

            // Opengraph
            add_action( 'rank_math/head', [ $this, 'disable_rankmath_opengraph_meta_for_directorist_pages' ], 20, 0 );
        }

        // Disable rankmath frontend meta for directorist pages
        public function disable_rankmath_frontend_meta_for_directorist_pages( $content ) {
            $current_directorist_page = $this->get_directorist_current_page();
            if ( ! empty( $current_directorist_page ) ) {
                return '';
            }
            
            return $content;
        }

        // Disable rankmath opengraph meta for directorist pages
        public function disable_rankmath_opengraph_meta_for_directorist_pages() {
            $current_directorist_page = $this->get_directorist_current_page();

            if ( empty( $current_directorist_page ) ) {
                return;
            }

            remove_all_actions( 'rank_math/opengraph/facebook' );
            remove_all_actions( 'rank_math/opengraph/twitter' );
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

        public function update_taxonomy_page_title($title, $id = null)
        {
            if ( is_null( $id ) ) return $title;

            return $this->get_taxonomy_page_title( $title, $id );
        }

        public function update_taxonomy_single_page_title( $title, $post )
        {
            if ( is_null( $post ) ) return $title;

            return $this->get_taxonomy_page_title( $title, $post->ID );
        }

        public function get_taxonomy_page_title( $default_title, $page_id )
        {
            if ( is_admin() ) { return $default_title; }
            if ( ! is_int( $page_id ) ) { return $default_title; }

            $category_page_id = get_directorist_option( 'single_category_page', 0 );
            $location_page_id = get_directorist_option( 'single_location_page', 0 );

            if ( ! ( $category_page_id == $page_id || $location_page_id == $page_id ) ) {
                return $default_title;
            }

            $term = $this->get_taxonomy_term();
            $page_title = ( ! empty( $term ) ) ? $term->name : $default_title;

            return $page_title;
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

        // remove_duplicate_canonical
        public function remove_duplicate_canonical() {
            global $post;

            if ( ! isset( $post ) ) return;

            $CAT_page_ID = get_directorist_option('single_category_page');
            $LOC_page_ID = get_directorist_option('single_location_page');
            $Tag_page_ID = get_directorist_option('single_tag_page');

            $targeted_pages = [ $CAT_page_ID, $LOC_page_ID, $Tag_page_ID ];
            if ( ! in_array( $post->ID, $targeted_pages ) ) return;

            remove_action('wp_head', 'rel_canonical');
        }

        public function add_texonomy_canonical()
        {
            global $post;

            $CAT_page_ID = get_directorist_option('single_category_page');
            $LOC_page_ID = get_directorist_option('single_location_page');
            $Tag_page_ID = get_directorist_option('single_tag_page');

            $targeted_pages = [ $CAT_page_ID, $LOC_page_ID, $Tag_page_ID ];
            if ( ! in_array( $post->ID, $targeted_pages ) ) return;

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

        // add_opengraph_meta
        public function add_opengraph_meta() {
            $seo_meta_data = $this->get_seo_meta_data();

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
                    'content' => 'summary_large_image',
                ],
                'twitter_title' => [
                    'property' => 'twitter:title',
                    'content'  => '',
                ],
                'twitter_description' => [
                    'property' => 'twitter:description',
                    'content'  => '',
                ],
                'twitter_image' => [
                    'property' => 'twitter:image',
                    'content'  => '',
                ]
            ];

            // Sync the data
            foreach ( $seo_meta_data as $meta_key => $meta_value ) {
                if ( ! empty( $og_metas[ $meta_key ] ) ) {
                    $og_metas[ $meta_key ]['content'] = $seo_meta_data[ $meta_key ];
                }
            }

            // Adjust the title
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

            // Adjust Twitter Meta
            if ( empty( $og_metas['twitter_title']['content'] ) ) {
                $og_metas['twitter_title']['content'] = $og_metas['title']['content'];
            }

            if ( empty( $og_metas['twitter_description']['content'] ) ) {
                $og_metas['twitter_description']['content'] = $og_metas['description']['content'];
            }

            if ( empty( $og_metas['twitter_image']['content'] ) ) {
                $og_metas['twitter_image']['content'] = $og_metas['image']['content'];
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
        public function get_seo_meta_data() {
            global $wp;
            
            $desc      = esc_html( get_the_excerpt() );
            $meta_desc = ( strlen( $desc ) > 200 ) ? substr( $desc, 0, 200 ) . "..." : $desc;

            $default_seo_meta = [
                'site_name'    => get_bloginfo('name'),
                'title'        => get_the_title(),
                'description'  => $meta_desc,
                'page'         => '',
                'current_page' => home_url( add_query_arg( array(), $wp->request ) ) . '/',
                'image'        => '',
            ];

            $current_page = self::get_directorist_current_page();
            $callback = "get_{$current_page}_page_seo_meta";
            $seo_meta = $default_seo_meta;

            if ( method_exists( $this, $callback ) ) {
                $seo_meta = call_user_func_array( [ $this, $callback ], [ $seo_meta ] );
            }

            $title = $seo_meta['title'] . ' | ' . get_bloginfo('name');
            $seo_meta['title'] = apply_filters( 'directorist_seo_meta_title', $title, $seo_meta['title'] );

            if ( ! empty( $seo_meta['description'] ) ) {
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

        // get_home_page_seo_meta
        public function get_home_page_seo_meta( $default_seo_meta = [] ) {
            $seo_meta = [];
            $seo_meta['page'] = 'home';

            // Title
            $settings_title = get_directorist_option('homepage_meta_title');
            if ( ! empty( $settings_title ) ) $seo_meta['title'] = $settings_title;

            // Description
            $settings_description = get_directorist_option('homepage_meta_desc');
            if ( ! empty( $settings_description ) ) $seo_meta['description'] = $settings_description;

            $seo_meta = ( is_array( $default_seo_meta ) ) ? array_merge( $default_seo_meta, $seo_meta ) : $seo_meta;

            // If Rank math is active
            if ( directorist_is_active_rankmath() ) {
                $seo_meta = $this->sync_with_rank_math_seo_meta([
                    'post_id'  => get_the_ID(),
                    'seo_meta' => $seo_meta,
                ]);
            }

            return $seo_meta;
        }

        // get_search_result_page_seo_meta
        public function get_search_result_page_seo_meta( $default_seo_meta = [] ) {
            $seo_meta = [];
            $seo_meta['page'] = 'search-result';

            // Title
            $settings_title = get_directorist_option('search_result_meta_title');
            if ( ! empty( $settings_title ) ) $seo_meta['title'] = $settings_title;

            $query    = ( isset( $_GET['q'] ) && ( '' !== $_GET['q'] ) ) ? ucfirst( $_GET['q'] ) : '';
            $category = ( isset( $_GET['in_cat'] ) && ( '' !== $_GET['in_cat'] ) ) ? ucfirst( $_GET['in_cat'] ) : '';
            $location = ( isset( $_GET['in_loc'] ) && ( '' !== $_GET['in_loc'] ) ) ? ucfirst( $_GET['in_loc'] ) : '';
            
            $category = get_term_by( 'id', $category, ATBDP_CATEGORY );
            $location = get_term_by( 'id', $location, ATBDP_LOCATION );

            $in_s_string_text = ! empty($query) ? sprintf(__( '%s', 'directorist' ), $query ) : '';
            $in_cat_text      = ! empty($category) ? sprintf(__( ' %s %s ', 'directorist' ), ! empty( $query ) ? 'from' : '', $category->name ) : '';
            $in_loc_text      = ! empty($location) ? sprintf(__( '%s %s', 'directorist' ), ! empty( $category ) ? 'in' : '', $location->name ) : '';

            $how_to = get_directorist_option('meta_title_for_search_result', 'searched_value');
            
            if ( 'searched_value' === $how_to ) {
                if ( ! empty( $query ) || ! empty( $category ) || ! empty( $location ) ) {
                    $seo_meta['title'] = $in_s_string_text . $in_cat_text . $in_loc_text;
                }
            }

            // Description
            $settings_description = get_directorist_option('search_result_meta_desc');
            if ( ! empty( $settings_description ) ) $seo_meta['description'] = $settings_description;

            $seo_meta = ( is_array( $default_seo_meta ) ) ? array_merge( $default_seo_meta, $seo_meta ) : $seo_meta;

            // If Rank math is active
            if ( directorist_is_active_rankmath() ) {
                $seo_meta = $this->sync_with_rank_math_seo_meta([
                    'post_id'  => get_the_ID(),
                    'seo_meta' => $seo_meta,
                ]);
            }

            return $seo_meta;
        }

        // get_add_listing_page_seo_meta
        public function get_add_listing_page_seo_meta( $default_seo_meta = [] ) {
            $seo_meta = [];
            $seo_meta['page'] = 'add-listing';

            // Title
            $settings_title = get_directorist_option('add_listing_page_meta_title');
            if ( ! empty( $settings_title ) ) $seo_meta['title'] = $settings_title;

            // Description
            $settings_description = get_directorist_option('all_listing_meta_desc');
            if ( ! empty( $settings_description ) ) $seo_meta['description'] = $settings_description;

            $seo_meta = ( is_array( $default_seo_meta ) ) ? array_merge( $default_seo_meta, $seo_meta ) : $seo_meta;


            // If Rank math is active
            if ( directorist_is_active_rankmath() ) {
                $seo_meta = $this->sync_with_rank_math_seo_meta([
                    'post_id'  => get_the_ID(),
                    'seo_meta' => $seo_meta,
                ]);
            }

            return $seo_meta;
        }

        // get_all_listing_page_seo_meta
        public function get_all_listing_page_seo_meta( $default_seo_meta = [] ) {
            $seo_meta = [];
            $seo_meta['page'] = 'all-listing';

            // Title
            $settings_title = get_directorist_option('all_listing_meta_title');
            if ( ! empty( $settings_title ) ) $seo_meta['title'] = $settings_title;

            // Description
            $settings_description = get_directorist_option('all_listing_meta_desc');
            if ( ! empty( $settings_description ) ) $seo_meta['description'] = $settings_description;

            $seo_meta = ( is_array( $default_seo_meta ) ) ? array_merge( $default_seo_meta, $seo_meta ) : $seo_meta;

            // If Rank math is active
            if ( directorist_is_active_rankmath() ) {
                $seo_meta = $this->sync_with_rank_math_seo_meta([
                    'post_id'  => get_the_ID(),
                    'seo_meta' => $seo_meta,
                ]);
            }

            return $seo_meta;
        }

        // get_dashboard_page_seo_meta
        public function get_dashboard_page_seo_meta( $default_seo_meta = [] ) {
            $seo_meta = [];
            $seo_meta['page'] = 'dashboard';

            // Title
            $settings_title = get_directorist_option('dashboard_meta_title');
            if ( ! empty( $settings_title ) ) $seo_meta['title'] = $settings_title;

            // Description
            $settings_description = get_directorist_option('dashboard_meta_desc');
            if ( ! empty( $settings_description ) ) $seo_meta['description'] = $settings_description;

            $seo_meta = ( is_array( $default_seo_meta ) ) ? array_merge( $default_seo_meta, $seo_meta ) : $seo_meta;

            // If Rank math is active
            if ( directorist_is_active_rankmath() ) {
                $seo_meta = $this->sync_with_rank_math_seo_meta([
                    'post_id'  => get_the_ID(),
                    'seo_meta' => $seo_meta,
                ]);
            }

            return $seo_meta;
        }

        // get_author_page_seo_meta
        public function get_author_page_seo_meta( $default_seo_meta = [] ) {
            $seo_meta = [];
            $seo_meta['page'] = 'author';

            // Title
            $settings_title = get_directorist_option('author_profile_meta_title');
            if ( ! empty( $settings_title ) ) $seo_meta['title'] = $settings_title;

            // Description
            $settings_description = get_directorist_option('author_page_meta_desc');
            if ( ! empty( $settings_description ) ) $seo_meta['description'] = $settings_description;

            $seo_meta = ( is_array( $default_seo_meta ) ) ? array_merge( $default_seo_meta, $seo_meta ) : $seo_meta;

            // If Rank math is active
            if ( directorist_is_active_rankmath() ) {
                $seo_meta = $this->sync_with_rank_math_seo_meta([
                    'post_id'  => get_the_ID(),
                    'seo_meta' => $seo_meta,
                ]);
            }

            return $seo_meta;
        }

        // get_category_page_seo_meta
        public function get_category_page_seo_meta( $default_seo_meta = [] ) {
            $seo_meta = [];
            $seo_meta['page'] = 'category';

            // Title
            $settings_title = get_directorist_option('category_meta_title');
            if ( ! empty( $settings_title ) ) $seo_meta['title'] = $settings_title;

            // Description
            $settings_description = get_directorist_option('category_meta_desc');
            if ( ! empty( $settings_description ) ) $seo_meta['description'] = $settings_description;

            $seo_meta = ( is_array( $default_seo_meta ) ) ? array_merge( $default_seo_meta, $seo_meta ) : $seo_meta;

            // If Rank math is active
            if ( directorist_is_active_rankmath() ) {
                $seo_meta = $this->sync_with_rank_math_seo_meta([
                    'post_id'  => get_the_ID(),
                    'seo_meta' => $seo_meta,
                ]);
            }

            return $seo_meta;
        }

        // get_all_locations_page_seo_meta
        public function get_all_locations_page_seo_meta( $default_seo_meta = [] ) {
            $seo_meta = [];
            $seo_meta['page'] = 'all_locations';

            // Title
            $settings_title = get_directorist_option('all_locations_meta_title');
            if ( ! empty( $settings_title ) ) $seo_meta['title'] = $settings_title;

            // Description
            $settings_description = get_directorist_option('all_locations_meta_desc');
            if ( ! empty( $settings_description ) ) $seo_meta['description'] = $settings_description;

            $seo_meta = ( is_array( $default_seo_meta ) ) ? array_merge( $default_seo_meta, $seo_meta ) : $seo_meta;

            // If Rank math is active
            if ( directorist_is_active_rankmath() ) {
                $seo_meta = $this->sync_with_rank_math_seo_meta([
                    'post_id'  => get_the_ID(),
                    'seo_meta' => $seo_meta,
                ]);
            }

            return $seo_meta;
        }

        // get_single_listing_page_seo_meta
        public function get_single_listing_page_seo_meta( $default_seo_meta = [] ) {
            $seo_meta = [];
            $seo_meta['page'] = 'all_locations';

            $desc      = esc_html( get_the_excerpt() );
            $meta_desc = ( strlen( $desc ) > 200 ) ? substr( $desc, 0, 200 ) . "..." : $desc;

            $seo_meta['title']       = get_the_title();
            $seo_meta['description'] = $meta_desc;
            $seo_meta['image']       = get_the_post_thumbnail_url();
            
            if ( empty( $seo_meta['image'] ) ) {
                $listing_img_id = get_post_meta( get_the_ID(), '_listing_prv_img', true);
                
                if ( empty( $listing_img_id ) || ! is_string( $listing_img_id ) || ! is_numeric( $listing_img_id ) ) {
                    $listing_img_id = get_post_meta( get_the_ID(), '_listing_img', true );
                    $listing_img_id = ( ! empty( $listing_img_id ) ) ? $listing_img_id[0] : null;
                }
                
                $seo_meta['image'] = ( ! empty($listing_img_id) || is_string( $listing_img_id ) || is_int( $listing_img_id )) ? wp_get_attachment_url($listing_img_id) : '';
            }

            $seo_meta = ( is_array( $default_seo_meta ) ) ? array_merge( $default_seo_meta, $seo_meta ) : $seo_meta;

            return $seo_meta;
        }

        // get_single_category_page_seo_meta
        public function get_single_category_page_seo_meta( $default_seo_meta = [] ) {
            global $post;

            $seo_meta = [];
            $seo_meta['page'] = 'single_category';

            $slug = get_query_var('atbdp_category');
            $term = get_term_by('slug', $slug, ATBDP_CATEGORY);

            // Title
            $settings_title = get_directorist_option('single_category_meta_title');
            if ( ! empty( $settings_title ) ) $seo_meta['title'] = $settings_title;
            if ( ! empty( $term ) ) $seo_meta['title'] = $term->name;

            // Description
            $settings_description = get_directorist_option('single_category_meta_desc');
            if ( ! empty( $settings_description ) ) $seo_meta['description'] = $settings_description;

            // URL
            $url = get_term_link( $slug, ATBDP_CATEGORY );
            $url = ( is_string( $url ) ) ? $url : '';
            
            if ( ! empty( $url ) ) {
                $seo_meta['url'] = $url;
            }
            
            $CAT_page_ID = get_directorist_option('single_category_page');

            // show term description as meta description first
            if ( $post->ID == $CAT_page_ID && $slug && ! empty( $term ) ) {
                if ( ! empty( $term->description ) ) $seo_meta['description'] = $term->description;
                $thumb_id = get_term_meta( $term->term_id, 'image', true );
                $seo_meta['image'] = wp_get_attachment_url( $thumb_id );
            }

            // If Yoast is active
            if ( atbdp_yoast_is_active() ) {
                $seo_meta = $this->sync_with_yoast_seo_meta([
                    'url'      => $url,
                    'seo_meta' => $seo_meta,
                ]);
            }

            // If Rank math is active
            if ( directorist_is_active_rankmath() ) {
                $seo_meta = $this->sync_with_rank_math_seo_meta([
                    'meta_type' => 'term_meta',
                    'term_id'   => $term->term_id,
                    'seo_meta'  => $seo_meta,
                ]);
            }

            $seo_meta = ( is_array( $default_seo_meta ) ) ? array_merge( $default_seo_meta, $seo_meta ) : $seo_meta;

            return $seo_meta;
        }

        // get_single_location_page_seo_meta
        public function get_single_location_page_seo_meta( $default_seo_meta = [] ) {
            global $post;

            $seo_meta = [];
            $seo_meta['page'] = 'single_location';

            $slug = get_query_var('atbdp_location');
            $term = get_term_by('slug', $slug, ATBDP_LOCATION);

            // Title
            $settings_title = get_directorist_option('single_locations_meta_title');
            if ( ! empty( $settings_title ) ) $seo_meta['title'] = $settings_title;
            if ( ! empty( $term ) ) $seo_meta['title'] = $term->name;

            // Description
            $settings_description = get_directorist_option('single_locations_meta_desc');
            if ( ! empty( $settings_description ) ) $seo_meta['description'] = $settings_description;
            
            $LOC_page_ID = get_directorist_option('single_location_page');

            // show term description as meta description first
            if ( $post->ID == $LOC_page_ID && $slug && ! empty( $term ) ) {
                if ( ! empty( $term->description ) ) $seo_meta['description'] = $term->description;
                $thumb_id = get_term_meta( $term->term_id, 'image', true );
                $seo_meta['image'] = wp_get_attachment_url( $thumb_id );
            }

            // If Yoast math is active
            if ( atbdp_yoast_is_active() ) {
                $url = get_term_link( $slug, ATBDP_LOCATION );
                $url = ( is_string( $url ) ) ? $url : '';
                
                $seo_meta = $this->sync_with_yoast_seo_meta([
                    'url'      => $url,
                    'seo_meta' => $seo_meta,
                ]);
            }

            // If Rank math is active
            if ( directorist_is_active_rankmath() ) {
                $seo_meta = $this->sync_with_rank_math_seo_meta([
                    'meta_type' => 'term_meta',
                    'term_id'   => $term->term_id,
                    'seo_meta'  => $seo_meta,
                ]);
            }

            $seo_meta = ( is_array( $default_seo_meta ) ) ? array_merge( $default_seo_meta, $seo_meta ) : $seo_meta;

            return $seo_meta;
        }

        // get_single_tag_page_seo_meta
        public function get_single_tag_page_seo_meta( $default_seo_meta = [] ) {
            global $post;

            $seo_meta = [];
            $seo_meta['page'] = 'single_tag';

            $slug = get_query_var('atbdp_tag');
            $term = get_term_by('slug', $slug, ATBDP_TAGS);
            $seo_meta['title'] = !empty($term) ? $term->name : '';

            $TAG_page_ID = get_directorist_option('single_tag_page');

            // show term description as meta description first
            if ( $post->ID == $TAG_page_ID && $slug && ! empty( $term ) ) {
                if ( ! empty( $term->description ) ) $seo_meta['description'] = $term->description;
                $thumb_id = get_term_meta( $term->term_id, 'image', true );
                $seo_meta['image'] = wp_get_attachment_url( $thumb_id );
            }

            if ( atbdp_yoast_is_active() ) {
                $url = get_term_link( $slug, ATBDP_TAGS );
                $url = ( is_string( $url ) ) ? $url : '';
                
                $seo_meta = $this->sync_with_yoast_seo_meta([
                    'url'      => $url,
                    'seo_meta' => $seo_meta,
                ]);
            }

            // If Rank math is active
            if ( directorist_is_active_rankmath() ) {
                $seo_meta = $this->sync_with_rank_math_seo_meta([
                    'meta_type' => 'term_meta',
                    'term_id'   => $term->term_id,
                    'seo_meta'  => $seo_meta,
                ]);
            }

            $seo_meta = ( is_array( $default_seo_meta ) ) ? array_merge( $default_seo_meta, $seo_meta ) : $seo_meta;

            return $seo_meta;
        }

        // get_registration_page_seo_meta
        public function get_registration_page_seo_meta( $default_seo_meta = [] ) {
            $seo_meta = [];
            $seo_meta['page'] = 'all_locations';

            // Title
            $settings_title = get_directorist_option('registration_meta_title');
            if ( ! empty( $settings_title ) ) $seo_meta['title'] = $settings_title;

            // Description
            $settings_description = get_directorist_option('registration_meta_desc');
            if ( ! empty( $settings_description ) ) $seo_meta['description'] = $settings_description;

            $seo_meta = ( is_array( $default_seo_meta ) ) ? array_merge( $default_seo_meta, $seo_meta ) : $seo_meta;


            // If Rank math is active
            if ( directorist_is_active_rankmath() ) {
                $seo_meta = $this->sync_with_rank_math_seo_meta([
                    'post_id'  => get_the_ID(),
                    'seo_meta' => $seo_meta,
                ]);
            }

            return $seo_meta;
        }

        // get_login_page_seo_meta
        public function get_login_page_seo_meta( $default_seo_meta = [] ) {
            $seo_meta = [];
            $seo_meta['page'] = 'login';

            // Title
            $settings_title = get_directorist_option('login_meta_title');
            if ( ! empty( $settings_title ) ) $seo_meta['title'] = $settings_title;

            // Description
            $settings_description = get_directorist_option('login_meta_desc');
            if ( ! empty( $settings_description ) ) $seo_meta['description'] = $settings_description;

            $seo_meta = ( is_array( $default_seo_meta ) ) ? array_merge( $default_seo_meta, $seo_meta ) : $seo_meta;

            // If Rank math is active
            if ( directorist_is_active_rankmath() ) {
                $seo_meta = $this->sync_with_rank_math_seo_meta([
                    'post_id'  => get_the_ID(),
                    'seo_meta' => $seo_meta,
                ]);
            }

            return $seo_meta;
        }

        public static function get_directorist_current_page() {
            $all_directorist_pages = [
                'home',
                'search_result',
                'add_listing',
                'all_listing',
                'dashboard',
                'author',
                'category',
                'single_category',
                'all_locations',
                'single_listing',
                'single_location',
                'single_tag',
                'login',
                'registration',
            ];

            $current_page = '';

            foreach( $all_directorist_pages as $page_name ) { 

                if ( ! atbdp_is_page( $page_name ) ) continue;
                $current_page = $page_name;
                
                break;
            }

            return $current_page;
        }

        // sync_with_rank_math_seo_meta
        public function sync_with_rank_math_seo_meta( $args = [] ) {
            $default = [ 
                'meta_type' => 'post_meta',
                'post_id'   => '',
                'term_id'   => '',
                'seo_meta'  => []
            ];

            $args = array_merge( $default, $args );

            if ( empty( $args['post_id'] ) && empty( $args['term_id'] ) ) {
                return $args['seo_meta'];
            }

            if ( ! empty( $args['term_id'] ) && ! term_exists( $args['term_id'] ) ) {
                return $args['seo_meta'];
            }

            if ( ! empty( $args['post_id'] ) && ! get_post( $args['post_id'] ) ) {
                return $args['seo_meta'];
            }

            if ( ! is_array( $args['seo_meta'] ) ) {
                return [];
            }

            $seo_meta      = $args['seo_meta'];
            $meta_id       = ( ! empty( $args['term_id'] ) ) ? $args['term_id'] : $args['post_id'] ;
            $meta_callback = (  'term_meta' === $args['meta_type'] ) ? 'get_term_meta' : 'get_post_meta';

            // Setting required fields if does not exist
            if ( ! isset( $seo_meta['title'] ) ) {
                $seo_meta['title'] = '';
            }

            if ( ! isset( $seo_meta['description'] ) ) {
                $seo_meta['description'] = '';
            }

            if ( ! isset( $seo_meta['image'] ) ) {
                $seo_meta['image'] = '';
            }

            $facebook_title       = call_user_func_array( $meta_callback, [ $meta_id, 'rank_math_facebook_title', true ] );
            $facebook_description = call_user_func_array( $meta_callback, [ $meta_id, 'rank_math_facebook_description', true ] );
            $facebook_image       = call_user_func_array( $meta_callback, [ $meta_id, 'rank_math_facebook_image', true ] );
            $twitter_use_facebook = call_user_func_array( $meta_callback, [ $meta_id, 'rank_math_twitter_use_facebook', true ] );
            
            $seo_meta['title']       = ( ! empty( $facebook_title ) ) ? $facebook_title : $seo_meta['title'];
            $seo_meta['description'] = ( ! empty( $facebook_description ) ) ? $facebook_description : $seo_meta['description'];
            $seo_meta['image']       = ( ! empty( $facebook_image ) ) ? $facebook_image : $seo_meta['image'];

            if ( 'on' !== $twitter_use_facebook ) {
                $twitter_title       = call_user_func_array( $meta_callback, [ $meta_id, 'rank_math_twitter_title', true ] );
                $twitter_description = call_user_func_array( $meta_callback, [ $meta_id, 'rank_math_twitter_description', true ] );
                $twitter_image       = call_user_func_array( $meta_callback, [ $meta_id, 'rank_math_twitter_image', true ] );
                $twitter_card_type   = call_user_func_array( $meta_callback, [ $meta_id, 'rank_math_twitter_card_type', true ] );
                
                $seo_meta['twitter_title']       = $twitter_title;
                $seo_meta['twitter_description'] = $twitter_description;
                $seo_meta['twitter_image']       = $twitter_image;
                $seo_meta['twitter_card']        = $twitter_card_type;
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

            if ( function_exists( 'YoastSEO' ) && is_string( $url ) ) {
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