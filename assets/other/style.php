<?php
do_action('include_style_settings');
// $primary_text_color               = get_directorist_option('primary_color', '#ffffff');
$primary_hover_color              = get_directorist_option('primary_hover_color', '#ffffff');
$back_primary_color               = get_directorist_option('back_primary_color', '#444752');
$back_primary_hover_color         = get_directorist_option('back_primary_hover_color', '#222222');
$border_primary_color             = get_directorist_option('border_primary_color', '#444752');
$border_primary_hover_color       = get_directorist_option('border_primary_hover_color', '#222222');
// $secondary_text_color             = get_directorist_option('secondary_color', '#fff');
$secondary_hover_color            = get_directorist_option('secondary_hover_color', '#fff');
$back_secondary_color             = get_directorist_option('back_secondary_color', '#122069');
$back_secondary_hover_color       = get_directorist_option('back_secondary_hover_color', '#131469');
$secondary_border_color           = get_directorist_option('secondary_border_color', '#131469');
$secondary_border_hover_color     = get_directorist_option('secondary_border_hover_color', '#131469');
// $danger_text_color                = get_directorist_option('danger_color', '#fff');
$danger_hover_color               = get_directorist_option('danger_hover_color', '#fff');
$back_danger_color                = get_directorist_option('back_danger_color', '#e23636');
$back_danger_hover_color          = get_directorist_option('back_danger_hover_color', '#c5001e');
$danger_border_color              = get_directorist_option('danger_border_color', '#e23636');
$danger_border_hover_color        = get_directorist_option('danger_border_hover_color', '#c5001e');
// $success_text_color               = get_directorist_option('success_color', '#fff');
$success_hover_color              = get_directorist_option('success_hover_color', '#fff');
$back_success_color               = get_directorist_option('back_success_color', '#32cc6f');
$back_success_hover_color         = get_directorist_option('back_success_hover_color', '#2ba251');
$border_success_color             = get_directorist_option('border_success_color', '#32cc6f');
$border_success_hover_color       = get_directorist_option('border_success_hover_color', '#2ba251');
$lighter_text_color               = get_directorist_option('lighter_color', '#1A1B29');
$lighter_hover_color              = get_directorist_option('lighter_hover_color', '#1A1B29');
$back_lighter_color               = get_directorist_option('back_lighter_color', '#F6F7F9');
$back_lighter_hover_color         = get_directorist_option('lighter_color', '#F6F7F9');
$border_lighter_color             = get_directorist_option('border_lighter_color', '#F6F7F9');
$border_lighter_hover_color       = get_directorist_option('border_lighter_hover_color', '#F6F7F9');
$priout_text_color                = get_directorist_option('priout_color', '#444752');
$priout_hover_color               = get_directorist_option('priout_hover_color', '#444752');
$back_priout_color                = get_directorist_option('back_priout_color', '#fff');
$back_priout_hover_color          = get_directorist_option('back_priout_hover_color', '#fff');
$border_priout_color              = get_directorist_option('border_priout_color', '#444752');
$border_priout_hover_color        = get_directorist_option('border_priout_hover_color', '#9299b8');
$prioutlight_color                = get_directorist_option('prioutlight_color', '#444752');
$prioutlight_hover_color          = get_directorist_option('prioutlight_hover_color', '#ffffff');
$back_prioutlight_color           = get_directorist_option('back_prioutlight_color', '#fff');
$back_prioutlight_hover_color     = get_directorist_option('back_prioutlight_hover_color', '#444752');
$border_prioutlight_color         = get_directorist_option('border_prioutlight_color', '#e3e6ef');
$border_prioutlight_hover_color   = get_directorist_option('border_prioutlight_hover_color', '#444752');
$danout_text_color                = get_directorist_option('danout_color', '#e23636');
$danout_hover_color               = get_directorist_option('danout_hover_color', '#fff');
$back_danout_color                = get_directorist_option('back_danout_color', '#fff');
$back_danout_hover_color          = get_directorist_option('back_danout_hover_color', '#e23636');
$border_danout_color              = get_directorist_option('border_danout_color', '#e23636');
$border_danout_hover_color        = get_directorist_option('border_danout_hover_color', '#e23636');

