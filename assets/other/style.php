<?php
do_action('include_style_settings');
$button_primary_color             = get_directorist_option('button_primary_color', '#ffffff');
$button_primary_bg_color          = get_directorist_option('button_primary_bg_color', '#000000');
$button_secondary_color           = get_directorist_option('button_secondary_color', '#000000');
$button_secondary_bg_color        = get_directorist_option('button_secondary_bg_color', '#f2f3f5');

$open_badge_color                 = get_directorist_option('open_back_color', '#28A800');
$closed_badge_color               = get_directorist_option('closed_back_color', '#e23636');
$featured_badge_color             = get_directorist_option('featured_back_color', '#fa8b0c');
$popular_badge_color              = get_directorist_option('popular_back_color', '#f51957');
$new_badge_color                  = get_directorist_option('new_back_color', '#2C99FF');

$marker_shape_color               = get_directorist_option('marker_shape_color', '#000000');
$marker_icon_color                = get_directorist_option('marker_icon_color', '#ffffff');

$primary_color                    = get_directorist_option( 'brand_color', '#000000' );
$secondary_color                  = get_directorist_option('color_secondary', '#F2F3F5');
$dark_color                       = get_directorist_option('color_dark', '#000000');
$white_color                      = get_directorist_option('color_white', '#ffffff');
$success_color                    = get_directorist_option('color_success', '#28A800');
$info_color                       = get_directorist_option('color_info', '#2c99ff');
$warning_color                    = get_directorist_option('color_warning', '#f28100');
$danger_color                     = get_directorist_option('color_danger', '#f80718');
$gray_color                       = get_directorist_option('color_gray', '#bcbcbc');

