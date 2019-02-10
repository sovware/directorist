<?php
if ( !class_exists('ATBDP_Shortcode') ):

    class ATBDP_Shortcode {

        public function __construct()
        {

            add_shortcode( 'directorist_search_listing', array( $this, 'search_listing' ) );

            add_shortcode( 'directorist_search_result', array( $this, 'search_result' ) );

            add_shortcode('directorist_author_profile', array($this, 'author_profile'));

            add_shortcode( 'directorist_add_listing', array( $this, 'add_listing' ) );

            add_shortcode( 'directorist_custom_registration', array( $this, 'user_registration' ) );

            add_shortcode( 'directorist_user_login', array( $this, 'custom_user_login' ) );

            add_shortcode( 'directorist_user_dashboard', array( $this, 'user_dashboard' ) );

            add_shortcode( 'directorist_all_listing', array( $this, 'all_listing' ) );

            add_shortcode( 'directorist_all_categories', array( $this, 'all_categories' ) );

            add_shortcode( 'directorist_all_locations', array( $this, 'all_locations' ) );
            $checkout = new ATBDP_Checkout;
            add_shortcode('directorist_checkout', array($checkout, 'display_checkout_content'));
            add_shortcode('directorist_payment_receipt', array($checkout, 'payment_receipt'));
            add_shortcode('directorist_transaction_failure', array($checkout, 'transaction_failure'));

            add_action('wp_ajax_atbdp_custom_fields_listings_front', array($this, 'ajax_callback_custom_fields'), 10, 2 );
            add_action('wp_ajax_atbdp_custom_fields_listings_front_selected', array($this, 'ajax_callback_custom_fields'), 10, 2 );

            add_filter( 'body_class', array($this, 'my_body_class'));

        }

        /*
         *  add own class in order to push custom style
         */
        public function my_body_class( $c ) {

            global $post;
            $shortcodes = array('');
            if( isset($post->post_content) && has_shortcode( $post->post_content, 'all_listing' ) ) {
                $c[] = 'atbd_content_active';
            }
            return $c;
        }
        /**
         * Display custom fields.
         *
         * @since	 3.2
         * @access   public
         * @param	 int    $post_id	Post ID.
         * @param	 int    $term_id    Category ID.
         */
        public function ajax_callback_custom_fields( $post_id = 0, $term_id = 0 ) {


            $ajax = false;
            if( isset( $_POST['term_id'] ) ) {
                $ajax = true;
                $post_ID = (int) $_POST['post_id'];
                $term_id = (int) $_POST['term_id'];
            }
            // var_dump($post_id);

            // Get custom fields
            $custom_field_ids = $term_id;
            $args = array(
                'post_type'      => ATBDP_CUSTOM_FIELD_POST_TYPE,
                'posts_per_page' => -1,
                'meta_query'    => array(
                    'relation' => 'AND',
                    array(
                        'key'       => 'category_pass',
                        'value'     => $custom_field_ids,
                        'compare'   => 'LIKE',
                    ),
                    array(
                        'key'       => 'associate',
                        'value'     => 'categories',
                        'compare'   => 'LIKE',
                    )
                )
            );

            $atbdp_query = new WP_Query( $args );

            if ($atbdp_query->have_posts()){
                // Start the Loop
                global $post;
                // Process output
                ob_start();

                include ATBDP_TEMPLATES_DIR . 'add-listing-custom-field.php';
                wp_reset_postdata(); // Restore global post data stomped by the_post()
                $output = ob_get_clean();

                print $output;

                if( $ajax ) {
                    wp_die();
                }
            }else{
                echo '<div class="custom_field_empty_area"></div>';
                ?>
                <script>
                    if(('#custom_field_empty_area').length )         // use this if you are using id to check
                    {
                        $('#atbdp-custom-fields-list' ).empty();
                    }
                </script>
                <?php
                if( $ajax ) {
                    wp_die();
                }
            }

        }


        public function search_result()
        {
            ob_start();
            $paged = atbdp_get_paged_num();
            $srch_p_num = get_directorist_option('search_posts_num', 6);
            $paginate = get_directorist_option('paginate_search_results');
            $s_string = !empty($_GET['q']) ? sanitize_text_field($_GET['q']) : '';// get the searched query
            $in_cat = !empty($_GET['in_cat']) ? sanitize_text_field($_GET['in_cat']) : '';
            $in_loc = !empty($_GET['in_loc']) ? sanitize_text_field($_GET['in_loc']) : '';
            $in_tag = !empty($_GET['in_tag']) ? sanitize_text_field($_GET['in_tag']) : '';

            // lets setup the query args
            $args = array(
                'post_type'      => ATBDP_POST_TYPE,
                'post_status'    => 'publish',
                'posts_per_page' => (int) $srch_p_num,
                'paged'          => $paged,
                's'            => $s_string,
            );
            if (!$paginate) $args['no_found_rows'] = true;

            $tax_queries=array(); // initiate the tax query var to append to it different tax query

            if( !empty($in_cat) ) {
                /*@todo; add option to the settings panel to let user choose whether to include result from children or not*/
                $tax_queries[] = array(
                    'taxonomy'         => ATBDP_CATEGORY,
                    'field'            => 'slug',
                    'terms'            => $in_cat,
                    'include_children' => true, /*@todo; Add option to include children or exclude it*/
                );

            }

            if( !empty($in_loc) ) {
                /*@todo; add option to the settings panel to let user choose whether to include result from children or not*/
                $tax_queries[] = array(
                    'taxonomy'         => ATBDP_LOCATION,
                    'field'            => 'slug',
                    'terms'            => $in_loc,
                    'include_children' => true
                );

            }

            if( !empty($in_tag) ) {
                $tax_queries[] = array(
                    'taxonomy'         => ATBDP_TAGS,
                    'field'            => 'slug',
                    'terms'            => $in_tag,
                );

            }

            if (!is_empty_v($tax_queries)){
                $args['tax_query'] = $tax_queries;
            }

            $meta_queries = array();
            // Show featured listing first. Eg. Order by Featured Listings eg.
            $featured_active = get_directorist_option('enable_featured_listing');
            if ($featured_active){
                $meta_queries[] = array(
                    'key'     => '_featured',
                    'type'    => 'NUMERIC',
                    'compare' => 'EXISTS',
                );

                $args['orderby']  = array(
                    'meta_value_num' => 'DESC',
                );
            }
            if (!is_empty_v($meta_queries)){
                $args['meta_query'] = $meta_queries;
            }

            $listings = new WP_Query(apply_filters('atbdp_search_query_args', $args));


            $data_for_template = compact('listings', 'in_loc', 'in_cat', 'in_tag', 's_string', 'paged', 'paginate');
            ATBDP()->load_template('search-at_biz_dir', array( 'data' => $data_for_template ));
            return ob_get_clean();
        }


        public function all_listing( $atts )
        {
            wp_enqueue_script('adminmainassets');
            $listing_orderby = get_directorist_option('order_listing_by');
            $listing_view    = get_directorist_option('default_listing_view');
            $listing_order   = get_directorist_option('sort_listing_by');
            $listing_grid_columns   = get_directorist_option('all_listing_columns',3);
            $atts = shortcode_atts( array(
                'view'              => !empty($listing_view) ? $listing_view : 'grid',
                '_featured'         => 1,
                'filterby'          => '',
                'orderby'           => !empty($listing_orderby) ? $listing_orderby : 'date',
                'order'             => !empty($listing_order) ? $listing_order : 'asc',
                'listings_per_page' => (int) get_directorist_option('all_listing_page_items', 6),
                'pagination'        => 1,
                'header'            => 1,
                'category'          => '',
                'location'          => '',
                'tag'               => '',
                'ids'               => '',
                'columns'            => !empty($listing_grid_columns) ? $listing_grid_columns : 3
            ), $atts );

            $categories = !empty($atts['category'] ) ? explode(',', $atts['category'] ) : '';
            $tags = !empty($atts['tag'] ) ? explode(',', $atts['tag'] ) : '';
            $locations = !empty($atts['location'] ) ? explode(',', $atts['location'] ) : '';
            $listing_id = !empty($atts['ids'] ) ? explode(',', $atts['ids'] ) : '';
            $columns = !empty($atts['columns']) ? $atts['columns'] : 3;
            //for pagination
            $paged = atbdp_get_paged_num();
            $paginate = get_directorist_option('paginate_all_listings');

            if (!$paginate) $args['no_found_rows'] = true;

            $has_featured = get_directorist_option('enable_featured_listing');
            if( $has_featured ) {
                $has_featured = $atts['_featured'];
            }

            $current_order       = atbdp_get_listings_current_order( $atts['orderby'].'-'.$atts['order'] );
            $view                = atbdp_get_listings_current_view_name( $atts['view'] );

            $args = array(
                'post_type'      => ATBDP_POST_TYPE,
                'post_status'    => 'publish',
                'posts_per_page' => (int) $atts['listings_per_page'],
                'paged'          => $paged,
            );

            $listingbyid_arg = array();

            if( !empty($listing_id)) {
                $listingbyid_arg = $listing_id;
                $args['post__in'] = $listingbyid_arg;
            }



            $tax_queries=array(); // initiate the tax query var to append to it different tax query

            if( !empty($categories) && !empty($locations) && !empty($tags)) {

                $tax_queries['tax_query'] = array(
                    'relation' => 'AND',
                    array(
                        'taxonomy'         => ATBDP_CATEGORY,
                        'field'            => 'slug',
                        'terms'            => !empty($categories) ? $categories : array(),
                        'include_children' => true, /*@todo; Add option to include children or exclude it*/
                    ),
                    array(
                        'taxonomy'         => ATBDP_LOCATION,
                        'field'            => 'slug',
                        'terms'            => !empty($locations) ? $locations : array(),
                        'include_children' => true, /*@todo; Add option to include children or exclude it*/
                    ),
                    array(
                        'taxonomy'         => ATBDP_TAGS,
                        'field'            => 'slug',
                        'terms'            => !empty($tags) ? $tags : array(),
                        'include_children' => true, /*@todo; Add option to include children or exclude it*/
                    ),

                );
            } elseif(!empty($categories) && !empty($tags)) {
                $tax_queries['tax_query'] = array(
                    'relation' => 'AND',
                    array(
                        'taxonomy'         => ATBDP_CATEGORY,
                        'field'            => 'slug',
                        'terms'            => !empty($categories) ? $categories : array(),
                        'include_children' => true, /*@todo; Add option to include children or exclude it*/
                    ),
                    array(
                        'taxonomy'         => ATBDP_TAGS,
                        'field'            => 'slug',
                        'terms'            => !empty($tags) ? $tags : array(),
                        'include_children' => true, /*@todo; Add option to include children or exclude it*/
                    ),

                );
            } elseif(!empty($categories) && !empty($locations)) {
                $tax_queries['tax_query'] = array(
                    'relation' => 'AND',
                    array(
                        'taxonomy'         => ATBDP_CATEGORY,
                        'field'            => 'slug',
                        'terms'            => !empty($categories) ? $categories : array(),
                        'include_children' => true, /*@todo; Add option to include children or exclude it*/
                    ),
                    array(
                        'taxonomy'         => ATBDP_LOCATION,
                        'field'            => 'slug',
                        'terms'            => !empty($locations) ? $locations : array(),
                        'include_children' => true, /*@todo; Add option to include children or exclude it*/
                    ),

                );
            } elseif(!empty($tags) && !empty($locations)) {
                $tax_queries['tax_query'] = array(
                    'relation' => 'AND',
                    array(
                        'taxonomy'         => ATBDP_TAGS,
                        'field'            => 'slug',
                        'terms'            => !empty($tags) ? $tags : array(),
                        'include_children' => true, /*@todo; Add option to include children or exclude it*/
                    ),
                    array(
                        'taxonomy'         => ATBDP_LOCATION,
                        'field'            => 'slug',
                        'terms'            => !empty($locations) ? $locations : array(),
                        'include_children' => true, /*@todo; Add option to include children or exclude it*/
                    ),

                );
            } elseif(!empty($categories)) {
                $tax_queries['tax_query'] = array(
                    array(
                        'taxonomy'         => ATBDP_CATEGORY,
                        'field'            => 'slug',
                        'terms'            => !empty($categories) ? $categories : array(),
                        'include_children' => true, /*@todo; Add option to include children or exclude it*/
                    )
                );
            } elseif(!empty($tags)) {
                $tax_queries['tax_query'] = array(
                    array(
                        'taxonomy'         => ATBDP_TAGS,
                        'field'            => 'slug',
                        'terms'            => !empty($tags) ? $tags : array(),
                        'include_children' => true, /*@todo; Add option to include children or exclude it*/
                    )
                );
            } elseif(!empty($locations)) {
                $tax_queries['tax_query'] = array(
                    array(
                        'taxonomy'         => ATBDP_LOCATION,
                        'field'            => 'slug',
                        'terms'            => !empty($locations) ? $locations : array(),
                        'include_children' => true, /*@todo; Add option to include children or exclude it*/
                    )
                );
            }
            $args['tax_query'] = $tax_queries;

            $meta_queries = array();

            if( $has_featured ) {

                if( '_featured' == $atts['filterby'] ) {
                    $meta_queries['_featured'] = array(
                        'key'     => '_featured',
                        'value'   => 1,
                        'compare' => '='
                    );

                } else {
                    $meta_queries['_featured'] = array(
                        'key'     => '_featured',
                        'type'    => 'NUMERIC',
                        'compare' => 'EXISTS',
                    );
                }

            }

            switch( $current_order ) {
                case 'title-asc' :
                    if( $has_featured ) {
                        $args['meta_key'] = '_featured';
                        $args['orderby']  = array(
                            'meta_value_num' => 'DESC',
                            'title'          => 'ASC',
                        );
                    } else {
                        $args['orderby'] = 'title';
                        $args['order']   = 'ASC';
                    };
                    break;
                case 'title-desc' :
                    if( $has_featured ) {
                        $args['meta_key'] = '_featured';
                        $args['orderby']  = array(
                            'meta_value_num' => 'DESC',
                            'title'          => 'DESC',
                        );
                    } else {
                        $args['orderby'] = 'title';
                        $args['order']   = 'DESC';
                    };
                    break;
                case 'date-asc' :
                    if( $has_featured ) {
                        $args['meta_key'] = '_featured';
                        $args['orderby']  = array(
                            'meta_value_num' => 'DESC',
                            'date'           => 'ASC',
                        );
                    } else {
                        $args['orderby'] = 'date';
                        $args['order']   = 'ASC';
                    };
                    break;
                case 'date-desc' :
                    if( $has_featured ) {
                        $args['meta_key'] = '_featured';
                        $args['orderby']  = array(
                            'meta_value_num' => 'DESC',
                            'date'           => 'DESC',
                        );
                    } else {
                        $args['orderby'] = 'date';
                        $args['order']   = 'DESC';
                    };
                    break;
                case 'price-asc' :
                    if( $has_featured ) {
                        $meta_queries['price'] = array(
                            'key'     => '_price',
                            'type'    => 'NUMERIC',
                            'compare' => 'EXISTS',
                        );

                        $args['orderby']  = array(
                            '_featured' => 'DESC',
                            'price'    => 'ASC',
                        );
                    } else {
                        $args['meta_key'] = '_price';
                        $args['orderby']  = 'meta_value_num';
                        $args['order']    = 'ASC';
                    };
                    break;
                case 'price-desc' :
                    if( $has_featured ) {
                        $meta_queries['price'] = array(
                            'key'     => '_price',
                            'type'    => 'NUMERIC',
                            'compare' => 'EXISTS',
                        );

                        $args['orderby']  = array(
                            '_featured' => 'DESC',
                            'price'    => 'DESC',
                        );
                    } else {
                        $args['meta_key'] = '_price';
                        $args['orderby']  = 'meta_value_num';
                        $args['order']    = 'DESC';
                    };
                    break;
                case 'views-desc' :
                    if( $has_featured ) {
                        $meta_queries['views'] = array(
                            'key'     => '_atbdp_post_views_count',
                            'type'    => 'NUMERIC',
                            'compare' => 'EXISTS',
                        );

                        $args['orderby']  = array(
                            '_featured' => 'DESC',
                            '_atbdp_post_views_count'    => 'DESC',
                        );
                    } else {
                        $args['meta_key'] = '_atbdp_post_views_count';
                        $args['orderby']  = 'meta_value_num';
                        $args['order']    = 'DESC';
                    };
                    break;
                case 'rand' :
                    if( $has_featured ) {
                        $args['meta_key'] = '_featured';
                        $args['orderby']  = 'meta_value_num rand';
                    } else {
                        $args['orderby'] = 'rand';
                    };
                    break;
            }

            $count_meta_queries = count( $meta_queries );
            if( $count_meta_queries ) {
                $args['meta_query'] = ( $count_meta_queries > 1 ) ? array_merge( array( 'relation' => 'AND' ), $meta_queries ) : $meta_queries;
            }

            $all_listings = new WP_Query($args);

            $all_listing_title = get_directorist_option('all_listing_title', __('All Items', ATBDP_TEXTDOMAIN));
            $data_for_template = compact('all_listings', 'all_listing_title', 'paged', 'paginate');

            ob_start();
            include ATBDP_TEMPLATES_DIR . "front-end/all-listings/all-$view-listings.php";
            return ob_get_clean();
        }

        public function user_dashboard()
        {
            ob_start();
            // show user dashboard if the user is logged in, else kick him out of this page or show a message
            if (is_user_logged_in()){
                ATBDP()->enquirer->front_end_enqueue_scripts(true); // all front end scripts forcibly here
                ATBDP()->user->user_dashboard();
            }else{
                // user not logged in;
                $error_message = sprintf(__('You need to be logged in to view the content of this page. You can login %s. Don\'t have an account? %s', ATBDP_TEXTDOMAIN), "<a href='".wp_login_url()."'> ". __('Here', ATBDP_TEXTDOMAIN)."</a>","<a href='".ATBDP_Permalink::get_registration_page_link()."'> ". __('Sign Up', ATBDP_TEXTDOMAIN)."</a>"); ?>
                <section class="directory_wrapper single_area">
                    <?php  ATBDP()->helper->show_login_message($error_message); ?>
                </section>
                <?php
            }
            return ob_get_clean();
        }

        public function all_categories ( $atts )
        {
            wp_enqueue_script('loc_cat_assets');
            ob_start();
            $display_categories_as   = get_directorist_option('display_categories_as','grid');
            $categories_settings = array();
            $categories_settings['depth'] = get_directorist_option('categories_depth_number',1);
            $categories_settings['columns'] = get_directorist_option('categories_column_number',3);
            $categories_settings['show_count'] = get_directorist_option('display_listing_count',1);
            $categories_settings['hide_empty'] = get_directorist_option('hide_empty_categories');
            $categories_settings['orderby'] = get_directorist_option('order_category_by','id');
            $categories_settings['order'] = get_directorist_option('sort_category_by','asc');

            $atts = shortcode_atts( array(
                'view'              => $display_categories_as,
                'orderby'           => $categories_settings['orderby'],
                'order'             => $categories_settings['order']
            ), $atts );

            $args = array(
                'orderby'      => $atts['orderby'],
                'order'        => $atts['order'],
                'hide_empty'   => ! empty( $categories_settings['hide_empty'] ) ? 1 : 0,
                'parent'       => 0,
                'hierarchical' => ! empty( $categories_settings['hide_empty'] ) ? true : false
            );

            $terms = get_terms( ATBDP_CATEGORY, $args );
            //var_dump($terms);
            if( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                if('grid' == $atts['view']) {
                    include ATBDP_TEMPLATES_DIR . 'front-end/categories-page/categories-grid.php';
                }elseif ('list' == $atts['view']) {
                    include ATBDP_TEMPLATES_DIR . 'front-end/categories-page/categories-list.php';
                }
            }else{
                _e('<p>No Results found!</p>', ATBDP_TEXTDOMAIN);
            }
            return ob_get_clean();

        }

        public function all_locations ($atts)
        {
            wp_enqueue_script('loc_cat_assets');
            ob_start();
            $display_locations_as              = get_directorist_option('display_locations_as','grid');
            $locations_settings                = array();
            $locations_settings['depth']       = get_directorist_option('locations_depth_number',1);
            $locations_settings['columns']     = get_directorist_option('locations_column_number',3);
            $locations_settings['show_count']  = get_directorist_option('display_location_listing_count',1);
            $locations_settings['hide_empty']  = get_directorist_option('hide_empty_locations');
            $locations_settings['orderby']     = get_directorist_option('order_location_by','id');
            $locations_settings['order']       = get_directorist_option('sort_location_by','asc');

            $atts = shortcode_atts( array(
                'view'              => $display_locations_as,
                'orderby'           => $locations_settings['orderby'],
                'order'             => $locations_settings['order']
            ), $atts );

            $args = array(
                'orderby'      => $atts['orderby'],
                'order'        => $atts['order'],
                'hide_empty'   => ! empty( $locations_settings['hide_empty'] ) ? 1 : 0,
                'parent'       => 0,
                'hierarchical' => ! empty( $locations_settings['hide_empty'] ) ? true : false
            );

            $terms = get_terms( ATBDP_LOCATION, $args );

            if( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                if('grid' == $atts['view']) {
                    include ATBDP_TEMPLATES_DIR . 'front-end/locations-page/locations-grid.php';
                }elseif ('list' == $atts['view']) {
                    include ATBDP_TEMPLATES_DIR . 'front-end/locations-page/locations-list.php';
                }
            }else{
                _e('<p>No Results found!</p>', ATBDP_TEXTDOMAIN);
            }
            return ob_get_clean();

        }

        public function search_listing($atts, $content = null) {
            ob_start();
            ATBDP()->load_template('listing-home');
            ATBDP()->enquirer->search_listing_scripts_styles();
            return ob_get_clean();
        }

        public function author_profile($atts){
            //for pagination
            $author_id = !empty($_GET['author_id'])?$_GET['author_id']:'';
            $category = !empty($_GET['category'])?$_GET['category']:'';
            $paged = atbdp_get_paged_num();
            $paginate = get_directorist_option('paginate_all_listings');
            if (!$paginate) $args['no_found_rows'] = true;
            $args = array(
                'post_type'      => ATBDP_POST_TYPE,
                'post_status'    => 'publish',
                'posts_per_page' => 9,
                'paged'          => $paged,
                'author'         => $author_id,
            );
            if (!empty($category)){
                $category = array(
                    array(
                        'taxonomy'         => ATBDP_CATEGORY,
                        'field'            => 'slug',
                        'terms'            => !empty($category)?$category:'',
                        'include_children' => true, /*@todo; Add option to include children or exclude it*/
                    )

                );
            }
            if (!empty($category)){
                $args['tax_query'] = $category;
            }


            $all_listings = new WP_Query($args);
            $data_for_template = compact('all_listings', 'paged', 'paginate', 'author_id');
            ob_start();
            ATBDP()->load_template('front-end/public-profile', array( 'data' => $data_for_template ));
            return ob_get_clean();
        }

        public function add_listing($atts, $content = null, $sc_name) {
            ob_start();
            if (is_user_logged_in()) {
                global $wp;
                global $pagenow;
                $current_url = home_url(add_query_arg(array(),$wp->request));
                if (is_fee_manager_active() && !selected_plan_id()){
                    if( (strpos( $current_url, '/edit/' ) !== false) && ($pagenow = 'at_biz_dir')) {
                        ATBDP()->enquirer->add_listing_scripts_styles();
                        ATBDP()->load_template('front-end/add-listing');
                    }else{
                        ATBDP_Fee_Manager()->load_template('fee-plans');
                    }
                }else{
                    ATBDP()->enquirer->add_listing_scripts_styles();
                    ATBDP()->load_template('front-end/add-listing');
                }

            }else{
                // user not logged in;
                $error_message = sprintf(__('You need to be logged in to view the content of this page. You can login %s. Don\'t have an account? %s', ATBDP_TEXTDOMAIN), "<a href='".wp_login_url()."'> ". __('Here', ATBDP_TEXTDOMAIN)."</a>","<a href='".ATBDP_Permalink::get_registration_page_link()."'> ". __('Sign Up', ATBDP_TEXTDOMAIN)."</a>"); ?>


                <section class="directory_wrapper single_area">
                    <?php  ATBDP()->helper->show_login_message($error_message); ?>
                </section>
                <?php

            }
            return ob_get_clean();
        }

        public function custom_user_login()
        {
            ob_start();
            echo '<div class="atbdp_login_form_shortcode">';
            wp_login_form();
            printf(__('<p>Don\'t have an account? %s</p>', ATBDP_TEXTDOMAIN), "<a href='".ATBDP_Permalink::get_registration_page_link()."'> ". __('Sign Up', ATBDP_TEXTDOMAIN)."</a>");
            echo '</div>';
            return ob_get_clean();
        }


        public function user_registration()
        {

            ob_start();
            // show registration form if the user is not
            if (!is_user_logged_in()){
                ATBDP()->user->registration_form();
            }else{
                $error_message = sprintf(__('Registration page is only for unregistered user. <a href="%s">Go Back To Home</a>', ATBDP_TEXTDOMAIN), esc_url(get_home_url()));
                ?>
                <?php ATBDP()->helper->show_login_message($error_message);  ?>

                <?php
            }

            return ob_get_clean();
        }
    }
endif;