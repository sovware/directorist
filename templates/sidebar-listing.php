<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Directorist
 */

if (!$disable_s_widget){  ?>
    <!--@todo; later make the listing submit widget as real widget instead of hard code-->
    <!--SIDE BAR -->
    <div class="col-md-4">
        <div class="directory_user_area">
            <div class="directory_are_title">
                <h4><?php _e('Submit Your Item', ATBDP_TEXTDOMAIN); ?></h4>
            </div>

            <div class="directory_user_area_form">
                <a href="<?= esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>" class="<?= atbdp_directorist_button_classes(); ?>"><?php _e('Submit New Listing', ATBDP_TEXTDOMAIN); ?></a>

                <?php
                if (!$disable_widget_login) {
                    atbdp_after_new_listing_button(); // fires an empty action to let dev extend by adding anything here
                    if (!is_user_logged_in()) {
                        wp_login_form();
                        wp_register();
                    }
                    /**
                     * Fires after the side bar login from is rendered on single listing page
                     *
                     *
                     * @since 1.0.0
                     *
                     * @param object|WP_post $post The current post object which is our listing post
                     * @param array $listing_info The meta information of the current listing
                     */

                    do_action('atbdp_after_sidebar_login_form', $post, $listing_info);
                }
                ?>
            </div> <!--ends .directory_user_area_form-->
        </div> <!--ends .directory_user_area-->

    </div> <!--ends .col-md-4 col-sm-4-->
<?php } ?>

<?php if (  is_active_sidebar( 'right-sidebar-listing' ) ) { ?>
<!-- start col-md-4  -->
<div class="directorist col-md-4">
    <div class="directorist sidebar_m">
        <!-- start search -->
        <?php dynamic_sidebar('right-sidebar-listing'); ?>
    </div>
</div>
<!-- end col-md-4  -->
<?php } ?>