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
                <div class="atbd_tab_inner" id="tab1">
                    <p>Empty Content</p>
                </div>
                <div class="atbd_tab_inner" id="tab2">
                    <p>Empty Content</p>
                </div>
                <div class="atbd_tab_inner" id="tab3">
                    <p>Empty Content</p>
                </div>
            </div>
        </div>

        <!-- Tempalte ReStructered Component -->
        <div class="directorist-card directorist-mt-30">
            <div class="directorist-card__header">
                <h2>Title Will be Going Here</h2>
            </div>
            <div class="directorist-card__body">
                <div class="single-component">
                    <h4>Checkbox Style</h4>
                    <div class="directorist-checkbox">
                        <input type="checkbox" id="ckbx-1">
                        <label for="ckbx-1" class="directorist-checkbox__label">Directorist Checkbox</label>
                    </div>
                    <div class="directorist-checkbox directorist-checkbox-circle">
                        <input type="checkbox" id="ckbx-2">
                        <label for="ckbx-2" class="directorist-checkbox__label">Directorist Checkbox Circle</label>
                    </div>
                    <div class="directorist-checkbox directorist-checkbox-primary">
                        <input type="checkbox" id="ckbx-3">
                        <label for="ckbx-3" class="directorist-checkbox__label">Directorist Checkbox Primary</label>
                    </div>
                    <div class="directorist-checkbox directorist-checkbox-secondary">
                        <input type="checkbox" id="ckbx-4">
                        <label for="ckbx-4" class="directorist-checkbox__label">Directorist Checkbox Secondary</label>
                    </div>
                </div>
                <div class="single-component">
                    <h4>Radio Style</h4>
                    <div class="directorist-radio directorist-radio-circle">
                        <input type="radio" id="radio-1">
                        <label for="radio-1" class="directorist-radio__label">Directorist Radio</label>
                    </div>
                    <div class="directorist-radio directorist-radio-circle directorist-radio-primary">
                        <input type="radio" id="radio-2">
                        <label for="radio-2" class="directorist-radio__label">Directorist Radio</label>
                    </div>
                    <div class="directorist-radio directorist-radio-circle directorist-radio-secondary">
                        <input type="radio" id="radio-3">
                        <label for="radio-3" class="directorist-radio__label">Directorist Radio</label>
                    </div>
                </div>
                <div class="single-component">
                    <h4>Button Style</h4>
                    <a href="#" class="directorist-btn directorist-btn-lg directorist-btn-primary">Primary</a>
                    <a href="#" class="directorist-btn directorist-btn-success">Success</a>
                    <a href="#" class="directorist-btn directorist-btn-info">Info</a>
                    <a href="#" class="directorist-btn directorist-btn-sm directorist-btn-warning">Warning</a>
                    <a href="#" class="directorist-btn directorist-btn-xs directorist-btn-danger">Danger</a>
                    <a href="#" class="directorist-btn directorist-btn-lg directorist-btn-bg-normal">Bg 1</a>
                    <a href="#" class="directorist-btn directorist-btn-lg directorist-btn-bg-light">Bg 2</a>
                    <a href="#" class="directorist-btn directorist-btn-lg">Bg 3</a>
                    <a href="#" class="directorist-btn directorist-btn-lg directorist-btn-outline">Border 1</a>
                    <a href="#" class="directorist-btn directorist-btn-lg directorist-btn-outline-normal">Border 2</a>
                    <a href="#" class="directorist-btn directorist-btn-lg directorist-btn-outline-light">Border 3</a>
                </div>
                <div class="single-component">
                    <h4>Badge Style</h4>
                    <span class="directorist-badge directorist-badge-primary">Featured</span>
                    <span class="directorist-badge directorist-badge-warning">Featured</span>
                    <span class="directorist-badge directorist-badge-info">Featured</span>
                    <span class="directorist-badge directorist-badge-success">Featured</span>
                    <span class="directorist-badge directorist-badge-danger">Featured</span>
                    <span class="directorist-badge directorist-badge-gray">Featured</span>
                    <span class="directorist-badge directorist-badge-light">Featured</span>
                </div>
                <div class="single-component">
                    <span class="directorist-badge directorist-badge-primary-transparent">Approved</span>
                    <span class="directorist-badge directorist-badge-warning-transparent">Approved</span>
                    <span class="directorist-badge directorist-badge-info-transparent">Approved</span>
                    <span class="directorist-badge directorist-badge-success-transparent">Approved</span>
                    <span class="directorist-badge directorist-badge-danger-transparent">Approved</span>
                    <span class="directorist-badge directorist-badge-gray-transparent">Approved</span>
                    <span class="directorist-badge directorist-badge-light-transparent">Approved</span>
                </div>
                <div class="single-component">
                    <h4>Switch Style</h4>
                    <div class="directorist-switch directorist-switch-success">
                        <input type="checkbox" class="directorist-switch-input" id="sw1">
                        <label for="sw1" class="directorist-switch-label">Basic Switch</label>
                    </div>
                    <div class="directorist-switch directorist-switch-secondary">
                        <input type="checkbox" class="directorist-switch-input" id="sw2">
                        <label for="sw2" class="directorist-switch-label">Basic Switch</label>
                    </div>
                    <div class="directorist-switch directorist-switch-info">
                        <input type="checkbox" class="directorist-switch-input" id="sw3">
                        <label for="sw3" class="directorist-switch-label">Basic Switch</label>
                    </div>
                    <div class="directorist-switch directorist-switch-warning">
                        <input type="checkbox" class="directorist-switch-input" id="sw4">
                        <label for="sw4" class="directorist-switch-label">Basic Switch</label>
                    </div>
                </div>
                <div class="single-component">
                    <h4>Yes No Switch Style</h4>
                    <label class="directorist-switch-Yn">
                        <input type="checkbox" name="atpp_recurring" id="atpp_claim-badge" value="yes">
                        <span class="switch_yes">Yes</span>
                        <span class="switch_no">No</span>
                    </label>
                </div>
                <div class="single-component">
                    <h4>Form Component</h4>
                    <div class="directorist-form-group directorist-mb-15">
                        <input type="text" class="directorist-form-element" placeholder="Enter Your Input">
                    </div>
                    <div class="directorist-form-group directorist-mb-15">
                        <input type="text" class="directorist-form-element directorist-form-element-lg" placeholder="Class .directorist-form-element-lg">
                    </div>
                    <div class="directorist-form-group directorist-mb-15">
                        <input type="text" class="directorist-form-element directorist-form-element-sm" placeholder="class .directorist-form-element-sm">
                    </div>
                    <div class="directorist-form-group directorist-mb-15">
                        <textarea class="directorist-form-element" placeholder="Textarea Input"></textarea>
                    </div>
                </div>
                <div class="single-component">
                    <div class="directorist-form-group directorist-icon-left directorist-mb-15">
                            <span class="directorist-input-icon">
                                <i class="fa fa-user"></i>
                            </span>
                            <input type="text" class="directorist-form-element" placeholder="Enter Your Input">
                    </div>
                    <div class="directorist-form-group directorist-icon-right directorist-mb-15">
                        <input type="text" class="directorist-form-element" placeholder="Enter Your Input">
                        <span class="directorist-input-icon">
                            <i class="fa fa-user"></i>
                        </span>
                    </div>
                </div>
                <div class="single-component">
                    <h4>Directorist Dropdown</h4>
                    <div class="directorist-dropdown directorist-dropdown-js">
                        <a href="" class="directorist-dropdown__toggle directorist-dropdown__toggle-js directorist-toggle-has-icon">Directorist Dropdown</a>
                        <div class="directorist-dropdown__links directorist-dropdown__links-js">
                            <a href="#" class="directorist-dropdown__links--single">Dropdown 1</a>
                            <a href="#" class="directorist-dropdown__links--single">Dropdown 1</a>
                            <a href="#" class="directorist-dropdown__links--single">Dropdown 1</a>
                        </div>
                    </div>
                </div>
                <div class="single-component">
                    <h4>Directorist Modal</h4>
                    <a href="#" class="directorist-btn directorist-btn-success directorist-btn-modal directorist-btn-modal-js" data-directorist_target="directorist-modal-js" >Directorist Modal</a>
                    <div class="directorist-modal directorist-modal-js directorist-fade">
                        <div class="directorist-modal__dialog">
                            <div class="directorist-modal__content">
                                <div class="directorist-modal__header">
                                    <h4 class="directorist-modal__header--title">Modal Title Goes to Here</h4>
                                    <a href="#" class="directorist-modal-close directorist-modal-close-js"><i class="la la-times"></i></a>
                                </div>
                                <div class="directorist-modal__body">
                                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Officia, distinctio?</p>
                                </div>
                                <div class="directorist-modal__footer">
                                    <div class="directorist-modal__action">
                                        <button class="directorist-btn directorist-btn-danger directorist-modal-close directorist-modal-close-js">Cancel</button>
                                        <button class="directorist-btn directorist-btn-info directorist-modal-close directorist-modal-close-js">Ok</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="single-component">
                    <h4>Directorist Tooltip</h4>
                    <a href="javascript:void(0)" class="directorist-tooltip" aria-label="Tooltip Text Here">
                        <span class="directorist-tooltip__label">tooltip Top</span>
                    </a>
                    <a href="javascript:void(0)" class="directorist-tooltip directorist-tooltip-bottom" aria-label="Tooltip Text Here">
                        <span class="directorist-tooltip__label">tooltip Bottom</span>
                    </a>
                    <a href="javascript:void(0)" class="directorist-tooltip" aria-label="Tooltip Text Here">
                        <span class="directorist-tooltip__label">tooltip Primary</span>
                    </a>
                    <a href="javascript:void(0)" class="directorist-tooltip directorist-tooltip-success" aria-label="Tooltip Text Here">
                        <span class="directorist-tooltip__label">tooltip success</span>
                    </a>
                    <a href="javascript:void(0)" class="directorist-tooltip directorist-tooltip-info" aria-label="Tooltip Text Here">
                        <span class="directorist-tooltip__label">tooltip info</span>
                    </a>
                    <a href="javascript:void(0)" class="directorist-tooltip directorist-tooltip-warning" aria-label="Tooltip Text Here">
                        <span class="directorist-tooltip__label">tooltip warning</span>
                    </a>
                </div>
                <div class="single-component directorist-pb-100">
                    <h4>Directorist Select</h4>
                    <div class="directorist-select" id="directorist-select" data-max="15">
                        <select name="mySelect">
                            <option value="">Select Item</option>
                            <option value="dhaka">Dhaka</option>
                            <option value="barisal">Barisal</option>
                            <option value="khulna">Khulna</option>
                            <option value="gazipur">Gazipur</option>
                            <option value="narsingdi">Narsingdi</option>
                        </select>
                    </div>
                </div>
                <div class="single-component directorist-pb-100">
                    <h4>Directorist Select</h4>
                    <div class="directorist-select directorist-select-multi" id="directorist-multi-select" data-isSearch="true" data-multiSelect='[{value: "dhaka", key: 0}]' data-max="4">
                        <select name="mySelect">
                            <option value="">Select Item</option>
                            <option value="dhaka">Dhaka</option>
                            <option value="barisal">Barisal</option>
                            <option value="khulna">Khulna</option>
                            <option value="gazipur">Gazipur</option>
                            <option value="narsingdi">Narsingdi</option>
                        </select>
                    </div>
                </div>
                <div class="single-component">
                    <h4>Directorist Alert</h4>
                    <div class="directorist-alert directorist-alert-success directorist-mb-15">
                        <div class="directorist-alert__content">
                            <p>Your Data Svaed Successfully</p>
                            <a href="#" class="directorist-alert__close"><i class="la la-times"></i></a>
                        </div>
                    </div>
                    <div class="directorist-alert directorist-alert-danger directorist-mb-15">
                        <div class="directorist-alert__content">
                            <p>Your Data Svaed Successfully</p>
                            <a href="#" class="directorist-alert__close"><i class="la la-times"></i></a>
                        </div>
                    </div>
                    <div class="directorist-alert directorist-alert-warning directorist-mb-15">
                        <div class="directorist-alert__content">
                            <p>Your Data Svaed Successfully</p>
                            <a href="#" class="directorist-alert__close"><i class="la la-times"></i></a>
                        </div>
                    </div>
                    <div class="directorist-alert directorist-alert-primary">
                        <div class="directorist-alert__content">
                            <p>Your Data Svaed Successfully</p>
                            <a href="#" class="directorist-alert__close"><i class="la la-times"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="directorist-card__footer">

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