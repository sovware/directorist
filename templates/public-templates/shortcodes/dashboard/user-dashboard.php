<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div id="directorist" class="directorist atbd_wrapper dashboard_area">

    <?php atbdp_get_shortcode_template( 'dashboard/alert-message' );?>

    <div class="<?php echo esc_attr( $container_fluid ); ?>">
        <div class="row">
            <div class="col-md-12">
                <?php
                if ('yes' === $show_title) {
                    ?>
                    <div class="atbd_add_listing_title">
                        <h2><?php _e('My Dashboard', 'directorist'); ?></h2>
                    </div> <!--ends add_listing_title-->
                    <?php
                }
                ?>
                <div class="atbd_dashboard_wrapper atbd_tab">
                    <div class="atbd_user_dashboard_nav atbd_tab_nav">
                        <!-- Nav tabs -->
                        <div class="atbdp_tab_nav_wrapper">
                            <ul class="atbdp_tab_nav--content atbd-dashboard-nav">
                                <?php foreach ($dashoard_items as $key => $value): ?>
                                    <li class="atbdp_tab_nav--content-link"><a href="" class="atbd_tn_link" target="<?php echo esc_attr($key);?>"><?php echo wp_kses_post( $value['title'] ); ?></a>
                                    </li>
                                    <?php
                                    if (!empty($value['after_nav_hook'])) {
                                       do_action($value['after_nav_hook']);
                                    }
                                    ?>
                                <?php endforeach; ?>
                                <li class="atbdp_tab_nav--content-link atbdp-tab-nav-last">
                                    <a href="#" class="atbdp-tab-nav-link"><span class="fa fa-ellipsis-h"></span></a>
                                </li>
                            </ul>
                        </div>

                        <?php atbdp_get_shortcode_template( 'dashboard/nav-buttons' );?>
                    </div> <!--ends dashboard_nav-->

                    <!-- Tab panes -->
                    <div class="atbd_tab-content">
                        <?php
                        foreach ($dashoard_items as $key => $value) {
                            echo $value['content'];
                            if (!empty($value['after_content_hook'])) {
                               do_action($value['after_content_hook']);
                            }
                        }
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