<?php
/**
 * @author  wpWax
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

    <div class="<?php echo esc_attr( $container_fluid ); ?>">
        <?php
        /**
         * @since 6.6
         * @hooked Directorist_Listing_Dashboard > section_title - 10
         */
        do_action( 'directorist_dashboard_title_area', $display_title );
        ?>
        <div class="atbd-dashboard-nav-toggle-icon">
            <a href="" class="atbd-dashboard-nav-toggler"><i class="la la-bars"></i></a>
        </div>
        <div class="atbd_dashboard_wrapper atbd_tab f">
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

        <!-- Tempalte ReStructered Component -->
        <div class="directorist_card directorist_mt-30">
            <div class="directorist_card__header">
                <h2>Title Will be Going Here</h2>
            </div>
            <div class="directorist_card__body">
                <div class="single-component">
                    <h4>Checkbox Style</h4>
                    <div class="directorist_checkbox">
                        <input type="checkbox" id="ckbx-1">
                        <label for="ckbx-1" class="directorist_checkbox__label">Directorist Checkbox</label>
                    </div>
                    <div class="directorist_checkbox directorist_checkbox-circle">
                        <input type="checkbox" id="ckbx-2">
                        <label for="ckbx-2" class="directorist_checkbox__label">Directorist Checkbox Circle</label>
                    </div>
                    <div class="directorist_checkbox directorist_checkbox-primary">
                        <input type="checkbox" id="ckbx-3">
                        <label for="ckbx-3" class="directorist_checkbox__label">Directorist Checkbox Primary</label>
                    </div>
                    <div class="directorist_checkbox directorist_checkbox-secondary">
                        <input type="checkbox" id="ckbx-4">
                        <label for="ckbx-4" class="directorist_checkbox__label">Directorist Checkbox Secondary</label>
                    </div>
                </div>
                <div class="single-component">
                    <h4>Radio Style</h4>
                    <div class="directorist_radio directorist_radio-circle">
                        <input type="radio" id="radio-1">
                        <label for="radio-1" class="directorist_radio__label">Directorist Radio</label>
                    </div>
                    <div class="directorist_radio directorist_radio-circle directorist_radio-primary">
                        <input type="radio" id="radio-2">
                        <label for="radio-2" class="directorist_radio__label">Directorist Radio</label>
                    </div>
                    <div class="directorist_radio directorist_radio-circle directorist_radio-secondary">
                        <input type="radio" id="radio-3">
                        <label for="radio-3" class="directorist_radio__label">Directorist Radio</label>
                    </div>
                </div>
                <div class="single-component">
                    <h4>Button Style</h4>
                    <a href="#" class="directorist_btn directorist_btn-lg directorist_btn-primary">Primary</a>
                    <a href="#" class="directorist_btn directorist_btn-success">Success</a>
                    <a href="#" class="directorist_btn directorist_btn-info">Info</a>
                    <a href="#" class="directorist_btn directorist_btn-sm directorist_btn-warning">Warning</a>
                    <a href="#" class="directorist_btn directorist_btn-xs directorist_btn-danger">Danger</a>
                    <a href="#" class="directorist_btn directorist_btn-lg directorist_btn-bg-normal">Bg 1</a>
                    <a href="#" class="directorist_btn directorist_btn-lg directorist_btn-bg-light">Bg 2</a>
                    <a href="#" class="directorist_btn directorist_btn-lg">Bg 3</a>
                    <a href="#" class="directorist_btn directorist_btn-lg directorist_btn-outline">Border 1</a>
                    <a href="#" class="directorist_btn directorist_btn-lg directorist_btn-outline-normal">Border 2</a>
                    <a href="#" class="directorist_btn directorist_btn-lg directorist_btn-outline-light">Border 3</a>
                </div>
                <div class="single-component">
                    <h4>Badge Style</h4>
                    <span class="directorist_badge directorist_badge-primary">Featured</span>
                    <span class="directorist_badge directorist_badge-warning">Featured</span>
                    <span class="directorist_badge directorist_badge-info">Featured</span>
                    <span class="directorist_badge directorist_badge-success">Featured</span>
                    <span class="directorist_badge directorist_badge-danger">Featured</span>
                    <span class="directorist_badge directorist_badge-gray">Featured</span>
                    <span class="directorist_badge directorist_badge-light">Featured</span>
                </div>
                <div class="single-component">
                    <span class="directorist_badge directorist_badge-primary-transparent">Approved</span>
                    <span class="directorist_badge directorist_badge-warning-transparent">Approved</span>
                    <span class="directorist_badge directorist_badge-info-transparent">Approved</span>
                    <span class="directorist_badge directorist_badge-success-transparent">Approved</span>
                    <span class="directorist_badge directorist_badge-danger-transparent">Approved</span>
                    <span class="directorist_badge directorist_badge-gray-transparent">Approved</span>
                    <span class="directorist_badge directorist_badge-light-transparent">Approved</span>
                </div>
                <div class="single-component">
                    <h4>Switch Style</h4>
                    <div class="directorist_switch directorist_switch-success">
                        <input type="checkbox" class="directorist_switch-input" id="sw1">
                        <label for="sw1" class="directorist_switch-label">Basic Switch</label>
                    </div>
                    <div class="directorist_switch directorist_switch-secondary">
                        <input type="checkbox" class="directorist_switch-input" id="sw2">
                        <label for="sw2" class="directorist_switch-label">Basic Switch</label>
                    </div>
                    <div class="directorist_switch directorist_switch-info">
                        <input type="checkbox" class="directorist_switch-input" id="sw3">
                        <label for="sw3" class="directorist_switch-label">Basic Switch</label>
                    </div>
                    <div class="directorist_switch directorist_switch-warning">
                        <input type="checkbox" class="directorist_switch-input" id="sw4">
                        <label for="sw4" class="directorist_switch-label">Basic Switch</label>
                    </div>
                </div>
                <div class="single-component">
                    <h4>Yes No Switch Style</h4>
                    <label class="directorist_switch-Yn">
                        <input type="checkbox" name="atpp_recurring" id="atpp_claim-badge" value="yes">
                        <span class="switch_yes">Yes</span>
                        <span class="switch_no">No</span>
                    </label>
                </div>
                <div class="single-component">
                    <h4>Form Component</h4>
                    <div class="directorist_form-group directorist_mb-15">
                        <input type="text" class="directorist_form-element" placeholder="Enter Your Input">
                    </div>
                    <div class="directorist_form-group directorist_mb-15">
                        <input type="text" class="directorist_form-element directorist_form-element-lg" placeholder="Enter Your Input">
                    </div>
                    <div class="directorist_form-group directorist_mb-15">
                        <input type="text" class="directorist_form-element directorist_form-element-sm" placeholder="Enter Your Input">
                    </div>
                    <div class="directorist_form-group directorist_mb-15">
                        <textarea class="directorist_form-element" placeholder="Textarea Input"></textarea>
                    </div>
                </div>
                <div class="single-component">
                    <div class="directorist_form-group directorist_icon-left directorist_mb-15">
                            <span class="directorist_input-icon">
                                <i class="fa fa-user"></i>
                            </span>
                            <input type="text" class="directorist_form-element" placeholder="Enter Your Input">
                    </div>
                    <div class="directorist_form-group directorist_icon-right directorist_mb-15">
                        <input type="text" class="directorist_form-element" placeholder="Enter Your Input">
                        <span class="directorist_input-icon">
                            <i class="fa fa-user"></i>
                        </span>
                    </div>
                </div>
                <div class="single-component">
                    <h4>Directorist Dropdown</h4>
                    <div class="directorist_dropdown">
                        <a href="" class="directorist_dropdown__toggle directorist_toggle-hasIcon">Directorist Dropdown</a>
                        <div class="directorist_dropdown__links">
                            <a href="#" class="directorist_dropdown__links--single">Dropdown 1</a>
                            <a href="#" class="directorist_dropdown__links--single">Dropdown 1</a>
                            <a href="#" class="directorist_dropdown__links--single">Dropdown 1</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="directorist_card__footer">

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