<?php
!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$all_listings = !empty($all_listings) ? $all_listings : new WP_Query;
$display_sortby_dropdown = get_directorist_option('display_sort_by', 1);
$display_viewas_dropdown = get_directorist_option('display_view_as', 1);
$display_image = !empty($display_image) ? $display_image : '';
$show_pagination = !empty($show_pagination) ? $show_pagination : '';
$paged = !empty($paged) ? $paged : '';
wp_enqueue_style('atbdp-search-style', ATBDP_PUBLIC_ASSETS . 'css/search-style.css');
?>
<div id="directorist" class="atbd_wrapper">
    <?php include ATBDP_TEMPLATES_DIR . "front-end/all-listings/listings-header.php";
    /**
     * @since 5.0
     * It fires before the listings columns
     * It only fires if the parameter [directorist_all_listing action_before_after_loop="yes"]
     */
    $action_before_after_loop = !empty($action_before_after_loop) ? $action_before_after_loop : '';
    if ('yes' === $action_before_after_loop) {
        do_action('atbdp_before_list_listings_loop');
    }
    ?>
    <?php if ($all_listings->have_posts()) { ?>
        <?php listing_view_by_list($all_listings, $display_image, $show_pagination, $paged); ?>
    <?php } else { ?>
        <p class="atbdp_nlf"><?php _e('No listing found.', ATBDP_TEXTDOMAIN); ?></p>
    <?php }
    /**
     * @since 5.0
     * It fires before the listings columns
     * It only fires if the parameter [directorist_all_listing action_before_after_loop="yes"]
     */
    $action_before_after_loop = !empty($action_before_after_loop) ? $action_before_after_loop : '';
    if ('yes' === $action_before_after_loop) {
        do_action('atbdp_after_list_listings_loop');
    }
    ?>

</div> <!--ends .row -->

