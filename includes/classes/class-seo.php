<?php
defined('ABSPATH') || die('Direct access is not allowed.');
if (!class_exists('ATBDP_SEO')) :
    /**
     * Class ATBDP_Email
     */
    class ATBDP_SEO {
        public function __construct()
        {
            if (atbdp_yoast_is_active()) {
                add_filter('wpseo_title', array($this, 'wpseo_title'));
                add_filter('wpseo_metadesc', array($this, 'wpseo_metadesc'));
                add_filter('wpseo_canonical', array($this, 'wpseo_canonical'));
                add_filter('wpseo_opengraph_url', array($this, 'wpseo_canonical'));
                add_filter('wpseo_opengraph_title', array($this, 'wpseo_opengraph_title'));
                //add_filter('wpseo_opengraph_image', array($this, 'wpseo_opengraph_image'));
            }

            if (atbdp_disable_overwrite_yoast()) {
                remove_action('wp_head', 'rel_canonical');
                if (atbdp_yoast_is_active()) {
                    add_filter('wpseo_canonical', array($this, 'wpseo_canonical'));
                } else {
                    add_action('wp_head', array($this, 'atbdp_texonomy_canonical'));
                }

                add_filter('pre_get_document_title', array($this, 'atbdp_custom_page_title'), 100);
                add_filter('wp_title', array($this, 'atbdp_custom_page_title'), 100, 2);
                add_action('wp_head', array($this, 'atbdp_add_meta_keywords'), 100, 2);
                add_action('wp_head', array($this, 'atbdp_add_og_meta'), 100, 2);
            }
            add_filter('the_title', array($this, 'atbdp_title_update'), 10, 2);
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
            if (!in_the_loop() || !is_main_query()) {
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
                if ($slug = get_query_var('atbdp_location')) {

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
            $meta_desc = $seo_meta['meta_desc'];
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
            $seo_meta = $this->get_seo_meta_data();
            $og_metas = apply_filters( 'atbdp_og_metas', [
                'site_name'   => [
                    'property' => 'og:site_name',
                    'content'  => $seo_meta['site_name'],
                ],
                'title' => [
                    'property' => 'og:title',
                    'content'  => $seo_meta['title'],
                ],
                'description' => [
                    'property' => 'og:description',
                    'content'  => $seo_meta['meta_desc'],
                ],
                'url' => [
                    'property' => 'og:url',
                    'content'  => $seo_meta['current_page_url'],
                ],
                'image' => [
                    'property' => 'og:image',
                    'content'  => $seo_meta['thumbnail_url'],
                ],
                'twitter_card' => [
                    'name'    => 'twitter:card',
                    'content' => 'summary',
                ],
            ]);

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
            
            $seo_meta = [
                'site_name'        => get_bloginfo('name'),
                'title'            => '',
                'meta_desc'        => '',
                'page'             => '',
                'current_page_url' => '',
                'thumbnail_url'    => '',
            ];

            $meta_desc         = '';
            $atbdp_page        = '';
            $CAT_page_ID       = get_directorist_option('single_category_page');
            $LOC_page_ID       = get_directorist_option('single_location_page');
            $TAG_page_ID       = get_directorist_option('single_tag_page');
            $current_url       = home_url( add_query_arg( array(), $wp->request ) );
            $home_page_id      = get_option('page_on_front');
            $default_thumbnail = get_the_post_thumbnail_url();
            $default_thumbnail = ! empty( $default_thumbnail ) ? $default_thumbnail : get_the_post_thumbnail_url( $home_page_id );
            
            if (is_singular('at_biz_dir')) {
                $title         = get_the_title();
                $excerpt       = esc_html( get_the_excerpt() );
                $content       = esc_html( get_the_content() );
                $desc          = ! empty( $excerpt ) ? $excerpt : $content;
                $desc          = ( strlen( $desc ) > 200 ) ? substr( $desc, 0, 200 ) . "..." : $desc;
                $meta_desc     = $desc;
                $thumbnail_url = get_the_post_thumbnail_url();
            }

            $atbdp_page = '';
            if (atbdp_is_page('home')) {
                $atbdp_page = 'home';
                $title = (get_directorist_option('homepage_meta_title')) ? get_directorist_option('homepage_meta_title') : $title;
                $meta_desc = (get_directorist_option('homepage_meta_desc')) ? get_directorist_option('homepage_meta_desc') : $meta_desc;
            } elseif (atbdp_is_page('search-result')) {
                $atbdp_page = 'search-result';

                $query = (isset($_GET['q']) && ('' !== $_GET['q'])) ? ucfirst($_GET['q']) : '';
                $category = (isset($_GET['in_cat']) && ('' !== $_GET['in_cat'])) ? ucfirst($_GET['in_cat']) : '';
                $location = (isset($_GET['in_loc']) && ('' !== $_GET['in_loc'])) ? ucfirst($_GET['in_loc']) : '';
                $category =  get_term_by('id', $category, ATBDP_CATEGORY);
                $location =  get_term_by('id', $location, ATBDP_LOCATION);


                $in_s_string_text       = !empty($query) ? sprintf(__('%s', 'directorist'), $query) : '';
                $in_cat_text            = !empty($category) ? sprintf(__(' %s %s ', 'directorist'), !empty($query) ? 'from' : '', $category->name) : '';
                $in_loc_text            = !empty($location) ? sprintf(__('%s %s', 'directorist'), !empty($category) ? 'in' : '', $location->name) : '';

                $how_to = get_directorist_option('meta_title_for_search_result', 'searched_value');
                if ('searched_value' === $how_to) {
                    if (!empty($query) || !empty($category) || !empty($location)) {
                        $title = $in_s_string_text . $in_cat_text . $in_loc_text;
                    }
                } else {
                    $title = (get_directorist_option('search_result_meta_title')) ? get_directorist_option('search_result_meta_title') : $title;
                }

                $meta_desc = (get_directorist_option('search_result_meta_desc')) ? get_directorist_option('search_result_meta_desc') : $meta_desc;
            } elseif (atbdp_is_page('add-listing')) {
                $atbdp_page = 'add-listing';
                $title = (get_directorist_option('add_listing_page_meta_title')) ? get_directorist_option('add_listing_page_meta_title') : $title;
                $meta_desc = (get_directorist_option('add_listing_page_meta_desc')) ? get_directorist_option('add_listing_page_meta_desc') : $meta_desc;
            } elseif (atbdp_is_page('all-listing')) {
                $atbdp_page = 'all-listing';
                $title = (get_directorist_option('all_listing_meta_title')) ? get_directorist_option('all_listing_meta_title') : $title;
                $meta_desc = (get_directorist_option('all_listing_meta_desc')) ? get_directorist_option('all_listing_meta_desc') : $meta_desc;
            } elseif (atbdp_is_page('dashboard')) {
                $atbdp_page = 'dashboard';
                $title = (get_directorist_option('dashboard_meta_title')) ? get_directorist_option('dashboard_meta_title') : $title;
                $meta_desc = (get_directorist_option('dashboard_meta_desc')) ? get_directorist_option('dashboard_meta_desc') : $meta_desc;
            } elseif (atbdp_is_page('author')) {
                $atbdp_page = 'author';
                $title = (get_directorist_option('author_profile_meta_title')) ? get_directorist_option('author_profile_meta_title') : $title;
                $meta_desc = (get_directorist_option('author_page_meta_desc')) ? get_directorist_option('author_page_meta_desc') : $meta_desc;
            } elseif (atbdp_is_page('category')) {
                $atbdp_page = 'category';
                $title = (get_directorist_option('category_meta_title')) ? get_directorist_option('category_meta_title') : $title;
                $meta_desc = (get_directorist_option('category_meta_desc')) ? get_directorist_option('category_meta_desc') : $meta_desc;
            } elseif (atbdp_is_page('single_category')) {
                $atbdp_page = 'single_category';
                $slug = get_query_var('atbdp_category');
                $term = get_term_by('slug', $slug, ATBDP_CATEGORY);
                $title = (get_directorist_option('single_category_meta_title')) ? get_directorist_option('single_category_meta_title') : (!empty($term) ? $term->name : '');
                $meta_desc = (get_directorist_option('single_category_meta_desc')) ? get_directorist_option('single_category_meta_desc') : $meta_desc;
                
                // show term description as meta description first
                if ($post->ID == $CAT_page_ID && $slug) {
                    $meta_desc     = ! empty( $term->description ) ? $term->description : $meta_desc;
                    $thumb_id      = get_term_meta( $term->term_id, 'image', true );
                    $thumbnail_url = wp_get_attachment_url( $thumb_id );
                }

            } elseif (atbdp_is_page('all_locations')) {
                $atbdp_page = 'all_locations';
                $title = (get_directorist_option('all_locations_meta_title')) ? get_directorist_option('all_locations_meta_title') : $title;
                $meta_desc = (get_directorist_option('all_locations_meta_desc')) ? get_directorist_option('all_locations_meta_desc') : $meta_desc;
            } elseif (atbdp_is_page('single_location')) {
                $atbdp_page = 'single_location';
                $slug = get_query_var('atbdp_location');
                $term = get_term_by('slug', $slug, ATBDP_LOCATION);
                $title = (get_directorist_option('single_locations_meta_title')) ? get_directorist_option('single_locations_meta_title') : (!empty($term) ? $term->name : '');
                $meta_desc = (get_directorist_option('single_locations_meta_desc')) ? get_directorist_option('single_locations_meta_desc') : $meta_desc;
                
                // show term description as meta description first
                if ($post->ID == $LOC_page_ID && $slug) {
                    $meta_desc     = ! empty( $term->description ) ? $term->description : $meta_desc;
                    $thumb_id      = get_term_meta( $term->term_id, 'image', true );
                    $thumbnail_url = wp_get_attachment_url( $thumb_id );
                }

            } elseif (atbdp_is_page('single_tag')) {
                $atbdp_page = 'single_tag';
                $slug = get_query_var('atbdp_tag');
                $term = get_term_by('slug', $slug, ATBDP_TAGS);
                $title = !empty($term) ? $term->name : '';

                // show term description as meta description first
                if ($post->ID == $TAG_page_ID && $slug ) {
                    $meta_desc     = ! empty( $term->description ) ? $term->description : $meta_desc;
                    $thumb_id      = get_term_meta( $term->term_id, 'image', true );
                    $thumbnail_url = wp_get_attachment_url( $thumb_id );
                }
                
            } elseif (atbdp_is_page('registration')) {
                $atbdp_page = 'registration';
                $title = (get_directorist_option('registration_meta_title')) ? get_directorist_option('registration_meta_title') : $title;
                $meta_desc = (get_directorist_option('registration_meta_desc')) ? get_directorist_option('registration_meta_desc') : $meta_desc;
            } elseif (atbdp_is_page('login')) {
                $atbdp_page = 'login';
                $title = (get_directorist_option('login_meta_title')) ? get_directorist_option('login_meta_title') : $title;
                $meta_desc = (get_directorist_option('login_meta_desc')) ? get_directorist_option('login_meta_desc') : $meta_desc;
            }

            if ( $meta_desc ) {
                $meta_desc = stripslashes_deep($meta_desc);
                /**
                 * Filter page description to replace variables.
                 *
                 * @since 1.5.4
                 *
                 * @param string $meta_desc   The page description including variables.
                 * @param string $gd_page The atbdpectory page type if any.
                 */
                $meta_desc = apply_filters('atbdp_seo_meta_description_pre', __($meta_desc, 'directorist'), $atbdp_page, '');
            }

            $seo_meta['title']            = $title;
            $seo_meta['meta_desc']        = ! empty( $meta_desc ) ? $meta_desc : get_bloginfo('description');
            $seo_meta['page']             = $atbdp_page;
            $seo_meta['current_page_url'] = $current_url;
            $seo_meta['thumbnail_url']    = ! empty( $thumbnail_url ) ? $thumbnail_url : $default_thumbnail;
            
            return $seo_meta;
        }
    } // ends class
endif;
