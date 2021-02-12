<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */

use \Directorist\Helper;
?>
<div id="directorist" class="directorist atbd_wrapper dashboard_area">

    <?php
    /**
     * @hooked Directorist_Listing_Dashboard > alert_message_template - 10
     */
    $dashboard->alert_message_template();
    ?>

    <div class="<?php echo esc_attr( $container_fluid ); ?>">
        <?php
        if ( $dashboard->display_title ) {
            Helper::get_template( 'dashboard/title' );
        }
        ?>
        <div class="atbd-dashboard-nav-toggle-icon">
            <a href="" class="atbd-dashboard-nav-toggler"><i class="la la-bars"></i></a>
        </div>
        <div class="atbd_dashboard_wrapper atbd_tab">
            <div class="atbd_user_dashboard_nav atbd_tab_nav">

                <?php
                $dashboard->nav_tabs_template();
                $dashboard->nav_buttons_template();
                ?>

            </div>

            <div class="atbd_tab-content">

                <?php
                $dashboard->tab_contents_html();
                ?>
            </div>
        </div>
    </div>
</div>



<?php
/**
 * @package Directorist
 * @since 5.9.3
 */
do_action('directorist_after_user_dashboard');