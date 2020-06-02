<?php

/**
 * @param WP_Query $all_listings It contains all the queried listings by a user
 * @since 7.0
 * @package Directorist
 */
do_action('atbdp_before_all_listings_map', $listings);
?>

<div id="directorist" class="atbd_wrapper">
    <?php
    /**
     * @since 7.0
     * @hooked Directorist_Template_Hooks::archive_header - 10
     */
    do_action( 'directorist_archive_header', $listings );
    ?>

    <div class="atbdp-divider"></div>
    <!-- the loop -->
    <?php
    $listings_map_height = !empty($map_height) ? $map_height : 350;
    ?>
    <div class="<?php echo !empty($map_container) ? $map_container : '';?>">
        <?php
        $listings->render_map();

        if ($listings->show_pagination) {
            echo atbdp_pagination($listings->query, $listings->paged);
        } ?>
    </div>
    <!-- Use reset postdata to restore orginal query -->
    <?php wp_reset_postdata(); ?>
</div>