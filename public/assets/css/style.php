<?php
$primary_color                   = get_directorist_option('primary_color','444752');
$back_primary_color              = get_directorist_option('back_primary_color','444752');
$border_primary_color            = get_directorist_option('border_primary_color','444752');
$secondary_color                 = get_directorist_option('secondary_color','122069');
$back_secondary_color            = get_directorist_option('back_secondary_color','122069');
$success_color                   = get_directorist_option('success_color','32cc6f');
$back_success_color              = get_directorist_option('back_success_color','32cc6f');
$info_color                      = get_directorist_option('info_color','3590ec');
$back_info_color                 = get_directorist_option('back_info_color','3590ec');
$warning_color                   = get_directorist_option('warning_color','ffaf00');
$back_warning_color              = get_directorist_option('back_warning_color','ffaf00');
$danger_color                    = get_directorist_option('danger_color','e23636');
$back_danger_color               = get_directorist_option('back_danger_color','e23636');
$dark_color                      = get_directorist_option('dark_color','202428');
$back_dark_color                 = get_directorist_option('back_dark_color','202428');
$featured_badge_color            = get_directorist_option('featured_badge_color','fa8b0c');
$featured_back_color             = get_directorist_option('featured_back_color','fa8b0c');
$popular_badge_color             = get_directorist_option('popular_badge_color','f51957');
$popular_back_color              = get_directorist_option('popular_back_color','f51957');
$heading_color                   = get_directorist_option('heading_color','272b41');
$text_color                      = get_directorist_option('text_color','7a82a6');
$rating_color                    = get_directorist_option('rating_color','fa8b0c');
?>
<style>
    /* Color: Primary */
    .atbd_color-primary, .atbd_content_active #directorist.atbd_wrapper h1 a:hover, .atbd_content_active #directorist.atbd_wrapper h2 a:hover, .atbd_content_active #directorist.atbd_wrapper h3 a:hover, .atbd_content_active #directorist.atbd_wrapper h4 a:hover, .atbd_content_active #directorist.atbd_wrapper h5 a:hover, .atbd_content_active #directorist.atbd_wrapper h6 a:hover,
    .atbd_content_active #directorist.atbd_wrapper .h1 a:hover, .atbd_content_active #directorist.atbd_wrapper .h2 a:hover, .atbd_content_active #directorist.atbd_wrapper .h3 a:hover, .atbd_content_active #directorist.atbd_wrapper .h4 a:hover, .atbd_content_active #directorist.atbd_wrapper .h5 a:hover, .atbd_content_active #directorist.atbd_wrapper .h6 a:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area h4 span, .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action a:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action.atbd_share .atbd_director_social_wrap ul li a > span, .atbd_content_active #directorist.atbd_wrapper .atbd_contact_info ul li .atbd_info_title span, .atbd_content_active #directorist.atbd_wrapper #client_review_list .atbd_single_review .review_content a, .atbd_content_active #directorist.atbd_wrapper .atbd_single_listing .atbd_listing_info .atbd_content_upper .atbd_listing_data_list ul li p span, .atbd_content_active #directorist.atbd_wrapper .atbd_upload_btn span, .atbd_content_active #directorist.atbd_wrapper .edit_btn_wrap .atbd_go_back, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_detail .atbd_data_info .atbd_listting_category .directory_tags li .directory_tag:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_detail .atbd_data_info .atbd_listting_category .directory_tags li .directory_tag span a:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_info .atbd_listting_category .atbd_cat_popup,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_categorized_listings ul li .atbd_right_content .atbd_cat_popup,
    .atbd_content_active .widget.atbd_widget .atbd_categorized_listings ul li .atbd_right_content .atbd_cat_popup, .atbd_content_active #directorist.atbd_wrapper .atbd_listting_category a span, .atbd_content_active #directorist.atbd_wrapper .atbd_single_listing .atbd_listing_info .atbd_content_upper .atbd_excerpt_content a, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .atbd_listing_bottom_content .listing-meta p span, .atbd_content_active #directorist.atbd_wrapper .atbd_location_grid_wrap .atbd_location_grid.atbd_location_grid-default figure figcaption h3, .atbd_content_active #directorist.atbd_wrapper .atbd_location_grid_wrap .atbd_location_grid.atbd_location_grid-default figure figcaption p, .atbd_content_active #directorist.atbd_wrapper .atbd_category_single.atbd_category-default figure figcaption .icon, .atbd_content_active #directorist.atbd_wrapper .atbd_category_single.atbd_category-default figure figcaption .cat-name, .atbd_content_active #directorist.atbd_wrapper .atbd_category_single.atbd_category-default figure figcaption .cat-info span {
        color: <?php echo !empty($primary_color) ? $primary_color : '#444752'; ?> !important;
    }

    /* Background: Primary */
    .atbd_bg-primary, .atbd_content_active #directorist.atbd_wrapper .atbd_contact_information_module .atbd_director_social_wrap a, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_meta .atbd_listing_rating, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_meta .atbd_listing_price, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbdp-search .submit_btn .btn-primary,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbdp-search .submit_btn .btn-primary,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp-search .submit_btn .btn-primary, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbdp.atbdp-widget-tags ul li a:hover, .atbd_content_active .widget.atbd_widget[id^='bd'] .directorist.atbdp-widget-tags ul li a:hover,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbdp.atbdp-widget-tags ul li a:hover,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .directorist.atbdp-widget-tags ul li a:hover,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp.atbdp-widget-tags ul li a:hover,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .directorist.atbdp-widget-tags ul li a:hover, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbdp-widget-categories > ul.atbdp_parent_category > li:hover > a span,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbdp-widget-categories > ul.atbdp_parent_category > li:hover > a span,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp-widget-categories > ul.atbdp_parent_category > li:hover > a span, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_author_info_widget .atbd_social_wrap p a:hover,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_author_info_widget .atbd_social_wrap p a:hover,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_author_info_widget .atbd_social_wrap p a:hover, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic, #directorist.atbd_wrapper .pagination .nav-links .current, .atbd_content_active #directorist.atbd_wrapper #atbdp-report-abuse-modal .at-modal-close, .atbd_content_active #directorist.atbd_wrapper .modal-dialog .modal-header .close, .atbd_content_active #directorist.atbd_wrapper .atbd_directry_gallery_wrapper .prev:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_directry_gallery_wrapper .next:hover, .gm-style .gm-style-iw .gm-style-iw-d .miw-contents .miwl-rating .atbd_meta, .atbd_content_active .widget.atbd_widget + #dcl-claim-modal .modal-footer .btn, .ads-advanced .price-frequency .pf-btn input:checked + span, .atbd_content_active .widget.atbd_widget[id^='bd'] .directorist .btn,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .directorist .btn,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .directorist .btn, .atbd_content_active #directorist.atbd_wrapper #dcl-claim-modal .atm-contents-inner .at-modal-close,
    .atbd_content_active #dcl-claim-modal .atm-contents-inner .at-modal-close, .pricing .price_action .price_action--btn {
        background: <?php echo !empty($back_primary_color) ? $back_primary_color : '#444752'; ?> !important;
    }

    /* Border-color: Primary */
    .ads-advanced .price-frequency .pf-btn input:checked + span, .atbd_content_active .widget.atbd_widget[id^='bd'] .directorist .btn, .atbd_content_active .widget.atbd_widget[id^='dcl'] .directorist .btn,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .directorist .btn {
        border-color: <?php echo !empty($border_primary_color) ? $border_primary_color : '#444752'; ?> !important;
    }

    /* Color: Secondary */
    .atbd_color-secondary {
        color: <?php echo !empty($secondary_color) ? $secondary_color : '#122069'; ?> !important;
    }

    /* Background: Secondary */
    .atbd_bg-secondary, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_new, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_new,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_new,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_new, .atbd_content_active #directorist.atbd_wrapper .pricing.pricing--1.atbd_pricing_special .pricing__title h4 {
        background: <?php echo !empty($back_secondary_color) ? $back_secondary_color : '#122069'; ?> !important;
    }

    /* Color: Success */
    .atbd_color-success, .atbd_content_active .widget.atbd_widget[id^='bd'] .directory_open_hours ul li.atbd_today, .atbd_content_active .widget.atbd_widget[id^='bd'] .directory_open_hours ul li.atbd_today .day,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .directory_open_hours ul li.atbd_today,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .directory_open_hours ul li.atbd_today .day,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .directory_open_hours ul li.atbd_today,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .directory_open_hours ul li.atbd_today .day, .atbd_content_active #directorist.atbd_wrapper .pricing.pricing--1 .pricing__features ul li span.fa.fa-check, .dcl_claimed .dcl_claimed--badge, .atbd_content_active #directorist.atbd_wrapper #dcl-claim-modal .atm-contents-inner .modal-footer span i, .atbd_content_active #directorist.atbd_wrapper #dcl-claim-modal .dcl_pricing_plan select .dcl_active_plan, #dcl-claim-submit-notification.text-success, .pricing.pricing--1 .pricing__features ul li span.fa-check, .atbdp_make_str_green {
        color: <?php echo !empty($success_color) ? $success_color : '#32cc6f'; ?> !important;
    }

    /* Background: Success */
    .atbd_bg-success, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_open, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_open,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_open,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_open, .dcl_claimed .dcl_claimed--badge span, .pricing .pricing__title .atbd_plan-active {
        background: <?php echo !empty($back_success_color) ? $back_success_color : '#32cc6f'; ?> !important;
    }

    /* Color: Info */
    .atbd_color-info {
        color: <?php echo !empty($info_color) ? $info_color : '#3590ec'; ?> !important;
    }

    /* Background: Info */
    .atbd_bg-info {
        background: <?php echo !empty($back_info_color) ? $back_info_color : '#3590ec'; ?> !important;
    }

    /* Color: Warning */
    .atbd_color-warning, #dcl-claim-warning-notification.text-warning {
        color: <?php echo !empty($warning_color) ? $warning_color : '#ffaf00'; ?> !important;
    }

    /* Background: Warning */
    .atbd_bg-warning {
        background: <?php echo !empty($back_warning_color) ? $back_warning_color : '#ffaf00'; ?> !important;
    }

    /* Color: Danger */
    .atbd_color-danger, .atbd_content_active .widget.atbd_widget[id^='bd'] .directory_open_hours ul li.atbd_closed span,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .directory_open_hours ul li.atbd_closed span,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .directory_open_hours ul li.atbd_closed span, .atbd_content_active #directorist.atbd_wrapper.atbd_add_listing_wrapper .listing-img-container .single_attachment .remove_image:hover, .atbd_content_active #directorist.atbd_wrapper .pricing.pricing--1 .pricing__features ul li span.fa.fa-times, .plupload-thumbs .thumb .atbdp-thumb-actions .thumbremovelink:hover, .atbd_content_active #directorist.atbd_wrapper.atbd_add_listing_wrapper .single_prv_attachment div .remove_prev_img:hover, #directorist.atbd_wrapper .directory_regi_btn a, .atbdp_required, .atbd_content_active #directorist.atbd_wrapper #dcl-claim-modal .atm-contents-inner .modal-body .form-group .dcl_plans, .pricing.pricing--1 .pricing__features ul li span.fa-times {
        color: <?php echo !empty($danger_color) ? $danger_color : '#e23636'; ?> !important;
    }

    /* Background: Danger */
    .atbd_bg-danger, .atbd_content_active #directorist.atbd_wrapper .atbd_give_review_area #atbd_up_preview .atbd_up_prev .rmrf:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_close, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_close,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_close,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_close, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img #remove_pro_pic {
        background: <?php echo !empty($back_danger_color) ? $back_danger_color : '#e23636'; ?> !important;
    }

    /* Color: Dark */
    .atbd_color-dark, #directorist.atbd_wrapper label, #directorist.atbd_wrapper .atbd_payment_recipt .atbd_thank_you, #directorist.atbd_wrapper .atbd_payment_recipt .atbd_payment_summary, #directorist.atbd_wrapper .pagination .nav-links .current, #directorist.atbd_wrapper .pagination .nav-links .page-numbers, .atbdp_login_form_shortcode #loginform p input[type="submit"], .atbd_content_active #directorist.atbd_wrapper .atbd_contact_info ul li .atbd_info_title, .atbd_content_active #directorist.atbd_wrapper .atbd_directry_gallery_wrapper .prev, .atbd_content_active #directorist.atbd_wrapper .atbd_directry_gallery_wrapper .next, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_detail .atbd_data_info .atbd_listting_category .directory_tags li .directory_tag, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_detail .atbd_data_info .atbd_listting_category .directory_tags li .directory_tag span a, .atbd_content_active #directorist.atbd_wrapper .atbd_custom_fields .atbd_custom_field_title p, .atbd_content_active #directorist.atbd_wrapper #client_review_list .atbd_single_review .atbd_review_top .atbd_name_time p {
        color: <?php echo !empty($dark_color) ? $dark_color : '#202428'; ?> !important;
    }

    /* Background: Dark */
    .atbd_bg-dark {
        background: <?php echo !empty($back_dark_color) ? $back_dark_color : '#202428'; ?> !important;
    }

    /* Badge Featured Color */
    .atbd_color-badge-feature {
        color: <?php echo !empty($featured_badge_color) ? $featured_badge_color : '#fa8b0c'; ?> !important;
    }

    /* Badge Featured Background */
    .atbd_bg-badge-feature, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_featured, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_featured,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_featured,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_featured, .pricing.atbd_pricing_special .atbd_popular_badge {
        background: <?php echo !empty($featured_back_color) ? $featured_back_color : '#fa8b0c'; ?> !important;
    }

    /* Badge Popular Color */
    .atbd_color-badge-popular, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_meta .atbd_close_now {
        color: <?php echo !empty($popular_badge_color) ? $popular_badge_color : '#f51957'; ?> !important;
    }

    /* Badge Popular Background */
    .atbd_bg-badge-popular, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_popular, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_popular,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_popular,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_popular {
        background: <?php echo !empty($popular_back_color) ? $popular_back_color : '#f51957'; ?> !important;
    }

    /* Headings Color */
    .atbd_content_active #directorist.atbd_wrapper h1, .atbd_content_active #directorist.atbd_wrapper h2, .atbd_content_active #directorist.atbd_wrapper h3, .atbd_content_active #directorist.atbd_wrapper h4, .atbd_content_active #directorist.atbd_wrapper h5, .atbd_content_active #directorist.atbd_wrapper h6,
    .atbd_content_active #directorist.atbd_wrapper .h1, .atbd_content_active #directorist.atbd_wrapper .h2, .atbd_content_active #directorist.atbd_wrapper .h3, .atbd_content_active #directorist.atbd_wrapper .h4, .atbd_content_active #directorist.atbd_wrapper .h5, .atbd_content_active #directorist.atbd_wrapper .h6, .atbd_content_active #directorist.atbd_wrapper h1 a, .atbd_content_active #directorist.atbd_wrapper h2 a, .atbd_content_active #directorist.atbd_wrapper h3 a, .atbd_content_active #directorist.atbd_wrapper h4 a, .atbd_content_active #directorist.atbd_wrapper h5 a, .atbd_content_active #directorist.atbd_wrapper h6 a,
    .atbd_content_active #directorist.atbd_wrapper .h1 a, .atbd_content_active #directorist.atbd_wrapper .h2 a, .atbd_content_active #directorist.atbd_wrapper .h3 a, .atbd_content_active #directorist.atbd_wrapper .h4 a, .atbd_content_active #directorist.atbd_wrapper .h5 a, .atbd_content_active #directorist.atbd_wrapper .h6 a, .ads-advanced .more-less, .ads-advanced .more-or-less, .pricing .pricing__price p.pricing_value, .pricing.pricing--1 .pricing__title h4 {
        color: <?php echo !empty($heading_color) ? $heading_color : '#272b41'; ?> !important;
    }

    /* Text Color */
    .atbd_content_active #directorist.atbd_wrapper, .atbd_content_active #directorist.atbd_wrapper p, .atbd_content_active #directorist.atbd_wrapper .atbd_generic_header .atbd_generic_header_title h3, #recover-pass-modal .modal-body label, .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action, .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action a, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_detail .atbd_data_info .atbd_rating_count p span, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_detail .atbd_listing_title .atbd_sub_title, .atbd_content_active #directorist.atbd_wrapper .atbd_single_listing .atbd_listing_info .atbd_content_upper .atbd_listing_tagline, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_author_info_widget .atbd_widget_contact_info ul li span.fa,
    .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_author_info_widget .atbd_widget_contact_info ul li span.fa,
    .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_author_info_widget .atbd_widget_contact_info ul li span.fa, .atbd_content_active #directorist.atbd_wrapper .select2-container--default .select2-selection--single .select2-selection__placeholder, .pricing.pricing--1 .pricing__price .pricing_subtitle, .pricing.pricing--1 .pricing__features ul li, .pricing .pricing__price p.pricing_description {
        color: <?php echo !empty($text_color) ? $text_color : '#7a82a6'; ?> !important;
    }

    /* Ratings Color */
    .atbd_rating_color, #directorist.atbd_wrapper .atbd_rated_stars ul li span.rate_active, #directorist.atbd_wrapper .atbd_rating_stars .br-widget a.br-selected:before, #directorist.atbd_wrapper .atbd_rating_stars .br-widget a.br-active:before {
        color: <?php echo !empty($rating_color) ? $rating_color : '#fa8b0c'; ?> !important;
    }
</style>