$open_badge_color                 = get_directorist_option('open_back_color', '#32cc6f');
$closed_badge_color               = get_directorist_option('closed_back_color', '#e23636');
$featured_badge_color             = get_directorist_option('featured_back_color', '#fa8b0c');
$popular_badge_color              = get_directorist_option('popular_back_color', '#f51957');
$new_badge_color                  = get_directorist_option('new_back_color', '#122069');

$primary_dark_border_color        = get_directorist_option('primary_dark_border_color', '#444752');

$marker_shape_color               = get_directorist_option('marker_shape_color', '#444752');
$marker_icon_color                = get_directorist_option('marker_icon_color', '#444752');

$primary_color                    = get_directorist_option('color_primary', '#000000');
$secondary_color                  = get_directorist_option('color_secondary', '#F2F3F5');
$dark_color                       = get_directorist_option('color_dark', '#000000');
$white_color                      = get_directorist_option('color_white', '#ffffff');
$success_color                    = get_directorist_option('color_success', '#28A800');
$info_color                       = get_directorist_option('color_info', '#2c99ff');
$warning_color                    = get_directorist_option('color_warning', '#f28100');
$danger_color                     = get_directorist_option('color_danger', '#f80718');
$gray_color                       = get_directorist_option('color_gray', '#bcbcbc');
?>
<style>
    /* Css Variable */
    :root {
        /* theme color */
        --directorist-color-primary: <?php echo $primary_color; ?>;
        --directorist-color-primary-rgb: 0,0,0;
        --directorist-color-secondary: <?php echo $secondary_color; ?>;
        --directorist-color-dark: <?php echo $dark_color; ?>;
        --directorist-color-success: <?php echo $success_color; ?>;
        --directorist-color-success-rgb: 40, 168, 0;
        --directorist-color-info: <?php echo $info_color; ?>;
        --directorist-color-info-rgb: 44, 153, 255;
        --directorist-color-warning: <?php echo $warning_color; ?>;
        --directorist-color-warning-rgb: 242, 129, 0;
        --directorist-color-danger: <?php echo $danger_color; ?>;
        --directorist-color-danger-rgb: 248, 7, 24;
        --directorist-color-white: <?php echo $white_color; ?>;
        --directorist-color-white-rgb: 255,255,255;
        --directorist-color-body: #404040;
        --directorist-color-gray: <?php echo $gray_color; ?>;
        --directorist-color-gray-hover: #BCBCBC;
        --directorist-color-light: #ededed;
        --directorist-color-light-hover: #BCBCBC;
        --directorist-color-light-gray: #808080;
        --directorist-color-light-gray-rgb: 237, 237, 237;
        --directorist-color-deep-gray: #808080;
        --directorist-color-bg-gray: #f4f4f4;
        --directorist-color-bg-light-gray: #F4F5F6;
        --directorist-color-bg-light: #EDEDED;

        /* other color */
        --directorist-color-overlay: rgba(0,0,0,0.5);
        --directorist-color-overlay-normal: rgba(0,0,0,0.2);
        --directorist-color-border: #e9e9e9;
        --directorist-color-border-gray: #d9d9d9;

        --directorist-box-shadow: 0 3px 7.5px rgba(0,0,0, 0.08);
        --directorist-box-shadow-sm: 0 5px 0.8px rgba(167,178,199, 0.1);

        /* Badge Color */
        --directorist-color-open-badge: <?php echo $open_badge_color; ?>;
        --directorist-color-closed-badge: <?php echo $closed_badge_color; ?>;
        --directorist-color-featured-badge: <?php echo $featured_badge_color; ?>;
        --directorist-color-popular-badge: <?php echo $popular_badge_color; ?>;
        --directorist-color-new-badge: <?php echo $new_badge_color; ?>;

        /* Map marker Color */
        --directorist-color-marker-shape: <?php echo $marker_shape_color; ?>;
        --directorist-color-marker-icon: <?php echo $marker_icon_color; ?>;
    }
    
    /* Settings Panel Structure

    Button: Color Name
    -----------------------
    - Color

    - Background
      --- Background Hover

    - Border Color
      --- Border Color Hover

    */
    /**

    @buttons
    1. Solid Primary
        - color
        - Bc color
        - Br color
        - Hover color
        - Hover Bc color
        - Hover Br color
    3. Solid Secondary
        - color
        - Bc color
        - Br color
        - Hover color
        - Hover Bc color
        - Hover Br color
    4. Solid Info
        - color
        - Bc color
        - Br color
        - Hover color
        - Hover Bc color
        - Hover Br color
    5. Solid Danger
        - color
        - Bc color
        - Br color
        - Hover color
        - Hover Bc color
        - Hover Br color
    6. Solid Success
        - color
        - Bc color
        - Br color
        - Hover color
        - Hover Bc color
        - Hover Br color
    7. Outline Primary
        - Color
        - Br color
        - Hover color
        - Hover Br color
    8. Outline Secondary
        - Color
        - Br color
        - Hover color
        - Hover Br color
    9. Outline Info
        - Color
        - Br color
        - Hover color
        - Hover Br color
    10. Outline Danger
        - Color
        - Br color
        - Hover color
        - Hover Br color
    11. Outline Success
        - Color
        - Br color
        - Hover color
        - Hover Br color
    12. Outline Light Primary
        - Br color
        - Hover Br color
     */



    /* =======================================
     Button: Primary
    ======================================== */
    /* Color */
    .pricing .price_action .price_action--btn,
    #directorist.atbd_wrapper .btn-primary,
    .default-ad-search .submit_btn .btn-default,
    .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic,
    #directorist.atbd_wrapper .at-modal .at-modal-close,
    .atbdp_login_form_shortcode #loginform p input[type="submit"],
    .atbd_manage_fees_wrapper .table tr .action p .btn-block,
    #directorist.atbd_wrapper #atbdp-checkout-form #atbdp_checkout_submit_btn,
    #directorist.atbd_wrapper .ezmu__btn, .default-ad-search .submit_btn .btn-primary, .directorist-btn.directorist-btn-primary, .directorist-content-active .widget.atbd_widget .directorist .btn, .directorist-btn.directorist-btn-dark, .atbd-add-payment-method form .atbd-save-card, #bhCopyTime, #bhAddNew, .bdb-select-hours .bdb-remove, .directorist-form-image-upload-field .ezmu__btn.ezmu__input-label, .directorist-content-active .widget.atbd_widget .atbd_author_info_widget .btn {
        color: var(--directorist-color-white) !important;
    }

    /* Color Hover */
    .pricing .price_action .price_action--btn:hover,
    #directorist.atbd_wrapper .btn-primary:hover,
    .default-ad-search .submit_btn .btn-default:hover,
    .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic:hover,
    #directorist.atbd_wrapper .at-modal .at-modal-close:hover,
    .atbdp_login_form_shortcode #loginform p input[type="submit"]:hover,
    .atbd_manage_fees_wrapper .table tr .action p .btn-block:hover,
    #directorist.atbd_wrapper #atbdp-checkout-form #atbdp_checkout_submit_btn:hover,
    #directorist.atbd_wrapper .ezmu__btn:hover, .default-ad-search .submit_btn .btn-primary:hover, .directorist-btn.directorist-btn-primary:hover, .directorist-content-active .widget.atbd_widget .directorist .btn:hover, .directorist-btn.directorist-btn-dark:hover, .atbd-add-payment-method form .atbd-save-card:hover, #bhCopyTime:hover, #bhAddNew:hover, .bdb-select-hours .bdb-remove:hover, .directorist-form-image-upload-field .ezmu__btn.ezmu__input-label:hover, .directorist-content-active .widget.atbd_widget .atbd_author_info_widget .btn:hover {
        color: var(--directorist-color-white) !important;
    }

    /* Background */
    .pricing .price_action .price_action--btn,
    #directorist.atbd_wrapper .btn-primary,
    .default-ad-search .submit_btn .btn-default,
    .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic,
    #directorist.atbd_wrapper .at-modal .at-modal-close,
    .atbdp_login_form_shortcode #loginform p input[type="submit"],
    .atbd_manage_fees_wrapper .table tr .action p .btn-block,
    #directorist.atbd_wrapper .ezmu__btn, .default-ad-search .submit_btn .btn-primary, .directorist-btn.directorist-btn-primary, .directorist-content-active .widget.atbd_widget .directorist .btn, .directorist-btn.directorist-btn-dark, .atbd-add-payment-method form .atbd-save-card, #bhCopyTime, #bhAddNew, .bdb-select-hours .bdb-remove, .directorist-form-image-upload-field .ezmu__btn.ezmu__input-label, .directorist-content-active .widget.atbd_widget .atbd_author_info_widget .btn {
        background: var(--directorist-color-primary) !important;
    }

    /* Hover Background */
    .pricing .price_action .price_action--btn:hover,
    #directorist.atbd_wrapper .btn-primary:hover,
    #directorist.atbd_wrapper .at-modal .at-modal-close:hover,
    .default-ad-search .submit_btn .btn-default:hover,
    .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic:hover,
    .atbdp_login_form_shortcode #loginform p input[type="submit"]:hover,
    #directorist.atbd_wrapper .ezmu__btn:hover, .default-ad-search .submit_btn .btn-primary:hover, .directorist-btn.directorist-btn-primary:hover, .directorist-content-active .widget.atbd_widget .directorist .btn:hover, .directorist-btn.directorist-btn-dark:hover, .atbd-add-payment-method form .atbd-save-card:hover, #bhCopyTime:hover, #bhAddNew:hover, .bdb-select-hours .bdb-remove:hover, .directorist-form-image-upload-field .ezmu__btn.ezmu__input-label:hover, .directorist-content-active .widget.atbd_widget .atbd_author_info_widget .btn:hover {
        background: rgba(var(--directorist-color-primary-rgb),.80) !important;
    }

    /* Border Color */
    #directorist.atbd_wrapper .btn-primary,
    .default-ad-search .submit_btn .btn-default,
    .atbdp_login_form_shortcode #loginform p input[type="submit"], .default-ad-search .submit_btn .btn-primary, .directorist-btn.directorist-btn-primary, .directorist-content-active .widget.atbd_widget .directorist .btn, .atbd-add-payment-method form .atbd-save-card, .directorist-content-active .widget.atbd_widget .atbd_author_info_widget .btn {
        border-color: var(--directorist-color-primary) !important;
    }

    /* Hover Border Color */
    #directorist.atbd_wrapper .btn-primary:hover,
    .default-ad-search .submit_btn .btn-default:hover,
    .atbdp_login_form_shortcode #loginform p input[type="submit"]:hover, .default-ad-search .submit_btn .btn-primary:hover, .directorist-btn.directorist-btn-primary:hover, .directorist-content-active .widget.atbd_widget .directorist .btn:hover, .atbd-add-payment-method form .atbd-save-card:hover, .directorist-content-active .widget.atbd_widget .atbd_author_info_widget .btn:hover {
        border-color: var(--directorist-color-primary) !important;
    }

    /* =======================================
     Button: Secondary
    ======================================== */
    /* Color */
    #directorist.atbd_wrapper .btn-secondary, .directorist-btn.directorist-btn-secondary {
        color: var(--directorist-color-white) !important;
    }

    #directorist.atbd_wrapper .btn-secondary:hover, .directorist-btn.directorist-btn-secondary:hover {
        color: var(--directorist-color-white) !important;
    }

    /* Background */
    #directorist.atbd_wrapper .btn-secondary, .directorist-btn.directorist-btn-secondary {
        background: var(--directorist-color-secondary) !important;
    }

    /* Hover Background */
    #directorist.atbd_wrapper .btn-secondary:hover, .directorist-btn.directorist-btn-secondary:hover {
        background: rgba(var(--directorist-color-secondary-rgb),.80) !important;
    }

    /* Border Color */
    #directorist.atbd_wrapper .btn-secondary, .directorist-btn.directorist-btn-secondary {
        border-color: var(--directorist-color-secondary) !important;
    }

    /* Hover Border Color */
    #directorist.atbd_wrapper .btn-secondary:hover, .directorist-btn.directorist-btn-secondary:hover {
        border-color: var(--directorist-color-secondary) !important;
    }


    /* =======================================
     Button: Danger
    ======================================== */
    /* Color*/
    #directorist.atbd_wrapper .btn-danger,
    .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img #remove_pro_pic,
    .sweet-alert button.confirm, .directorist-form-social-fields__remove, .directorist-btn.directorist-btn-danger {
        color: var(--directorist-color-white) !important;
    }

    /* color hover */
    #directorist.atbd_wrapper .btn-danger:hover,
    .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img #remove_pro_pic,
    .sweet-alert button.confirm:hover, .directorist-form-social-fields__remove:hover, .directorist-btn.directorist-btn-danger:hover {
        color: var(--directorist-color-white) !important;
    }

    /* Background */
    #directorist.atbd_wrapper .btn-danger,
    .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img #remove_pro_pic,
    .sweet-alert button.confirm, .directorist-form-social-fields__remove, .directorist-btn.directorist-btn-danger {
        background: var(--directorist-color-danger) !important;
    }

    /* Hover Background */
    #directorist.atbd_wrapper .btn-danger:hover,
    .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img #remove_pro_pic:hover,
    .sweet-alert button.confirm:hover, .directorist-form-social-fields__remove:hover, .directorist-btn.directorist-btn-danger:hover {
        background: rgba(var(--directorist-color-danger-rgb),.80) !important;
    }

    /* Border Color */
    #directorist.atbd_wrapper .btn-danger, .directorist-btn.directorist-btn-danger {
        border-color: var(--directorist-color-danger) !important;
    }

    /* Hover Border Color */
    #directorist.atbd_wrapper .btn-danger:hover, .directorist-btn.directorist-btn-danger:hover {
        border-color: var(--directorist-color-danger) !important;
    }


    /* =======================================
     Button: Success
    ======================================== */
    /* Color */
    #directorist.atbd_wrapper .btn-success {
        color: var(--directorist-color-white) !important;
    }

    /* color hover */
    #directorist.atbd_wrapper .btn-success:hover {
        color: var(--directorist-color-white) !important;
    }

    /* Background */
    #directorist.atbd_wrapper .btn-success {
        background: var(--directorist-color-success) !important;
    }

    /* Hover Background */
    #directorist.atbd_wrapper .btn-success:hover {
        background: rgba(var(--directorist-color-success-rgb),.80) !important;
    }

    /* Border Color */
    #directorist.atbd_wrapper .btn-success {
        border-color: var(--directorist-color-success) !important;
    }

    /* Hover Border Color */
    #directorist.atbd_wrapper .btn-success:hover {
        border-color: var(--directorist-color-success) !important;
    }

    /* =======================================
     Button: primary outline
    ======================================== */

    /* color */
    .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter,
    #directorist.atbd_wrapper .btn-outline-primary,
    .atbd_dropdown .atbd_dropdown-toggle, .directorist-btn.directorist-btn-outline-dark, .directorist-btn.directorist-btn-outline-primary {
        color: var(--directorist-color-primary) !important;
    }

    /* color hover */
    .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter:hover,
    #directorist.atbd_wrapper .btn-outline-primary:hover,
    .atbd_dropdown .atbd_dropdown-toggle:hover, .directorist-btn.directorist-btn-outline-dark:hover, .directorist-btn.directorist-btn-outline-primary:hover {
        color: var(--directorist-color-primary) !important;
    }

    /* border color */
    .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter,
    #directorist.atbd_wrapper .btn-outline-primary,
    .atbd_dropdown .atbd_dropdown-toggle, .directorist-btn.directorist-btn-outline-dark, .directorist-btn.directorist-btn-outline-primary {
        border: 1px solid var(--directorist-color-primary) !important;
    }

    .atbd_dropdown .atbd_dropdown-toggle .atbd_drop-caret:before {
        border-left: 1px solid var(--directorist-color-primary) !important;
        border-bottom: 1px solid var(--directorist-color-primary) !important;
    }

    /* border color hover */
    .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter:hover,
    #directorist.atbd_wrapper .btn-outline-primary:hover,
    .atbd_dropdown .atbd_dropdown-toggle:hover, .directorist-btn.directorist-btn-outline-dark:hover, .directorist-btn.directorist-btn-outline-primary:hover {
        border-color: rgba(var(--directorist-color-primary-rgb),.80) !important;
    }

    .atbd_dropdown .atbd_dropdown-toggle:hover .atbd_drop-caret:before {
        border-left-color: rgba(var(--directorist-color-primary-rgb),.80) !important;
        border-bottom-color: rgba(var(--directorist-color-primary-rgb),.80) !important;
    }

    /* background */
    .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter,
    #directorist.atbd_wrapper .btn-outline-primary,
    .atbd_dropdown .atbd_dropdown-toggle, .directorist-btn.directorist-btn-outline-dark, .directorist-btn.directorist-btn-outline-primary {
        background: transparent !important;
    }

    /* background hover */
    .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter:hover,
    #directorist.atbd_wrapper .btn-outline-primary:hover,
    .atbd_dropdown .atbd_dropdown-toggle:hover, .directorist-btn.directorist-btn-outline-dark:hover, .directorist-btn.directorist-btn-outline-primary:hover {
        background: transparent !important;
    }

    /* =======================================
     Button: LIGHT Outline
    ======================================== */

    /* color */
    .atbdp_float_none .btn.btn-outline-light,
    .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action {
        color: var(--directorist-color-dark) !important;
    }

    /* color hover */
    .atbdp_float_none .btn.btn-outline-light:hover,
    .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action:hover {
        color: var(--directorist-color-dark) !important;
    }

    /* border color */
    .atbdp_float_none .btn.btn-outline-light {
        border: 1px solid var(--directorist-color-light) !important;
    }

    /* border color hover */
    .atbdp_float_none .btn.btn-outline-light:hover{
        border-color: var(--directorist-color-light) !important;
    }

    /* background */
    .atbdp_float_none .btn.btn-outline-light{
        background: var(--directorist-color-light) !important;
    }

    /* background hover */
    .atbdp_float_none .btn.btn-outline-light:hover{
        background: var(--directorist-color-light-hover) !important;
    }

    /* =======================================
     Button: Danger outline
    ======================================== */

    /* color */
    #directorist.atbd_wrapper .btn-outline-danger {
        color: <?php echo $danout_text_color; ?> !important;
    }

    /* color hover */
    #directorist.atbd_wrapper .btn-outline-danger:hover {
        color: <?php echo $danout_hover_color; ?> !important;
    }

    /* border color */
    #directorist.atbd_wrapper .btn-outline-danger {
        border: 1px solid <?php echo $border_danout_color; ?> !important;
    }

    /* border color hover */
    #directorist.atbd_wrapper .btn-outline-danger:hover {
        border-color: <?php echo $border_danout_hover_color; ?> !important;
    }

    /* background */
    #directorist.atbd_wrapper .btn-outline-danger {
        background: <?php echo $back_danout_color; ?> !important;
    }

    /* background hover */
    #directorist.atbd_wrapper .btn-outline-danger:hover {
        background: <?php echo $back_danout_hover_color; ?> !important;
    }

    /* =======================================
     Button: Lighter
    ======================================== */

    /* color */
    .directorist-btn.directorist-btn-lighter {
        color: <?php echo $lighter_text_color; ?> !important;
    }

    /* color hover */
    .directorist-btn.directorist-btn-lighter:hover {
        color: <?php echo $lighter_hover_color; ?> !important;
    }

    /* border color */
    .directorist-btn.directorist-btn-lighter {
        border: 1px solid <?php echo $border_lighter_color; ?> !important;
    }

    /* border color hover */
    .directorist-btn.directorist-btn-lighter:hover {
        border-color: <?php echo $border_lighter_hover_color; ?> !important;
    }

    /* background */
    .directorist-btn.directorist-btn-lighter {
        background: <?php echo $back_lighter_color; ?> !important;
    }

    /* background hover */
    .directorist-btn.directorist-btn-lighter:hover {
        background: <?php echo $back_lighter_hover_color; ?> !important;
    }

    /*
        Badge Colors
          - Badge Open
          - Badge Closed
          - Badge Featured
          - Badge Popular
          - Badge New
    */
    /* Badge Open */
    .atbd_bg-success i::after,
    .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_open i::after,
    .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_open i::after,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_open i::after,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_open i::after, .directorist-badge-open {
        background: var(--directorist-color-open-badge) !important;
    }

    /* Badge Closed */
    .atbd_bg-danger i::after,
    .atbd_content_active #directorist.atbd_wrapper .atbd_give_review_area #atbd_up_preview .atbd_up_prev .rmrf:hover i::after,
    .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_close i::after,
    .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_close i::after,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_close i::after,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_close i::after, .directorist-badge-close, .directorist-badge.directorist-badge-danger   {
        background: var(--directorist-color-closed-badge) !important;
    }

    /* Badge Featured */
    .atbd_bg-badge-feature i::after,
    .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_featured i::after,
    .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_featured i::after,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_featured i::after,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_featured i::after, .directorist-listing-single .directorist-badge.directorist-badge-featured i::after {
        background: var(directorist-color-featured-badge) !important;
    }

    /* Badge Popular */
    .atbd_bg-badge-popular i::after,
    .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_popular i::after,
    .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_popular i::after,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_popular i::after,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_popular i::after, .directorist-listing-single .directorist-badge.directorist-badge-popular i::after {
        background: var(--directorist-color-popular-badge) !important;
    }

    /* Badge New */
    .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_new, .directorist-listing-single .directorist-badge.directorist-badge-new i::after {
        background: var(--directorist-color-badge-new) !important;
    }

    /*
        Change default primary dark background
    */
    .ads-advanced .price-frequency .pf-btn input:checked+span,
    .atbdpr-range .ui-slider-horizontal .ui-slider-range,
    .custom-control .custom-control-input:checked~.check--select,
    #directorist.atbd_wrapper .pagination .nav-links .current,
    .atbd_director_social_wrap a,
    .widget.atbd_widget[id^=bd] .atbd_author_info_widget .atbd_social_wrap p a,
    .widget.atbd_widget[id^=dcl] .atbd_author_info_widget .atbd_social_wrap p a,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp-widget-categories>ul.atbdp_parent_category>li:hover>a span,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp.atbdp-widget-tags ul li a:hover {
        background: var(--directorist-color-primary) !important;
    }

    /*
        Change default primary dark border
    */
    .ads-advanced .price-frequency .pf-btn input:checked+span,
    .directorist-content-active .directorist-type-nav__list .directorist-type-nav__list__current .directorist-type-nav__link,
    .atbdpr-range .ui-slider-horizontal .ui-slider-handle,
    .custom-control .custom-control-input:checked~.check--select,
    .custom-control .custom-control-input:checked~.radio--select,
    #atpp-plan-change-modal .atm-contents-inner .dcl_pricing_plan input:checked+label:before,
    #dwpp-plan-renew-modal .atm-contents-inner .dcl_pricing_plan input:checked+label:before {
        border-color: var(--directorist-color-primary) !important;
    }

    /*
        Map Marker Icon Colors
    */
    /* Marker Shape color */
    .atbd_map_shape {
        background: var(--directorist-color-marker-shape) !important;
    }

    .atbd_map_shape:before {
        border-top-color: var(--directorist-color-marker-shape) !important;
    }

    /* Marker icon color */
    .map-icon-label i,
    .atbd_map_shape>span {
        color: var(--directorist-color-marker-icon) !important;
    }
</style>