<?php
!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$all_listings = !empty($all_listings) ? $all_listings : new WP_Query;
$display_sortby_dropdown = get_directorist_option('display_sort_by', 1);
$display_viewas_dropdown = get_directorist_option('display_view_as', 1);
$display_image = !empty($display_image) ? $display_image : '';
$show_pagination = !empty($show_pagination) ? $show_pagination : '';
$paged = !empty($paged) ? $paged : '';
if (is_rtl()){
    wp_enqueue_style('atbdp-search-style-rtl', ATBDP_PUBLIC_ASSETS . 'css/search-style-rtl.css');

}else{
    wp_enqueue_style('atbdp-search-style', ATBDP_PUBLIC_ASSETS . 'css/search-style.css');
}
?>
<div id="directorist" class="atbd_wrapper">
    <?php ob_start();
    include ATBDP_TEMPLATES_DIR . "front-end/all-listings/listings-header.php";
    $header_output = ob_get_clean();
    /**
     * @since 6.3.5
     * 
     */
    $header_output = apply_filters( 'atbdp_listing_header_html', $header_output, compact( 'display_header','header_container_fluid','search_more_filters_fields', 'listing_filters_button', 'header_title','listing_filters_icon','display_viewas_dropdown','display_sortby_dropdown' ) );
    echo $header_output;
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
        <p class="atbdp_nlf"><?php _e('No listing found.', 'directorist'); ?></p>
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

