<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * ATBDP_Review_Custom_Post Class
 *
 * @since    5.6.4
 * @access   public
 */
class ATBDP_Review_Custom_Post
{
    public function __construct()
    {
        $approve_immediately = get_directorist_option('approve_immediately',1);
        if(empty($approve_immediately)) {
            //create custom post for review
            add_action('init', array($this, 'review_custom_post_type'));
        }
        //create meta boxes
        add_action('add_meta_boxes', array($this, 'create_meta_box_for_review'));
        //save meta box
        add_action('save_post', array($this, 'save_review_data'));
        //manage columns for review custom post
        add_filter('manage_atbdp_listing_review_posts_columns', array($this, 'atbdp_add_new_plan_columns'));
        add_action('manage_atbdp_listing_review_posts_custom_column', array($this, 'atbdp_custom_field_column_content'), 10, 2);
    }

    /**
     * Register a custom post type "review_custom_post_type".
     *
     * @since    5.6.4
     * @access   public
     */
    public function review_custom_post_type () {
        $labels = array(
            'name' => _x('Reviews', 'Post Type General Name', 'directorist'),
            'singular_name' => _x('Reviews', 'Post Type Singular Name', 'directorist'),
            'menu_name' => __('Reviews', 'directorist'),
            'name_admin_bar' => __('Review', 'directorist'),
            'all_items' => __('Reviews', 'directorist'),
            'add_new_item' => __('Add Review', 'directorist'),
            'add_new' => __('Add Review', 'directorist'),
            'new_item' => __('Review', 'directorist'),
            'edit_item' => __('Edit Review', 'directorist'),
            'update_item' => __('Update Review', 'directorist'),
            'view_item' => __('View Review', 'directorist'),
            'search_items' => __('Search Review', 'directorist'),
            'not_found' => __('No Review found', 'directorist'),
            'not_found_in_trash' => __('No Review found in Trash', 'directorist'),
        );

        $args = array(
            'labels' => $labels,
            'description' => __('This order post type will keep track of admin fee plans', 'directorist'),
            'supports' => array('title'),
            'taxonomies' => array(''),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => current_user_can('manage_atbdp_options') ? true : false, // show the menu only to the admin
            'show_in_menu' => current_user_can('manage_atbdp_options') ? 'edit.php?post_type=' . ATBDP_POST_TYPE : false,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'capabilities' => array(
                'create_posts' => false,
            ),
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'capability_type' => 'at_biz_dir',
            'map_meta_cap' => true,
        );

        register_post_type('atbdp_listing_review', $args);
    }
    /**
     * Register meta boxes for review.
     *
     * @since    5.6.4
     * @access   public
     */
    public function create_meta_box_for_review() {
        add_meta_box('atbdp_review_details', __('Review Details', 'directorist'), array($this, 'review_details_meta_box'), 'atbdp_listing_review', 'normal', 'high');
    }
    /**
     * review details meta box.
     *
     * @param $post
     * @since    5.6.4
     * @access   public
     */
    public function review_details_meta_box($post) {
        // Add a nonce field so we can check for it later
        wp_nonce_field('atbdp_review_save_details', 'atbdp_review_details_nonce');

        $post_meta = get_post_meta( $post->ID ) ? get_post_meta( $post->ID ) : '' ;
        ?>
        <table class="atbdp-input widefat" id="atbdp-field-details">
            <tbody>
            <tr class="field-type">
                <td class="label">
                    <label for="review_listing" class="widefat"><?php _e( 'Select Listing for Review', 'directorist-claim-listing' ); ?></label>
                </td>
                <td class="field_lable">
                    <select id="review_listing" name="review_listing" class="atbdp-radio-list radio horizontal">
                        <?php
                        $current_val = isset($post_meta['_review_listing']) ? esc_attr($post_meta['_review_listing'][0]) : '';

                        echo '<option>' . __("-Select a Listing-", 'directorist') . '</option>';
                        $args = array(
                            'post_type'      => ATBDP_POST_TYPE,
                            'post_status'    => 'any',
                            'posts_per_page' => -1,
                        );
                        $listings = new WP_Query($args);
                        if ($listings->have_posts()){
                            while ( $listings->have_posts() ) {
                                $listings->the_post();
                                printf('<option value="%s" %s>%s</option>', get_the_ID(), selected(get_the_ID(), $current_val), get_the_title());
                            }
                        }
                        wp_reset_postdata();
                        ?>
                    </select>
                </td>
            </tr>

            <tr class="field-type">
                <td class="label">
                    <label for="listing_reviewer" class="widefat"><?php _e( 'Review by', 'directorist' ); ?></label>
                </td>
                <td class="field_lable">
                    <?php
                    $listing_reviewer = isset( $post_meta['_listing_reviewer'] ) ? esc_attr($post_meta['_listing_reviewer'][0]) : '';
                    ?>
                    <input type="text" id="listing_reviewer" name="listing_reviewer" class="atbdp-radio-list radio horizontal" value="<?php echo $listing_reviewer;?>">
                </td>
            </tr>

            <tr class="field-type">
                <td class="label">
                    <label for="review_status" class="widefat"><?php _e( 'Status', 'directorist' ); ?></label>
                </td>
                <td class="field_lable">
                    <select id="review_status" name="review_status" class="atbdp-radio-list radio horizontal">
                        <?php
                        $current_status = isset( $post_meta['_review_status'] ) ? esc_attr($post_meta['_review_status'][0]) : '';
                        $status = array(
                            'pending' => __('Pending', 'directorist'),
                            'approved' => __('Approved', 'directorist'),
                            'declined' => __('Decline', 'directorist'),
                        );
                        foreach($status as $key => $value){
                            printf('<option value="%s" %s>%s</option>', $key, selected($key, $current_status), $value);
                        }?>
                    </select>
                </td>
            </tr>

            <tr class="field-instructions">
                <td class="label">
                    <label for="reviewer_details"><?php _e( 'Details', 'directorist-claim-listing' ); ?></label>
                </td>
                <td>
                    <textarea id="reviewer_details" class="textarea" name="reviewer_details" rows="6" cols="64"><?php if( isset( $post_meta['_reviewer_details'] ) ) echo esc_textarea( $post_meta['_reviewer_details'][0] ); ?></textarea>
                </td>
            </tr>

            <tr class="field-type">
                <td class="label">
                    <label for="reviewer_rating" class="widefat"><?php _e( 'Rating', 'directorist' ); ?></label>
                </td>
                <td class="field_lable">
                    <select id="review_status" name="reviewer_rating" class="atbdp-radio-list radio horizontal">
                        <?php
                        $reviewer_rating = isset( $post_meta['_reviewer_rating'] ) ? esc_attr($post_meta['_reviewer_rating'][0]) : '';
                        $rating = array(
                            '1' => __('1', 'directorist'),
                            '2' => __('2', 'directorist'),
                            '3' => __('3', 'directorist'),
                            '4' => __('4', 'directorist'),
                            '5' => __('5', 'directorist'),
                        );
                        foreach($rating as $key => $value){
                            printf('<option value="%s" %s>%s</option>', $key, selected($key, $reviewer_rating), $value);
                        }?>
                    </select>
                </td>
            </tr>
            <?php
            $post_id = isset( $post_meta['_post_id'] ) ? $post_meta['_post_id'][0] : '';
            $email = isset( $post_meta['_email'] ) ? $post_meta['_email'][0] : '';
            $by_guest = isset( $post_meta['_by_guest'] ) ? $post_meta['_by_guest'][0] : '';
            $by_user_id = isset( $post_meta['_by_user_id'] ) ? $post_meta['_by_user_id'][0] : '';
            ?>
            <input type="hidden" id="post_id" name="post_id" class="atbdp-radio-list radio horizontal" value="<?php echo $post_id;?>">
            <input type="hidden" id="email" name="email" class="atbdp-radio-list radio horizontal" value="<?php echo $email;?>">
            <input type="hidden" id="by_guest" name="by_guest" class="atbdp-radio-list radio horizontal" value="<?php echo $by_guest;?>">
            <input type="hidden" id="by_user_id" name="by_user_id" class="atbdp-radio-list radio horizontal" value="<?php echo $by_user_id;?>">
            </tbody>
        </table>
    <?php
    }

