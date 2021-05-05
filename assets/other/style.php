<?php
do_action('include_style_settings');
$primary_color                    = get_directorist_option('primary_color', '#ffffff');
$primary_hover_color              = get_directorist_option('primary_hover_color', '#ffffff');
$back_primary_color               = get_directorist_option('back_primary_color', '#444752');
$back_primary_hover_color         = get_directorist_option('back_primary_hover_color', '#222222');
$border_primary_color             = get_directorist_option('border_primary_color', '#444752');
$border_primary_hover_color       = get_directorist_option('border_primary_hover_color', '#222222');
$secondary_color                  = get_directorist_option('secondary_color', '#fff');
$secondary_hover_color            = get_directorist_option('secondary_hover_color', '#fff');
$back_secondary_color             = get_directorist_option('back_secondary_color', '#122069');
$back_secondary_hover_color       = get_directorist_option('back_secondary_hover_color', '#131469');
$secondary_border_color           = get_directorist_option('secondary_border_color', '#131469');
$secondary_border_hover_color     = get_directorist_option('secondary_border_hover_color', '#131469');
$danger_color                     = get_directorist_option('danger_color', '#fff');
$danger_hover_color               = get_directorist_option('danger_hover_color', '#fff');
$back_danger_color                = get_directorist_option('back_danger_color', '#e23636');
$back_danger_hover_color          = get_directorist_option('back_danger_hover_color', '#c5001e');
$danger_border_color              = get_directorist_option('danger_border_color', '#e23636');
$danger_border_hover_color        = get_directorist_option('danger_border_hover_color', '#c5001e');
$success_color                    = get_directorist_option('success_color', '#fff');
$success_hover_color              = get_directorist_option('success_hover_color', '#fff');
$back_success_color               = get_directorist_option('back_success_color', '#32cc6f');
$back_success_hover_color         = get_directorist_option('back_success_hover_color', '#2ba251');
$border_success_color             = get_directorist_option('border_success_color', '#32cc6f');
$border_success_hover_color       = get_directorist_option('border_success_hover_color', '#2ba251');
$lighter_color                    = get_directorist_option('lighter_color', '#1A1B29');
$lighter_hover_color              = get_directorist_option('lighter_hover_color', '#1A1B29');
$back_lighter_color               = get_directorist_option('back_lighter_color', '#F6F7F9');
$back_lighter_hover_color         = get_directorist_option('lighter_color', '#F6F7F9');
$border_lighter_color             = get_directorist_option('border_lighter_color', '#F6F7F9');
$border_lighter_hover_color       = get_directorist_option('border_lighter_hover_color', '#F6F7F9');
$priout_color                     = get_directorist_option('priout_color', '#444752');
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
$danout_color                     = get_directorist_option('danout_color', '#e23636');
$danout_hover_color               = get_directorist_option('danout_hover_color', '#fff');
$back_danout_color                = get_directorist_option('back_danout_color', '#fff');
$back_danout_hover_color          = get_directorist_option('back_danout_hover_color', '#e23636');
$border_danout_color              = get_directorist_option('border_danout_color', '#e23636');
$border_danout_hover_color        = get_directorist_option('border_danout_hover_color', '#e23636');
$open_back_color                  = get_directorist_option('open_back_color', '#32cc6f');
$closed_back_color                = get_directorist_option('closed_back_color', '#e23636');
$featured_back_color              = get_directorist_option('featured_back_color', '#fa8b0c');
$popular_back_color               = get_directorist_option('popular_back_color', '#f51957');
$new_back_color                   = get_directorist_option('new_back_color', '#122069');
$primary_dark_back_color          = get_directorist_option('primary_dark_back_color', '#444752');
$primary_dark_border_color        = get_directorist_option('primary_dark_border_color', '#444752');
$marker_shape_color               = get_directorist_option('marker_shape_color', '#444752');
$marker_icon_color                = get_directorist_option('marker_icon_color', '#444752');
?>
<style>
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
        color: <?php echo !empty($primary_color) ? $primary_color : '#fff'; ?> !important;
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
        color: <?php echo !empty($primary_hover_color) ? $primary_hover_color : '#fff'; ?> !important;
    }

    /* Background */
    .pricing .price_action .price_action--btn,
    #directorist.atbd_wrapper .btn-primary,
    .default-ad-search .submit_btn .btn-default,
    .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic,
    #directorist.atbd_wrapper .at-modal .at-modal-close,
    .atbdp_login_form_shortcode #loginform p input[type="submit"],
    .atbd_manage_fees_wrapper .table tr .action p .btn-block,
    #directorist.atbd_wrapper #atbdp-checkout-form #atbdp_checkout_submit_btn,
    #directorist.atbd_wrapper .ezmu__btn, .default-ad-search .submit_btn .btn-primary, .directorist-btn.directorist-btn-primary, .directorist-content-active .widget.atbd_widget .directorist .btn, .directorist-btn.directorist-btn-dark, .atbd-add-payment-method form .atbd-save-card, #bhCopyTime, #bhAddNew, .bdb-select-hours .bdb-remove, .directorist-form-image-upload-field .ezmu__btn.ezmu__input-label, .directorist-content-active .widget.atbd_widget .atbd_author_info_widget .btn {
        background: <?php echo !empty($back_primary_color) ? $back_primary_color : '#444752'; ?> !important;
    }

    /* Hover Background */
    .pricing .price_action .price_action--btn:hover,
    #directorist.atbd_wrapper .btn-primary:hover,
    #directorist.atbd_wrapper .at-modal .at-modal-close:hover,
    .default-ad-search .submit_btn .btn-default:hover,
    .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic:hover,
    .atbdp_login_form_shortcode #loginform p input[type="submit"]:hover,
    #directorist.atbd_wrapper .ezmu__btn:hover, .default-ad-search .submit_btn .btn-primary:hover, .directorist-btn.directorist-btn-primary:hover, .directorist-content-active .widget.atbd_widget .directorist .btn:hover, .directorist-btn.directorist-btn-dark:hover, .atbd-add-payment-method form .atbd-save-card:hover, #bhCopyTime:hover, #bhAddNew:hover, .bdb-select-hours .bdb-remove:hover, .directorist-form-image-upload-field .ezmu__btn.ezmu__input-label:hover, .directorist-content-active .widget.atbd_widget .atbd_author_info_widget .btn:hover {
        background: <?php echo !empty($back_primary_hover_color) ? $back_primary_hover_color : '#222222'; ?> !important;
    }

    /* Border Color */
    #directorist.atbd_wrapper .btn-primary,
    .default-ad-search .submit_btn .btn-default,
    .atbdp_login_form_shortcode #loginform p input[type="submit"], .default-ad-search .submit_btn .btn-primary, .directorist-btn.directorist-btn-primary, .directorist-content-active .widget.atbd_widget .directorist .btn, .atbd-add-payment-method form .atbd-save-card, .directorist-content-active .widget.atbd_widget .atbd_author_info_widget .btn {
        border-color: <?php echo !empty($border_primary_color) ? $border_primary_color : '#444752'; ?> !important;
    }

    /* Hover Border Color */
    #directorist.atbd_wrapper .btn-primary:hover,
    .default-ad-search .submit_btn .btn-default:hover,
    .atbdp_login_form_shortcode #loginform p input[type="submit"]:hover, .default-ad-search .submit_btn .btn-primary:hover, .directorist-btn.directorist-btn-primary:hover, .directorist-content-active .widget.atbd_widget .directorist .btn:hover, .atbd-add-payment-method form .atbd-save-card:hover, .directorist-content-active .widget.atbd_widget .atbd_author_info_widget .btn:hover {
        border-color: <?php echo !empty($border_primary_hover_color) ? $border_primary_hover_color : '#222222'; ?> !important;
    }

    /* =======================================
     Button: Secondary
    ======================================== */
    /* Color */
    #directorist.atbd_wrapper .btn-secondary, .directorist-btn.directorist-btn-secondary {
        color: <?php echo !empty($secondary_color) ? $secondary_color : '#fff'; ?> !important;
    }

    #directorist.atbd_wrapper .btn-secondary:hover, .directorist-btn.directorist-btn-secondary:hover {
        color: <?php echo !empty($secondary_hover_color) ? $secondary_hover_color : '#fff'; ?> !important;
    }

    /* Background */
    #directorist.atbd_wrapper .btn-secondary, .directorist-btn.directorist-btn-secondary {
        background: <?php echo !empty($back_secondary_color) ? $back_secondary_color : '#122069'; ?> !important;
    }

    /* Hover Background */
    #directorist.atbd_wrapper .btn-secondary:hover, .directorist-btn.directorist-btn-secondary:hover {
        background: <?php echo !empty($back_secondary_hover_color) ? $back_secondary_hover_color : '#131469'; ?> !important;
    }

    /* Border Color */
    #directorist.atbd_wrapper .btn-secondary, .directorist-btn.directorist-btn-secondary {
        border-color: <?php echo !empty($secondary_border_color) ? $secondary_border_color : '#131469'; ?> !important;
    }

    /* Hover Border Color */
    #directorist.atbd_wrapper .btn-secondary:hover, .directorist-btn.directorist-btn-secondary:hover {
        border-color: <?php echo !empty($secondary_border_hover_color) ? $secondary_border_hover_color : '#131469'; ?> !important;
    }


    /* =======================================
     Button: Danger
    ======================================== */
    /* Color*/
    #directorist.atbd_wrapper .btn-danger,
    .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img #remove_pro_pic,
    .sweet-alert button.confirm, .directorist-form-social-fields__remove, .directorist-btn.directorist-btn-danger {
        color: <?php echo !empty($danger_color) ? $danger_color : '#fff'; ?> !important;
    }

    /* color hover */
    #directorist.atbd_wrapper .btn-danger:hover,
    .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img #remove_pro_pic,
    .sweet-alert button.confirm:hover, .directorist-form-social-fields__remove:hover, .directorist-btn.directorist-btn-danger:hover {
        color: <?php echo !empty($danger_hover_color) ? $danger_hover_color : '#fff'; ?> !important;
    }

    /* Background */
    #directorist.atbd_wrapper .btn-danger,
    .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img #remove_pro_pic,
    .sweet-alert button.confirm, .directorist-form-social-fields__remove, .directorist-btn.directorist-btn-danger {
        background: <?php echo !empty($back_danger_color) ? $back_danger_color : '#e23636'; ?> !important;
    }

    /* Hover Background */
    #directorist.atbd_wrapper .btn-danger:hover,
    .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img #remove_pro_pic:hover,
    .sweet-alert button.confirm:hover, .directorist-form-social-fields__remove:hover, .directorist-btn.directorist-btn-danger:hover {
        background: <?php echo !empty($back_danger_hover_color) ? $back_danger_hover_color : '#c5001e'; ?> !important;
    }

    /* Border Color */
    #directorist.atbd_wrapper .btn-danger, .directorist-btn.directorist-btn-danger {
        border-color: <?php echo !empty($danger_border_color) ? $danger_border_color : '#e23636'; ?> !important;
    }

    /* Hover Border Color */
    #directorist.atbd_wrapper .btn-danger:hover, .directorist-btn.directorist-btn-danger:hover {
        border-color: <?php echo !empty($danger_border_hover_color) ? $danger_border_hover_color : '#c5001e'; ?> !important;
    }


    /* =======================================
     Button: Success
    ======================================== */
    /* Color */
    #directorist.atbd_wrapper .btn-success {
        color: <?php echo !empty($success_color) ? $success_color : '#fff'; ?> !important;
    }

    /* color hover */
    #directorist.atbd_wrapper .btn-success:hover {
        color: <?php echo !empty($success_hover_color) ? $success_hover_color : '#fff'; ?> !important;
    }

    /* Background */
    #directorist.atbd_wrapper .btn-success {
        background: <?php echo !empty($back_success_color) ? $back_success_color : '#32cc6f'; ?> !important;
    }

    /* Hover Background */
    #directorist.atbd_wrapper .btn-success:hover {
        background: <?php echo !empty($back_success_hover_color) ? $back_success_hover_color : '#2ba251'; ?> !important;
    }

    /* Border Color */
    #directorist.atbd_wrapper .btn-success {
        border-color: <?php echo !empty($border_success_color) ? $border_success_color : '#32cc6f'; ?> !important;
    }

    /* Hover Border Color */
    #directorist.atbd_wrapper .btn-success:hover {
        border-color: <?php echo !empty($border_success_hover_color) ? $border_success_hover_color : '#2ba251'; ?> !important;
    }

    /* =======================================
     Button: primary outline
    ======================================== */

    /* color */
    .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter,
    #directorist.atbd_wrapper .btn-outline-primary,
    .atbd_dropdown .atbd_dropdown-toggle, .directorist-btn.directorist-btn-outline-dark, .directorist-btn.directorist-btn-outline-primary {
        color: <?php echo !empty($priout_color) ? $priout_color : '#444752'; ?> !important;
    }

    /* color hover */
    .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter:hover,
    #directorist.atbd_wrapper .btn-outline-primary:hover,
    .atbd_dropdown .atbd_dropdown-toggle:hover, .directorist-btn.directorist-btn-outline-dark:hover, .directorist-btn.directorist-btn-outline-primary:hover {
        color: <?php echo !empty($priout_hover_color) ? $priout_hover_color : '#444752'; ?> !important;
    }

    /* border color */
    .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter,
    #directorist.atbd_wrapper .btn-outline-primary,
    .atbd_dropdown .atbd_dropdown-toggle, .directorist-btn.directorist-btn-outline-dark, .directorist-btn.directorist-btn-outline-primary {
        border: 1px solid <?php echo !empty($border_priout_color) ? $border_priout_color : '#444752'; ?> !important;
    }

    .atbd_dropdown .atbd_dropdown-toggle .atbd_drop-caret:before {
        border-left: 1px solid <?php echo !empty($border_priout_color) ? $border_priout_color : '#444752'; ?> !important;
        border-bottom: 1px solid <?php echo !empty($border_priout_color) ? $border_priout_color : '#444752'; ?> !important;
    }

    /* border color hover */
    .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter:hover,
    #directorist.atbd_wrapper .btn-outline-primary:hover,
    .atbd_dropdown .atbd_dropdown-toggle:hover, .directorist-btn.directorist-btn-outline-dark:hover, .directorist-btn.directorist-btn-outline-primary:hover {
        border-color: <?php echo !empty($border_priout_hover_color) ? $border_priout_hover_color : '#9299b8'; ?> !important;
    }

    .atbd_dropdown .atbd_dropdown-toggle:hover .atbd_drop-caret:before {
        border-left-color: <?php echo !empty($border_priout_hover_color) ? $border_priout_hover_color : '#9299b8'; ?> !important;
        border-bottom-color: <?php echo !empty($border_priout_hover_color) ? $border_priout_hover_color : '#9299b8'; ?> !important;
    }

    /* background */
    .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter,
    #directorist.atbd_wrapper .btn-outline-primary,
    .atbd_dropdown .atbd_dropdown-toggle, .directorist-btn.directorist-btn-outline-dark, .directorist-btn.directorist-btn-outline-primary {
        background: <?php echo !empty($back_priout_color) ? $back_priout_color : '#fff'; ?> !important;
    }

    /* background hover */
    .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter:hover,
    #directorist.atbd_wrapper .btn-outline-primary:hover,
    .atbd_dropdown .atbd_dropdown-toggle:hover, .directorist-btn.directorist-btn-outline-dark:hover, .directorist-btn.directorist-btn-outline-primary:hover {
        background: <?php echo !empty($back_priout_hover_color) ? $back_priout_hover_color : '#fff'; ?> !important;
    }

    /* =======================================
     Button: primary outline LIGHT
    ======================================== */

    /* color */
    .atbdp_float_none .btn.btn-outline-light,
    .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action,
    .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action a, .directorist-signle-listing-top__btn-edit.directorist-btn.directorist-btn-outline-light {
        color: <?php echo !empty($prioutlight_color) ? $prioutlight_color : '#444752'; ?> !important;
    }

    /* color hover */
    .atbdp_float_none .btn.btn-outline-light:hover,
    .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action:hover,
    .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action a:hover, .directorist-signle-listing-top__btn-edit.directorist-btn.directorist-btn-outline-light:hover {
        color: <?php echo !empty($prioutlight_hover_color) ? $prioutlight_hover_color : '#ffffff'; ?> !important;
    }

    /* border color */
    .atbdp_float_none .btn.btn-outline-light,
    .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action, .directorist-signle-listing-top__btn-edit.directorist-btn.directorist-btn-outline-light {
        border: 1px solid <?php echo !empty($border_prioutlight_color) ? $border_prioutlight_color : '#e3e6ef'; ?> !important;
    }

    /* border color hover */
    .atbdp_float_none .btn.btn-outline-light:hover,
    .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action:hover, .directorist-signle-listing-top__btn-edit.directorist-btn.directorist-btn-outline-light:hover {
        border-color: <?php echo !empty($border_prioutlight_hover_color) ? $border_prioutlight_hover_color : '#444752'; ?> !important;
    }

    /* background */
    .atbdp_float_none .btn.btn-outline-light,
    .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action, .directorist-signle-listing-top__btn-edit.directorist-btn.directorist-btn-outline-light {
        background: <?php echo !empty($back_prioutlight_color) ? $back_prioutlight_color : '#fff'; ?> !important;
    }

    /* background hover */
    .atbdp_float_none .btn.btn-outline-light:hover,
    .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action:hover, .directorist-signle-listing-top__btn-edit.directorist-btn.directorist-btn-outline-light:hover {
        background: <?php echo !empty($back_prioutlight_hover_color) ? $back_prioutlight_hover_color : '#444752'; ?> !important;
    }


    /* =======================================
     Button: Danger outline
    ======================================== */

    /* color */
    #directorist.atbd_wrapper .btn-outline-danger {
        color: <?php echo !empty($danout_color) ? $danout_color : '#e23636'; ?> !important;
    }

    /* color hover */
    #directorist.atbd_wrapper .btn-outline-danger:hover {
        color: <?php echo !empty($danout_hover_color) ? $danout_hover_color : '#fff'; ?> !important;
    }

    /* border color */
    #directorist.atbd_wrapper .btn-outline-danger {
        border: 1px solid <?php echo !empty($border_danout_color) ? $border_danout_color : '#e23636'; ?> !important;
    }

    /* border color hover */
    #directorist.atbd_wrapper .btn-outline-danger:hover {
        border-color: <?php echo !empty($border_danout_hover_color) ? $border_danout_hover_color : '#e23636'; ?> !important;
    }

    /* background */
    #directorist.atbd_wrapper .btn-outline-danger {
        background: <?php echo !empty($back_danout_color) ? $back_danout_color : '#fff'; ?> !important;
    }

    /* background hover */
    #directorist.atbd_wrapper .btn-outline-danger:hover {
        background: <?php echo !empty($back_danout_hover_color) ? $back_danout_hover_color : '#e23636'; ?> !important;
    }

    /* =======================================
     Button: Lighter
    ======================================== */

    /* color */
    .directorist-btn.directorist-btn-lighter {
        color: <?php echo !empty($lighter_color) ? $lighter_color : '#1A1B29'; ?> !important;
    }

    /* color hover */
    .directorist-btn.directorist-btn-lighter:hover {
        color: <?php echo !empty($lighter_hover_color) ? $lighter_hover_color : '#1A1B29'; ?> !important;
    }

    /* border color */
    .directorist-btn.directorist-btn-lighter {
        border: 1px solid <?php echo !empty($border_lighter_color) ? $border_lighter_color : '#F6F7F9'; ?> !important;
    }

    /* border color hover */
    .directorist-btn.directorist-btn-lighter:hover {
        border-color: <?php echo !empty($border_lighter_hover_color) ? $border_lighter_hover_color : '#F6F7F9'; ?> !important;
    }

    /* background */
    .directorist-btn.directorist-btn-lighter {
        background: <?php echo !empty($back_lighter_color) ? $back_lighter_color : '#F6F7F9'; ?> !important;
    }

    /* background hover */
    .directorist-btn.directorist-btn-lighter:hover {
        background: <?php echo !empty($back_lighter_hover_color) ? $back_lighter_hover_color : '#F6F7F9'; ?> !important;
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
    .atbd_bg-success,
    .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_open,
    .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_open,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_open,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_open, .directorist-badge-open, .directorist-badge.directorist-badge-success {
        background: <?php echo !empty($open_back_color) ? $open_back_color : '#32cc6f'; ?> !important;
    }

    /* Badge Closed */
    .atbd_bg-danger,
    .atbd_content_active #directorist.atbd_wrapper .atbd_give_review_area #atbd_up_preview .atbd_up_prev .rmrf:hover,
    .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_close,
    .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_close,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_close,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_close, .directorist-badge.directorist-badge-danger, .directorist-listing-single .directorist-badge.directorist-badge-closejhg   {
        background: <?php echo !empty($closed_back_color) ? $closed_back_color : '#e23636'; ?> !important;
    }

    /* Badge Featured */
    .atbd_bg-badge-feature,
    .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_featured,
    .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_featured,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_featured,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_featured, .directorist-listing-single .directorist-badge.directorist-badge-featured {
        background: <?php echo !empty($featured_back_color) ? $featured_back_color : '#fa8b0c'; ?> !important;
    }

    /* Badge Popular */
    .atbd_bg-badge-popular,
    .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_popular,
    .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_popular,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_popular,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_popular, .directorist-listing-single .directorist-badge.directorist-badge-popular {
        background: <?php echo !empty($popular_back_color) ? $popular_back_color : '#f51957'; ?> !important;
    }

    /* Badge New */
    .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_new, .directorist-listing-single .directorist-badge.directorist-badge-new {
        background: <?php echo !empty($new_back_color) ? $new_back_color : '#122069'; ?> !important;
    }

    /*
        Change default primary dark background
    */
    .ads-advanced .price-frequency .pf-btn input:checked+span,
    .btn-checkbox label input:checked+span,
    .atbdpr-range .ui-slider-horizontal .ui-slider-range,
    .custom-control .custom-control-input:checked~.check--select,
    #directorist.atbd_wrapper .pagination .nav-links .current,
    .atbd_director_social_wrap a,
    .directorist-author-info-widget .directorist-author-social .directorist-author-social-item a, 
    .directorist-single-author-info .directorist-author-social .directorist-author-social-item a,
    .widget.atbd_widget[id^=bd] .atbd_author_info_widget .atbd_social_wrap p a, 
    .widget.atbd_widget[id^=dcl] .atbd_author_info_widget .atbd_social_wrap p a, 
    .widget.atbd_widget .atbd_author_info_widget .atbd_social_wrap p a,
    .directorist-mark-as-favorite__btn.directorist-added-to-favorite,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp-widget-categories>ul.atbdp_parent_category>li:hover>a span,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp.atbdp-widget-tags ul li a:hover {
        background: <?php echo !empty($primary_dark_back_color) ? $primary_dark_back_color : '#444752'; ?> !important;
    }

    /*
        Change default primary dark border
    */
    .ads-advanced .price-frequency .pf-btn input:checked+span,
    .btn-checkbox label input:checked+span,
    .directorist-content-active .directorist-type-nav__list .current .directorist-type-nav__link,
    .atbdpr-range .ui-slider-horizontal .ui-slider-handle,
    .custom-control .custom-control-input:checked~.check--select,
    .custom-control .custom-control-input:checked~.radio--select,
    #atpp-plan-change-modal .atm-contents-inner .dcl_pricing_plan input:checked+label:before,
    #dwpp-plan-renew-modal .atm-contents-inner .dcl_pricing_plan input:checked+label:before {
        border-color: <?php echo !empty($primary_dark_border_color) ? $primary_dark_border_color : '#444752'; ?> !important;
    }


    /*
        Map Marker Icon Colors
    */
    /* Marker Shape color */
    .atbd_map_shape {
        background: <?php echo !empty($marker_shape_color) ? $marker_shape_color : '#444752'; ?> !important;
    }

    .atbd_map_shape:before {
        border-top-color: <?php echo !empty($marker_shape_color) ? $marker_shape_color : '#444752'; ?> !important;
    }

    /* Marker icon color */
    .map-icon-label i,
    .atbd_map_shape>span {
        color: <?php echo !empty($marker_icon_color) ? $marker_icon_color : '#444752'; ?> !important;
    }
</style>