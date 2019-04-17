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
            add_action( 'wp_login_failed', array($this, 'my_login_fail'));

        }


        /**
         *
         * @since 4.7.4
         */
        public function my_login_fail($username){

                /*$id = get_directorist_option('user_login');
                wp_redirect(home_url( "?page_id=$id" ) . "&login_error" );
                exit;*/
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
            $listings_header_title     = get_directorist_option('search_header_title',__('Search Result', ATBDP_TEXTDOMAIN));
            $listings_header_sub_title = get_directorist_option('search_header_sub_title',__('Total Listing Found: ', ATBDP_TEXTDOMAIN));
            $atts = shortcode_atts( array(
                'view'              => !empty($listing_view) ? $listing_view : 'grid',
                '_featured'         => 1,
                'filterby'          => '',
                'orderby'           => !empty($listing_orderby) ? $listing_orderby : 'date',
                'order'             => !empty($listing_order) ? $listing_order : 'asc',
                'listings_per_page' => (int) get_directorist_option('search_posts_num', 6),
                'pagination'        => 1,
                'header'            => !empty($display_listings_header) ? 'yes' : '',
                'header_title'      => !empty($listings_header_title) ? $listings_header_title : '',
                'header_sub_title'  => !empty($listings_header_sub_title) ? $listings_header_sub_title : '',
                'columns'           => !empty($listing_grid_columns) ? $listing_grid_columns : 3,
                'featured_only'     => '',
                'popular_only'      => '',
            ), $atts );


            $columns             = !empty($atts['columns']) ? $atts['columns'] : 3;
            $display_header      = !empty($atts['header']) ? $atts['header'] : '';

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
                    'terms'            => (int) $_GET['in_cat'],
                    'include_children' => true,
                );

            }

            if( isset( $_GET['in_loc'] ) && (int) $_GET['in_loc'] > 0 ) {

                $tax_queries[] = array(
                    'taxonomy'         => ATBDP_LOCATION,
                    'field'            => 'term_id',
                    'terms'            => (int) $_GET['in_loc'],
                    'include_children' => true,
                );

            }

            if( isset( $_GET['in_tag'] ) && (int) $_GET['in_tag'] > 0 ) {
                $tax_queries[] = array(
                    'taxonomy'         => ATBDP_TAGS,
                    'field'            => 'term_id',
                    'terms'            => $_GET['in_tag'],
                );

            }

            $count_tax_queries = count( $tax_queries );
            if( $count_tax_queries ) {
                $args['tax_query'] = ( $count_tax_queries > 1 ) ? array_merge( array( 'relation' => 'AND' ), $tax_queries ) : $tax_queries;
            }

            $meta_queries = array();

            if( isset( $_GET['cf'] ) ) {

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
            $cat_id = !empty($_GET['in_cat']) ? $_GET['in_cat'] : '';
            $loc_id = !empty($_GET['in_loc']) ? $_GET['in_loc'] : '';
            $cat_name =  get_term_by('id',$cat_id,ATBDP_CATEGORY);
            $loc_name =  get_term_by('id',$loc_id,ATBDP_LOCATION);
            $for_cat = !empty($cat_name) ? sprintf(__('for %s',ATBDP_TEXTDOMAIN),$cat_name->name): '';
            $in_loc = !empty($loc_name) ? sprintf(__('in %s',ATBDP_TEXTDOMAIN),$loc_name->name) : '';
            $_s = (1 < count($all_listings->posts)) ? 's' : '';

            $header_title    = sprintf(__('%d result%s %s %s',ATBDP_TEXTDOMAIN),$all_listings->found_posts,$_s,$for_cat,$in_loc);
            $header_sub_title         = "More Filter";
            $data_for_template = compact('all_listings', 'all_listing_title', 'paged', 'paginate');

            ob_start();
            include ATBDP_TEMPLATES_DIR . "front-end/all-listings/all-$view-listings.php";
            return ob_get_clean();
        }


        public function all_listing( $atts )
        {
            wp_enqueue_script('adminmainassets');
            $listing_orderby           = get_directorist_option('order_listing_by');
            $listing_view              = get_directorist_option('default_listing_view');
            $listing_order             = get_directorist_option('sort_listing_by');
            $listing_grid_columns      = get_directorist_option('all_listing_columns',3);
            $display_listings_header   = get_directorist_option('display_listings_header',1);
            $listings_header_title     = get_directorist_option('all_listing_title',__('All Items', ATBDP_TEXTDOMAIN));
            $listings_header_sub_title = get_directorist_option('listings_header_sub_title',__('Total Listing Found: ', ATBDP_TEXTDOMAIN));
            $atts = shortcode_atts( array(
                'view'              => !empty($listing_view) ? $listing_view : 'grid',
                '_featured'         => 1,
                'filterby'          => '',
                'orderby'           => !empty($listing_orderby) ? $listing_orderby : 'date',
                'order'             => !empty($listing_order) ? $listing_order : 'asc',
                'listings_per_page' => (int) get_directorist_option('all_listing_page_items', 6),
                'pagination'        => 1,
                'header'            => !empty($display_listings_header) ? 'yes' : '',
                'header_title'      => !empty($listings_header_title) ? $listings_header_title : '',
                'header_sub_title'  => !empty($listings_header_sub_title) ? $listings_header_sub_title : '',
                'category'          => '',
                'location'          => '',
                'tag'               => '',
                'ids'               => '',
                'columns'           => !empty($listing_grid_columns) ? $listing_grid_columns : 3,
                'featured_only'     => '',
                'popular_only'      => '',
            ), $atts );

            $categories          = !empty($atts['category'] ) ? explode(',', $atts['category'] ) : '';
            $tags                = !empty($atts['tag'] ) ? explode(',', $atts['tag'] ) : '';
            $locations           = !empty($atts['location'] ) ? explode(',', $atts['location'] ) : '';
            $listing_id          = !empty($atts['ids'] ) ? explode(',', $atts['ids'] ) : '';
            $columns             = !empty($atts['columns']) ? $atts['columns'] : 3;
            $display_header      = !empty($atts['header']) ? $atts['header'] : '';
            $header_title        = !empty($atts['header_title']) ? $atts['header_title'] : '';
            $header_sub_title    = !empty($atts['header_sub_title']) ? $atts['header_sub_title'] : '';
            $feature_only        = !empty($atts['featured_only']) ? $atts['featured_only'] : '';
            $popular_only        = !empty($atts['popular_only']) ? $atts['popular_only'] : '';
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
            if('yes' == $popular_only) {
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

            $all_listings               = new WP_Query($args);
            if ($paginate){
                $listing_count =  $all_listings->found_posts;
            }else{
                $listing_count =  count($all_listings->posts);
            }
            $display_header               = !empty($display_header) ? $display_header : '';
            $header_title                 = !empty($header_sub_title) ? $header_sub_title . $listing_count : '';
            $filters                      = get_directorist_option('listings_filter_button_text',__('Filters',ATBDP_TEXTDOMAIN));
            $text_placeholder             = get_directorist_option('listings_search_text_placeholder',__('What are you looking for?',ATBDP_TEXTDOMAIN));
            $category_placeholder         = get_directorist_option('listings_category_placeholder',__('Select a category',ATBDP_TEXTDOMAIN));
            $location_placeholder         = get_directorist_option('listings_location_placeholder',__('Select a location',ATBDP_TEXTDOMAIN));
            $data_for_template            = compact('all_listings', 'all_listing_title', 'paged', 'paginate');
            $search_more_filters_fields   = get_directorist_option('listing_filters_fields',array('search_text','search_category','search_location','search_price','search_price_range','search_rating','search_tag','search_custom_fields'));
            
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
                $listings_header_title     = get_directorist_option('all_listing_title',__('All Items', ATBDP_TEXTDOMAIN));
                $listings_header_sub_title = get_directorist_option('listings_header_sub_title',__('Total Listing Found: ', ATBDP_TEXTDOMAIN));

                $atts = shortcode_atts(array(
                    'view'              => !empty($listing_view) ? $listing_view : 'grid',
                    '_featured'         => 1,
                    'filterby'          => '',
                    'orderby'           => !empty($listing_orderby) ? $listing_orderby : 'date',
                    'order'             => !empty($listing_order) ? $listing_order : 'asc',
                    'listings_per_page' => (int) get_directorist_option('all_listing_page_items', 6),
                    'pagination'        => 1,
                    'header'            => !empty($display_listings_header) ? 'yes' : '',
                    'header_title'      => !empty($listings_header_title) ? $listings_header_title : '',
                    'header_sub_title'  => !empty($listings_header_sub_title) ? $listings_header_sub_title : '',
                    'columns'           => !empty($listing_grid_columns) ? $listing_grid_columns : 3,
                ), $atts);

                $columns             = !empty($atts['columns']) ? $atts['columns'] : 3;
                $display_header      = !empty($atts['header']) ? $atts['header'] : '';
                $header_title        = !empty($atts['header_title']) ? $atts['header_title'] : '';
                $header_sub_title    = !empty($atts['header_sub_title']) ? $atts['header_sub_title'] : '';
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

                $data_for_template = compact('all_listings', 'all_listing_title', 'paged', 'paginate');

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
                $listings_header_sub_title = get_directorist_option('listings_header_sub_title',__('Total Listing Found: ', ATBDP_TEXTDOMAIN));

                $atts = shortcode_atts(array(
                    'view'              => !empty($listing_view) ? $listing_view : 'grid',
                    '_featured'         => 1,
                    'filterby'          => '',
                    'orderby'           => !empty($listing_orderby) ? $listing_orderby : 'date',
                    'order'             => !empty($listing_order) ? $listing_order : 'asc',
                    'listings_per_page' => (int) get_directorist_option('all_listing_page_items', 6),
                    'pagination'        => 1,
                    'header'            => !empty($display_listings_header) ? 'yes' : '',
                    'header_title'      => !empty($listings_header_title) ? $listings_header_title : '',
                    'header_sub_title'  => !empty($listings_header_sub_title) ? $listings_header_sub_title : '',
                    'columns'           => !empty($listing_grid_columns) ? $listing_grid_columns : 3,
                ), $atts);

                $columns             = !empty($atts['columns']) ? $atts['columns'] : 3;
                $display_header      = !empty($atts['header']) ? $atts['header'] : '';
                $header_title        = !empty($atts['header_title']) ? $atts['header_title'] : '';
                $header_sub_title    = !empty($atts['header_sub_title']) ? $atts['header_sub_title'] : '';
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

                $data_for_template = compact('all_listings', 'all_listing_title', 'paged', 'paginate');

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

                $atts = shortcode_atts(array(
                    'view'              => !empty($listing_view) ? $listing_view : 'grid',
                    '_featured'         => 1,
                    'filterby'          => '',
                    'orderby'           => !empty($listing_orderby) ? $listing_orderby : 'date',
                    'order'             => !empty($listing_order) ? $listing_order : 'asc',
                    'listings_per_page' => (int) get_directorist_option('all_listing_page_items', 6),
                    'pagination'        => 1,
                    'header'            => !empty($display_listings_header) ? 'yes' : '',
                    'header_title'      => !empty($listings_header_title) ? $listings_header_title : '',
                    'header_sub_title'  => !empty($listings_header_sub_title) ? $listings_header_sub_title : '',
                    'columns'           => !empty($listing_grid_columns) ? $listing_grid_columns : 3,
                ), $atts);

                $columns             = !empty($atts['columns']) ? $atts['columns'] : 3;
                $display_header      = !empty($atts['header']) ? $atts['header'] : '';
                $header_title        = !empty($atts['header_title']) ? $atts['header_title'] : '';
                $header_sub_title    = !empty($atts['header_sub_title']) ? $atts['header_sub_title'] : '';
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

                $data_for_template = compact('all_listings', 'all_listing_title', 'paged', 'paginate');

                ob_start();
                include ATBDP_TEMPLATES_DIR . "front-end/all-listings/all-$view-listings.php";
                return ob_get_clean();

            }
            return '<span>'.__( 'No Results Found.', ATBDP_TEXTDOMAIN
                ).'</span>';
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
                echo '<div class="atbdp_login_form_shortcode">';
                if (isset($_GET['login']) && $_GET['login'] == 'failed'){
                    printf('<p class="alert-danger"><span class="fa fa-exclamation"></span>%s</p>',__(' Invalid username or password!', ATBDP_TEXTDOMAIN));
                }
                wp_login_form();
                printf(__('<p>Don\'t have an account? %s</p>', ATBDP_TEXTDOMAIN), "<a href='".ATBDP_Permalink::get_registration_page_link()."'> ". __('Sign Up', ATBDP_TEXTDOMAIN)."</a>");
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