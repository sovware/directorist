<?php
if ( !class_exists('ATBDP_Shortcode') ):

class ATBDP_Shortcode {

    public function __construct()
    {

        add_shortcode( 'search_listing', array( $this, 'search_listing' ) );

        add_shortcode( 'search_result', array( $this, 'search_result' ) );

        add_shortcode( 'all_listing', array( $this, 'all_listing' ) );

        add_shortcode( 'add_listing', array( $this, 'add_listing' ) );

        add_shortcode( 'custom_registration', array( $this, 'user_registration' ) );

        add_shortcode( 'user_login', array( $this, 'custom_user_login' ) );

        add_shortcode( 'user_dashboard', array( $this, 'user_dashboard' ) );
        $checkout = new ATBDP_Checkout;
        add_shortcode('directorist_checkout', array($checkout, 'display_checkout_content'));
        add_shortcode('directorist_payment_receipt', array($checkout, 'payment_receipt'));
        add_shortcode('transaction_failure', array($checkout, 'transaction_failure'));

        add_action('wp_ajax_atbdp_custom_fields_listings_front', array($this, 'ajax_callback_custom_fields'), 10, 2 );
        add_action('wp_ajax_atbdp_custom_fields_listings_front_selected', array($this, 'ajax_callback_custom_fields'), 10, 2 );

        add_filter( 'body_class', array($this, 'my_body_class'));

    }

    /*
     *  add own class in order to push custom style
     */
    public function my_body_class( $c ) {

        global $post;

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
            'post_type'      => 'atbdp_fields',
            'posts_per_page' => -1,
            'meta_key'   => 'category_pass',
            'meta_value' => $custom_field_ids

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
        }
    }


    public function search_result()
    {
        ob_start();
        if( !isset( $_GET['q'] ) ) {
            $no_result = get_directorist_option('no_result_found_text', __( 'Sorry, No Matched Results Found !', ATBDP_TEXTDOMAIN ));
            return apply_filters('atbdp_no_result_found_text', "<span class='no-result'>".esc_html($no_result)."</span>");
        }
        $paged = atbdp_get_paged_num();
        $srch_p_num = get_directorist_option('search_posts_num', 6);
        $paginate = get_directorist_option('paginate_search_results');
        $s_string = sanitize_text_field( $_GET['q'] );// get the searched query
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

    public function all_listing()
    {
        ob_start();
        //for pagination
        $paged = atbdp_get_paged_num();
        $paginate = get_directorist_option('paginate_all_listings');
        $args = array(
            'post_type'=> ATBDP_POST_TYPE,
            'post_status'=> 'publish',
            'posts_per_page' => (int) get_directorist_option('all_listing_page_items', 6),
            'paged' => $paged
        );
        if (!$paginate) $args['no_found_rows'] = true;
        $meta_queries = array();
        $featured_active = get_directorist_option('enable_featured_listing');
        if ($featured_active){
            $meta_queries[] = array(
                'key'     => '_featured',
                'type'    => 'NUMERIC',
                'compare' => 'EXISTS',
            );

            $args['orderby']  = array(
                'meta_value_num' => 'DESC',
                //'date'           => 'ASC',
            );
        }
        if (!is_empty_v($meta_queries)){
            $args['meta_query'] = $meta_queries;
        }
        $all_listings = new WP_Query($args);
        $all_listing_title = get_directorist_option('all_listing_title', __('All Items', ATBDP_TEXTDOMAIN));
        $data_for_template = compact('all_listings', 'all_listing_title', 'paged', 'paginate');

        ATBDP()->load_template('front-end/all-listing', array('data' => $data_for_template));
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
            $error_message = sprintf(__('You need to be logged in to view the content of this page. You can login %s.', ATBDP_TEXTDOMAIN), "<a href='".wp_login_url()."'> ". __('Here', ATBDP_TEXTDOMAIN)."</a>");


             ?>
            <section class="directory_wrapper single_area">
                <div class="<?php echo is_directoria_active() ? 'container': 'container-fluid'; ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <?php  ATBDP()->helper->show_login_message($error_message); ?>
                        </div>
                    </div>
            </section>
<?php

        }

        return ob_get_clean();

    }
    public function search_listing($atts, $content = null) {
        ob_start();
        ATBDP()->load_template('listing-home');
        ATBDP()->enquirer->search_listing_scripts_styles();
        return ob_get_clean();
    }

    public function add_listing($atts, $content = null, $sc_name) {
        ob_start();
        if (is_user_logged_in()) {
           ATBDP()->enquirer->add_listing_scripts_styles();

           ATBDP()->load_template('front-end/add-listing');
        }else{
            // user not logged in;
            $error_message = sprintf(__('You need to be logged in to view the content of this page. You can login %s.', ATBDP_TEXTDOMAIN), "<a href='".wp_login_url()."'> ". __('Here', ATBDP_TEXTDOMAIN)."</a>");
            ?>


            <section class="directory_wrapper single_area">
                <div class="<?php echo is_directoria_active() ? 'container': ' container-fluid'; ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <?php  ATBDP()->helper->show_login_message($error_message); ?>
                        </div>
                    </div>
                </div> <!--ends container-fluid-->
            </section>
<?php

        }
    }

    public function custom_user_login()
    {
        ob_start();
        wp_login_form();
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
            <div class="single_area">
                <div class="<?php echo is_directoria_active() ? 'container': ' container-fluid'; ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <?php ATBDP()->helper->show_login_message($error_message);  ?>
                        </div>
                    </div> <!--ends .row-->
                </div>
            </div>
        <?php
        }

        return ob_get_clean();
    }


}


endif;