$button_primary_color_rgb         = directorist_hex_to_rgb( get_directorist_option( 'button_primary_bg_color', '#000000' ) );
$button_secondary_color_rgb         = directorist_hex_to_rgb( get_directorist_option( 'button_secondary_bg_color', '#000000' ) );
$primary_color_rgb    = directorist_hex_to_rgb( get_directorist_option( 'brand_color', '#000000' ) );
$secondary_color_rgb  = directorist_hex_to_rgb( get_directorist_option( 'color_secondary', '#F2F3F5' ) );
$dark_color_rgb       = directorist_hex_to_rgb( get_directorist_option( 'color_dark', '#000000' ) );
$white_color_rgb      = directorist_hex_to_rgb( get_directorist_option( 'color_white', '#ffffff' ) );
$success_color_rgb    = directorist_hex_to_rgb( get_directorist_option( 'color_success', '#28A800' ) );
$info_color_rgb       = directorist_hex_to_rgb( get_directorist_option( 'color_info', '#2c99ff' ) );
$warning_color_rgb    = directorist_hex_to_rgb( get_directorist_option( 'color_warning', '#f28100' ) );
$danger_color_rgb     = directorist_hex_to_rgb( get_directorist_option( 'color_danger', '#f80718' ) );
$gray_color_rgb       = directorist_hex_to_rgb( get_directorist_option( 'color_gray', '#bcbcbc' ) );
?>
<style>
    /* Css Variable */
    :root {
        /* theme color */
        --directorist-color-primary: <?php echo $primary_color; ?>;
        --directorist-color-primary-rgb: <?php echo $primary_color_rgb ?? '0,0,0'; ?>;
        --directorist-color-secondary: <?php echo $secondary_color; ?>;
        --directorist-color-secondary-rgb: <?php echo $secondary_color_rgb ?? '242,243,245'; ?>;
        --directorist-color-dark: <?php echo $dark_color; ?>;
        --directorist-color-dark-rgb: <?php echo $dark_color_rgb ?? '0,0,0'; ?>;
        --directorist-color-success: <?php echo $success_color; ?>;
        --directorist-color-success-rgb: <?php echo $success_color_rgb ?? '40,168,0'; ?>;
        --directorist-color-info: <?php echo $info_color; ?>;
        --directorist-color-info-rgb: <?php echo $info_color_rgb ?? '44,153,255'; ?>;
        --directorist-color-warning: <?php echo $warning_color; ?>;
        --directorist-color-warning-rgb: <?php echo $warning_color_rgb ?? '242,129,0'; ?>;
        --directorist-color-danger: <?php echo $danger_color; ?>;
        --directorist-color-danger-rgb: <?php echo $danger_color_rgb ?? '248,7,24'; ?>;
        --directorist-color-white: <?php echo $white_color; ?>;
        --directorist-color-white-rgb: 255,255,255;
        --directorist-color-body: #404040;
        --directorist-color-gray: <?php echo $gray_color; ?>;
        --directorist-color-gray-rgb: <?php echo $gray_color_rgb ?? '188,188,188'; ?>;
        --directorist-color-gray-hover: #BCBCBC;
        --directorist-color-light: #ededed;
        --directorist-color-light-hover: #ffffff;
        --directorist-color-light-gray: #808080;
        --directorist-color-light-gray-rgb: 237, 237, 237;
        --directorist-color-deep-gray: #808080;
        --directorist-color-bg-gray: #f4f4f4;
        --directorist-color-bg-light-gray: #F4F5F6;
        --directorist-color-bg-light: #EDEDED;
        --directorist-color-placeholder: #6c757d;

        /* other color */
        --directorist-color-overlay: rgba(0,0,0,0.5);
        --directorist-color-overlay-normal: rgba(0,0,0,0.2);
        --directorist-color-border: #e9e9e9;
        --directorist-color-border-gray: #d9d9d9;

        --directorist-box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        --directorist-box-shadow-sm: 0 2px 10px rgba(0,0,0,0.1);

        /* Badge Color */
        --directorist-color-open-badge: <?php echo $open_badge_color; ?>;
        --directorist-color-closed-badge: <?php echo $closed_badge_color; ?>;
        --directorist-color-featured-badge: <?php echo $featured_badge_color; ?>;
        --directorist-color-popular-badge: <?php echo $popular_badge_color; ?>;
        --directorist-color-new-badge: <?php echo $new_badge_color; ?>;

        /* Map marker Color */
        --directorist-color-marker-shape: <?php echo $marker_shape_color; ?>;
        --directorist-color-marker-icon: <?php echo $marker_icon_color; ?>;

        /* Font config */
        --directorist-fw-regular: 400;
        --directorist-fw-medium: 500;
        --directorist-fw-semiBold: 600;
        --directorist-fw-bold: 700;

        /* Border Radius */
        --directorist-border-radius-xs: 8px;
        --directorist-border-radius-sm: 10px;
        --directorist-border-radius-md: 12px;
        --directorist-border-radius-lg: 16px;

        /* Button */
        --directorist-color-btn:var(--directorist-color-primary);
        --directorist-color-btn-hover:rgba(var(--directorist-color-primary-rgb),.80);
        --directorist-color-btn-border:var(--directorist-color-primary);

        --directorist-color-btn-primary:<?php echo $button_primary_color; ?>;
        --directorist-color-btn-primary-rgb: <?php echo $button_primary_color_rgb ?? '0,0,0'; ?>;
        --directorist-color-btn-primary-bg:<?php echo $button_primary_bg_color; ?>;
        --directorist-color-btn-primary-border:<?php echo $button_primary_bg_color; ?>;
        --directorist-color-btn-secondary:<?php echo $button_secondary_color; ?>;
        --directorist-color-btn-secondary-rgb:<?php echo $button_secondary_color_rgb ?? '0,0,0'; ?>;
        --directorist-color-btn-secondary-bg:<?php echo $button_secondary_bg_color; ?>;
        --directorist-color-btn-secondary-border:<?php echo $button_secondary_bg_color; ?>;

        /* Star Color */
        --directorist-color-star:var(--directorist-color-warning);
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
    1. Primary
        - Color
        - Hover Color
        - BG Color
    3. Solid Secondary
        - Color
        - Hover Color
        - BG Color
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
    #directorist.atbd_wrapper .ezmu__btn, .default-ad-search .submit_btn .btn-primary, .directorist-content-active .widget.atbd_widget .directorist .btn, .directorist-btn.directorist-btn-dark, .atbd-add-payment-method form .atbd-save-card, #bhCopyTime, #bhAddNew, .directorist-content-active .widget.atbd_widget .atbd_author_info_widget .btn {
        color: var(--directorist-color-white);
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
    #directorist.atbd_wrapper .ezmu__btn:hover, .default-ad-search .submit_btn .btn-primary:hover, .directorist-content-active .widget.atbd_widget .directorist .btn:hover, .directorist-btn.directorist-btn-dark:hover, .atbd-add-payment-method form .atbd-save-card:hover, #bhCopyTime:hover, #bhAddNew:hover, .bdb-select-hours .bdb-remove:hover, .directorist-content-active .widget.atbd_widget .atbd_author_info_widget .btn:hover {
        color: var(--directorist-color-white);
    }

    /* Background */
    .pricing .price_action .price_action--btn,
    #directorist.atbd_wrapper .btn-primary,
    .default-ad-search .submit_btn .btn-default,
    .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic,
    #directorist.atbd_wrapper .at-modal .at-modal-close,
    .atbdp_login_form_shortcode #loginform p input[type="submit"],
    .atbd_manage_fees_wrapper .table tr .action p .btn-block,
    #directorist.atbd_wrapper .ezmu__btn, .default-ad-search .submit_btn .btn-primary .directorist-content-active .widget.atbd_widget .directorist .btn, .directorist-btn.directorist-btn-dark, .atbd-add-payment-method form .atbd-save-card, #bhCopyTime, #bhAddNew, .bdb-select-hours .bdb-remove, .directorist-content-active .widget.atbd_widget .atbd_author_info_widget .btn {
        background: var(--directorist-color-btn-primary-bg);
    }

    /* Hover Background */
    .pricing .price_action .price_action--btn:hover,
    #directorist.atbd_wrapper .btn-primary:hover,
    #directorist.atbd_wrapper .at-modal .at-modal-close:hover,
    .default-ad-search .submit_btn .btn-default:hover,
    .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic:hover,
    .atbdp_login_form_shortcode #loginform p input[type="submit"]:hover,
    #directorist.atbd_wrapper .ezmu__btn:hover, .default-ad-search .submit_btn .btn-primary:hover, .directorist-content-active .widget.atbd_widget .directorist .btn:hover, .directorist-btn.directorist-btn-dark:hover, .atbd-add-payment-method form .atbd-save-card:hover, #bhCopyTime:hover, #bhAddNew:hover, .bdb-select-hours .bdb-remove:hover, .directorist-content-active .widget.atbd_widget .atbd_author_info_widget .btn:hover {
        background: var(--directorist-color-btn-hover);
    }

    /* Border Color */
    #directorist.atbd_wrapper .btn-primary,
    .default-ad-search .submit_btn .btn-default,
    .atbdp_login_form_shortcode #loginform p input[type="submit"], .default-ad-search .submit_btn .btn-primary, .directorist-content-active .widget.atbd_widget .directorist .btn, .atbd-add-payment-method form .atbd-save-card, .directorist-content-active .widget.atbd_widget .atbd_author_info_widget .btn {
        border-color: var(--directorist-color-btn-border);
    }

    /* Hover Border Color */
    #directorist.atbd_wrapper .btn-primary:hover,
    .default-ad-search .submit_btn .btn-default:hover,
    .atbdp_login_form_shortcode #loginform p input[type="submit"]:hover, .default-ad-search .submit_btn .btn-primary:hover, .directorist-content-active .widget.atbd_widget .directorist .btn:hover, .atbd-add-payment-method form .atbd-save-card:hover, .directorist-content-active .widget.atbd_widget .atbd_author_info_widget .btn:hover {
        border-color: var(--directorist-color-primary);
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
        background: var(--directorist-color-featured-badge) !important;
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
        background: var(--directorist-color-new-badge) !important;
    }

    /*
        Change default primary dark background
    */
    .ads-advanced .price-frequency .pf-btn input:checked+span,
    .atbdpr-range .ui-slider-horizontal .ui-slider-range,
    .custom-control .custom-control-input:checked~.check--select,
    #directorist.atbd_wrapper .pagination .nav-links .current,
    .atbd_director_social_wrap a,
    .widget.atbd_widget[id^=bd] .atbd_author_info_widget .directorist-author-social li a,
    .widget.atbd_widget[id^=dcl] .atbd_author_info_widget .directorist-author-social li a,
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
        border-color: var(--directorist-color-primary);
    }

    /*
        Map Marker Icon Colors
    */
    /* Marker Shape color */
    .atbd_map_shape {
        background: var(--directorist-color-marker-shape) !important;
    }

    /* Marker icon color */
    .map-icon-label i,
    .atbd_map_shape>span {
        color: var(--directorist-color-marker-icon) !important;
    }
</style>