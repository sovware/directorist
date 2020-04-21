<?php
/**
 * This template displays the Directorist listings in map view.
 */
?>
<div id="directorist" class="atbd_wrapper">
    <?php  
    $header_path = dirname( __FILE__ ) . '/listings-header.php';
    if ( file_exists( $header_path ) ) { include $header_path; }
    /**
     * @since 6.3.5
     * 
     */
    $header_output = apply_filters( 'atbdp_listing_header_html', $header_output, compact( 'display_header','header_container_fluid','search_more_filters_fields', 'listing_filters_button', 'header_title','listing_filters_icon','display_viewas_dropdown','display_sortby_dropdown', 'filters', 'view_as_text', 'view_as_items', 'sort_by_text', 'sort_by_items', 'listing_location_address', 'filters_button' ) );    echo $header_output;
    ?>
    <div class="atbdp-divider"></div>
    <!-- the loop -->
    <?php
    $listings_map_height = !empty($map_height) ? $map_height : 350;
    ?>
    <div class="<?php echo !empty($map_container) ? $map_container : '';?>">
        <?php 
        $osm_path = dirname( __FILE__ ) . '/loop/openstreet-map.php';
        $gmap_path = dirname( __FILE__ ) . '/loop/google-map.php';

        if ( 'google' == $select_listing_map && file_exists( $gmap_path ) ) {
            include $gmap_path;
        }

        if ( 'google' != $select_listing_map && file_exists( $osm_path ) ) {
            include $osm_path;
        }

        $show_pagination = !empty($show_pagination) ? $show_pagination : '';
        if ('yes' == $show_pagination){
            ?>
                    <?php
                    $paged = !empty($paged) ? $paged : '';
                    echo atbdp_pagination($all_listings, $paged);
                    ?>
        <?php } ?>
    </div>
    <!-- Use reset postdata to restore orginal query -->
    <?php wp_reset_postdata(); ?>
</div>