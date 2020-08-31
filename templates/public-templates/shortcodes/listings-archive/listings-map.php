<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */

/**
 * @param WP_Query $listings It contains all the queried listings by a user
 * @since 6.6
 * @package Directorist
 */
do_action('atbdp_before_all_listings_map', $listings);
?>

<div id="directorist" class="atbd_wrapper">
    <?php
    /**
     * @since 6.6
     * @hooked Directorist_Listings::archive_header - 10
     */
    do_action( 'directorist_archive_header', $listings );
    ?>

    <div class="atbdp-divider"></div>

    <?php
    $listings->map_template();

    if ($listings->show_pagination) {
        echo atbdp_pagination( $listings->query_results );
    }
    ?>
</div>