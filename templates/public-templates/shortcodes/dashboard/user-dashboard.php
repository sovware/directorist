<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div id="directorist" class="directorist atbd_wrapper dashboard_area">

    <?php
    /**
     * @hooked Directorist_Template_Hooks::dashboard_alert_message - 10
     */
    do_action( 'directorist_dashboard_before_container' );
    ?>

    <div class="<?php echo esc_attr( $container_fluid ); ?>">
        <div class="row">
            <div class="col-md-12">

                <?php
                /**
                 * @hooked Directorist_Template_Hooks::dashboard_title - 10
                 */
                do_action( 'directorist_dashboard_title_area', $show_title );
                ?>

                <div class="atbd_dashboard_wrapper atbd_tab">
                    <div class="atbd_user_dashboard_nav atbd_tab_nav">

                        <?php
                        /**
                         * @hooked Directorist_Template_Hooks::dashboard_nav_tabs - 10
                         * @hooked Directorist_Template_Hooks::dashboard_nav_buttons - 15
                         */
                        do_action( 'directorist_dashboard_navigation', $dashboard_items );
                        ?>

                    </div>

                    <div class="atbd_tab-content">

                        <?php
                        /**
                         * @hooked Directorist_Template_Hooks::dashboard_tab_contents - 10
                         */
                        do_action( 'directorist_dashboard_tab_contents', $dashboard_items );
                        ?>
                        
                    </div>
                </div>
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