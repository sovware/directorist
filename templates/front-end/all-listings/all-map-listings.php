<?php
/**
 * This template displays the Directorist listings in map view.
 */

!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$all_listings               = !empty($all_listings) ? $all_listings : new WP_Query;
$is_disable_price           = get_directorist_option('disable_list_price');
$display_sortby_dropdown    = get_directorist_option('display_sort_by',1);
$display_viewas_dropdown    = get_directorist_option('display_view_as',1);
$pagenation                 = get_directorist_option('paginate_all_listings',1);
$select_listing_map         = get_directorist_option('select_listing_map','google');
$zoom                       = get_directorist_option('map_zoom_level', 4);
$container                  = 'container';
$map_container              = apply_filters('atbdp_map_container',$container);


?>
<div id="directorist" class="atbd_wrapper">
    <?php  include ATBDP_TEMPLATES_DIR . "front-end/all-listings/listings-header.php"; ?>
    <div class="atbdp-divider"></div>
    <!-- the loop -->
    <?php
    $listings_map_height = get_directorist_option('listings_map_height',350);
    ?>
    <div class="<?php echo !empty($map_container) ? $map_container : '';?>">
        <?php if('google' == $select_listing_map) {
            include ATBDP_TEMPLATES_DIR . 'front-end/all-listings/maps/google/google-map.php';
        } else {
            include ATBDP_TEMPLATES_DIR . 'front-end/all-listings/maps/openstreet/openstreet-map.php';
        } ?>


        <!-- end of the loop -->
    </div>
    <!-- Use reset postdata to restore orginal query -->
    <?php wp_reset_postdata(); ?>

    <div class="row atbd_listing_pagination">
        <?php
        if (1 == $pagenation){
            ?>
            <div class="col-md-12">
                <div class="">
                    <?php
                    $paged = !empty($paged)?$paged:'';
                    echo atbdp_pagination($all_listings, $paged);
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>



