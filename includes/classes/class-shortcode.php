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

            add_shortcode( 'directorist_category', array( $this, 'atbdp_category' ) );

            add_shortcode( 'directorist_all_locations', array( $this, 'all_locations' ) );

            add_shortcode( 'directorist_location', array( $this, 'atbdp_location' ) );

            add_shortcode( 'directorist_tag', array( $this, 'atbdp_tag' ) );
            $checkout = new ATBDP_Checkout;
            add_shortcode('directorist_checkout', array($checkout, 'display_checkout_content'));
            add_shortcode('directorist_payment_receipt', array($checkout, 'payment_receipt'));
            add_shortcode('directorist_transaction_failure', array($checkout, 'transaction_failure'));

            add_action('wp_ajax_atbdp_custom_fields_listings_front', array($this, 'ajax_callback_custom_fields'), 10, 2 );
            add_action('wp_ajax_atbdp_custom_fields_listings_front_selected', array($this, 'ajax_callback_custom_fields'), 10, 2 );

            add_filter( 'body_class', array($this, 'my_body_class'));
            if(!in_array( 'easy-registration-forms/erforms.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                // void action if someone use erforams or other plugin
                add_action( 'wp_login_failed', array($this, 'my_login_fail'));
            }
        }

        /**
         *
         * @since 4.7.4
         */
        public function my_login_fail($username){

            $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
            // if there's a valid referrer, and it's not the default log-in screen
            if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
                wp_redirect( $referrer . '?login=failed' );  // let's append some information (login=failed) to the URL for the theme to use
                exit;
            }
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
                $post_ID = !empty($_POST['post_id'])?(int)$_POST['post_id']:'' ;
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


        public function search_result($atts)
        {
            $listing_orderby           = get_directorist_option('search_order_listing_by');
            $listing_view              = get_directorist_option('search_view_as');
            $listing_order             = get_directorist_option('search_sort_by');
            $listing_grid_columns      = get_directorist_option('search_listing_columns',3);
            $display_listings_header   = get_directorist_option('search_header',1);
            $listings_header_title     = get_directorist_option('search_header_title',__('Total Found: ', ATBDP_TEXTDOMAIN));
            $filters_display           = get_directorist_option('search_result_display_filter','sliding');

            $atts = shortcode_atts( array(
                'view'              => !empty($listing_view) ? $listing_view : 'grid',
                '_featured'         => 1,
                'filterby'          => '',
                'orderby'           => !empty($listing_orderby) ? $listing_orderby : 'date',
                'order'             => !empty($listing_order) ? $listing_order : 'asc',
                'listings_per_page' => (int) get_directorist_option('search_posts_num', 6),
                'pagination'        => 1,
                'show_pagination'   => 'yes',
                'header'            => !empty($display_listings_header) ? 'yes' : '',
                'header_title'      => !empty($listings_header_title) ? $listings_header_title : '',
                'columns'           => !empty($listing_grid_columns) ? $listing_grid_columns : 3,
                'featured_only'     => '',
                'popular_only'      => '',
            ), $atts );


            $columns             = !empty($atts['columns']) ? $atts['columns'] : 3;
            $display_header      = !empty($atts['header']) ? $atts['header'] : '';
            $show_pagination       = !empty($atts['show_pagination']) ? $atts['show_pagination'] : '';

            $feature_only        = !empty($atts['featured_only']) ? $atts['featured_only'] : '';
            $popular_only        = !empty($atts['popular_only']) ? $atts['popular_only'] : '';
            //for pagination
            $paged               = atbdp_get_paged_num();
            $paginate            = get_directorist_option('paginate_search_results');

            if (!$paginate) $args['no_found_rows'] = true;

            $has_featured        = get_directorist_option('enable_featured_listing');
            if( $has_featured || is_fee_manager_active()) {
                $has_featured    = $atts['_featured'];
            }

            $current_order       = atbdp_get_listings_current_order( $atts['orderby'].'-'.$atts['order'] );
            $view                = atbdp_get_listings_current_view_name( $atts['view'] );
            $s_string            = !empty($_GET['q']) ? sanitize_text_field($_GET['q']) : '';// get the searched query
            $args = array(
                'post_type'      => ATBDP_POST_TYPE,
                'post_status'    => 'publish',
                'posts_per_page' => (int) $atts['listings_per_page'],
                'paged'          => $paged,
                's'              => $s_string,
            );

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

            // Define tax queries( only if applicable )
            $tax_queries = array();

            if( isset( $_GET['in_cat'] ) && (int) $_GET['in_cat'] > 0 ) {
                $tax_queries[] = array(
                    'taxonomy'         => ATBDP_CATEGORY,
                    'field'            => 'term_id',
                    'terms'            => (int)$_GET['in_cat'],
                    'include_children' => true,
                );

            }

            if( isset( $_GET['in_loc'] ) && (int) $_GET['in_loc'] > 0 ) {

                $tax_queries[] = array(
                    'taxonomy'         => ATBDP_LOCATION,
                    'field'            => 'term_id',
                    'terms'            =>  (int)$_GET['in_loc'],
                    'include_children' => true,
                );

            }

            if( isset( $_GET['in_tag'] ) && (int) $_GET['in_tag'] > 0 ) {
                $tax_queries[] = array(
                    'taxonomy'         => ATBDP_TAGS,
                    'field'            => 'term_id',
                    'terms'            => (int)$_GET['in_tag'],
                );

            }
            $count_tax_queries = count( $tax_queries );
            if( $count_tax_queries ) {
                $args['tax_query'] = ( $count_tax_queries > 1 ) ? array_merge( array( 'relation' => 'AND' ), $tax_queries ) : $tax_queries;
            }

            $meta_queries = array();

            if( isset( $_GET['cf'] )  ) {

                $cf = array_filter( $_GET['cf'] );

                foreach( $cf as $key => $values ) {

                    if( is_array( $values ) ) {

                        if( count( $values ) > 1 ) {

                            $sub_meta_queries = array();

                            foreach( $values as $value ) {
                                $sub_meta_queries[] = array(
                                    'key'		=> $key,
                                    'value'		=> sanitize_text_field( $value ),
                                    'compare'	=> 'LIKE'
                                );
                            }

                            $meta_queries[] = array_merge( array( 'relation' => 'OR' ), $sub_meta_queries );

                        } else {

                            $meta_queries[] = array(
                                'key'		=> $key,
                                'value'		=> sanitize_text_field( $values[0] ),
                                'compare'	=> 'LIKE'
                            );
                        }

                    } else {

                        $field_type = get_post_meta( $key, 'type', true );
                        $operator = ( 'text' == $field_type || 'textarea' == $field_type || 'url' == $field_type ) ? 'LIKE' : '=';
                        $meta_queries[] = array(
                            'key'		=> $key,
                            'value'		=> sanitize_text_field( $values ),
                            'compare'	=> $operator
                        );

                    }

                }

            } // end get['cf']

            if( isset( $_GET['price'] ) ) {

                $price = array_filter( $_GET['price'] );

                if( $n = count( $price ) ) {

                    if( 2 == $n ) {
                        $meta_queries[] = array(
                            'key'		=> '_price',
                            'value'		=> array_map( 'intval', $price ),
                            'type'      => 'NUMERIC',
                            'compare'	=> 'BETWEEN'
                        );
                    } else {
                        if( empty( $price[0] ) ) {
                            $meta_queries[] = array(
                                'key'		=> '_price',
                                'value'		=> (int) $price[1],
                                'type'      => 'NUMERIC',
                                'compare'	=> '<='
                            );
                        } else {
                            $meta_queries[] = array(
                                'key'		=> '_price',
                                'value'		=> (int) $price[0],
                                'type'      => 'NUMERIC',
                                'compare'	=> '>='
                            );
                        }
                    }

                }

            }// end price


            if (isset($_GET['price_range']) && 'none' != $_GET['price_range']) {
                $price_range = $_GET['price_range'];
                $meta_queries[] = array(
                    'key'     => '_price_range',
                    'value'   => $price_range,
                    'compare' => 'LIKE'
                );
            }


            //filter by open now business
            if (isset($_GET['open_now']) && ($_GET['open_now']  == 'open_now')) {
                $listings = get_atbdp_listings_ids();
                if ($listings->have_posts()) {
                    $closed = array();
                    while ($listings->have_posts()) {
                        $listings->the_post();
                        $id = get_the_ID();
                        $business_hours = get_post_meta($id, '_bdbh', true);
                        $always_open = get_post_meta($id, '_enable247hour', true);
                        $no_time = get_post_meta($id, '_disable_bz_hour_listing', true);
                        $business_hours = !empty($business_hours) ? atbdp_sanitize_array($business_hours) : array();
                        $_day = '';
                        foreach ($business_hours as $day => $time) {
                            if (empty($time)) continue; // do not print the day if time is not set
                            $day_ = date('D');
                            $timezone = get_directorist_option('timezone');
                            $timezone = !empty($timezone) ? $timezone : 'America/New_York';
                            $interval = DateTime::createFromFormat('H:i a', '11:59 am');
                            switch ($day_) {
                                case 'Sat' :
                                    $start_time = date('h:i a', strtotime(esc_attr($business_hours['saturday']['start'])));
                                    $close_time = date('h:i a', strtotime(esc_attr($business_hours['saturday']['close'])));
                                    $dt = new DateTime('now', new DateTimezone($timezone));
                                    $current_time = $dt->format('g:i a');
                                    $time_now = DateTime::createFromFormat('H:i a', $current_time);
                                    $time_start = DateTime::createFromFormat('H:i a', $start_time);
                                    $time_end = DateTime::createFromFormat('H:i a', $close_time);
                                    $remain_close = !empty($time['remain_close']) ? 1 : '';
                                    if (!empty($remain_close)){
                                        $_day = false;
                                    }elseif (!empty($always_open)){
                                        $_day = true;
                                    }else{
                                        /*
                                      * time start as pm (12.01 pm to 11.59 pm)
                                      * lets calculate time
                                      * is start time is smaller than current time and grater than close time
                                      */
                                        if ($interval < $time_now) {
                                            //pm
                                            if (($time_start < $time_now) && ($time_now > $time_end)) {
                                                $_day = true;

                                            }

                                        } else {
                                            //am
                                            //is the business start in a pm time
                                            if ((($time_start && $time_end) < $interval)) {
                                                if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                    $_day = true;

                                                }
                                            } else {
                                                if ($time_end < $interval) {
                                                    if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                        $_day = true;

                                                    }
                                                }
                                            }
                                        }
                                        if (($time_now > $time_start) && ($time_now < $time_end)) {
                                            $_day = true;
                                        }
                                    }


                                    break;
                                case 'Sun' :
                                    $start_time = date('h:i a', strtotime(esc_attr($business_hours['sunday']['start'])));
                                    $close_time = date('h:i a', strtotime(esc_attr($business_hours['sunday']['close'])));
                                    $dt = new DateTime('now', new DateTimezone($timezone));
                                    $current_time = $dt->format('g:i a');
                                    $time_now = DateTime::createFromFormat('H:i a', $current_time);
                                    $time_start = DateTime::createFromFormat('H:i a', $start_time);
                                    $time_end = DateTime::createFromFormat('H:i a', $close_time);
                                    $remain_close = !empty($time['remain_close']) ? 1 : '';
                                    if (!empty($remain_close)){
                                        $_day = false;
                                    }elseif (!empty($always_open)){
                                        $_day = true;
                                    }else{
                                        /*
                                      * time start as pm (12.01 pm to 11.59 pm)
                                      * lets calculate time
                                      * is start time is smaller than current time and grater than close time
                                      */
                                        if ($interval < $time_now) {
                                            //pm
                                            if (($time_start < $time_now) && ($time_now > $time_end)) {
                                                $_day = true;

                                            }

                                        } else {
                                            //am
                                            //is the business start in a pm time
                                            if ((($time_start && $time_end) < $interval)) {
                                                if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                    $_day = true;

                                                }
                                            } else {
                                                if ($time_end < $interval) {
                                                    if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                        $_day = true;

                                                    }
                                                }
                                            }
                                        }
                                        if (($time_now > $time_start) && ($time_now < $time_end)) {
                                            $_day = true;
                                        }
                                    }

                                    break;
                                case 'Mon' :
                                    $start_time = date('h:i a', strtotime(esc_attr($time['start'])));
                                    $close_time = date('h:i a', strtotime(esc_attr($time['close'])));
                                    $dt = new DateTime('now', new DateTimezone($timezone));
                                    $current_time = $dt->format('g:i a');
                                    $time_now = DateTime::createFromFormat('H:i a', $current_time);
                                    $time_start = DateTime::createFromFormat('H:i a', $start_time);
                                    $time_end = DateTime::createFromFormat('H:i a', $close_time);
                                    $remain_close = !empty($time['remain_close']) ? 1 : '';
                                    if (!empty($remain_close)){
                                        $_day = false;
                                    }elseif (!empty($always_open)){
                                        $_day = true;
                                    }else{
                                        /*
                                      * time start as pm (12.01 pm to 11.59 pm)
                                      * lets calculate time
                                      * is start time is smaller than current time and grater than close time
                                      */
                                        if ($interval < $time_now) {
                                            //pm
                                            if (($time_start < $time_now) && ($time_now > $time_end)) {
                                                $_day = true;

                                            }

                                        } else {
                                            //am
                                            //is the business start in a pm time
                                            if ((($time_start && $time_end) < $interval)) {
                                                if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                    $_day = true;

                                                }
                                            } else {
                                                if ($time_end < $interval) {
                                                    if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                        $_day = true;

                                                    }
                                                }
                                            }
                                        }
                                        if (($time_now > $time_start) && ($time_now < $time_end)) {
                                            $_day = true;
                                        }
                                    }


                                    break;
                                case 'Tue' :
                                    $start_time = date('h:i a', strtotime(esc_attr($business_hours['tuesday']['start'])));
                                    $close_time = date('h:i a', strtotime(esc_attr($business_hours['tuesday']['close'])));
                                    $dt = new DateTime('now', new DateTimezone($timezone));
                                    $current_time = $dt->format('g:i a');
                                    $time_now = DateTime::createFromFormat('H:i a', $current_time);
                                    $time_start = DateTime::createFromFormat('H:i a', $start_time);
                                    $time_end = DateTime::createFromFormat('H:i a', $close_time);
                                    $remain_close = !empty($time['remain_close']) ? 1 : '';
                                    if (!empty($remain_close)){
                                        $_day = false;
                                    }elseif (!empty($always_open)){
                                        $_day = true;
                                    }else{
                                        /*
                                      * time start as pm (12.01 pm to 11.59 pm)
                                      * lets calculate time
                                      * is start time is smaller than current time and grater than close time
                                      */
                                        if ($interval < $time_now) {
                                            //pm
                                            if (($time_start < $time_now) && ($time_now > $time_end)) {
                                                $_day = true;

                                            }

                                        } else {
                                            //am
                                            //is the business start in a pm time
                                            if ((($time_start && $time_end) < $interval)) {
                                                if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                    $_day = true;

                                                }
                                            } else {
                                                if ($time_end < $interval) {
                                                    if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                        $_day = true;

                                                    }
                                                }
                                            }
                                        }
                                        if (($time_now > $time_start) && ($time_now < $time_end)) {
                                            $_day = true;
                                        }
                                    }

                                    break;
                                case 'Wed' :
                                    $start_time = date('h:i a', strtotime(esc_attr($business_hours['wednesday']['start'])));
                                    $close_time = date('h:i a', strtotime(esc_attr($business_hours['wednesday']['close'])));
                                    $dt = new DateTime('now', new DateTimezone($timezone));
                                    $current_time = $dt->format('g:i a');
                                    $time_now = DateTime::createFromFormat('H:i a', $current_time);
                                    $time_start = DateTime::createFromFormat('H:i a', $start_time);
                                    $time_end = DateTime::createFromFormat('H:i a', $close_time);
                                    $remain_close = !empty($time['remain_close']) ? 1 : '';
                                    if (!empty($remain_close)){
                                        $_day = false;
                                    }elseif (!empty($always_open)){
                                        $_day = true;
                                    }else{
                                        /*
                                      * time start as pm (12.01 pm to 11.59 pm)
                                      * lets calculate time
                                      * is start time is smaller than current time and grater than close time
                                      */
                                        if ($interval < $time_now) {
                                            //pm
                                            if (($time_start < $time_now) && ($time_now > $time_end)) {
                                                $_day = true;

                                            }

                                        } else {
                                            //am
                                            //is the business start in a pm time
                                            if ((($time_start && $time_end) < $interval)) {
                                                if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                    $_day = true;

                                                }
                                            } else {
                                                if ($time_end < $interval) {
                                                    if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                        $_day = true;

                                                    }
                                                }
                                            }
                                        }
                                        if (($time_now > $time_start) && ($time_now < $time_end)) {
                                            $_day = true;
                                        }
                                    }

                                    break;
                                case 'Thu' :
                                    $start_time = date('h:i a', strtotime(esc_attr($business_hours['thursday']['start'])));
                                    $close_time = date('h:i a', strtotime(esc_attr($business_hours['thursday']['close'])));
                                    $dt = new DateTime('now', new DateTimezone($timezone));
                                    $current_time = $dt->format('g:i a');
                                    $time_now = DateTime::createFromFormat('H:i a', $current_time);
                                    $time_start = DateTime::createFromFormat('H:i a', $start_time);
                                    $time_end = DateTime::createFromFormat('H:i a', $close_time);
                                    $remain_close = !empty($time['remain_close']) ? 1 : '';
                                    if (!empty($remain_close)){
                                        $_day = false;
                                    }elseif (!empty($always_open)){
                                        $_day = true;
                                    }else{
                                        /*
                                      * time start as pm (12.01 pm to 11.59 pm)
                                      * lets calculate time
                                      * is start time is smaller than current time and grater than close time
                                      */
                                        if ($interval < $time_now) {
                                            //pm
                                            if (($time_start < $time_now) && ($time_now > $time_end)) {
                                                $_day = true;

                                            }

                                        } else {
                                            //am
                                            //is the business start in a pm time
                                            if ((($time_start && $time_end) < $interval)) {
                                                if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                    $_day = true;

                                                }
                                            } else {
                                                if ($time_end < $interval) {
                                                    if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                        $_day = true;

                                                    }
                                                }
                                            }
                                        }
                                        if (($time_now > $time_start) && ($time_now < $time_end)) {
                                            $_day = true;
                                        }
                                    }

                                    break;
                                case 'Fri':
                                    $start_time = date('h:i a', strtotime(esc_attr($business_hours['thursday']['start'])));
                                    $close_time = date('h:i a', strtotime(esc_attr($business_hours['thursday']['close'])));
                                    $dt = new DateTime('now', new DateTimezone($timezone));
                                    $current_time = $dt->format('g:i a');
                                    $time_now = DateTime::createFromFormat('H:i a', $current_time);
                                    $time_start = DateTime::createFromFormat('H:i a', $start_time);
                                    $time_end = DateTime::createFromFormat('H:i a', $close_time);
                                    $remain_close = !empty($time['remain_close']) ? 1 : '';
                                    if (!empty($remain_close)){
                                        $_day = false;
                                    }elseif (!empty($always_open)){
                                        $_day = true;
                                    }else{
                                        /*
                                      * time start as pm (12.01 pm to 11.59 pm)
                                      * lets calculate time
                                      * is start time is smaller than current time and grater than close time
                                      */
                                        if ($interval < $time_now) {
                                            //pm
                                            if (($time_start < $time_now) && ($time_now > $time_end)) {
                                                $_day = true;

                                            }

                                        } else {
                                            //am
                                            //is the business start in a pm time
                                            if ((($time_start && $time_end) < $interval)) {
                                                if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                    $_day = true;

                                                }
                                            } else {
                                                if ($time_end < $interval) {
                                                    if (($time_start > $time_now) && ($time_now < $time_end)) {
                                                        $_day = true;

                                                    }
                                                }
                                            }
                                        }
                                        if (($time_now > $time_start) && ($time_now < $time_end)) {
                                            $_day = true;
                                        }
                                    }

                                    break;
                            }

                        }
                        if (empty($_day)){
                            $closed[] = get_the_ID();
                        }
                    }

                    $closed_id = array(
                        'post__not_in' => !empty($closed) ? $closed : array()
                    );
                    $args = array_merge($args, $closed_id);

                }

            }

            // search by rating
            if(isset($_GET['search_by_rating'])) {
                $q_rating = $_GET['search_by_rating'];
                $listings = get_atbdp_listings_ids();
                $rated = array();
                if ($listings->have_posts()) {
                    while ($listings->have_posts()) {
                        $listings->the_post();
                        $listing_id = get_the_ID();
                        $average = ATBDP()->review->get_average($listing_id);
                        if ($q_rating === '5'){
                            if (($average>'4') && ($average<='5')){
                                $rated[] = get_the_ID();
                            }else{
                                $rated[] =array();
                            }
                        }elseif ($q_rating === '4'){
                            if (($average>'3') && ($average<='4')){
                                $rated[] = get_the_ID();
                            }else{
                                $rated[] =array();
                            }
                        }elseif ($q_rating === '3'){
                            if (($average>'2') && ($average<='3')){
                                $rated[] = get_the_ID();
                            }else{
                                $rated[] =array();
                            }
                        }elseif ($q_rating === '2'){
                            if (($average>'1') && ($average<='2')){
                                $rated[] = get_the_ID();
                            }else{
                                $rated[] =array();
                            }
                        }elseif ($q_rating === '1'){
                            if (($average>'0') && ($average<='1')){
                                $rated[] = get_the_ID();
                            }else{
                                $rated[] =array();
                            }
                        }elseif ('' === $q_rating){
                            if ($average === ''){
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

            if(isset($_GET['website'])) {
                $website = $_GET['website'];
                $meta_queries[] = array(
                    'key'     => '_website',
                    'value'   => $website,
                    'compare' => 'LIKE'
                );
            }

            if(isset($_GET['email'])) {
                $email = $_GET['email'];
                $meta_queries[] = array(
                    'key'  => '_email',
                    'value' => $email,
                    'compare' => 'LIKE'
                );
            }

            if(isset($_GET['phone'])) {
                $phone = $_GET['phone'];
                $meta_queries[] = array(
                    'key'   => '_phone',
                    'value' => $phone,
                    'compare' => 'LIKE'
                );
            }

            if(isset($_GET['address'])) {
                $address = $_GET['address'];
                $meta_queries[] = array(
                    'key'   => '_address',
                    'value' => $address,
                    'compare' => 'LIKE'
                );
            }

            if(isset($_GET['zip_code'])) {
                $zip_code = $_GET['zip_code'];
                $meta_queries[] = array(
                    'key'   => '_zip',
                    'value' => $zip_code,
                    'compare' => 'LIKE'
                );
            }



            $meta_queries['expired'] = array(
                'relation' => 'OR',
                array(
                    'key'	  => '_expiry_date',
                    'value'	  => current_time( 'mysql' ),
                    'compare' => '>', // eg. expire date 6 <= current date 7 will return the post
                    'type'    => 'DATETIME'
                ),
                array(
                    'key'	  => '_never_expire',
                    'value' => 1,
                )

            );
            $args['expired'] = $meta_queries;
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
                    $listings = get_atbdp_listings_ids();
                    $rated = array();
                    $listing_popular_by = get_directorist_option('listing_popular_by');
                    $average_review_for_popular = get_directorist_option('average_review_for_popular', 4);
                    $view_to_popular = get_directorist_option('views_for_popular');
                        if( $has_featured ) {
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
                                    'key'     => '_atbdp_post_views_count',
                                    'value'   => $view_to_popular,
                                    'type'    => 'NUMERIC',
                                    'compare' => '>=',
                                );
                            } else {
                                $meta_queries['views'] = array(
                                    'key'     => '_atbdp_post_views_count',
                                    'value'   => $view_to_popular,
                                    'type'    => 'NUMERIC',
                                    'compare' => '>=',
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

                            $args['orderby']  = array(
                                '_featured' => 'DESC',
                                '_atbdp_post_views_count'    => 'DESC',
                            );
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
                                    'key'     => '_atbdp_post_views_count',
                                    'value'   => $view_to_popular,
                                    'type'    => 'NUMERIC',
                                    'compare' => '>=',
                                );
                            } else {
                                $meta_queries['views'] = array(
                                    'key'     => '_atbdp_post_views_count',
                                    'value'   => (int)$view_to_popular,
                                    'type'    => 'NUMERIC',
                                    'compare' => '>=',
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
            $cat_id = !empty($_GET['in_cat']) ? $_GET['in_cat'] : '';
            $loc_id = !empty($_GET['in_loc']) ? $_GET['in_loc'] : '';
            $cat_name =  get_term_by('id',$cat_id,ATBDP_CATEGORY);
            $loc_name =  get_term_by('id',$loc_id,ATBDP_LOCATION);
            $for_cat = !empty($cat_name) ? sprintf(__('for %s',ATBDP_TEXTDOMAIN),$cat_name->name): '';
            $in_loc = !empty($loc_name) ? sprintf(__('in %s',ATBDP_TEXTDOMAIN),$loc_name->name) : '';
            $_s = (1 < count($all_listings->posts)) ? 's' : '';

            $header_title    = sprintf(__('%d result%s %s %s',ATBDP_TEXTDOMAIN),$all_listings->found_posts,$_s,$for_cat,$in_loc);
            $listing_filters_button       = get_directorist_option('search_result_filters_button_display', 1);
            $filters                      = get_directorist_option('search_result_filter_button_text',__('Filters',ATBDP_TEXTDOMAIN));
            $text_placeholder             = get_directorist_option('search_result_search_text_placeholder',__('What are you looking for?',ATBDP_TEXTDOMAIN));
            $category_placeholder         = get_directorist_option('search_result_category_placeholder',__('Select a category',ATBDP_TEXTDOMAIN));
            $location_placeholder         = get_directorist_option('search_result_location_placeholder',__('Select a location',ATBDP_TEXTDOMAIN));
            $data_for_template            = compact('all_listings', 'all_listing_title', 'paged', 'paginate');
            $search_more_filters_fields   = get_directorist_option('search_result_filters_fields',array('search_text','search_category','search_location','search_price','search_price_range','search_rating','search_tag','search_custom_fields'));
            $filters_button               = get_directorist_option('search_result_filters_button',array('reset_button','apply_button'));
            $reset_filters_text           = get_directorist_option('sresult_reset_text',__('Reset Filters',ATBDP_TEXTDOMAIN));
            $apply_filters_text           = get_directorist_option('sresult_apply_text',__('Apply Filters',ATBDP_TEXTDOMAIN));
            $data_for_template            = compact('all_listings', 'all_listing_title', 'paged', 'paginate');
            $view_as_items                = get_directorist_option('search_view_as_items',array('listings_grid','listings_list','listings_map'));
            $sort_by_items                = get_directorist_option('search_sort_by_items',array('a_z','z_a','latest','oldest','popular','price_low_high','price_high_low','random'));
            $listing_header_container_fluid  = is_directoria_active() ? 'container' : 'container-fluid';
            $header_container_fluid                 = apply_filters('atbdp_search_result_header_container_fluid',$listing_header_container_fluid);
            $listing_grid_container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
            $grid_container_fluid         = apply_filters('atbdp_search_result_grid_container_fluid',$listing_grid_container_fluid);
            ob_start();
            include ATBDP_TEMPLATES_DIR . "front-end/all-listings/all-$view-listings.php";
            return ob_get_clean();
        }

        public function all_listing( $atts )
        {
            wp_enqueue_script('adminmainassets');
            $listing_orderby           = get_directorist_option('order_listing_by');
            $listing_view              = get_directorist_option('default_listing_view');
            $filters_display           = get_directorist_option('listings_display_filter','sliding');
            $listing_filters_button    = get_directorist_option('listing_filters_button');
            $listing_order             = get_directorist_option('sort_listing_by');
            $listing_grid_columns      = get_directorist_option('all_listing_columns',3);
            $display_listings_header   = get_directorist_option('display_listings_header',1);
            $listings_header_title     = get_directorist_option('all_listing_title',__('All Items', ATBDP_TEXTDOMAIN));
            $atts = shortcode_atts( array(
                'view'                      => !empty($listing_view) ? $listing_view : 'grid',
                '_featured'                 => 1,
                'filterby'                  => '',
                'orderby'                   => !empty($listing_orderby) ? $listing_orderby : 'date',
                'order'                     => !empty($listing_order) ? $listing_order : 'asc',
                'listings_per_page'         => (int) get_directorist_option('all_listing_page_items', 6),
                'pagination'                => 1,
                'show_pagination'           => 'yes',
                'header'                    => !empty($display_listings_header) ? 'yes' : '',
                'header_title'              => !empty($listings_header_title) ? $listings_header_title : '',
                'category'                  => '',
                'location'                  => '',
                'tag'                       => '',
                'ids'                       => '',
                'columns'                   => !empty($listing_grid_columns) ? $listing_grid_columns : 3,
                'featured_only'             => '',
                'popular_only'              => '',
                'advanced_filter'           => '',
                'display_preview_image'     => 'yes',
                'action_before_after_loop'  => 'yes',
            ), $atts );

            $categories          = !empty($atts['category'] ) ? explode(',', $atts['category'] ) : '';
            $tags                = !empty($atts['tag'] ) ? explode(',', $atts['tag'] ) : '';
            $locations           = !empty($atts['location'] ) ? explode(',', $atts['location'] ) : '';
            $listing_id          = !empty($atts['ids'] ) ? explode(',', $atts['ids'] ) : '';
            $columns             = !empty($atts['columns']) ? $atts['columns'] : 3;
            $display_header      = !empty($atts['header']) ? $atts['header'] : '';
            $header_title        = !empty($atts['header_title']) ? $atts['header_title'] : '';
            $feature_only        = !empty($atts['featured_only']) ? $atts['featured_only'] : '';
            $popular_only        = !empty($atts['popular_only']) ? $atts['popular_only'] : '';
            $action_before_after_loop  = !empty($atts['action_before_after_loop']) ? $atts['action_before_after_loop'] : '';
            $show_pagination       = !empty($atts['show_pagination']) ? $atts['show_pagination'] : '';
            $display_image       = !empty($atts['display_preview_image'])  ? $atts['display_preview_image'] : '';
            //for pagination
            $paged               = atbdp_get_paged_num();
            $paginate            = get_directorist_option('paginate_all_listings');

            if (!$paginate) $args['no_found_rows'] = true;

            $has_featured        = get_directorist_option('enable_featured_listing');
            if( $has_featured || is_fee_manager_active()) {
                $has_featured    = $atts['_featured'];
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

            $meta_queries['expired'] = array(
                'relation' => 'OR',
                array(
                    'key'	  => '_expiry_date',
                    'value'	  => current_time( 'mysql' ),
                    'compare' => '>', // eg. expire date 6 <= current date 7 will return the post
                    'type'    => 'DATETIME'
                ),
                array(
                    'key'	  => '_never_expire',
                    'value' => 1,
                )
            );
            $args['expired'] = $meta_queries;

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
            if( 'yes' == $feature_only) {
                $meta_queries['_featured'] = array(
                    'key'     => '_featured',
                    'value'   => 1,
                    'compare' => '='
                );
            }

            $listings = get_atbdp_listings_ids();
            $rated = array();
            $listing_popular_by = get_directorist_option('listing_popular_by');
            $average_review_for_popular = get_directorist_option('average_review_for_popular', 4);
            $view_to_popular = get_directorist_option('views_for_popular');

            if(('yes' == $popular_only) || ('views-desc' === $current_order)) {
                if( $has_featured ) {
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
                            'key'     => '_atbdp_post_views_count',
                            'value'   => $view_to_popular,
                            'type'    => 'NUMERIC',
                            'compare' => '>=',
                        );
                    } else {
                        $meta_queries['views'] = array(
                            'key'     => '_atbdp_post_views_count',
                            'value'   => $view_to_popular,
                            'type'    => 'NUMERIC',
                            'compare' => '>=',
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

                    $args['orderby']  = array(
                        '_featured' => 'DESC',
                        '_atbdp_post_views_count'    => 'DESC',
                    );
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
                            'key'     => '_atbdp_post_views_count',
                            'value'   => $view_to_popular,
                            'type'    => 'NUMERIC',
                            'compare' => '>=',
                        );
                    } else {
                        $meta_queries['views'] = array(
                            'key'     => '_atbdp_post_views_count',
                            'value'   => (int)$view_to_popular,
                            'type'    => 'NUMERIC',
                            'compare' => '>=',
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

            $all_listings                    = new WP_Query($args);
            $paginate                        = get_directorist_option('paginate_all_listings');
            if ($paginate){
                $listing_count               =  '<span>'.$all_listings->found_posts.'</span>';
            }else{
                $listing_count               =  '<span>'.count($all_listings->posts).'</span>';
            }
            $display_header                  = !empty($display_header) ? $display_header : '';
            $header_title                    = !empty($header_title) ? $header_title.' ' .$listing_count : '';
            $listing_filters_button          = !empty($atts['advanced_filter'])?(('yes' === $atts['advanced_filter'])?1:(('no' === $atts['advanced_filter'])?0:$listing_filters_button)): $listing_filters_button;
            $filters                         = get_directorist_option('listings_filter_button_text',__('Filters',ATBDP_TEXTDOMAIN));
            $text_placeholder                = get_directorist_option('listings_search_text_placeholder',__('What are you looking for?',ATBDP_TEXTDOMAIN));
            $category_placeholder            = get_directorist_option('listings_category_placeholder',__('Select a category',ATBDP_TEXTDOMAIN));
            $location_placeholder            = get_directorist_option('listings_location_placeholder',__('Select a location',ATBDP_TEXTDOMAIN));
            $data_for_template               = compact('all_listings', 'all_listing_title', 'paged', 'paginate');
            $search_more_filters_fields      = get_directorist_option('listing_filters_fields',array('search_text','search_category','search_location','search_price','search_price_range','search_rating','search_tag','search_custom_fields'));
            $filters_button                  = get_directorist_option('listings_filters_button',array('reset_button','apply_button'));
            $reset_filters_text              = get_directorist_option('listings_reset_text',__('Reset Filters',ATBDP_TEXTDOMAIN));
            $apply_filters_text              = get_directorist_option('listings_apply_text',__('Apply Filters',ATBDP_TEXTDOMAIN));
            $view_as_items                   = get_directorist_option('listings_view_as_items',array('listings_grid','listings_list','listings_map'));
            $sort_by_items                   = get_directorist_option('listings_sort_by_items',array('a_z','z_a','latest','oldest','popular','price_low_high','price_high_low','random'));
            $listing_header_container_fluid  = is_directoria_active() ? 'container' : 'container-fluid';
            $header_container_fluid                 = apply_filters('atbdp_listings_header_container_fluid',$listing_header_container_fluid);
            $listing_grid_container_fluid  = is_directoria_active() ? 'container' : 'container-fluid';
            $grid_container_fluid                 = apply_filters('atbdp_listings_grid_container_fluid',$listing_grid_container_fluid);
            ob_start();
            include ATBDP_TEMPLATES_DIR . "front-end/all-listings/all-$view-listings.php";
            return ob_get_clean();
        }

        public function user_dashboard($atts)
        {
            ob_start();
            // show user dashboard if the user is logged in, else kick him out of this page or show a message
            if (is_user_logged_in()){
                $atts = shortcode_atts( array(
                    'show_title'    => '',
                ), $atts );
                $show_title = !empty($atts['show_title']) ? $atts['show_title'] : '';
                ATBDP()->enquirer->front_end_enqueue_scripts(true); // all front end scripts forcibly here
                include ATBDP_TEMPLATES_DIR . 'front-end/user-dashboard.php';
                //ATBDP()->user->user_dashboard($show_title);
            }else{
                // user not logged in;
                $error_message = sprintf(__('You need to be logged in to view the content of this page. You can login %s. Don\'t have an account? %s', ATBDP_TEXTDOMAIN), "<a href='".ATBDP_Permalink::get_login_page_link()."'> ". __('Here', ATBDP_TEXTDOMAIN)."</a>","<a href='".ATBDP_Permalink::get_registration_page_link()."'> ". __('Sign Up', ATBDP_TEXTDOMAIN)."</a>"); ?>
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
            $categories_settings['show_count'] = get_directorist_option('display_listing_count',1);
            $categories_settings['hide_empty'] = get_directorist_option('hide_empty_categories');
            $categories_settings['orderby'] = get_directorist_option('order_category_by','id');
            $categories_settings['order'] = get_directorist_option('sort_category_by','asc');

            $atts = shortcode_atts( array(
                'view'              => $display_categories_as,
                'orderby'           => $categories_settings['orderby'],
                'order'             => $categories_settings['order'],
                'cat_per_page'       => 100,
                'columns'           => ''
            ), $atts );
            $categories_settings['columns'] = !empty($atts['columns'])?$atts['columns']:get_directorist_option('categories_column_number',3);
            $args = array(
                'orderby'      => $atts['orderby'],
                'order'        => $atts['order'],
                'hide_empty'   => ! empty( $categories_settings['hide_empty'] ) ? 1 : 0,
                'parent'       => 0,
                'hierarchical' => ! empty( $categories_settings['hide_empty'] ) ? true : false
            );

            $terms = get_terms( ATBDP_CATEGORY, $args );
            $terms = array_slice($terms, 0, $atts['cat_per_page'] );
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

        public function atbdp_category ($atts) {

            $term_slug = get_query_var( 'atbdp_category' );

            $term = '';

            if( '' == $term_slug && ! empty( $atts['id'] ) ) {
                $term = get_term_by( 'id', (int) $atts['id'], ATBDP_CATEGORY );
                $term_slug = $term->slug;
            } elseif( '' != $term_slug ) {
                $term = get_term_by( 'slug', $term_slug, ATBDP_CATEGORY );
            }

            if( '' != $term_slug ) {
                $listing_orderby           = get_directorist_option('order_listing_by');
                $listing_view              = get_directorist_option('default_listing_view');
                $listing_order             = get_directorist_option('sort_listing_by');
                $listing_grid_columns      = get_directorist_option('all_listing_columns',3);
                $display_listings_header   = get_directorist_option('display_listings_header',1);
                $listings_header_title     = get_directorist_option('all_listing_title',__('Total Listing Found: ', ATBDP_TEXTDOMAIN));
                $filters_display           = get_directorist_option('listings_display_filter','sliding');

                $atts = shortcode_atts(array(
                    'view'              => !empty($listing_view) ? $listing_view : 'grid',
                    '_featured'         => 1,
                    'filterby'          => '',
                    'orderby'           => !empty($listing_orderby) ? $listing_orderby : 'date',
                    'order'             => !empty($listing_order) ? $listing_order : 'asc',
                    'listings_per_page' => (int) get_directorist_option('all_listing_page_items', 6),
                    'pagination'        => 1,
                    'show_pagination'   => 'yes',
                    'header'            => !empty($display_listings_header) ? 'yes' : '',
                    'header_title'      => !empty($listings_header_title) ? $listings_header_title : '',
                    'columns'           => !empty($listing_grid_columns) ? $listing_grid_columns : 3,
                ), $atts);

                $columns             = !empty($atts['columns']) ? $atts['columns'] : 3;
                $display_header      = !empty($atts['header']) ? $atts['header'] : '';
                $header_title        = !empty($atts['header_title']) ? $atts['header_title'] : '';
                $show_pagination     = !empty($atts['show_pagination']) ? $atts['show_pagination'] : '';
                //for pagination
                $paged               = atbdp_get_paged_num();
                $paginate            = get_directorist_option('paginate_all_listings');

                if (!$paginate) $args['no_found_rows'] = true;

                $has_featured        = get_directorist_option('enable_featured_listing');
                if( $has_featured || is_fee_manager_active()) {
                    $has_featured    = $atts['_featured'];
                }

                $current_order       = atbdp_get_listings_current_order( $atts['orderby'].'-'.$atts['order'] );
                $view                = atbdp_get_listings_current_view_name( $atts['view'] );

                $args = array(
                    'post_type'      => ATBDP_POST_TYPE,
                    'post_status'    => 'publish',
                    'posts_per_page' => (int) $atts['listings_per_page'],
                    'paged'          => $paged,
                );

                $tax_queries[] = array(
                    'taxonomy'         => ATBDP_CATEGORY,
                    'field'            => 'slug',
                    'terms'            => $term_slug,
                    'include_children' => true,
                );

                $args['tax_query'] = $tax_queries;

                $meta_queries = array();
                $meta_queries['expired'] = array(
                    'relation' => 'OR',
                    array(
                        'key'	  => '_expiry_date',
                        'value'	  => current_time( 'mysql' ),
                        'compare' => '>', // eg. expire date 6 <= current date 7 will return the post
                        'type'    => 'DATETIME'
                    ),
                    array(
                        'key'	  => '_never_expire',
                        'value' => 1,
                    )

                );
                $args['expired'] = $meta_queries;
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
                        $listings = get_atbdp_listings_ids();
                        $rated = array();
                        $listing_popular_by = get_directorist_option('listing_popular_by');
                        $average_review_for_popular = get_directorist_option('average_review_for_popular', 4);
                        $view_to_popular = get_directorist_option('views_for_popular');
                        if( $has_featured ) {
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
                                    'key'     => '_atbdp_post_views_count',
                                    'value'   => $view_to_popular,
                                    'type'    => 'NUMERIC',
                                    'compare' => '>=',
                                );
                            } else {
                                $meta_queries['views'] = array(
                                    'key'     => '_atbdp_post_views_count',
                                    'value'   => $view_to_popular,
                                    'type'    => 'NUMERIC',
                                    'compare' => '>=',
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

                            $args['orderby']  = array(
                                '_featured' => 'DESC',
                                '_atbdp_post_views_count'    => 'DESC',
                            );
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
                                    'key'     => '_atbdp_post_views_count',
                                    'value'   => $view_to_popular,
                                    'type'    => 'NUMERIC',
                                    'compare' => '>=',
                                );
                            } else {
                                $meta_queries['views'] = array(
                                    'key'     => '_atbdp_post_views_count',
                                    'value'   => (int)$view_to_popular,
                                    'type'    => 'NUMERIC',
                                    'compare' => '>=',
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
                if ($paginate){
                    $listing_count =  '<span>'.$all_listings->found_posts.'</span>';
                }else{
                    $listing_count =  '<span>'.count($all_listings->posts).'</span>';
                }
                $display_header               = !empty($display_header) ? $display_header : '';
                $header_title                 = !empty($header_title) ? $header_title .' '. $listing_count : '';
                $listing_filters_button       = get_directorist_option('listing_filters_button', 1);
                $filters                      = get_directorist_option('listings_filter_button_text',__('Filters',ATBDP_TEXTDOMAIN));
                $text_placeholder             = get_directorist_option('listings_search_text_placeholder',__('What are you looking for?',ATBDP_TEXTDOMAIN));
                $category_placeholder         = get_directorist_option('listings_category_placeholder',__('Select a category',ATBDP_TEXTDOMAIN));
                $location_placeholder         = get_directorist_option('listings_location_placeholder',__('Select a location',ATBDP_TEXTDOMAIN));
                $data_for_template            = compact('all_listings', 'all_listing_title', 'paged', 'paginate');
                $search_more_filters_fields   = get_directorist_option('listing_filters_fields',array('search_text','search_category','search_location','search_price','search_price_range','search_rating','search_tag','search_custom_fields'));
                $filters_button   = get_directorist_option('listings_filters_button',array('reset_button','apply_button'));
                $reset_filters_text           = get_directorist_option('listings_reset_text',__('Reset Filters',ATBDP_TEXTDOMAIN));
                $apply_filters_text           = get_directorist_option('listings_apply_text',__('Apply Filters',ATBDP_TEXTDOMAIN));
                $data_for_template            = compact('all_listings', 'all_listing_title', 'paged', 'paginate');
                $view_as_items                = get_directorist_option('listings_view_as_items',array('listings_grid','listings_list','listings_map'));
                $sort_by_items                = get_directorist_option('listings_sort_by_items',array('a_z','z_a','latest','oldest','popular','price_low_high','price_high_low','random'));
                $listing_header_container_fluid  = is_directoria_active() ? 'container' : 'container-fluid';
                $header_container_fluid                 = apply_filters('atbdp_single_cat_header_container_fluid',$listing_header_container_fluid);
                $listing_grid_container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
                $grid_container_fluid         = apply_filters('atbdp_single_cat_grid_container_fluid',$listing_grid_container_fluid);
                ob_start();
                include ATBDP_TEMPLATES_DIR . "front-end/all-listings/all-$view-listings.php";
                return ob_get_clean();

            }
            return '<span>'.__( 'No Results Found.', ATBDP_TEXTDOMAIN
                ).'</span>';
        }

        public function all_locations ($atts)
        {
            wp_enqueue_script('loc_cat_assets');
            ob_start();
            $display_locations_as              = get_directorist_option('display_locations_as','grid');
            $locations_settings                = array();
            $locations_settings['depth']       = get_directorist_option('locations_depth_number',1);
            $locations_settings['show_count']  = get_directorist_option('display_location_listing_count',1);
            $locations_settings['hide_empty']  = get_directorist_option('hide_empty_locations');
            $locations_settings['orderby']     = get_directorist_option('order_location_by','id');
            $locations_settings['order']       = get_directorist_option('sort_location_by','asc');

            $atts = shortcode_atts( array(
                'view'              => $display_locations_as,
                'orderby'           => $locations_settings['orderby'],
                'order'             => $locations_settings['order'],
                'loc_per_page'       => 100,
                'columns'           => ''
            ), $atts );
            $locations_settings['columns'] = !empty($atts['columns'])?$atts['columns']:get_directorist_option('locations_column_number',3);
            $args = array(
                'orderby'      => $atts['orderby'],
                'order'        => $atts['order'],
                'hide_empty'   => ! empty( $locations_settings['hide_empty'] ) ? 1 : 0,
                'parent'       => 0,
                'hierarchical' => ! empty( $locations_settings['hide_empty'] ) ? true : false,
            );

            $terms = get_terms( ATBDP_LOCATION, $args );
            $terms = array_slice($terms, 0, $atts['loc_per_page'] );

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

        public function atbdp_location ($atts) {

            $term_slug = get_query_var( 'atbdp_location' );

            $term = '';

            if( '' == $term_slug && ! empty( $atts['id'] ) ) {
                $term = get_term_by( 'id', (int) $atts['id'], ATBDP_LOCATION );
                $term_slug = $term->slug;
            } elseif( '' != $term_slug ) {
                $term = get_term_by( 'slug', $term_slug, ATBDP_LOCATION );
            }

            if( '' != $term_slug ) {
                $listing_orderby           = get_directorist_option('order_listing_by');
                $listing_view              = get_directorist_option('default_listing_view');
                $listing_order             = get_directorist_option('sort_listing_by');
                $listing_grid_columns      = get_directorist_option('all_listing_columns',3);
                $display_listings_header   = get_directorist_option('display_listings_header',1);
                $listings_header_title     = get_directorist_option('all_listing_title',__('All Items', ATBDP_TEXTDOMAIN));
                $filters_display           = get_directorist_option('listings_display_filter','sliding');

                $atts = shortcode_atts(array(
                    'view'              => !empty($listing_view) ? $listing_view : 'grid',
                    '_featured'         => 1,
                    'filterby'          => '',
                    'orderby'           => !empty($listing_orderby) ? $listing_orderby : 'date',
                    'order'             => !empty($listing_order) ? $listing_order : 'asc',
                    'listings_per_page' => (int) get_directorist_option('all_listing_page_items', 6),
                    'pagination'        => 1,
                    'show_pagination'   => 'yes',
                    'header'            => !empty($display_listings_header) ? 'yes' : '',
                    'header_title'      => !empty($listings_header_title) ? $listings_header_title : '',
                    'columns'           => !empty($listing_grid_columns) ? $listing_grid_columns : 3,
                ), $atts);

                $columns             = !empty($atts['columns']) ? $atts['columns'] : 3;
                $display_header      = !empty($atts['header']) ? $atts['header'] : '';
                $header_title        = !empty($atts['header_title']) ? $atts['header_title'] : '';
                $header_sub_title    = !empty($atts['header_sub_title']) ? $atts['header_sub_title'] : '';
                $show_pagination    = !empty($atts['show_pagination']) ? $atts['show_pagination'] : '';
                //for pagination
                $paged               = atbdp_get_paged_num();
                $paginate            = get_directorist_option('paginate_all_listings');

                if (!$paginate) $args['no_found_rows'] = true;

                $has_featured        = get_directorist_option('enable_featured_listing');
                if( $has_featured || is_fee_manager_active()) {
                    $has_featured    = $atts['_featured'];
                }

                $current_order       = atbdp_get_listings_current_order( $atts['orderby'].'-'.$atts['order'] );
                $view                = atbdp_get_listings_current_view_name( $atts['view'] );

                $args = array(
                    'post_type'      => ATBDP_POST_TYPE,
                    'post_status'    => 'publish',
                    'posts_per_page' => (int) $atts['listings_per_page'],
                    'paged'          => $paged,
                );

                $tax_queries[] = array(
                    'taxonomy'         => ATBDP_LOCATION,
                    'field'            => 'slug',
                    'terms'            => $term_slug,
                    'include_children' => true,
                );

                $args['tax_query'] = $tax_queries;

                $meta_queries = array();
                $meta_queries['expired'] = array(
                    'relation' => 'OR',
                    array(
                        'key'	  => '_expiry_date',
                        'value'	  => current_time( 'mysql' ),
                        'compare' => '>', // eg. expire date 6 <= current date 7 will return the post
                        'type'    => 'DATETIME'
                    ),
                    array(
                        'key'	  => '_never_expire',
                        'value' => 1,
                    )

                );
                $args['expired'] = $meta_queries;
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
                        $listings = get_atbdp_listings_ids();
                        $rated = array();
                        $listing_popular_by = get_directorist_option('listing_popular_by');
                        $average_review_for_popular = get_directorist_option('average_review_for_popular', 4);
                        $view_to_popular = get_directorist_option('views_for_popular');
                        if( $has_featured ) {
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
                                    'key'     => '_atbdp_post_views_count',
                                    'value'   => $view_to_popular,
                                    'type'    => 'NUMERIC',
                                    'compare' => '>=',
                                );
                            } else {
                                $meta_queries['views'] = array(
                                    'key'     => '_atbdp_post_views_count',
                                    'value'   => $view_to_popular,
                                    'type'    => 'NUMERIC',
                                    'compare' => '>=',
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

                            $args['orderby']  = array(
                                '_featured' => 'DESC',
                                '_atbdp_post_views_count'    => 'DESC',
                            );
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
                                    'key'     => '_atbdp_post_views_count',
                                    'value'   => $view_to_popular,
                                    'type'    => 'NUMERIC',
                                    'compare' => '>=',
                                );
                            } else {
                                $meta_queries['views'] = array(
                                    'key'     => '_atbdp_post_views_count',
                                    'value'   => (int)$view_to_popular,
                                    'type'    => 'NUMERIC',
                                    'compare' => '>=',
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
                if ($paginate){
                    $listing_count =  '<span>'.$all_listings->found_posts.'</span>';
                }else{
                    $listing_count =  '<span>'.count($all_listings->posts).'</span>';
                }
                $display_header               = !empty($display_header) ? $display_header : '';
                $header_title                 = !empty($header_title) ? $header_title .' ' . $listing_count : '';
                $listing_filters_button       = get_directorist_option('listing_filters_button', 1);
                $filters                      = get_directorist_option('listings_filter_button_text',__('Filters',ATBDP_TEXTDOMAIN));
                $text_placeholder             = get_directorist_option('listings_search_text_placeholder',__('What are you looking for?',ATBDP_TEXTDOMAIN));
                $category_placeholder         = get_directorist_option('listings_category_placeholder',__('Select a category',ATBDP_TEXTDOMAIN));
                $location_placeholder         = get_directorist_option('listings_location_placeholder',__('Select a location',ATBDP_TEXTDOMAIN));
                $data_for_template            = compact('all_listings', 'all_listing_title', 'paged', 'paginate');
                $search_more_filters_fields   = get_directorist_option('listing_filters_fields',array('search_text','search_category','search_location','search_price','search_price_range','search_rating','search_tag','search_custom_fields'));
                $filters_button   = get_directorist_option('listings_filters_button',array('reset_button','apply_button'));
                $reset_filters_text           = get_directorist_option('listings_reset_text',__('Reset Filters',ATBDP_TEXTDOMAIN));
                $apply_filters_text           = get_directorist_option('listings_apply_text',__('Apply Filters',ATBDP_TEXTDOMAIN));
                $data_for_template = compact('all_listings', 'all_listing_title', 'paged', 'paginate');
                $view_as_items               = get_directorist_option('listings_view_as_items',array('listings_grid','listings_list','listings_map'));
                $sort_by_items               = get_directorist_option('listings_sort_by_items',array('a_z','z_a','latest','oldest','popular','price_low_high','price_high_low','random'));
                $listing_header_container_fluid  = is_directoria_active() ? 'container' : 'container-fluid';
                $header_container_fluid                 = apply_filters('atbdp_single_loc_header_container_fluid',$listing_header_container_fluid);
                $listing_grid_container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
                $grid_container_fluid         = apply_filters('atbdp_single_loc_grid_container_fluid',$listing_grid_container_fluid);
                ob_start();
                include ATBDP_TEMPLATES_DIR . "front-end/all-listings/all-$view-listings.php";
                return ob_get_clean();

            }
            return '<span>'.__( 'No Results Found.', ATBDP_TEXTDOMAIN
                ).'</span>';
        }

        public function atbdp_tag ($atts) {

            $term_slug = get_query_var( 'atbdp_tag' );

            $term = '';

            if( '' == $term_slug && ! empty( $atts['id'] ) ) {
                $term = get_term_by( 'id', (int) $atts['id'], ATBDP_TAGS );
                $term_slug = $term->slug;
            } elseif( '' != $term_slug ) {
                $term = get_term_by( 'slug', $term_slug, ATBDP_TAGS );
            }

            if( '' != $term_slug ) {
                $listing_orderby           = get_directorist_option('order_listing_by');
                $listing_view              = get_directorist_option('default_listing_view');
                $listing_order             = get_directorist_option('sort_listing_by');
                $listing_grid_columns      = get_directorist_option('all_listing_columns',3);
                $display_listings_header   = get_directorist_option('display_listings_header',1);
                $listings_header_title     = get_directorist_option('all_listing_title',__('All Items', ATBDP_TEXTDOMAIN));
                $listings_header_sub_title = get_directorist_option('listings_header_sub_title',__('Total Listing Found: ', ATBDP_TEXTDOMAIN));
                $filters_display           = get_directorist_option('listings_display_filter','sliding');

                $atts = shortcode_atts(array(
                    'view'              => !empty($listing_view) ? $listing_view : 'grid',
                    '_featured'         => 1,
                    'filterby'          => '',
                    'orderby'           => !empty($listing_orderby) ? $listing_orderby : 'date',
                    'order'             => !empty($listing_order) ? $listing_order : 'asc',
                    'listings_per_page' => (int) get_directorist_option('all_listing_page_items', 6),
                    'pagination'        => 1,
                    'show_pagination'   => 'yes',
                    'header'            => !empty($display_listings_header) ? 'yes' : '',
                    'header_title'      => !empty($listings_header_title) ? $listings_header_title : '',
                    'header_sub_title'  => !empty($listings_header_sub_title) ? $listings_header_sub_title : '',
                    'columns'           => !empty($listing_grid_columns) ? $listing_grid_columns : 3,
                ), $atts);

                $columns             = !empty($atts['columns']) ? $atts['columns'] : 3;
                $display_header      = !empty($atts['header']) ? $atts['header'] : '';
                $header_title        = !empty($atts['header_title']) ? $atts['header_title'] : '';
                $header_sub_title    = !empty($atts['header_sub_title']) ? $atts['header_sub_title'] : '';
                $show_pagination    = !empty($atts['show_pagination']) ? $atts['show_pagination'] : '';
                //for pagination
                $paged               = atbdp_get_paged_num();
                $paginate            = get_directorist_option('paginate_all_listings');

                if (!$paginate) $args['no_found_rows'] = true;

                $has_featured        = get_directorist_option('enable_featured_listing');
                if( $has_featured || is_fee_manager_active()) {
                    $has_featured    = $atts['_featured'];
                }

                $current_order       = atbdp_get_listings_current_order( $atts['orderby'].'-'.$atts['order'] );
                $view                = atbdp_get_listings_current_view_name( $atts['view'] );

                $args = array(
                    'post_type'      => ATBDP_POST_TYPE,
                    'post_status'    => 'publish',
                    'posts_per_page' => (int) $atts['listings_per_page'],
                    'paged'          => $paged,
                );

                $tax_queries[] = array(
                    'taxonomy'         => ATBDP_TAGS,
                    'field'            => 'slug',
                    'terms'            => $term_slug,
                    'include_children' => true,
                );

                $args['tax_query'] = $tax_queries;

                $meta_queries = array();
                $meta_queries['expired'] = array(
                    'relation' => 'OR',
                    array(
                        'key'	  => '_expiry_date',
                        'value'	  => current_time( 'mysql' ),
                        'compare' => '>', // eg. expire date 6 <= current date 7 will return the post
                        'type'    => 'DATETIME'
                    ),
                    array(
                        'key'	  => '_never_expire',
                        'value' => 1,
                    )

                );
                $args['expired'] = $meta_queries;
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
                        $listings = get_atbdp_listings_ids();
                        $rated = array();
                        $listing_popular_by = get_directorist_option('listing_popular_by');
                        $average_review_for_popular = get_directorist_option('average_review_for_popular', 4);
                        $view_to_popular = get_directorist_option('views_for_popular');
                        if( $has_featured ) {
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
                                    'key'     => '_atbdp_post_views_count',
                                    'value'   => $view_to_popular,
                                    'type'    => 'NUMERIC',
                                    'compare' => '>=',
                                );
                            } else {
                                $meta_queries['views'] = array(
                                    'key'     => '_atbdp_post_views_count',
                                    'value'   => $view_to_popular,
                                    'type'    => 'NUMERIC',
                                    'compare' => '>=',
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

                            $args['orderby']  = array(
                                '_featured' => 'DESC',
                                '_atbdp_post_views_count'    => 'DESC',
                            );
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
                                    'key'     => '_atbdp_post_views_count',
                                    'value'   => $view_to_popular,
                                    'type'    => 'NUMERIC',
                                    'compare' => '>=',
                                );
                            } else {
                                $meta_queries['views'] = array(
                                    'key'     => '_atbdp_post_views_count',
                                    'value'   => (int)$view_to_popular,
                                    'type'    => 'NUMERIC',
                                    'compare' => '>=',
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
                if ($paginate){
                    $listing_count =  '<span>'.$all_listings->found_posts.'</span>';
                }else{
                    $listing_count =  '<span>'.count($all_listings->posts).'</span>';
                }
                $display_header               = !empty($display_header) ? $display_header : '';
                $header_title                 = !empty($header_sub_title) ? $header_sub_title .' ' . $listing_count : '';
                $listing_filters_button       = get_directorist_option('listing_filters_button', 1);
                $filters                      = get_directorist_option('listings_filter_button_text',__('Filters',ATBDP_TEXTDOMAIN));
                $text_placeholder             = get_directorist_option('listings_search_text_placeholder',__('What are you looking for?',ATBDP_TEXTDOMAIN));
                $category_placeholder         = get_directorist_option('listings_category_placeholder',__('Select a category',ATBDP_TEXTDOMAIN));
                $location_placeholder         = get_directorist_option('listings_location_placeholder',__('Select a location',ATBDP_TEXTDOMAIN));
                $data_for_template            = compact('all_listings', 'all_listing_title', 'paged', 'paginate');
                $search_more_filters_fields   = get_directorist_option('listing_filters_fields',array('search_text','search_category','search_location','search_price','search_price_range','search_rating','search_tag','search_custom_fields'));
                $filters_button               = get_directorist_option('listings_filters_button',array('reset_button','apply_button'));
                $reset_filters_text           = get_directorist_option('listings_reset_text',__('Reset Filters',ATBDP_TEXTDOMAIN));
                $apply_filters_text           = get_directorist_option('listings_apply_text',__('Apply Filters',ATBDP_TEXTDOMAIN));
                $data_for_template            = compact('all_listings', 'all_listing_title', 'paged', 'paginate');
                $view_as_items                = get_directorist_option('listings_view_as_items',array('listings_grid','listings_list','listings_map'));
                $sort_by_items                = get_directorist_option('listings_sort_by_items',array('a_z','z_a','latest','oldest','popular','price_low_high','price_high_low','random'));
                $listing_header_container_fluid  = is_directoria_active() ? 'container' : 'container-fluid';
                $header_container_fluid                 = apply_filters('atbdp_single_tag_header_container_fluid',$listing_header_container_fluid);
                $listing_grid_container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
                $grid_container_fluid         = apply_filters('atbdp_single_tag_grid_container_fluid',$listing_grid_container_fluid);
                ob_start();
                include ATBDP_TEMPLATES_DIR . "front-end/all-listings/all-$view-listings.php";
                return ob_get_clean();

            }
            return '<span>'.__( 'No Results Found.', ATBDP_TEXTDOMAIN
                ).'</span>';
        }

        public function search_listing($atts, $content = null) {
            $search_title                = get_directorist_option('search_title', __("Search here", ATBDP_TEXTDOMAIN));
            $search_subtitle              = get_directorist_option('search_subtitle', __("Find the best match of your interest
", ATBDP_TEXTDOMAIN));
            $search_fields               = get_directorist_option('search_tsc_fields',array('search_text','search_category','search_location'));
            $search_more_filter          = get_directorist_option('search_more_filter',1);
            $search_button               = get_directorist_option('search_button',1);
            $search_more_filters_fields  = get_directorist_option('search_more_filters_fields',array('search_price','search_price_range','search_rating','search_tag','search_custom_fields'));
            $search_filters              = get_directorist_option('search_filters',array('search_reset_filters','search_apply_filters'));
            $search_more_filters         = get_directorist_option('search_more_filters',  __('More Filters', ATBDP_TEXTDOMAIN));
            $search_listing_text         = get_directorist_option('search_listing_text',  __('Search Listing', ATBDP_TEXTDOMAIN));
            $search_reset_text           = get_directorist_option('search_reset_text',  __('Reset Filters', ATBDP_TEXTDOMAIN));
            $search_apply_text           = get_directorist_option('search_apply_filter',  __('Apply Filters', ATBDP_TEXTDOMAIN));
            $atts = shortcode_atts(array(
                'show_title_subtitle'      => 'yes',
                'search_bar_title'         => !empty($search_title) ? $search_title : '',
                'search_bar_sub_title'     => !empty($search_subtitle) ? $search_subtitle : '',
                'text_field'               => in_array( 'search_text', $search_fields ) ? 'yes' : '',
                'category_field'           => in_array( 'search_category', $search_fields ) ? 'yes' : '',
                'location_field'           => in_array( 'search_location', $search_fields ) ? 'yes' : '',
                'search_button'            => !empty($search_button) ? 'yes' : '',
                'search_button_text'       => !empty($search_listing_text) ? $search_listing_text : 'Search Listing',
                'more_filters_button'      => !empty($search_more_filter) ? 'yes' : '',
                'more_filters_text'        => !empty($search_more_filters) ? $search_more_filters : 'More Filters',
                'price_min_max_field'      => in_array( 'search_price', $search_more_filters_fields ) ? 'yes' : '',
                'price_range_field'        => in_array( 'search_price_range', $search_more_filters_fields ) ? 'yes' : '',
                'rating_field'             => in_array( 'search_rating', $search_more_filters_fields ) ? 'yes' : '',
                'tag_field'                => in_array( 'search_tag', $search_more_filters_fields ) ? 'yes' : '',
                'open_now_field'           => in_array( 'search_open_now', $search_more_filters_fields ) ? 'yes' : '',
                'custom_fields'            => in_array( 'search_custom_fields', $search_more_filters_fields ) ? 'yes' : '',
                'website_field'            => in_array( 'search_website', $search_more_filters_fields ) ? 'yes' : '',
                'email_field'              => in_array( 'search_email', $search_more_filters_fields ) ? 'yes' : '',
                'phone_field'              => in_array( 'search_phone', $search_more_filters_fields ) ? 'yes' : '',
                'address_field'            => in_array( 'search_address', $search_more_filters_fields ) ? 'yes' : '',
                'zip_code_field'           => in_array( 'search_zip_code', $search_more_filters_fields ) ? 'yes' : '',
                'reset_filters_button'     => in_array( 'search_reset_filters', $search_filters ) ? 'yes' : '',
                'apply_filters_button'     => in_array( 'search_apply_filters', $search_filters ) ? 'yes' : '',
                'reset_filters_text'       => !empty($search_reset_text) ? $search_reset_text : 'Reset Filters',
                'apply_filters_text'       => !empty($search_apply_text) ? $search_apply_text : 'Apply Filters',
            ), $atts);

            $search_bar_title       = (!empty($atts['search_bar_title']) ) ? $atts['search_bar_title'] : '';
            $search_bar_sub_title   = (!empty($atts['search_bar_sub_title']) ) ? $atts['search_bar_sub_title'] : '';
            $text_field             = (!empty($atts['text_field']) && 'yes' == $atts['text_field']) ? $atts['text_field'] : '';
            $category_field         = (!empty($atts['category_field']) && 'yes' == $atts['category_field']) ? $atts['category_field'] : '';
            $location_field         = (!empty($atts['location_field']) && 'yes' == $atts['location_field']) ? $atts['location_field'] : '';
            $search_button          = (!empty($atts['search_button']) && 'yes' == $atts['search_button']) ? $atts['search_button'] : '';
            $search_button_text     = (!empty($atts['search_button_text']) ) ? $atts['search_button_text'] : '';
            $more_filters_button    = (!empty($atts['more_filters_button']) && 'yes' == $atts['more_filters_button']) ? $atts['more_filters_button'] : '';
            $more_filters_text      = (!empty($atts['more_filters_text']) ) ? $atts['more_filters_text'] : '';
            $price_min_max_field    = (!empty($atts['price_min_max_field']) && 'yes' == $atts['price_min_max_field']) ? $atts['price_min_max_field'] : '';
            $price_range_field      = (!empty($atts['price_range_field']) && 'yes' == $atts['price_range_field']) ? $atts['price_range_field'] : '';
            $rating_field           = (!empty($atts['rating_field']) && 'yes' == $atts['rating_field']) ? $atts['rating_field'] : '';
            $tag_field              = (!empty($atts['tag_field']) && 'yes' == $atts['tag_field']) ? $atts['tag_field'] : '';
            $open_now_field         = (!empty($atts['open_now_field']) && 'yes' == $atts['open_now_field']) ? $atts['open_now_field'] : '';
            $custom_fields          = (!empty($atts['custom_fields']) && 'yes' == $atts['custom_fields']) ? $atts['custom_fields'] : '';
            $website_field          = (!empty($atts['website_field']) && 'yes' == $atts['website_field']) ? $atts['website_field'] : '';
            $email_field            = (!empty($atts['email_field']) && 'yes' == $atts['email_field']) ? $atts['email_field'] : '';
            $phone_field            = (!empty($atts['phone_field']) && 'yes' == $atts['phone_field']) ? $atts['phone_field'] : '';
            $address_field          = (!empty($atts['address_field']) && 'yes' == $atts['address_field']) ? $atts['address_field'] : '';
            $zip_code_field         = (!empty($atts['zip_code_field']) && 'yes' == $atts['zip_code_field']) ? $atts['zip_code_field'] : '';
            $reset_filters_button   = (!empty($atts['reset_filters_button']) && 'yes' == $atts['reset_filters_button']) ? $atts['reset_filters_button'] : '';
            $apply_filters_button   = (!empty($atts['apply_filters_button']) && 'yes' == $atts['apply_filters_button']) ? $atts['apply_filters_button'] : '';
            $reset_filters_text     = (!empty($atts['reset_filters_text']) ) ? $atts['reset_filters_text'] : '';
            $apply_filters_text     = (!empty($atts['apply_filters_text']) ) ? $atts['apply_filters_text'] : '';
            $show_title_subtitle    = ('yes' === $atts['show_title_subtitle'])?$atts['show_title_subtitle']:'';
            $filters_display        = get_directorist_option('home_display_filter','overlapping');
            ob_start();
            include ATBDP_TEMPLATES_DIR . 'listing-home.php';
            //ATBDP()->load_template('listing-home');
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

            $args['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key'	  => '_expiry_date',
                    'value'	  => current_time( 'mysql' ),
                    'compare' => '>', // eg. expire date 6 <= current date 7 will return the post
                    'type'    => 'DATETIME'
                ),
                array(
                    'key'	  => '_never_expire',
                    'value' => 1,
                )
            );


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
                        if (class_exists('ATBDP_Pricing_Plans')){
                            ATBDP_Pricing_Plans()->load_template('fee-plans');
                        }else{
                            DWPP_Pricing_Plans()->load_template('fee-plans');
                        }

                    }
                }else{
                    ATBDP()->enquirer->add_listing_scripts_styles();
                    ATBDP()->load_template('front-end/add-listing');
                }

            }else{
                // user not logged in;
                $error_message = sprintf(__('You need to be logged in to view the content of this page. You can login %s. Don\'t have an account? %s', ATBDP_TEXTDOMAIN), "<a href='".ATBDP_Permalink::get_login_page_link()."'> ". __('Here', ATBDP_TEXTDOMAIN)."</a>","<a href='".ATBDP_Permalink::get_registration_page_link()."'> ". __('Sign Up', ATBDP_TEXTDOMAIN)."</a>"); ?>


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


            if (!is_user_logged_in()){
                wp_enqueue_script('adminmainassets');
                echo '<div class="atbdp_login_form_shortcode">';
                if (isset($_GET['login']) && $_GET['login'] == 'failed'){

                    printf('<p class="alert-danger"><span class="fa fa-exclamation"></span>%s</p>',__(' Invalid username or password!', ATBDP_TEXTDOMAIN));
                    $location = ATBDP_Permalink::get_login_page_link();
                    ?>
                    <script>
                        if(typeof window.history.pushState == 'function') {
                            window.history.pushState({}, "Hide", '<?php echo $location;?>');
                        }
                    </script>
                    <?php
                }
                wp_login_form();
                echo "<div class='d-flex justify-content-between'>";
                printf(__('<p>Don\'t have an account? %s</p>', ATBDP_TEXTDOMAIN), "<a href='".ATBDP_Permalink::get_registration_page_link()."'> ". __('Sign Up', ATBDP_TEXTDOMAIN)."</a>");
                printf(__('<p>%s</p>', ATBDP_TEXTDOMAIN), "<a href='' class='atbdp_recovery_pass'> ". __('Recover Password', ATBDP_TEXTDOMAIN)."</a>");
                echo "</div>";
                global $wpdb;

                $error = '';
                $success = '';

                // check if we're in reset form
                if( isset( $_POST['action'] ) && 'reset' == $_POST['action'] )
                {
                    $email = trim($_POST['user_login']);

                    if( empty( $email ) ) {
                        $error = __('Enter a username or e-mail address..', ATBDP_TEXTDOMAIN);
                    } else if( ! is_email( $email )) {
                        $error = __('Invalid username or e-mail address.', ATBDP_TEXTDOMAIN);
                    } else if( ! email_exists( $email ) ) {
                        $error = __('There is no user registered with that email address.', ATBDP_TEXTDOMAIN);
                    } else {

                        $random_password = wp_generate_password( 12, false );
                        $user = get_user_by( 'email', $email );

                        $update_user = wp_update_user( array (
                                'ID' => $user->ID,
                                'user_pass' => $random_password
                            )
                        );

                        // if  update user return true then lets send user an email containing the new password
                        if( $update_user ) {
                            $to = $email;
                            $subject = 'Your new password';
                            $sender = get_option('name');

                            $message = 'Your new password is: '.$random_password;

                            $headers[] = 'MIME-Version: 1.0' . "\r\n";
                            $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            $headers[] = "X-Mailer: PHP \r\n";
                            $headers[] = 'From: '.$sender.' < '.$email.'>' . "\r\n";

                            $mail = wp_mail( $to, $subject, $message, $headers );
                            if( $mail ){
                                $success =  __('Check your email address for you new password.', ATBDP_TEXTDOMAIN);
                            }else{
                                $error = __('Oops something went wrong sending email.', ATBDP_TEXTDOMAIN);
                            }

                        } else {
                            $error = __('Oops something went wrong updaing your account.', ATBDP_TEXTDOMAIN);
                        }

                    }

                    if( ! empty( $error ) )
                        echo '<div class="message"><p class="error"><strong>'. __("ERROR:", ATBDP_TEXTDOMAIN) .'</strong> '. $error .'</p></div>';

                    if( ! empty( $success ) )
                        echo '<div class="error_login"><p class="success">'. $success .'</p></div>';
                }
                ?>

                <div id="recover-pass-modal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <form method="post">
                                    <fieldset>
                                        <p><?php _e('Please enter your email address. You will receive a link to create a new password via email.', ATBDP_TEXTDOMAIN)?></p>
                                        <label for="reset_user_login"><?php _e('Username or E-mail:', ATBDP_TEXTDOMAIN)?></label>
                                            <?php $user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : ''; ?>
                                            <input type="text" name="user_login" id="reset_user_login" value="<?php echo $user_login; ?>" placeholder="eg. mail@example.com" />
                                        <p>
                                            <input type="hidden" name="action" value="reset" />
                                            <input type="submit" value="<?php _e('Get New Password', ATBDP_TEXTDOMAIN)?>" class="btn btn-primary" id="submit" />
                                        </p>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                echo '</div>';
            }else{
                $error_message = sprintf(__('Login page is not for logged-in user. <a href="%s">Go Back To Home</a>', ATBDP_TEXTDOMAIN), esc_url(get_home_url()));
                ATBDP()->helper->show_login_message($error_message);
            }
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