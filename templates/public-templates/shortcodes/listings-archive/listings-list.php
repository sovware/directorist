<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */

/**
 * @param WP_Query $all_listings It contains all the queried listings by a user
 * @since 5.5.1
 * @package Directorist
 */
do_action('atbdp_before_all_listings_list', $listings);
?>

<div id="directorist" class="atbd_wrapper ads-advaced--wrapper">

    <?php
    /**
     * @since 7.0
     * @hooked Directorist_Listings::archive_header - 10
     */
    do_action( 'directorist_archive_header', $listings );

    /**
     * @since 5.0
     * It fires before the listings columns
     * It only fires if the parameter [directorist_all_listing action_before_after_loop="yes"]
     */
    if ($listings->action_before_after_loop) {
        do_action('atbdp_before_list_listings_loop');
    }

    $container = apply_filters('list_view_container', 'container-fluid');
    $col_container = apply_filters('atbdp_listing_list_view_html_class', 'col-md-12');
    ?>

    <div class="<?php echo esc_attr( $container ); ?>">
        <div class="row">
            <div class="<?php echo esc_attr( $col_container ); ?>">
                <?php $listings->setup_loop( ['template' => 'list'] ); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <?php
                /**
                 * @since 5.0
                 */
                do_action('atbdp_before_listings_pagination');
                
                if ( $listings->show_pagination ) {
                    echo atbdp_pagination( $listings->query_results );
                } ?>
            </div>
        </div>

        <?php
        /**
         * @since 5.0
         * to add custom html
         * It only fires if the parameter [directorist_all_listing action_before_after_loop="yes"]
         */
        if ($listings->action_before_after_loop) {
            do_action('atbdp_after_grid_listings_loop');
        }
        ?>
    </div>
</div>