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
            $listing = new Directorist_Single_Listing();
            return $listing->render_shortcode_listing_review();
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

        public function search_result($atts) {
            $listings = new Directorist_Listings( $atts, 'search' );
            return $listings->render_shortcode();
            // @todo @kowsar 'Post_Your_Need' template file - atbdp_get_theme_file("/directorist/shortcodes/listings/extension/post-your-need/need-card.php")
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