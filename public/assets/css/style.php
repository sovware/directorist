<?php
do_action('include_style_settings');
$primary_color                    = get_directorist_option('primary_color','#444752');
$back_primary_color               = get_directorist_option('back_primary_color','#444752');
$back_primary_hover_color         = get_directorist_option('back_primary_hover_color','#222222');
$border_primary_color             = get_directorist_option('border_primary_color','#444752');
$border_primary_hover_color       = get_directorist_option('border_primary_hover_color','#222222');
$back_secondary_color             = get_directorist_option('back_secondary_color','#122069');
$back_secondary_hover_color       = get_directorist_option('back_secondary_hover_color','#131469');
$secondary_border_color           = get_directorist_option('secondary_border_color','#122069');
$secondary_border_hover_color     = get_directorist_option('secondary_border_hover_color','#122069');
$danger_color                     = get_directorist_option('danger_color','#e23636');
$back_danger_color                = get_directorist_option('back_danger_color','#e23636');
$back_danger_hover_color          = get_directorist_option('back_danger_hover_color','#c5001e');
$border_danger_color              = get_directorist_option('border_danger_color','#e23636');
$border_danger_hover_color        = get_directorist_option('border_danger_hover_color','#c5001e');
$back_success_color               = get_directorist_option('back_success_color','#32cc6f');
$open_back_color                  = get_directorist_option('open_back_color','#32cc6f');
$closed_back_color                = get_directorist_option('closed_back_color','#e23636');
$featured_back_color              = get_directorist_option('featured_back_color','#fa8b0c');
$popular_back_color               = get_directorist_option('popular_back_color','#f51957');
$new_back_color                   = get_directorist_option('new_back_color','#122069');
$primary_dark_back_color          = get_directorist_option('primary_dark_back_color','#444752');
$primary_dark_border_color        = get_directorist_option('primary_dark_border_color','#444752');
$marker_shape_color               = get_directorist_option('marker_shape_color','#444752');
$marker_icon_color                = get_directorist_option('marker_icon_color','#444752');
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


    /* =======================================
     Button: Primary
    ======================================== */
    /* Color */
    /*#directorist.atbd_wrapper .btn-outline-primary{
        color: <?php echo !empty($primary_color) ? $primary_color : '#444752';?> !important;
    }*/

    /* Background */
    .pricing .price_action .price_action--btn, #directorist.atbd_wrapper .btn-primary, .default-ad-search .submit_btn .btn-default, /*#directorist.atbd_wrapper .btn-outline-primary:hover,*/ .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic, #directorist.atbd_wrapper .at-modal .at-modal-close, .atbdp_login_form_shortcode #loginform p input[type="submit"], .atbd_manage_fees_wrapper .table tr .action p .btn-block:hover, #directorist.atbd_wrapper #atbdp-checkout-form #atbdp_checkout_submit_btn{
        background: <?php echo !empty($back_primary_color) ? $back_primary_color : '#444752';?> !important;
    }

    /* Hover Background */
    .pricing .price_action .price_action--btn:hover, #directorist.atbd_wrapper .btn-primary:hover, #directorist.atbd_wrapper .at-modal .at-modal-close:hover, .default-ad-search .submit_btn .btn-default:hover, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic:hover, .atbdp_login_form_shortcode #loginform p input[type="submit"]:hover{
        background: <?php echo !empty($back_primary_hover_color) ? $back_primary_hover_color : '#222222';?> !important;
    }

    /* Border Color */
    #directorist.atbd_wrapper .btn-primary, .default-ad-search .submit_btn .btn-default, /*#directorist.atbd_wrapper .btn-outline-primary, #directorist.atbd_wrapper .btn-outline-primary:hover,*/ .atbdp_login_form_shortcode #loginform p input[type="submit"]{
        border-color: <?php echo !empty($border_primary_color) ? $border_primary_color : '#444752';?> !important;
    }

    /* Hover Border Color */
    #directorist.atbd_wrapper .btn-primary:hover, .default-ad-search .submit_btn .btn-default:hover, .atbdp_login_form_shortcode #loginform p input[type="submit"]:hover{
        border-color: <?php echo !empty($border_primary_hover_color) ? $border_primary_hover_color : '#222222';?> !important;
    }

    /* =======================================
     Button: Secondary
    ======================================== */
    /* Color */

    /* Background */
    #directorist.atbd_wrapper .btn-secondary {
        background: <?php echo !empty($back_secondary_color) ? $back_secondary_color : '#122069';?> !important;
    }

    /* Hover Background */
    #directorist.atbd_wrapper .btn-secondary:hover{
        background: <?php echo !empty($back_secondary_hover_color) ? $back_secondary_hover_color : '#131469';?> !important;
    }

    /* Border Color */
    #directorist.atbd_wrapper .btn-secondary {
        border-color: <?php echo !empty($secondary_border_color) ? $secondary_border_color : '#131469';?> !important;
    }

    /* Hover Border Color */
    #directorist.atbd_wrapper .btn-secondary:hover{
        border-color: <?php echo !empty($secondary_border_hover_color) ? $secondary_border_hover_color : '#131469';?> !important;
    }


    /* =======================================
     Button: Danger
    ======================================== */
    /* Color*/
    #directorist.atbd_wrapper .btn-outline-danger{
        color: <?php echo !empty($danger_color) ? $danger_color : '#e23636';?> !important;
    }

    /* Background */
    #directorist.atbd_wrapper .btn-danger, #directorist.atbd_wrapper .btn-outline-danger:hover, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img #remove_pro_pic{
        background: <?php echo !empty($back_danger_color) ? $back_danger_color : '#e23636';?> !important;
    }

    /* Hover Background */
    #directorist.atbd_wrapper .btn-danger:hover, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img #remove_pro_pic:hover{
        background: <?php echo !empty($back_danger_hover_color) ? $back_danger_hover_color : '#c5001e';?> !important;
    }

    /* Border Color */
    #directorist.atbd_wrapper .btn-danger, #directorist.atbd_wrapper .btn-outline-danger{
        border-color: <?php echo !empty($border_danger_color) ? $border_danger_color : '#e23636';?>  !important;
    }

    /* Hover Border Color */
    #directorist.atbd_wrapper .btn-danger:hover{
        border-color: <?php echo !empty($border_danger_hover_color) ? $border_danger_hover_color : '#c5001e';?> !important;
    }


    /* =======================================
     Button: Success
    ======================================== */
    /* Color */

    /* Background */
    #directorist.atbd_wrapper .btn-success{
        background: <?php echo !empty($back_success_color) ? $back_success_color : '#32cc6f';?>  !important;
    }

    /* Hover Background */
    #directorist.atbd_wrapper .btn-success:hover{
        background: <?php echo !empty($back_success_hover_color) ? $back_success_hover_color : '#2ba251';?> !important;
    }

    /* Border Color */
    #directorist.atbd_wrapper .btn-success{
        border-color: <?php echo !empty($border_success_color) ? $border_success_color : '#32cc6f';?>  !important;
    }

    /* Hover Border Color */
    #directorist.atbd_wrapper .btn-success:hover{
        border-color: <?php echo !empty($border_success_hover_color) ? $border_success_hover_color : '#2ba251';?> !important;
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
    .atbd_bg-success, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_open, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_open, .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_open, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_open{
        background: <?php echo !empty($open_back_color) ? $open_back_color : '#32cc6f';?> !important;
    }

    /* Badge Closed */
    .atbd_bg-danger, .atbd_content_active #directorist.atbd_wrapper .atbd_give_review_area #atbd_up_preview .atbd_up_prev .rmrf:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_close, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_close, .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_close, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_close {
        background: <?php echo !empty($closed_back_color) ? $closed_back_color : '#e23636';?> !important;
    }

    /* Badge Featured */
    .atbd_bg-badge-feature, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_featured, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_featured, .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_featured, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_featured {
        background: <?php echo !empty($featured_back_color) ? $featured_back_color : '#fa8b0c';?>  !important;
    }

    /* Badge Popular */
    .atbd_bg-badge-popular, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_popular, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_popular, .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_popular, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_popular {
        background: <?php echo !empty($popular_back_color) ? $popular_back_color : '#f51957';?> !important;
    }

    /* Badge New */
    .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_new {
        background: <?php echo !empty($new_back_color) ? $new_back_color : '#122069';?> !important;
    }

    /*
        Change default primary dark background
    */
    .ads-advanced .price-frequency .pf-btn input:checked + span, .btn-checkbox label input:checked + span, .atbdpr-range .ui-slider-horizontal .ui-slider-range, .custom-control .custom-control-input:checked ~ .check--select, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_meta .atbd_listing_rating, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_meta .atbd_listing_price, #directorist.atbd_wrapper .pagination .nav-links .current, .atbd_content_active #directorist.atbd_wrapper .atbd_contact_information_module .atbd_director_social_wrap a, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp-widget-categories > ul.atbdp_parent_category > li:hover > a span, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp.atbdp-widget-tags ul li a:hover{
        background: <?php echo !empty($primary_dark_back_color) ? $primary_dark_back_color : '#444752';?> !important;
    }

    /*
        Change default primary dark border
    */
    .ads-advanced .price-frequency .pf-btn input:checked + span, .btn-checkbox label input:checked + span, .atbdpr-range .ui-slider-horizontal .ui-slider-handle, .custom-control .custom-control-input:checked ~ .check--select, .custom-control .custom-control-input:checked ~ .radio--select, #atpp-plan-change-modal .atm-contents-inner .dcl_pricing_plan input:checked + label:before, #dwpp-plan-renew-modal .atm-contents-inner .dcl_pricing_plan input:checked + label:before{
        border-color: <?php echo !empty($primary_dark_border_color) ? $primary_dark_border_color : '#444752';?> !important;
    }


    /*
        Map Marker Icon Colors
    */
    /* Marker Shape color */
    .atbd_map_shape{
        background: <?php echo !empty($marker_shape_color) ? $marker_shape_color : '#444752';?>  !important;
    }
    .atbd_map_shape:before{
        border-top-color: <?php echo !empty($marker_shape_color) ? $marker_shape_color : '#444752';?>  !important;
    }

    /* Marker icon color */
    .map-icon-label i, .atbd_map_shape > span {
        color: <?php echo !empty($marker_icon_color) ? $marker_icon_color : '#444752';?> !important;
    }


</style>