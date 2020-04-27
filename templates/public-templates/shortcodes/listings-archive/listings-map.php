<?php
/**
 * This template displays the Directorist listings in map view.
 */
?>
<div id="directorist" class="atbd_wrapper">
    <?php atbdp_listings_header( $atts ); ?>

    <div class="atbdp-divider"></div>
    <!-- the loop -->
    <?php
    $listings_map_height = !empty($map_height) ? $map_height : 350;
    ?>
    <div class="<?php echo !empty($map_container) ? $map_container : '';?>">
        <?php 
        atbdp_listings_map( $atts );

        $show_pagination = !empty($show_pagination) ? $show_pagination : '';
        if ('yes' == $show_pagination) {
            $paged = !empty($paged) ? $paged : '';
            echo atbdp_pagination($all_listings, $paged);
        } ?>
    </div>
    <!-- Use reset postdata to restore orginal query -->
    <?php wp_reset_postdata(); ?>
</div>