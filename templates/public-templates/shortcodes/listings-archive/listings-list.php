<?php

/**
 * @param WP_Query $all_listings It contains all the queried listings by a user
 * @since 5.5.1
 * @package Directorist
 */
do_action('atbdp_before_all_listings_list', $all_listings);
?>

<div id="directorist" class="atbd_wrapper ads-advaced--wrapper">

    <?php
    /**
     * @since 7.0
     * @hooked Directorist_Template_Hooks::archive_header - 10
     */
    do_action( 'directorist_archive_header', $atts );

    /**
     * @since 5.0
     * It fires before the listings columns
     * It only fires if the parameter [directorist_all_listing action_before_after_loop="yes"]
     */
    $action_before_after_loop = !empty($action_before_after_loop) ? $action_before_after_loop : '';
    if ('yes' === $action_before_after_loop) {
        do_action('atbdp_before_list_listings_loop');
    }

    $class_name = 'container-fluid';
    $container = apply_filters('list_view_container', $class_name);
    ?>
    
    <div class="<?php echo !empty($container) ? $container : 'container'; ?>">
        <div class="row">
            <div class="<?php echo apply_filters('atbdp_listing_list_view_html_class', 'col-md-12') ?>">
            <?php
            if ($all_listings->have_posts()) {
                while ($all_listings->have_posts()) {
                    $all_listings->the_post();
                    atbdp_listings_loop( 'list', $atts );
                }
                wp_reset_postdata();
            } else { ?>
                <p class="atbdp_nlf"><?php _e('No listing found.', 'directorist'); ?></p>
            <?php }
            ?>
            </div>
        </div>
        <!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <?php
                /**
                 * @since 5.0
                 */
                do_action('atbdp_before_listings_pagination');
                $show_pagination = !empty($show_pagination) ? $show_pagination : '';
                if ('yes' == $show_pagination) {
                    $paged = !empty($paged) ? $paged : '';
                    echo atbdp_pagination($all_listings, $paged);
                } ?>
            </div>
        </div>

        <?php
        /**
         * @since 5.0
         * to add custom html
         * It only fires if the parameter [directorist_all_listing action_before_after_loop="yes"]
         */
        $action_before_after_loop = !empty($action_before_after_loop) ? $action_before_after_loop : '';
        if ('yes' === $action_before_after_loop) {
            do_action('atbdp_after_grid_listings_loop');
        }
        ?>
    </div>
</div>