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
            $primary_color                  = get_directorist_option( 'primary_color', '#ffffff' );
            $primary_hover_color            = get_directorist_option( 'primary_hover_color', '#ffffff' );
            $back_primary_color             = get_directorist_option( 'back_primary_color', '#444752' );
            $back_primary_hover_color       = get_directorist_option( 'back_primary_hover_color', '#222222' );
            $border_primary_color           = get_directorist_option( 'border_primary_color', '#444752' );
            $border_primary_hover_color     = get_directorist_option( 'border_primary_hover_color', '#222222' );
            $secondary_color                = get_directorist_option( 'secondary_color', '#fff' );
            $secondary_hover_color          = get_directorist_option( 'secondary_hover_color', '#fff' );
            $back_secondary_color           = get_directorist_option( 'back_secondary_color', '#122069' );
            $back_secondary_hover_color     = get_directorist_option( 'back_secondary_hover_color', '#131469' );
            $secondary_border_color         = get_directorist_option( 'secondary_border_color', '#131469' );
            $secondary_border_hover_color   = get_directorist_option( 'secondary_border_hover_color', '#131469' );
            $danger_color                   = get_directorist_option( 'danger_color', '#fff' );
            $danger_hover_color             = get_directorist_option( 'danger_hover_color', '#fff' );
            $back_danger_color              = get_directorist_option( 'back_danger_color', '#e23636' );
            $back_danger_hover_color        = get_directorist_option( 'back_danger_hover_color', '#c5001e' );
            $danger_border_color            = get_directorist_option( 'danger_border_color', '#e23636' );
            $danger_border_hover_color      = get_directorist_option( 'danger_border_hover_color', '#c5001e' );
            $success_color                  = get_directorist_option( 'success_color', '#fff' );
            $success_hover_color            = get_directorist_option( 'success_hover_color', '#fff' );
            $back_success_color             = get_directorist_option( 'back_success_color', '#32cc6f' );
            $back_success_hover_color       = get_directorist_option( 'back_success_hover_color', '#2ba251' );
            $border_success_color           = get_directorist_option( 'border_success_color', '#32cc6f' );
            $border_success_hover_color     = get_directorist_option( 'border_success_hover_color', '#2ba251' );
            $priout_color                   = get_directorist_option( 'priout_color', '#444752' );
            $priout_hover_color             = get_directorist_option( 'priout_hover_color', '#444752' );
            $back_priout_color              = get_directorist_option( 'back_priout_color', '#fff' );
            $back_priout_hover_color        = get_directorist_option( 'back_priout_hover_color', '#fff' );
            $border_priout_color            = get_directorist_option( 'border_priout_color', '#444752' );
            $border_priout_hover_color      = get_directorist_option( 'border_priout_hover_color', '#9299b8' );
            $prioutlight_color              = get_directorist_option( 'prioutlight_color', '#444752' );
            $prioutlight_hover_color        = get_directorist_option( 'prioutlight_hover_color', '#ffffff' );
            $back_prioutlight_color         = get_directorist_option( 'back_prioutlight_color', '#fff' );
            $back_prioutlight_hover_color   = get_directorist_option( 'back_prioutlight_hover_color', '#444752' );
            $border_prioutlight_color       = get_directorist_option( 'border_prioutlight_color', '#e3e6ef' );
            $border_prioutlight_hover_color = get_directorist_option( 'border_prioutlight_hover_color', '#444752' );
            $danout_color                   = get_directorist_option( 'danout_color', '#e23636' );
            $danout_hover_color             = get_directorist_option( 'danout_hover_color', '#fff' );
            $back_danout_color              = get_directorist_option( 'back_danout_color', '#fff' );
            $back_danout_hover_color        = get_directorist_option( 'back_danout_hover_color', '#e23636' );
            $border_danout_color            = get_directorist_option( 'border_danout_color', '#e23636' );
            $border_danout_hover_color      = get_directorist_option( 'border_danout_hover_color', '#e23636' );
            $open_back_color                = get_directorist_option( 'open_back_color', '#32cc6f' );
            $closed_back_color              = get_directorist_option( 'closed_back_color', '#e23636' );
            $featured_back_color            = get_directorist_option( 'featured_back_color', '#fa8b0c' );
            $popular_back_color             = get_directorist_option( 'popular_back_color', '#f51957' );
            $new_back_color                 = get_directorist_option( 'new_back_color', '#122069' );
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
                .pricing .price_action .price_action--btn, #directorist.atbd_wrapper .btn-primary, .default-ad-search .submit_btn .btn-default, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic, #directorist.atbd_wrapper .at-modal .at-modal-close, .atbdp_login_form_shortcode #loginform p input[type="submit"], .atbd_manage_fees_wrapper .table tr .action p .btn-block, #directorist.atbd_wrapper #atbdp-checkout-form #atbdp_checkout_submit_btn, #directorist.atbd_wrapper .ezmu__btn{
                    color: <?php echo ! empty( $primary_color ) ? $primary_color : '#fff'; ?> !important;
                }

                /* Color Hover */
                .pricing .price_action .price_action--btn:hover, #directorist.atbd_wrapper .btn-primary:hover, .default-ad-search .submit_btn .btn-default:hover, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic:hover, #directorist.atbd_wrapper .at-modal .at-modal-close:hover, .atbdp_login_form_shortcode #loginform p input[type="submit"]:hover, .atbd_manage_fees_wrapper .table tr .action p .btn-block:hover, #directorist.atbd_wrapper #atbdp-checkout-form #atbdp_checkout_submit_btn:hover, #directorist.atbd_wrapper .ezmu__btn:hover{
                    color: <?php echo ! empty( $primary_hover_color ) ? $primary_hover_color : '#fff'; ?> !important;
                }

                /* Background */
                .pricing .price_action .price_action--btn, #directorist.atbd_wrapper .btn-primary, .default-ad-search .submit_btn .btn-default, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic, #directorist.atbd_wrapper .at-modal .at-modal-close, .atbdp_login_form_shortcode #loginform p input[type="submit"], .atbd_manage_fees_wrapper .table tr .action p .btn-block, #directorist.atbd_wrapper #atbdp-checkout-form #atbdp_checkout_submit_btn, #directorist.atbd_wrapper .ezmu__btn{
                    background: <?php echo ! empty( $back_primary_color ) ? $back_primary_color : '#444752'; ?> !important;
                }

                /* Hover Background */
                .pricing .price_action .price_action--btn:hover, #directorist.atbd_wrapper .btn-primary:hover, #directorist.atbd_wrapper .at-modal .at-modal-close:hover, .default-ad-search .submit_btn .btn-default:hover, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img .choose_btn #upload_pro_pic:hover, .atbdp_login_form_shortcode #loginform p input[type="submit"]:hover, #directorist.atbd_wrapper .ezmu__btn:hover{
                    background: <?php echo ! empty( $back_primary_hover_color ) ? $back_primary_hover_color : '#222222'; ?> !important;
                }

                /* Border Color */
                #directorist.atbd_wrapper .btn-primary, .default-ad-search .submit_btn .btn-default, .atbdp_login_form_shortcode #loginform p input[type="submit"]{
                    border-color: <?php echo ! empty( $border_primary_color ) ? $border_primary_color : '#444752'; ?> !important;
                }

                /* Hover Border Color */
                #directorist.atbd_wrapper .btn-primary:hover, .default-ad-search .submit_btn .btn-default:hover, .atbdp_login_form_shortcode #loginform p input[type="submit"]:hover{
                    border-color: <?php echo ! empty( $border_primary_hover_color ) ? $border_primary_hover_color : '#222222'; ?> !important;
                }

                /* =======================================
                Button: Secondary
                ======================================== */
                /* Color */
                #directorist.atbd_wrapper .btn-secondary {
                    color: <?php echo ! empty( $secondary_color ) ? $secondary_color : '#fff'; ?> !important;
                }
                #directorist.atbd_wrapper .btn-secondary:hover{
                    color: <?php echo ! empty( $secondary_hover_color ) ? $secondary_hover_color : '#fff'; ?> !important;
                }

                /* Background */
                #directorist.atbd_wrapper .btn-secondary {
                    background: <?php echo ! empty( $back_secondary_color ) ? $back_secondary_color : '#122069'; ?> !important;
                }

                /* Hover Background */
                #directorist.atbd_wrapper .btn-secondary:hover{
                    background: <?php echo ! empty( $back_secondary_hover_color ) ? $back_secondary_hover_color : '#131469'; ?> !important;
                }

                /* Border Color */
                #directorist.atbd_wrapper .btn-secondary {
                    border-color: <?php echo ! empty( $secondary_border_color ) ? $secondary_border_color : '#131469'; ?> !important;
                }

                /* Hover Border Color */
                #directorist.atbd_wrapper .btn-secondary:hover{
                    border-color: <?php echo ! empty( $secondary_border_hover_color ) ? $secondary_border_hover_color : '#131469'; ?> !important;
                }


                /* =======================================
                Button: Danger
                ======================================== */
                /* Color*/
                #directorist.atbd_wrapper .btn-danger, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img #remove_pro_pic, .sweet-alert button.confirm{
                    color: <?php echo ! empty( $danger_color ) ? $danger_color : '#fff'; ?> !important;
                }

                /* color hover */
                #directorist.atbd_wrapper .btn-danger:hover, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img #remove_pro_pic, .sweet-alert button.confirm:hover{
                    color: <?php echo ! empty( $danger_hover_color ) ? $danger_hover_color : '#fff'; ?> !important;
                }

                /* Background */
                #directorist.atbd_wrapper .btn-danger, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img #remove_pro_pic, .sweet-alert button.confirm{
                    background: <?php echo ! empty( $back_danger_color ) ? $back_danger_color : '#e23636'; ?> !important;
                }

                /* Hover Background */
                #directorist.atbd_wrapper .btn-danger:hover, .atbd_content_active #directorist.atbd_wrapper.dashboard_area .user_pro_img_area .user_img #remove_pro_pic:hover, .sweet-alert button.confirm:hover{
                    background: <?php echo ! empty( $back_danger_hover_color ) ? $back_danger_hover_color : '#c5001e'; ?> !important;
                }

                /* Border Color */
                #directorist.atbd_wrapper .btn-danger{
                    border-color: <?php echo ! empty( $danger_border_color ) ? $danger_border_color : '#e23636'; ?> !important;
                }

                /* Hover Border Color */
                #directorist.atbd_wrapper .btn-danger:hover{
                    border-color: <?php echo ! empty( $danger_border_hover_color ) ? $danger_border_hover_color : '#c5001e'; ?> !important;
                }


                /* =======================================
                Button: Success
                ======================================== */
                /* Color */
                #directorist.atbd_wrapper .btn-success{
                    color: <?php echo ! empty( $success_color ) ? $success_color : '#fff'; ?> !important;
                }

                /* color hover */
                #directorist.atbd_wrapper .btn-success:hover{
                    color: <?php echo ! empty( $success_hover_color ) ? $success_hover_color : '#fff'; ?> !important;
                }

                /* Background */
                #directorist.atbd_wrapper .btn-success{
                    background: <?php echo ! empty( $back_success_color ) ? $back_success_color : '#32cc6f'; ?> !important;
                }

                /* Hover Background */
                #directorist.atbd_wrapper .btn-success:hover{
                    background: <?php echo ! empty( $back_success_hover_color ) ? $back_success_hover_color : '#2ba251'; ?> !important;
                }

                /* Border Color */
                #directorist.atbd_wrapper .btn-success{
                    border-color: <?php echo ! empty( $border_success_color ) ? $border_success_color : '#32cc6f'; ?> !important;
                }

                /* Hover Border Color */
                #directorist.atbd_wrapper .btn-success:hover{
                    border-color: <?php echo ! empty( $border_success_hover_color ) ? $border_success_hover_color : '#2ba251'; ?> !important;
                }

                /* =======================================
                Button: primary outline
                ======================================== */

                /* color */
                .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter, #directorist.atbd_wrapper .btn-outline-primary, .atbd_dropdown .atbd_dropdown-toggle{
                    color: <?php echo ! empty( $priout_color ) ? $priout_color : '#444752'; ?> !important;
                }
                /* color hover */
                .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter:hover, #directorist.atbd_wrapper .btn-outline-primary:hover, .atbd_dropdown .atbd_dropdown-toggle:hover{
                    color: <?php echo ! empty( $priout_hover_color ) ? $priout_hover_color : '#444752'; ?> !important;
                }

                /* border color */
                .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter, #directorist.atbd_wrapper .btn-outline-primary, .atbd_dropdown .atbd_dropdown-toggle{
                    border: 1px solid <?php echo ! empty( $border_priout_color ) ? $border_priout_color : '#444752'; ?> !important;
                }
                .atbd_dropdown .atbd_dropdown-toggle .atbd_drop-caret:before{
                    border-left: 1px solid <?php echo ! empty( $border_priout_color ) ? $border_priout_color : '#444752'; ?> !important;
                    border-bottom: 1px solid <?php echo ! empty( $border_priout_color ) ? $border_priout_color : '#444752'; ?> !important;
                }
                /* border color hover */
                .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter:hover, #directorist.atbd_wrapper .btn-outline-primary:hover, .atbd_dropdown .atbd_dropdown-toggle:hover{
                    border-color: <?php echo ! empty( $border_priout_hover_color ) ? $border_priout_hover_color : '#9299b8'; ?> !important;
                }
                .atbd_dropdown .atbd_dropdown-toggle:hover .atbd_drop-caret:before{
                    border-left-color: <?php echo ! empty( $border_priout_hover_color ) ? $border_priout_hover_color : '#9299b8'; ?> !important;
                    border-bottom-color: <?php echo ! empty( $border_priout_hover_color ) ? $border_priout_hover_color : '#9299b8'; ?> !important;
                }
                /* background */
                .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter, #directorist.atbd_wrapper .btn-outline-primary, .atbd_dropdown .atbd_dropdown-toggle{
                    background: <?php echo ! empty( $back_priout_color ) ? $back_priout_color : '#fff'; ?> !important;
                }
                /* background hover */
                .atbd_content_active #directorist.atbd_wrapper .atbd_submit_btn_wrapper .more-filter:hover, #directorist.atbd_wrapper .btn-outline-primary:hover, .atbd_dropdown .atbd_dropdown-toggle:hover{
                    background: <?php echo ! empty( $back_priout_hover_color ) ? $back_priout_hover_color : '#fff'; ?> !important;
                }

                /* =======================================
                Button: primary outline LIGHT
                ======================================== */

                /* color */
                .atbdp_float_none .btn.btn-outline-light, .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action, .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action a{
                    color: <?php echo ! empty( $prioutlight_color ) ? $prioutlight_color : '#444752'; ?> !important;
                }
                /* color hover */
                .atbdp_float_none .btn.btn-outline-light:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action a:hover{
                    color: <?php echo ! empty( $prioutlight_hover_color ) ? $prioutlight_hover_color : '#ffffff'; ?> !important;
                }

                /* border color */
                .atbdp_float_none .btn.btn-outline-light, .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action{
                    border: 1px solid <?php echo ! empty( $border_prioutlight_color ) ? $border_prioutlight_color : '#e3e6ef'; ?> !important;
                }
                /* border color hover */
                .atbdp_float_none .btn.btn-outline-light:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action:hover{
                    border-color: <?php echo ! empty( $border_prioutlight_hover_color ) ? $border_prioutlight_hover_color : '#444752'; ?> !important;
                }
                /* background */
                .atbdp_float_none .btn.btn-outline-light, .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action{
                    background: <?php echo ! empty( $back_prioutlight_color ) ? $back_prioutlight_color : '#fff'; ?> !important;
                }
                /* background hover */
                .atbdp_float_none .btn.btn-outline-light:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_content_module__tittle_area .atbd_listing_action_area .atbd_action:hover{
                    background: <?php echo ! empty( $back_prioutlight_hover_color ) ? $back_prioutlight_hover_color : '#444752'; ?> !important;
                }


                /* =======================================
                Button: Danger outline
                ======================================== */

                /* color */
                #directorist.atbd_wrapper .btn-outline-danger{
                    color: <?php echo ! empty( $danout_color ) ? $danout_color : '#e23636'; ?> !important;
                }
                /* color hover */
                #directorist.atbd_wrapper .btn-outline-danger:hover{
                    color: <?php echo ! empty( $danout_hover_color ) ? $danout_hover_color : '#fff'; ?> !important;
                }
                /* border color */
                #directorist.atbd_wrapper .btn-outline-danger{
                    border: 1px solid <?php echo ! empty( $border_danout_color ) ? $border_danout_color : '#e23636'; ?> !important;
                }
                /* border color hover */
                #directorist.atbd_wrapper .btn-outline-danger:hover{
                    border-color: <?php echo ! empty( $border_danout_hover_color ) ? $border_danout_hover_color : '#e23636'; ?> !important;
                }

                /* background */
                #directorist.atbd_wrapper .btn-outline-danger{
                    background: <?php echo ! empty( $back_danout_color ) ? $back_danout_color : '#fff'; ?> !important;
                }
                /* background hover */
                #directorist.atbd_wrapper .btn-outline-danger:hover{
                    background: <?php echo ! empty( $back_danout_hover_color ) ? $back_danout_hover_color : '#e23636'; ?> !important;
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
                    background: <?php echo ! empty( $open_back_color ) ? $open_back_color : '#32cc6f'; ?> !important;
                }

                /* Badge Closed */
                .atbd_bg-danger, .atbd_content_active #directorist.atbd_wrapper .atbd_give_review_area #atbd_up_preview .atbd_up_prev .rmrf:hover, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_close, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_close, .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_close, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_close {
                    background: <?php echo ! empty( $closed_back_color ) ? $closed_back_color : '#e23636'; ?> !important;
                }

                /* Badge Featured */
                .directorist-badge-featured, .atbd_bg-badge-feature, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_featured, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_featured, .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_featured, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_featured {
                    background: <?php echo ! empty( $featured_back_color ) ? $featured_back_color : '#fa8b0c'; ?>  !important;
                }

                /* Badge Popular */
                .atbd_bg-badge-popular, .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_popular, .atbd_content_active .widget.atbd_widget[id^='bd'] .atbd_badge.atbd_badge_popular, .atbd_content_active .widget.atbd_widget[id^='dcl'] .atbd_badge.atbd_badge_popular, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbd_badge.atbd_badge_popular {
                    background: <?php echo ! empty( $popular_back_color ) ? $popular_back_color : '#f51957'; ?> !important;
                }

                /* Badge New */
                .atbd_content_active #directorist.atbd_wrapper .atbd_badge.atbd_badge_new {
                    background: <?php echo ! empty( $new_back_color ) ? $new_back_color : '#122069'; ?> !important;
                }

                /*
                    Change default primary dark background
                */
                .ads-advanced .price-frequency .pf-btn input:checked + span, .btn-checkbox label input:checked + span, .atbdpr-range .ui-slider-horizontal .ui-slider-range, .custom-control .custom-control-input:checked ~ .check--select, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_meta .atbd_listing_rating, .atbd_content_active #directorist.atbd_wrapper .atbd_listing_meta .atbd_listing_price, #directorist.atbd_wrapper .pagination .nav-links .current, .atbd_content_active #directorist.atbd_wrapper .atbd_contact_information_module .atbd_director_social_wrap a, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp-widget-categories > ul.directorist-parent-category > li:hover > a span, .atbd_content_active #directorist.atbd_wrapper .widget.atbd_widget .atbdp.atbdp-widget-tags ul li a:hover{
                    background: <?php echo ! empty( $primary_dark_back_color ) ? $primary_dark_back_color : '#444752'; ?> !important;
                }

                /*
                    Change default primary dark border
                */
                .ads-advanced .price-frequency .pf-btn input:checked + span, .btn-checkbox label input:checked + span, .atbdpr-range .ui-slider-horizontal .ui-slider-handle, .custom-control .custom-control-input:checked ~ .check--select, .custom-control .custom-control-input:checked ~ .radio--select, #atpp-plan-change-modal .atm-contents-inner .dcl_pricing_plan input:checked + label:before, #dwpp-plan-renew-modal .atm-contents-inner .dcl_pricing_plan input:checked + label:before{
                    border-color: <?php echo ! empty( $primary_dark_border_color ) ? $primary_dark_border_color : '#444752'; ?> !important;
                }


                /*
                    Map Marker Icon Colors
                */
                /* Marker Shape color */
                .atbd_map_shape{
                    background: <?php echo ! empty( $marker_shape_color ) ? $marker_shape_color : '#444752'; ?>  !important;
                }
                .atbd_map_shape:before{
                    border-top-color: <?php echo ! empty( $marker_shape_color ) ? $marker_shape_color : '#444752'; ?>  !important;
                }

                /* Marker icon color */
                .map-icon-label i, .atbd_map_shape > span {
                    color: <?php echo ! empty( $marker_icon_color ) ? $marker_icon_color : '#444752'; ?> !important;
                }
            <?php

            return ob_get_clean();
        }
    }
endif;