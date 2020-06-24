<?php
if (!class_exists('ATBDP_Shortcode')):

    class ATBDP_Shortcode {

        public function __construct() {
            // Search
            add_shortcode('directorist_search_listing',  array($this, 'search_listing'));
            add_shortcode('directorist_search_result',   array($this, 'search_result'));

            // Taxonomy
            add_shortcode('directorist_all_categories',  array($this, 'all_categories'));
            add_shortcode('directorist_all_locations',   array($this, 'all_locations'));
            
            // Archive
            add_shortcode('directorist_all_listing',     array($this, 'listing_archive'));
            add_shortcode('directorist_category',        array($this, 'category_archive'));
            add_shortcode('directorist_tag',             array($this, 'tag_archive'));
            add_shortcode('directorist_location',        array($this, 'location_archive'));
            
            // Single
            add_shortcode('directorist_listing_top_area',             array($this, 'directorist_listing_header' ));
            add_shortcode('directorist_listing_tags',                 array($this, 'directorist_tags'));
            add_shortcode('directorist_listing_custom_fields',        array($this, 'directorist_custom_field'));
            add_shortcode('directorist_listing_video',                array($this, 'directorist_listing_video'));
            add_shortcode('directorist_listing_map',                  array($this, 'directorist_listing_map'));
            add_shortcode('directorist_listing_contact_information',  array($this, 'directorist_listing_contact_information'));
            add_shortcode('directorist_listing_author_info',          array($this, 'directorist_listing_author_details'));
            add_shortcode('directorist_listing_contact_owner',        array($this, 'directorist_listing_contact_owner'));
            add_shortcode('directorist_listing_review',               array($this, 'directorist_listing_review'));
            add_shortcode('directorist_related_listings',             array($this, 'directorist_related_listings'));

            // Author
            add_shortcode('directorist_author_profile',      array($this, 'author_profile'));
            add_shortcode('directorist_user_dashboard',      array($this, 'user_dashboard'));

            // Forms
            add_shortcode('directorist_add_listing',         array($this, 'add_listing')); 
            add_shortcode('directorist_custom_registration', array($this, 'user_registration'));
            add_shortcode('directorist_user_login',          array($this, 'custom_user_login'));

            // Checkout
            $checkout = new ATBDP_Checkout;
            add_shortcode('directorist_checkout',            array($checkout, 'display_checkout_content'));
            add_shortcode('directorist_payment_receipt',     array($checkout, 'payment_receipt'));
            add_shortcode('directorist_transaction_failure', array($checkout, 'transaction_failure'));

            // Ajax
            add_action('wp_ajax_atbdp_custom_fields_listings_front',                 array($this, 'ajax_callback_custom_fields'), 10, 2);
            add_action('wp_ajax_nopriv_atbdp_custom_fields_listings_front',          array($this, 'ajax_callback_custom_fields'), 10, 2);
            add_action('wp_ajax_atbdp_custom_fields_listings_front_selected',        array($this, 'ajax_callback_custom_fields'), 10, 2);
            add_action('wp_ajax_nopriv_atbdp_custom_fields_listings_front_selected', array($this, 'ajax_callback_custom_fields'), 10, 2);
        }

        // listing header area
        public function directorist_listing_header() {
            $listing = new Directorist_Single_Listing();
            return $listing->render_shortcode_top_area();
        }

        // listing custom tags
        public function directorist_tags() {
            $listing = new Directorist_Single_Listing();
            return $listing->render_shortcode_tags();
        }

        // listing custom fields area
        public function directorist_custom_field() {
            $listing = new Directorist_Single_Listing();
            return $listing->render_shortcode_custom_fields();
        }

        //listing video area
        public function directorist_listing_video() {
            $listing = new Directorist_Single_Listing();
            return $listing->render_shortcode_video();
        }

        //listing map area
        public function directorist_listing_map() {
            $listing = new Directorist_Single_Listing();
            return $listing->render_shortcode_map();
        }

        //listing contact information area
        public function directorist_listing_contact_information() {
            $listing = new Directorist_Single_Listing();
            return $listing->render_shortcode_contact_information();
        }

        //listing author details
        public function directorist_listing_author_details() {
            $listing = new Directorist_Single_Listing();
            return $listing->render_shortcode_author_info();
        }

        //listing contact owner area
        public function directorist_listing_contact_owner() {
            $listing = new Directorist_Single_Listing();
            return $listing->render_shortcode_contact_owner();
        }

        //listing review area
        public function directorist_listing_review() {
            ob_start();
            if (is_singular(ATBDP_POST_TYPE)) {
                $template_file = 'single-listing/listing-review';
                $template_path = atbdp_get_shortcode_template_paths( $template_file );
                
                if ( file_exists( $template_path['theme'] ) ) {
                    include $template_path['theme'];
                } elseif ( file_exists( $template_path['plugin'] ) ) {
                    include $template_path['plugin'];
                }
            }
            return ob_get_clean();
        }
        
        //related listing area
        public function directorist_related_listings() {
            ob_start();
            
            if (is_singular(ATBDP_POST_TYPE)) {
                global $post;
                $listing_id    = $post->ID;
                $fm_plan       = get_post_meta($listing_id, '_fm_plans', true);
                $listing_info['never_expire'] = get_post_meta($post->ID, '_never_expire', true);
                $listing_info['featured'] = get_post_meta($post->ID, '_featured', true);
                $listing_info['price'] = get_post_meta($post->ID, '_price', true);
                $listing_info['price_range'] = get_post_meta($post->ID, '_price_range', true);
                $listing_info['atbd_listing_pricing'] = get_post_meta($post->ID, '_atbd_listing_pricing', true);
                $listing_info['videourl'] = get_post_meta($post->ID, '_videourl', true);
                $listing_info['listing_status'] = get_post_meta($post->ID, '_listing_status', true);
                $listing_info['tagline'] = get_post_meta($post->ID, '_tagline', true);
                $listing_info['excerpt'] = get_post_meta($post->ID, '_excerpt', true);
                $listing_info['address'] = get_post_meta($post->ID, '_address', true);
                $listing_info['phone'] = get_post_meta($post->ID, '_phone', true);
                $listing_info['email'] = get_post_meta($post->ID, '_email', true);
                $listing_info['website'] = get_post_meta($post->ID, '_website', true);
                $listing_info['zip'] = get_post_meta($post->ID, '_zip', true);
                $listing_info['social'] = get_post_meta($post->ID, '_social', true);
                $listing_info['faqs'] = get_post_meta($post->ID, '_faqs', true);
                $listing_info['manual_lat'] = get_post_meta($post->ID, '_manual_lat', true);
                $listing_info['manual_lng'] = get_post_meta($post->ID, '_manual_lng', true);
                $listing_info['hide_map'] = get_post_meta($post->ID, '_hide_map', true);
                $listing_info['listing_img'] = get_post_meta($post->ID, '_listing_img', true);
                $listing_info['listing_prv_img'] = get_post_meta($post->ID, '_listing_prv_img', true);
                $listing_info['hide_contact_info'] = get_post_meta($post->ID, '_hide_contact_info', true);
                $listing_info['hide_contact_owner'] = get_post_meta($post->ID, '_hide_contact_owner', true);
                $listing_info['expiry_date'] = get_post_meta($post->ID, '_expiry_date', true);
                extract($listing_info);
                $main_col_size = is_active_sidebar('right-sidebar-listing') ? 'col-lg-8' : 'col-lg-12';

                $enable_rel_listing = get_directorist_option('enable_rel_listing', 1);
                if (empty($enable_rel_listing)) return; // vail if related listing is not enabled
                $related_listings = $this->get_related_listings($post);
                $is_disable_price = get_directorist_option('disable_list_price');

                $is_rtl = is_rtl() ? true : false;
                $rel_listing_column = get_directorist_option('rel_listing_column', 3);
                $localized_data = [
                    'is_rtl' => $is_rtl,
                    'rel_listing_column' => $rel_listing_column,
                ];
                
                wp_localize_script( 'atbdp-related-listings-slider', 'data', $localized_data );
                wp_enqueue_script('atbdp-related-listings-slider');

                $template_file = 'single-listing/related_listings';
                $template_path = atbdp_get_shortcode_template_paths( $template_file );

                if ( file_exists( $template_path['theme'] ) ) {
                    include $template_path['theme'];
                } elseif ( file_exists( $template_path['plugin'] ) ) {
                    include $template_path['plugin'];
                }
            }
            return ob_get_clean();
        }

        /**
         * It gets the related listings of the given listing/post
         * @param object|WP_Post $post The WP Post Object of whose related listing we would like to show
         * @return object|WP_Query It returns the related listings if found.
         */
        public function get_related_listings($post)
        {
            $rel_listing_num = get_directorist_option('rel_listing_num', 2);
            $atbd_cats = get_the_terms($post, ATBDP_CATEGORY);
            $atbd_tags = get_the_terms($post, ATBDP_TAGS);
            // get the tag ids of the listing post type
            $atbd_cats_ids = array();
            $atbd_tags_ids = array();

            if (!empty($atbd_cats)) {
                foreach ($atbd_cats as $atbd_cat) {
                    $atbd_cats_ids[] = $atbd_cat->term_id;
                }
            }
            if (!empty($atbd_tags)) {
                foreach ($atbd_tags as $atbd_tag) {
                    $atbd_tags_ids[] = $atbd_tag->term_id;
                }
            }
            $relationship = get_directorist_option('rel_listings_logic','OR');
            $args = array(
                'post_type' => ATBDP_POST_TYPE,
                'tax_query' => array(
                    'relation' => $relationship,
                    array(
                        'taxonomy' => ATBDP_CATEGORY,
                        'field' => 'term_id',
                        'terms' => $atbd_cats_ids,
                    ),
                    array(
                        'taxonomy' => ATBDP_TAGS,
                        'field' => 'term_id',
                        'terms' => $atbd_tags_ids,
                    ),
                ),
                'posts_per_page' => (int)$rel_listing_num,
                'post__not_in' => array($post->ID),
            );

            $meta_queries = array();
            $meta_queries[] = array(
                'relation' => 'OR',
                array(
                    'key' => '_expiry_date',
                    'value' => current_time('mysql'),
                    'compare' => '>', // eg. expire date 6 <= current date 7 will return the post
                    'type' => 'DATETIME'
                ),
                array(
                    'key' => '_never_expire',
                    'value' => 1,
                )
            );

            $meta_queries = apply_filters('atbdp_related_listings_meta_queries', $meta_queries);
            $count_meta_queries = count($meta_queries);
            if ($count_meta_queries) {
                $args['meta_query'] = ($count_meta_queries > 1) ? array_merge(array('relation' => 'AND'), $meta_queries) : $meta_queries;
            }

            return new WP_Query(apply_filters('atbdp_related_listing_args', $args));

        }

        /**
         * Display custom fields.Columns
         *
         * @param int $post_id Post ID.
         * @param array $term_id Category ID.
         * @since     3.2
         * @access   public
         */
        public function ajax_callback_custom_fields($post_id = 0, $term_id = array())
        {
            $ajax = false;
            if (isset($_POST['term_id'])) {
                $ajax = true;
                $post_ID = !empty($_POST['post_id']) ? (int)$_POST['post_id'] : '';
                $term_id = $_POST['term_id'];
            }
            // Get custom fields
            $categories = !empty($term_id) ? $term_id : array();
            $args = array(
                'post_type' => ATBDP_CUSTOM_FIELD_POST_TYPE,
                'posts_per_page' => -1,
                'status' => 'published'
            );
            $meta_queries = array();

            if ( ! empty( $categories ) && is_array( $categories )){
                if ( count( $categories ) > 1) {
                    $sub_meta_queries = array();
                    foreach ($categories as $value) {
                        $sub_meta_queries[] = array(
                            'key' => 'category_pass',
                            'value' => $value,
                            'compare' => '='
                        );
                    }

                    $meta_queries[] = array_merge(array('relation' => 'OR'), $sub_meta_queries);
                } else {
                    $meta_queries[] = array(
                        'key' => 'category_pass',
                        'value' => $categories[0],
                        'compare' => '='
                    );
                }
            }
            $meta_queries[] = array(
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'admin_use',
                        'compare' => 'NOT EXISTS'
                    ),
                    array(
                        'key' => 'admin_use',
                        'value' => 1,
                        'compare' => '!='
                    ),
                )
            );
            $meta_queries[] = array(
                array(
                    'key' => 'associate',
                    'value' => 'categories',
                    'compare' => 'LIKE',
                ),
            );


            $count_meta_queries = count($meta_queries);
            if ($count_meta_queries) {
                $args['meta_query'] = ($count_meta_queries > 1) ? array_merge(array('relation' => 'AND'), $meta_queries) : $meta_queries;
            }

            $atbdp_query = new WP_Query($args);

            if ($atbdp_query->have_posts()) {
                // Start the Loop
                global $post;
                // Process output
                ob_start();
                $include = apply_filters('include_style_settings', true);
                include ATBDP_TEMPLATES_DIR . 'add-listing-custom-field.php';
                wp_reset_postdata(); // Restore global post data stomped by the_post()
                $output = ob_get_clean();

                print $output;

                if ($ajax) {
                    wp_die();
                }
            } else {
                echo '<div class="custom_field_empty_area"></div>';
                if ($ajax) {
                    wp_die();
                }
            }

        }

        public function search_result($atts)
        {
            wp_enqueue_script('adminmainassets');
            wp_enqueue_script('atbdp-search-listing', ATBDP_PUBLIC_ASSETS . 'js/search-form-listing.js');
            wp_localize_script('atbdp-search-listing', 'atbdp_search', array(
                'ajaxnonce' => wp_create_nonce('bdas_ajax_nonce'),
                'ajax_url' => admin_url('admin-ajax.php'),
                'added_favourite' => __('Added to favorite', 'directorist'),
                'please_login' => __('Please login first', 'directorist')
            ));
            wp_enqueue_script('atbdp-range-slider');
            $radius_search_unit            = get_directorist_option('radius_search_unit', 'miles');
            if(!empty($radius_search_unit) && 'kilometers' == $radius_search_unit) {
                $miles = __(' Kilometers', 'directorist');
            }else{
                $miles = __(' Miles', 'directorist');
            }
            $default_radius_distance = get_directorist_option('sresult_default_radius_distance', 0);

            $listing_orderby = get_directorist_option('search_order_listing_by');
            $search_sort_listing_by = get_directorist_option('search_sort_listing_by');
            $listing_view = get_directorist_option('search_view_as');
            $listing_order = get_directorist_option('search_sort_by');
            $listing_grid_columns = get_directorist_option('search_listing_columns', 3);
            $display_listings_header = get_directorist_option('search_header', 1);
            $filters_display = get_directorist_option('search_result_display_filter', 'sliding');
            $paginate = get_directorist_option('paginate_search_results');
            $listings_map_height = get_directorist_option('listings_map_height', 350);
            $params = apply_filters('atbdp_search_results_param', array(
                'view' => !empty($listing_view) ? $listing_view : 'grid',
                '_featured' => 1,
                'filterby' => '',
                'orderby' => !empty($listing_orderby) ? $listing_orderby : 'date',
                'order' => !empty($search_sort_listing_by) ? $search_sort_listing_by : 'asc',
                'listings_per_page' => (int)get_directorist_option('search_posts_num', 6),
                'show_pagination' => !empty($paginate) ? 'yes' : '',
                'header' => !empty($display_listings_header) ? 'yes' : '',
                'columns' => !empty($listing_grid_columns) ? $listing_grid_columns : 3,
                'featured_only' => '',
                'popular_only' => '',
                'logged_in_user_only' => '',
                'redirect_page_url' => '',
                'map_height' => !empty($listings_map_height) ? $listings_map_height : 350,
            ));
            $atts = shortcode_atts($params, $atts);
            $columns = !empty($atts['columns']) ? $atts['columns'] : 3;
            $display_header = !empty($atts['header']) ? $atts['header'] : '';
            $show_pagination = !empty($atts['show_pagination']) ? $atts['show_pagination'] : '';
            $feature_only = !empty($atts['featured_only']) ? $atts['featured_only'] : '';
            $popular_only = !empty($atts['popular_only']) ? $atts['popular_only'] : '';
            $logged_in_user_only = !empty($atts['logged_in_user_only']) ? $atts['logged_in_user_only'] : '';
            $redirect_page_url = !empty($atts['redirect_page_url']) ? $atts['redirect_page_url'] : '';
            $map_height = !empty($atts['map_height']) ? $atts['map_height'] : '';
            //for pagination
            $paged = atbdp_get_paged_num();

            if ( 'yes' == $logged_in_user_only && ! atbdp_logged_in_user() ) {
                ob_start();
                // user not logged in;
                $error_message = sprintf(__('You need to be logged in to view the content of this page. You can login %s. Don\'t have an account? %s', 'directorist'), apply_filters('atbdp_listing_form_login_link', "<a href='" . ATBDP_Permalink::get_login_page_link() . "'> " . __('Here', 'directorist') . "</a>"), apply_filters('atbdp_listing_form_signup_link', "<a href='" . ATBDP_Permalink::get_registration_page_link() . "'> " . __('Sign Up', 'directorist') . "</a>")); 
                ?>
                <section class="directory_wrapper single_area">
                    <?php ATBDP()->helper->show_login_message($error_message); ?>
                </section>
                <?php

                return ob_get_clean();
            }

            $has_featured = get_directorist_option('enable_featured_listing');
            if ($has_featured || is_fee_manager_active()) {
                $has_featured = $atts['_featured'];
            }
            if ('rand' == $atts['orderby']) {
                $current_order = atbdp_get_listings_current_order($atts['orderby']);
            } else {
                $current_order = atbdp_get_listings_current_order($atts['orderby'] . '-' . $atts['order']);
            }
            $view = atbdp_get_listings_current_view_name($atts['view']);
            $s_string = !empty($_GET['q']) ? sanitize_text_field($_GET['q']) : '';// get the searched query
            $args = array(
                'post_type' => ATBDP_POST_TYPE,
                'post_status' => 'publish',
            );

            if (!empty($s_string)) {
                $args['s'] = $s_string;
            }

            if ('yes' == $show_pagination) {
                $args['posts_per_page'] = (int)$atts['listings_per_page'];
                $args['paged'] = $paged;
            }

            if ($has_featured) {
                $args['meta_key'] = '_featured';
                $args['orderby'] = array(
                    'meta_value_num' => 'DESC',
                    'title' => 'ASC',
                );
            } else {
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
            };

            // Define tax queries( only if applicable )
            $tax_queries = array();
            if (isset($_GET['in_cat']) && (int)$_GET['in_cat'] > 0) {
                $tax_queries[] = array(
                    'taxonomy' => ATBDP_CATEGORY,
                    'field' => 'term_id',
                    'terms' => (int)$_GET['in_cat'],
                    'include_children' => true,
                );
            }

            if (isset($_GET['in_loc']) && (int)$_GET['in_loc'] > 0) {
                $tax_queries[] = array(
                    'taxonomy' => ATBDP_LOCATION,
                    'field' => 'term_id',
                    'terms' => (int)$_GET['in_loc'],
                    'include_children' => true,
                );
            }

            if (isset($_GET['in_tag']) && (int)$_GET['in_tag'] > 0) {
                $tag_value = $_GET['in_tag'];
                $tax_queries[] = array(
                    'taxonomy' => ATBDP_TAGS,
                    'field' => 'term_id',
                    'terms' => $tag_value,
                );

            }
            $count_tax_queries = count($tax_queries);
            if ($count_tax_queries) {
                $args['tax_query'] = ($count_tax_queries > 1) ? array_merge(array('relation' => 'AND'), $tax_queries) : $tax_queries;
            }

            $meta_queries = array();

            if (isset($_GET['custom_field'])) {
                $cf = array_filter($_GET['custom_field']);

                foreach ($cf as $key => $values) {
                    if (is_array($values)) {

                        if (count($values) > 1) {

                            $sub_meta_queries = array();

                            foreach ($values as $value) {
                                $sub_meta_queries[] = array(
                                    'key' => $key,
                                    'value' => sanitize_text_field($value),
                                    'compare' => 'LIKE'
                                );
                            }

                            $meta_queries[] = array_merge(array('relation' => 'OR'), $sub_meta_queries);

                        } else {

                            $meta_queries[] = array(
                                'key' => $key,
                                'value' => sanitize_text_field($values[0]),
                                'compare' => 'LIKE'
                            );
                        }

                    } else {

                        $field_type = get_post_meta($key, 'type', true);
                        $operator = ('text' == $field_type || 'textarea' == $field_type || 'url' == $field_type) ? 'LIKE' : '=';
                        $meta_queries[] = array(
                            'key' => $key,
                            'value' => sanitize_text_field($values),
                            'compare' => $operator
                        );

                    }

                }

            } // end get['cf']

            if (isset($_GET['price'])) {
                $price = array_filter($_GET['price']);

                if ($n = count($price)) {

                    if (2 == $n) {
                        $meta_queries[] = array(
                            'key' => '_price',
                            'value' => array_map('intval', $price),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN'
                        );
                    } else {
                        if (empty($price[0])) {
                            $meta_queries[] = array(
                                'key' => '_price',
                                'value' => (int)$price[1],
                                'type' => 'NUMERIC',
                                'compare' => '<='
                            );
                        } else {
                            $meta_queries[] = array(
                                'key' => '_price',
                                'value' => (int)$price[0],
                                'type' => 'NUMERIC',
                                'compare' => '>='
                            );
                        }
                    }

                }

            }// end price
            if (isset($_GET['price_range']) && 'none' != $_GET['price_range']) {
                $price_range = $_GET['price_range'];
                $meta_queries[] = array(
                    'key' => '_price_range',
                    'value' => $price_range,
                    'compare' => 'LIKE'
                );
            }

            // search by rating
            if (isset($_GET['search_by_rating'])) {
                $q_rating = $_GET['search_by_rating'];
                $listings = get_atbdp_listings_ids();
                $rated = array();
                if ($listings->have_posts()) {
                    while ($listings->have_posts()) {
                        $listings->the_post();
                        $listing_id = get_the_ID();
                        $average = ATBDP()->review->get_average($listing_id);
                        if ($q_rating === '5') {
                            if (($average == '5')) {
                                $rated[] = get_the_ID();
                            } else {
                                $rated[] = array();
                            }
                        } elseif ($q_rating === '4') {
                            if ($average >= '4') {
                                $rated[] = get_the_ID();
                            } else {
                                $rated[] = array();
                            }
                        } elseif ($q_rating === '3') {
                            if ($average >= '3') {
                                $rated[] = get_the_ID();
                            } else {
                                $rated[] = array();
                            }
                        } elseif ($q_rating === '2') {
                            if ($average >= '2') {
                                $rated[] = get_the_ID();
                            } else {
                                $rated[] = array();
                            }
                        } elseif ($q_rating === '1') {
                            if ($average >= '1') {
                                $rated[] = get_the_ID();
                            } else {
                                $rated[] = array();
                            }
                        } elseif ('' === $q_rating) {
                            if ($average === '') {
                                $rated[] = get_the_ID();
                            }
                        }
                    }
                    $rating_id = array(
                        'post__in' => !empty($rated) ? $rated : array()
                    );
                    $args = array_merge($args, $rating_id);
                }


            }

            if (isset($_GET['website'])) {
                $website = $_GET['website'];
                $meta_queries[] = array(
                    'key' => '_website',
                    'value' => $website,
                    'compare' => 'LIKE'
                );
            }

            if (isset($_GET['email'])) {
                $email = $_GET['email'];
                $meta_queries[] = array(
                    'key' => '_email',
                    'value' => $email,
                    'compare' => 'LIKE'
                );
            }


            if (isset($_GET['phone'])) {
                $phone = $_GET['phone'];
                $meta_queries[] = array(
                    'relation' => 'OR',
                    array(
                        'key' => '_phone2',
                        'value' => $phone,
                        'compare' => 'LIKE'
                    ),
                    array(
                        'key' => '_phone',
                        'value' => $phone,
                        'compare' => 'LIKE'
                    )
                );
            }

            if (!empty($_GET['fax'])) {
                $fax = $_GET['fax'];
                $meta_queries[] = array(
                    'key' => '_fax',
                    'value' => $fax,
                    'compare' => 'LIKE'
                );
            }
            if (!empty($_GET['miles']) && $_GET['miles'] > 0 && !empty($_GET['cityLat']) && !empty($_GET['cityLng'])) {
                $radius_search_unit = get_directorist_option('radius_search_unit', 'miles');
                $args['atbdp_geo_query'] = array(
                    'lat_field' => '_manual_lat',  // this is the name of the meta field storing latitude
                    'lng_field' => '_manual_lng', // this is the name of the meta field storing longitude
                    'latitude' => $_GET['cityLat'],    // this is the latitude of the point we are getting distance from
                    'longitude' => $_GET['cityLng'],   // this is the longitude of the point we are getting distance from
                    'distance' => $_GET['miles'],           // this is the maximum distance to search
                    'units' => $radius_search_unit       // this supports options: miles, mi, kilometers, km
                );
            } elseif (isset($_GET['address'])) {
                $address = $_GET['address'];
                $meta_queries[] = array(
                    'key' => '_address',
                    'value' => $address,
                    'compare' => 'LIKE'
                );
            }

            if (!empty($_GET['zip_code'])) {
                $zip_code = $_GET['zip_code'];
                $meta_queries[] = array(
                    'key' => '_zip',
                    'value' => $zip_code,
                    'compare' => 'LIKE'
                );
            }

            $meta_queries['expired'] = array(
                'relation' => 'OR',
                array(
                    'key' => '_expiry_date',
                    'value' => current_time('mysql'),
                    'compare' => '>', // eg. expire date 6 <= current date 7 will return the post
                    'type' => 'DATETIME'
                ),
                array(
                    'key' => '_never_expire',
                    'value' => 1,
                )

            );
            $args['expired'] = $meta_queries;
            if ($has_featured) {

                if ('_featured' == $atts['filterby']) {
                    $meta_queries['_featured'] = array(
                        'key' => '_featured',
                        'value' => 1,
                        'compare' => '='
                    );

                } else {
                    $meta_queries['_featured'] = array(
                        'key' => '_featured',
                        'type' => 'NUMERIC',
                        'compare' => 'EXISTS',
                    );
                }

            }

            switch ($current_order) {
                case 'title-asc' :
                if ($has_featured) {
                    $args['meta_key'] = '_featured';
                    $args['orderby'] = array(
                        'meta_value_num' => 'DESC',
                        'title' => 'ASC',
                    );
                } else {
                    $args['orderby'] = 'title';
                    $args['order'] = 'ASC';
                };
                break;
                case 'title-desc' :
                if ($has_featured) {
                    $args['meta_key'] = '_featured';
                    $args['orderby'] = array(
                        'meta_value_num' => 'DESC',
                        'title' => 'DESC',
                    );
                } else {
                    $args['orderby'] = 'title';
                    $args['order'] = 'DESC';
                };
                break;
                case 'date-asc' :
                if ($has_featured) {
                    $args['meta_key'] = '_featured';
                    $args['orderby'] = array(
                        'meta_value_num' => 'DESC',
                        'date' => 'ASC',
                    );
                } else {
                    $args['orderby'] = 'date';
                    $args['order'] = 'ASC';
                };
                break;
                case 'date-desc' :
                if ($has_featured) {
                    $args['meta_key'] = '_featured';
                    $args['orderby'] = array(
                        'meta_value_num' => 'DESC',
                        'date' => 'DESC',
                    );
                } else {
                    $args['orderby'] = 'date';
                    $args['order'] = 'DESC';
                };
                break;
                case 'price-asc' :
                if ($has_featured) {
                    $meta_queries['price'] = array(
                        'key' => '_price',
                        'type' => 'NUMERIC',
                        'compare' => 'EXISTS',
                    );

                    $args['orderby'] = array(
                        '_featured' => 'DESC',
                        'price' => 'ASC',
                    );
                } else {
                    $args['meta_key'] = '_price';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'ASC';
                };
                break;
                case 'price-desc' :
                if ($has_featured) {
                    $meta_queries['price'] = array(
                        'key' => '_price',
                        'type' => 'NUMERIC',
                        'compare' => 'EXISTS',
                    );

                    $args['orderby'] = array(
                        '_featured' => 'DESC',
                        'price' => 'DESC',
                    );
                } else {
                    $args['meta_key'] = '_price';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                };
                break;
                case 'views-desc' :
                $listings = get_atbdp_listings_ids();
                $rated = array();
                $listing_popular_by = get_directorist_option('listing_popular_by');
                $average_review_for_popular = get_directorist_option('average_review_for_popular', 4);
                $view_to_popular = get_directorist_option('views_for_popular');
                if ($has_featured) {
                    if ('average_rating' === $listing_popular_by) {
                        if ($listings->have_posts()) {
                            while ($listings->have_posts()) {
                                $listings->the_post();
                                $listing_id = get_the_ID();
                                $average = ATBDP()->review->get_average($listing_id);
                                if ($average_review_for_popular <= $average) {
                                    $rated[] = get_the_ID();
                                }

                            }
                            $rating_id = array(
                                'post__in' => !empty($rated) ? $rated : array()
                            );
                            $args = array_merge($args, $rating_id);
                        }
                    } elseif ('view_count' === $listing_popular_by) {
                        $meta_queries['views'] = array(
                            'key' => '_atbdp_post_views_count',
                            'value' => $view_to_popular,
                            'type' => 'NUMERIC',
                            'compare' => '>=',
                        );
                        $args['orderby'] = array(
                            '_featured' => 'DESC',
                            'views' => 'DESC',
                        );
                    } else {
                        $meta_queries['views'] = array(
                            'key' => '_atbdp_post_views_count',
                            'value' => $view_to_popular,
                            'type' => 'NUMERIC',
                            'compare' => '>=',
                        );
                        $args['orderby'] = array(
                            '_featured' => 'DESC',
                            'views' => 'DESC',
                        );
                        if ($listings->have_posts()) {
                            while ($listings->have_posts()) {
                                $listings->the_post();
                                $listing_id = get_the_ID();
                                $average = ATBDP()->review->get_average($listing_id);
                                if ($average_review_for_popular <= $average) {
                                    $rated[] = get_the_ID();
                                }

                            }
                            $rating_id = array(
                                'post__in' => !empty($rated) ? $rated : array()
                            );
                            $args = array_merge($args, $rating_id);
                        }
                    }
                } else {
                    if ('average_rating' === $listing_popular_by) {
                        if ($listings->have_posts()) {
                            while ($listings->have_posts()) {
                                $listings->the_post();
                                $listing_id = get_the_ID();
                                $average = ATBDP()->review->get_average($listing_id);
                                if ($average_review_for_popular <= $average) {
                                    $rated[] = get_the_ID();
                                }

                            }
                            $rating_id = array(
                                'post__in' => !empty($rated) ? $rated : array()
                            );
                            $args = array_merge($args, $rating_id);
                        }
                    } elseif ('view_count' === $listing_popular_by) {
                        $meta_queries['views'] = array(
                            'key' => '_atbdp_post_views_count',
                            'value' => $view_to_popular,
                            'type' => 'NUMERIC',
                            'compare' => '>=',
                        );
                        $args['orderby'] = array(
                            'views' => 'DESC',
                        );
                    } else {
                        $meta_queries['views'] = array(
                            'key' => '_atbdp_post_views_count',
                            'value' => (int)$view_to_popular,
                            'type' => 'NUMERIC',
                            'compare' => '>=',
                        );
                        $args['orderby'] = array(
                            'views' => 'DESC',
                        );
                        if ($listings->have_posts()) {
                            while ($listings->have_posts()) {
                                $listings->the_post();
                                $listing_id = get_the_ID();
                                $average = ATBDP()->review->get_average($listing_id);
                                if ($average_review_for_popular <= $average) {
                                    $rated[] = get_the_ID();
                                }

                            }
                            $rating_id = array(
                                'post__in' => !empty($rated) ? $rated : array()
                            );
                            $args = array_merge($args, $rating_id);
                        }
                    }
                }
                break;
                case 'rand' :
                if ($has_featured) {
                    $args['meta_key'] = '_featured';
                    $args['orderby'] = 'meta_value_num rand';
                } else {
                    $args['orderby'] = 'rand';
                };
                break;
            }

            $meta_queries = apply_filters('atbdp_search_listings_meta_queries', $meta_queries);

            $count_meta_queries = count($meta_queries);
            if ($count_meta_queries) {
                $args['meta_query'] = ($count_meta_queries > 1) ? array_merge(array('relation' => 'AND'), $meta_queries) : $meta_queries;
            }

            $all_listings = new WP_Query(apply_filters('atbdp_listing_search_query_argument', $args));
            $default_radius_distance = !empty($_GET['miles']) ? $_GET['miles'] : $default_radius_distance;
            wp_localize_script( 'atbdp-range-slider', 'atbdp_range_slider', array(
                'Miles'     =>  $miles,
                'default_val'   =>  $default_radius_distance
            ) );
            $cat_id = !empty($_GET['in_cat']) ? $_GET['in_cat'] : '';
            $loc_id = !empty($_GET['in_loc']) ? $_GET['in_loc'] : '';
            $cat_name = get_term_by('id', $cat_id, ATBDP_CATEGORY);
            $loc_name = get_term_by('id', $loc_id, ATBDP_LOCATION);
            $for_cat = !empty($cat_name) ? sprintf(__('for %s', 'directorist'), $cat_name->name) : '';
            if (isset($_GET['in_loc']) && (int)$_GET['in_loc'] > 0) {
                $in_loc = !empty($loc_name) ? sprintf(__('in %s', 'directorist'), $loc_name->name) : '';
            } else {
                $in_loc = !empty($_GET['address']) ? sprintf(__('in %s', 'directorist'), $_GET['address']) : '';
            }
            $_s = (1 < count($all_listings->posts)) ? 's' : '';

            $header_title = sprintf(__('%d result%s %s %s', 'directorist'), $all_listings->found_posts, $_s, $for_cat, $in_loc);
            $listing_filters_button = get_directorist_option('search_result_filters_button_display', 1);
            $filters = get_directorist_option('search_result_filter_button_text', __('Filters', 'directorist'));
            $text_placeholder = get_directorist_option('search_result_search_text_placeholder', __('What are you looking for?', 'directorist'));
            $category_placeholder = get_directorist_option('search_result_category_placeholder', __('Select a category', 'directorist'));
            $location_placeholder = get_directorist_option('search_result_location_placeholder', __('Select a location', 'directorist'));
            $sort_by_text = get_directorist_option('search_sortby_text', __('Sort By', 'directorist'));
            $view_as_text = get_directorist_option('search_viewas_text', __('View As', 'directorist'));
            $all_listing_title = !empty($all_listing_title) ? $all_listing_title : '';
            $data_for_template = compact('all_listings', 'all_listing_title', 'paged', 'paginate');
            $search_more_filters_fields = get_directorist_option('search_result_filters_fields', array('search_text', 'search_category', 'search_location', 'search_price', 'search_price_range', 'search_rating', 'search_tag', 'search_custom_fields', 'radius_search'));
            $filters_button = get_directorist_option('search_result_filters_button', array('reset_button', 'apply_button'));
            $reset_filters_text = get_directorist_option('sresult_reset_text', __('Reset Filters', 'directorist'));
            $apply_filters_text = get_directorist_option('sresult_apply_text', __('Apply Filters', 'directorist'));
            $data_for_template = compact('all_listings', 'all_listing_title', 'paged', 'paginate');
            $view_as_items = get_directorist_option('search_view_as_items', array('listings_grid', 'listings_list', 'listings_map'));
            $sort_by_items = get_directorist_option('search_sort_by_items', array('a_z', 'z_a', 'latest', 'oldest', 'popular', 'price_low_high', 'price_high_low', 'random'));
            $listing_header_container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
            $header_container_fluid = apply_filters('atbdp_search_result_header_container_fluid', $listing_header_container_fluid);
            $listing_grid_container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
            $grid_container_fluid = apply_filters('atbdp_search_result_grid_container_fluid', $listing_grid_container_fluid);
            $listing_location_address = get_directorist_option('sresult_location_address', 'map_api');
            ob_start();
            $include = apply_filters('include_style_settings', true);

            /* if ($include) {
                wp_enqueue_style('atbdp-settings-style');
            } */

            // Add Inline Style
            $column_width = 100 / (int)$columns . '%';
            $style = '.atbd_content_active #directorist.atbd_wrapper .atbdp_column {';
            $style .= "width: $column_width; } \n";

            $listing_map_type = get_directorist_option( 'select_listing_map', 'google' );
            
            if ( $listing_map_type === 'openstreet' ) {
                $style .= '.myDivIcon {';
                $style .= 'text-align: center !important;';
                $style .= 'line-height: 20px !important;';
                $style .= "position: relative; }\n";

                $style .= '.myDivIcon div.atbd_map_shape {';
                $style .= 'position: absolute;';
                $style .= 'top: -38px;';
                $style .= "left: -15px; }\n";
            }

            wp_add_inline_style( 'atbdp-inline-style', $style );
            wp_enqueue_style('atbdp-inline-style');


            if (!empty($redirect_page_url)) {
                $redirect = '<script>window.location="' . esc_url($redirect_page_url) . '"</script>';
                return $redirect;
            }
            $listing_type = isset($_GET['listing_type']) ? sanitize_text_field($_GET['listing_type']) : '';
            

            // Base Template Data
            !empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
            $all_listings = !empty($all_listings) ? $all_listings : new WP_Query;
            $display_sortby_dropdown = get_directorist_option('display_sort_by', 1);
            $display_viewas_dropdown = get_directorist_option('display_view_as', 1);
            $display_image = !empty($display_image) ? $display_image : '';
            $show_pagination = !empty($show_pagination) ? $show_pagination : '';
            $paged = !empty($paged) ? $paged : '';

            $is_disable_price = get_directorist_option('disable_list_price');
            $view_as = get_directorist_option('grid_view_as', 'normal_grid');
            $column_width = 100 / (int)$columns . '%';

            if (is_rtl()) {
                wp_enqueue_style('atbdp-search-style-rtl', ATBDP_PUBLIC_ASSETS . 'css/search-style-rtl.css');
            } else {
                wp_enqueue_style('atbdp-search-style', ATBDP_PUBLIC_ASSETS . 'css/search-style.css');
            }

            // Header Template Data
            $address_label               = get_directorist_option('address_label',__('Address','directorist'));
            $fax_label                   = get_directorist_option('fax_label',__('Fax','directorist'));
            $email_label                 = get_directorist_option('email_label',__('Email','directorist'));
            $website_label               = get_directorist_option('website_label',__('Website','directorist'));
            $tag_label                   = get_directorist_option('tag_label',__('Tag','directorist'));
            $zip_label                   = get_directorist_option('zip_label',__('Zip','directorist'));
            $listing_filters_icon        = get_directorist_option('listing_filters_icon',1);
            $query_args = array(
                'parent'             => 0,
                'term_id'            => 0,
                'hide_empty'         => 0,
                'orderby'            => 'name',
                'order'              => 'asc',
                'show_count'         => 0,
                'single_only'        => 0,
                'pad_counts'         => true,
                'immediate_category' => 0,
                'active_term_id'     => 0,
                'ancestors'          => array()
            );
            $categories_fields = search_category_location_filter( $query_args, ATBDP_CATEGORY );
            $locations_fields  = search_category_location_filter( $query_args, ATBDP_LOCATION );
            $currency = get_directorist_option('g_currency', 'USD');
            $c_symbol = atbdp_currency_symbol($currency);


            // If Post_Your_Need is active
            if ( class_exists('Post_Your_Need') && ($listing_type === 'need') ) {
                $path = atbdp_get_theme_file("/directorist/shortcodes/listings/extension/post-your-need/need-card.php");
                
                if ( $path ) {
                    include $path;
                } else {
                    include PYN_TEMPLATES_DIR . "/need-card.php";
                }

                return ob_get_clean();
            }

            // If listings_with_map is active
            if ( 'listings_with_map' == $view ) {
                $path = atbdp_get_theme_file("/directorist/shortcodes/listings/extension/listing-with-map/map-view.php");
                
                if ( $path ) {
                    include $path;
                } else {
                    include BDM_TEMPLATES_DIR . '/map-view.php';
                }

                return ob_get_clean();
            }

            // Default Template
            $path = atbdp_get_theme_file("/directorist/shortcodes/listings-archive/listings-{$view}.php");
            if ( $path ) {
                include $path;
            } else {
                include ATBDP_TEMPLATES_DIR . "public-templates/shortcodes/listings-archive/listings-$view.php";
            }
            
            return ob_get_clean();
        }

        public function listing_archive( $atts ) {
            $listings = new Directorist_Listings($atts);
            return $listings->render_shortcode();
        }

        public function category_archive( $atts ) {
            $atts             = !empty( $atts ) ? $atts : array();
            $category_slug    = get_query_var('atbdp_category');
            $atts['category'] = sanitize_text_field( $category_slug );
            return $this->listing_archive( $atts );
        }

        public function tag_archive( $atts ) {
            $atts        = !empty( $atts ) ? $atts : array();
            $tag_slug    = get_query_var('atbdp_tag');
            $atts['tag'] = sanitize_text_field( $tag_slug );
            return $this->listing_archive( $atts );
        }

        public function location_archive( $atts ) {
            $atts        = !empty( $atts ) ? $atts : array();
            $tag_slug    = get_query_var('atbdp_location');
            $atts['location'] = sanitize_text_field( $tag_slug );
            return $this->listing_archive( $atts );
        }

        public function user_dashboard($atts) {
            $dashboard = new Directorist_Listing_Dashboard();
            return $dashboard->render_shortcode_user_dashboard($atts);
        }

        public function all_categories($atts) {
            $taxonomy  = new Directorist_Listing_Taxonomy($atts, 'category');
            return $taxonomy->render_shortcode();
        }

        public function all_locations($atts) {
            $taxonomy  = new Directorist_Listing_Taxonomy($atts, 'location');
            return $taxonomy->render_shortcode();
        }

        public function search_listing($atts) {
            $searchform = new Directorist_Listing_Search_Form( 'search', $atts );
            return $searchform->render_search_shortcode();
        }

        public function author_profile($atts) {
            $author = new Directorist_Listing_Author();
            return $author->render_shortcode_author_profile($atts);
        }

        public function add_listing($atts) {
            $forms  = Directorist_Listing_Forms::instance();
            return $forms->render_shortcode_add_listing($atts);
        }

        public function custom_user_login() {
            $forms = new Directorist_Listing_Forms();
            return $forms->render_shortcode_user_login();
        }

        public function user_registration() {
            $forms = new Directorist_Listing_Forms();
            return $forms->render_shortcode_custom_registration();
        }

        // guard
        public function guard( Array $args = [] ) {
            $type = ( ! empty( $args['type'] ) ) ? $args['type'] : 'auth';
            $login_redirect = ( ! empty( $args['login_redirect'] ) ) ? $args['login_redirect'] : false;

            if ( $type === 'auth' && ! atbdp_logged_in_user() && ! $login_redirect ) {
                ob_start();
                // user not logged in;
                $error_message = sprintf(__('You need to be logged in to view the content of this page. You can login %s. Don\'t have an account? %s', 'directorist'), apply_filters('atbdp_listing_form_login_link', "<a href='" . ATBDP_Permalink::get_login_page_link() . "'> " . __('Here', 'directorist') . "</a>"), apply_filters('atbdp_listing_form_signup_link', "<a href='" . ATBDP_Permalink::get_registration_page_link() . "'> " . __('Sign Up', 'directorist') . "</a>")); 
                ?>
                <section class="directory_wrapper single_area">
                    <?php ATBDP()->helper->show_login_message($error_message); ?>
                </section>
                <?php
                return ob_get_clean();
            }

            return '';
        }

    }
endif;