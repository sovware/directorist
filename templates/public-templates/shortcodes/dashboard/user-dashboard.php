<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div id="directorist" class="directorist atbd_wrapper dashboard_area">

    <?php
    /**
     * @hooked Directorist_Listing_Dashboard > alert_message_template - 10
     */
    do_action( 'directorist_dashboard_before_container' );
    ?>

    <div class="<?php echo esc_attr( $container_fluid ); ?> atbd-dashboard-container-wrapper">
        <?php
        /**
         * @since 6.6
         * @hooked Directorist_Listing_Dashboard > section_title - 10
         */
        do_action( 'directorist_dashboard_title_area', $display_title );
        ?>
        <div class="atbd-dashboard-sidenav">
            <a href="" class="atbd-dashboard-sidenav-toggle"><i class="fas fa-bars"></i></a>
            <ul>
                <li><a href="">My Listings <span>(5)</span></a></li>
                <li><a href="">My Profile</a></li>
                <li><a href="">Favorite Listings</a></li>
                <li><a href="">My Bookings</a></li>
                <li class="atbd-has-child">
                    <a href="" class="atbd-sidenav-dropdown">All Bookings <span class="fas fa-angle-right"></span></a>
                    <ul>
                        <li><a href="">Approved</a></li>
                        <li><a href="">Pending</a></li>
                        <li><a href="">Cancelled</a></li>
                    </ul>
                </li>
                <li><a href="">Compare Listings</a></li>
                <li><a href="">My Wallet</a></li>
                <li><a href="">Chat</a></li>
                <li class="atbd-has-child">
                    <a href="" class="atbd-sidenav-dropdown">Test <span class="fas fa-angle-right"></span></a>
                    <ul>
                        <li><a href="">Approved</a></li>
                        <li><a href="">Pending</a></li>
                        <li><a href="">Cancelled</a></li>
                    </ul>
                </li>
                <li><a href="">Orders</a></li>
                <li><a href="">Settings</a></li>
                <li><a href="">Others</a></li>
            </ul>
        </div>
        <div class="atbd_dashboard_wrapper atbd_tab">
            <div class="atbd_user_dashboard_nav atbd_tab_nav">

                <?php
                /**
                 * @since 6.6
                 * @hooked Directorist_Listing_Dashboard > nav_tabs_template - 10
                 * @hooked Directorist_Listing_Dashboard > nav_buttons_template - 15
                 */
                do_action( 'directorist_dashboard_navigation');
                ?>

            </div>

            <div class="atbd_tab-content">

                <?php
                /**
                 * @since 6.6
                 * @hooked Directorist_Listing_Dashboard > tab_contents_html - 10
                 */
                do_action( 'directorist_dashboard_tab_contents');
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
do_action('atbdp_after_user_dashboard');