    /**
     * save meta box.
     *
     * @param int $post_id
     * @since    5.6.4
     * @access   public
     * @return int
     */
    public function save_review_data($post_id) {
        if (!isset($_POST['post_type'])) {
            return $post_id;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Check the logged in user has permission to edit this post
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
        /*
         * save all the metadata to option table
         */
        // Check if "dcl_claim_details_nonce" nonce is set
        if (isset($_POST['atbdp_review_details_nonce'])) {
            // Verify that the nonce is valid
            if (wp_verify_nonce($_POST['atbdp_review_details_nonce'], 'atbdp_review_save_details')) {
                // OK to save meta data
                $review_listing =  !empty($_POST['review_listing'])?$_POST['review_listing']:'';
                update_post_meta($post_id, '_review_listing', $review_listing);

                $listing_reviewer =  !empty($_POST['listing_reviewer'])?$_POST['listing_reviewer']:'';
                update_post_meta($post_id, '_listing_reviewer', $listing_reviewer);


                $review_status = isset($_POST['review_status'])?sanitize_text_field($_POST['review_status']):'';
                update_post_meta($post_id, '_review_status', $review_status);

                $reviewer_details = isset($_POST['reviewer_details'])?sanitize_text_field($_POST['reviewer_details']):'';
                update_post_meta($post_id, '_reviewer_details', $reviewer_details);

                $reviewer_rating = isset($_POST['reviewer_rating'])?$_POST['reviewer_rating']:'';
                update_post_meta($post_id, '_reviewer_rating', $reviewer_rating);

                $id = isset($_POST['post_id']) ? $_POST['post_id']:'';
                update_post_meta($post_id, '_post_id', $id);

                $email = isset($_POST['email']) ? $_POST['email']:'';
                update_post_meta($post_id, '_email', $email);

                $by_guest = isset($_POST['by_guest']) ? $_POST['by_guest']:'';
                update_post_meta($post_id, '_by_guest', $by_guest);

                $by_user_id = isset($_POST['by_user_id']) ? $_POST['by_user_id']:'';
                update_post_meta($post_id, '_by_user_id', $by_user_id);

                if('approved' == $review_status) {
                    $data = array(
                        'post_id'          => $review_listing,
                        'name'             => $listing_reviewer,
                        'email'            => $email,
                        'content'          => $reviewer_details,
                        'rating'           => $reviewer_rating,
                        'by_guest'         => $by_guest,
                        'by_user_id'       => $by_user_id,
                    );
                    ATBDP()->review->db->add($data);
                } elseif( 'declined' == $review_status) {
                    $data = array(
                        'post_id'          => $review_listing,
                        'name'             => $listing_reviewer,
                        'email'            => $email,
                        'content'          => $reviewer_details,
                        'rating'           => $reviewer_rating,
                        'by_guest'         => $by_guest,
                        'by_user_id'       => $by_user_id,
                    );
                    ATBDP()->review->db->delete_reviews_by('post_id', $data['post_id']);
                }

            }
        }
    }
    /**
     * Retrieve the table columns.
     *
     * @param array $columns
     *
     * @return   array    $columns    Array of all the list table columns.
     * @since    1.0.0
     * @access   public
     */
    public function atbdp_add_new_plan_columns ($columns) {
        $columns = array(
            'cb' => '<input type="checkbox" />', // Render a checkbox instead of text
            'review_for' => __('Title', 'directorist'),
            'reviewer' => __('Author', 'directorist'),
            'status' => __('Status', 'directorist'),
            'details' => __('Details', 'directorist'),
            'rating' => __('Rating', 'directorist'),
            'date' => __('Date', 'directorist')
        );

        return $columns;
    }

    /**
     * Retrieve the table columns.
     *
     * @param array $column all the column
     * @param array $post_id post id
     * @since    1.0.0
     * @access   public
     */
    public function atbdp_custom_field_column_content ($column, $post_id) {
        echo '</select>';
        switch ($column) {
            case 'review_for' :
                $post_meta = get_post_meta($post_id);
                $review_listing = isset($post_meta['_review_listing']) ? esc_attr($post_meta['_review_listing'][0]) : '';
                $reviews = ATBDP()->review->db->get_reviews_by('post_id', $post_meta['_review_listing'][0]);
                $review_id = !empty($reviews) ? ' #' .$reviews[0]->id : '';

                echo __('Review for ') . get_the_title($review_listing) . $review_id ;
                break;

            case 'reviewer' :
                $post_meta = get_post_meta($post_id);
                $listing_reviewer = isset($post_meta['_listing_reviewer']) ? esc_attr($post_meta['_listing_reviewer'][0]) : '';
                //$user = get_user_by('id', $current_author);
                echo $listing_reviewer;
                break;
            case 'status' :
                $post_meta = get_post_meta($post_id);
                $review_status = isset($post_meta['_review_status']) ? esc_attr($post_meta['_review_status'][0]) : '';
                echo '<span class="atbdp-tick-cross2">' . ($review_status == 'approved' ? '<span style="color: #4caf50;">&#x2713;</span>' : '<span style="color: red;">&#x2717;</span>') . '</span>';
                echo ucwords($review_status);
                break;
            case 'details' :
                $post_meta = get_post_meta($post_id);
                $details = isset($post_meta['_reviewer_details']) ? esc_textarea($post_meta['_reviewer_details'][0]) : '';
                echo $details;
                break;
            case 'rating' :
                $post_meta = get_post_meta($post_id);
                $rating = isset($post_meta['_reviewer_rating']) ? esc_textarea($post_meta['_reviewer_rating'][0]) : '';
                echo $rating;
                break;

        }
    }
}