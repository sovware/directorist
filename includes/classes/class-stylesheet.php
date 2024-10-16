<?php

if ( ! class_exists( 'ATBDP_Stylesheet' ) ):
    class ATBDP_Stylesheet {

        // add_listing_css
        public static function add_listing_css() {
            ob_start(); ?>
                #OL_Icon_33 {
                    position: relative;
                }

                .mapHover {
                    position: absolute;
                    background: #fff;
                    padding: 5px;
                    width: 150px;
                    border-radius: 3px;
                    border: 1px solid #ddd;
                    display: none;
                }

                #OL_Icon_33:hover .mapHover {
                    display: block;
                }

                /*#directorist.atbd_wrapper a {
                    display: block;
                    background: #fff;
                    padding: 8px 10px;
                }*/

                #directorist.atbd_wrapper a:hover {
                    background: #eeeeee50;
                }

                #directorist.atbd_wrapper a.active {
                    background: #eeeeee70;
                }

                .g_address_wrap ul li {
                    margin-bottom: 0px;
                    border-bottom: 1px solid #eee;
                    padding-bottom: 0px;
                } <?php

                return ob_get_clean();
        }

        // osm_css
        public static function osm_css()
        {
            ob_start(); ?>
            .myDivIcon {
                text-align: center !important;
                line-height: 20px !important;
                position: relative;
            }

            .myDivIcon div.atbd_map_shape {
                position: absolute;
                top: -38px;
                left: -15px;
            }
            .leaflet-pane .myDivIcon div.atbd_map_shape {
                position: absolute;
                top: -50px;
                left: 50%;
                margin-left: -26px;
            }
            <?php

            return ob_get_clean();
        }

        // business_hour_css
        public static function business_hour_css()
        {
            ob_start(); ?>
            .atbd_badge_close,
            .atbd_badge_open {
                position: absolute;
                left: 15px;
                top: 15px;
            } <?php

            return ob_get_clean();
        }

        // style_settings_css
        public static function style_settings_css() {
            do_action( 'include_style_settings' );
            $button_primary_color             = get_directorist_option('button_primary_color', '#ffffff');
            $button_primary_hover_color       = get_directorist_option('button_primary_hover_color', '#ffffff');
            $button_primary_bg_color          = get_directorist_option('button_primary_bg_color', '#444752');
            $button_secondary_color           = get_directorist_option('button_secondary_color', '#ffffff');
            $button_secondary_hover_color     = get_directorist_option('button_secondary_hover_color', '#ffffff');
            $button_secondary_bg_color        = get_directorist_option('button_secondary_bg_color', '#222222');
            $open_back_color                = get_directorist_option( 'open_back_color', '#32cc6f' );
            $closed_back_color              = get_directorist_option( 'closed_back_color', '#e23636' );
            $featured_back_color            = get_directorist_option( 'featured_back_color', '#fa8b0c' );
            $popular_back_color             = get_directorist_option( 'popular_back_color', '#f51957' );
            $new_back_color                 = get_directorist_option( 'new_back_color', '#2C99FF' );
            $primary_dark_back_color        = get_directorist_option( 'primary_dark_back_color', '#444752' );
            $primary_dark_border_color      = get_directorist_option( 'primary_dark_border_color', '#444752' );
            $marker_shape_color             = get_directorist_option( 'marker_shape_color', '#444752' );
            $marker_icon_color              = get_directorist_option( 'marker_icon_color', '#444752' );

            ob_start();
            ?>
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
                .pricing .price_action .price_action--btn, #directorist.atbd_wrapper .btn-primary, .default-ad-search .submit_btn .btn-default, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic, #directorist.atbd_wrapper .at-modal .at-modal-close, .atbdp_login_form_shortcode #loginform p input[type="submit"], .atbd_manage_fees_wrapper .table tr .action p .btn-block, #directorist.atbd_wrapper #atbdp-checkout-form #atbdp_checkout_submit_btn, #directorist.atbd_wrapper .ezmu__btn{
                    color: <?php echo ! empty( $button_primary_color ) ? esc_attr( $button_primary_color ) : esc_attr( '#fff' ); ?> !important;
                }

                /* Color Hover */
                .pricing .price_action .price_action--btn:hover, #directorist.atbd_wrapper .btn-primary:hover, .default-ad-search .submit_btn .btn-default:hover, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic:hover, #directorist.atbd_wrapper .at-modal .at-modal-close:hover, .atbdp_login_form_shortcode #loginform p input[type="submit"]:hover, .atbd_manage_fees_wrapper .table tr .action p .btn-block:hover, #directorist.atbd_wrapper #atbdp-checkout-form #atbdp_checkout_submit_btn:hover, #directorist.atbd_wrapper .ezmu__btn:hover{
                    color: <?php echo ! empty( $button_primary_hover_color ) ? esc_attr( $button_primary_hover_color ) : esc_attr( '#fff' ); ?> !important;
                }

                /* Background */
                .pricing .price_action .price_action--btn, #directorist.atbd_wrapper .btn-primary, .default-ad-search .submit_btn .btn-default, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic, #directorist.atbd_wrapper .at-modal .at-modal-close, .atbdp_login_form_shortcode #loginform p input[type="submit"], .atbd_manage_fees_wrapper .table tr .action p .btn-block, #directorist.atbd_wrapper #atbdp-checkout-form #atbdp_checkout_submit_btn, #directorist.atbd_wrapper .ezmu__btn{
                    background: <?php echo ! empty( $button_primary_bg_color ) ? esc_attr( $button_primary_bg_color ) : esc_attr( '#444752' ); ?> !important;
                }

                /* Hover Background */
                .pricing .price_action .price_action--btn:hover, #directorist.atbd_wrapper .btn-primary:hover, #directorist.atbd_wrapper .at-modal .at-modal-close:hover, .default-ad-search .submit_btn .btn-default:hover, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic:hover, .atbdp_login_form_shortcode #loginform p input[type="submit"]:hover, #directorist.atbd_wrapper .ezmu__btn:hover{
                    background: <?php echo ! empty( $button_primary_bg_color ) ? esc_attr( $button_primary_bg_color ) : esc_attr( '#222222' ); ?> !important;
                }

                /* Border Color */
                #directorist.atbd_wrapper .btn-primary, .default-ad-search .submit_btn .btn-default, .atbdp_login_form_shortcode #loginform p input[type="submit"]{
                    border-color: <?php echo ! empty( $button_primary_bg_color ) ? esc_attr( $button_primary_bg_color ) : esc_attr( '#444752' ); ?> !important;
                }

                /* Hover Border Color */
                #directorist.atbd_wrapper .btn-primary:hover, .default-ad-search .submit_btn .btn-default:hover, .atbdp_login_form_shortcode #loginform p input[type="submit"]:hover{
                    border-color: <?php echo ! empty( $button_primary_bg_color ) ? esc_attr( $button_primary_bg_color ) : esc_attr( '#222222' ); ?> !important;
                }

                /* =======================================
                Button: Secondary
                ======================================== */
                /* Color */
                #directorist.atbd_wrapper .btn-secondary {
                    color: <?php echo ! empty( $button_secondary_color ) ? esc_attr( $button_secondary_color ) : esc_attr( '#fff' ); ?> !important;
                }
                #directorist.atbd_wrapper .btn-secondary:hover{
                    color: <?php echo ! empty( $button_secondary_hover_color ) ? esc_attr( $button_secondary_hover_color ) : esc_attr( '#fff' ); ?> !important;
                }

                /* Background */
                #directorist.atbd_wrapper .btn-secondary {
                    background: <?php echo ! empty( $button_secondary_bg_color ) ? esc_attr( $button_secondary_bg_color ) : esc_attr( '#122069' ); ?> !important;
                }

                /* Hover Background */
                #directorist.atbd_wrapper .btn-secondary:hover{
                    background: <?php echo ! empty( $button_secondary_bg_color ) ? esc_attr( $button_secondary_bg_color ) : esc_attr( '#131469' ); ?> !important;
                }

                /* Border Color */
                #directorist.atbd_wrapper .btn-secondary {
                    border-color: <?php echo ! empty( $button_secondary_bg_color ) ? esc_attr( $button_secondary_bg_color ) : esc_attr( '#131469' ); ?> !important;
                }

                /* Hover Border Color */
                #directorist.atbd_wrapper .btn-secondary:hover{
                    border-color: <?php echo ! empty( $button_secondary_bg_color ) ? esc_attr( $button_secondary_bg_color ) : esc_attr( '#131469' ); ?> !important;
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
                    background: <?php echo ! empty( $open_back_color ) ? esc_attr( $open_back_color ) : esc_attr( '#32cc6f' ); ?> !important;
                }

                /* Badge Closed */
                .atbd_bg-danger, .atbd_content_active #directorist.atbd_wrapper .atbd_give_review_area #atbd_up_preview .atbd_up_prev .rmrf:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_close, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_close, .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_close, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_close {
                    background: <?php echo ! empty( $closed_back_color ) ? esc_attr( $closed_back_color ) : esc_attr( '#e23636' ); ?> !important;
                }

                /* Badge Featured */
                .directorist-badge-featured, .atbd_bg-badge-feature, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_featured, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_featured, .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_featured, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_featured {
                    background: <?php echo ! empty( $featured_back_color ) ? esc_attr( $featured_back_color ) : esc_attr( '#fa8b0c' ); ?>  !important;
                }

                /* Badge Popular */
                .atbd_bg-badge-popular, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_popular, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_popular, .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_popular, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_popular {
                    background: <?php echo ! empty( $popular_back_color ) ? esc_attr( $popular_back_color ) : esc_attr( '#f51957' ); ?> !important;
                }

                /* Badge New */
                .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_new {
                    background: <?php echo ! empty( $new_back_color ) ? esc_attr( $new_back_color ) : esc_attr( '#2C99FF' ); ?> !important;
                }

                /*
                    Change default primary dark background
                */
                .ads-advanced .price-frequency .pf-btn input:checked + span, .btn-checkbox label input:checked + span, .atbdpr-range .ui-slider-horizontal .ui-slider-range, .custom-control .custom-control-input:checked ~ .check--select, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_meta .atbd_listing_rating, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_meta .atbd_listing_price, #directorist.atbd_wrapper .pagination .nav-links .current, .atbd_content_active #directorist.atbd_wrapper .atbd_contact_information_module .atbd_director_social_wrap a, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp-widget-categories > ul.directorist-parent-category > li:hover > a span{
                    background: <?php echo ! empty( $primary_dark_back_color ) ? esc_attr( $primary_dark_back_color ) : esc_attr( '#444752' ); ?> !important;
                }

                /*
                    Change default primary dark border
                */
                .ads-advanced .price-frequency .pf-btn input:checked + span, .btn-checkbox label input:checked + span, .atbdpr-range .ui-slider-horizontal .ui-slider-handle, .custom-control .custom-control-input:checked ~ .check--select, .custom-control .custom-control-input:checked ~ .radio--select, #atpp-plan-change-modal .atm-contents-inner .dcl_pricing_plan input:checked + label:before, #dwpp-plan-renew-modal .atm-contents-inner .dcl_pricing_plan input:checked + label:before{
                    border-color: <?php echo ! empty( $primary_dark_border_color ) ? esc_attr( $primary_dark_border_color ) : esc_attr( '#444752' ); ?> !important;
                }


                /*
                    Map Marker Icon Colors
                */
                /* Marker Shape color */
                .atbd_map_shape{
                    background: <?php echo ! empty( $marker_shape_color ) ? esc_attr( $marker_shape_color ) : esc_attr( '#444752' ); ?>  !important;
                }
                .atbd_map_shape:before{
                    border-top-color: <?php echo ! empty( $marker_shape_color ) ? esc_attr( $marker_shape_color ) : esc_attr( '#444752' ); ?>  !important;
                }

                /* Marker icon color */
                .map-icon-label i, .atbd_map_shape > span {
                    color: <?php echo ! empty( $marker_icon_color ) ? esc_attr( $marker_icon_color ) : esc_attr( '#ffffff' ); ?> !important;
                }
            <?php

            return ob_get_clean();
        }
    }
endif;