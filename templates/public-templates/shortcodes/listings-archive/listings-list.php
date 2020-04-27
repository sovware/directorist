<div id="directorist" class="atbd_wrapper">
    <?php
    atbdp_listings_header( $atts );

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
    <?php if ($all_listings->have_posts()) {


        $class_name = 'container-fluid';
        $container = apply_filters('list_view_container', $class_name);
    ?>
        <div class="<?php echo !empty($container) ? $container : 'container'; ?>">
            <div class="row">
                <div class="<?php echo apply_filters('atbdp_listing_list_view_html_class', 'col-md-12') ?>">
                    <?php
                    while ($all_listings->have_posts()) {
                        $all_listings->the_post();

                        atbdp_listings_loop( 'list', $atts );
                    }
                    wp_reset_postdata(); ?>
                    <?php
                    /**
                     * @since 5.0
                     */
                    do_action('atbdp_before_listings_pagination');

                    if ('yes' == $show_pagination) { ?>
                        <?php
                        echo atbdp_pagination($all_listings, $paged);
                        ?>
                    <?php } ?>

                </div>
            </div>
        </div>
    <?php

    } else { ?>
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

</div>
<!--ends .row -->