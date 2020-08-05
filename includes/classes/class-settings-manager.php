<?php
if (!class_exists('ATBDP_Settings_Manager')):
    class ATBDP_Settings_Manager
    {

        private $extension_url = '';

        public function __construct()
        {
            // the safest hook to use is after_setup_theme, since Vafpress Framework may exists in Theme or Plugin
            add_action('after_setup_theme', array($this, 'display_plugin_settings'));
            $this->extension_url = sprintf("<a target='_blank' href='%s'>%s</a>", esc_url(admin_url('edit.php?post_type=at_biz_dir&page=atbdp-extension')), __('Checkout Awesome Extensions', 'directorist'));
        }

        /**
         * It displays the settings page of the plugin using VafPress framework
         * @return void
         **@since 3.0.0
         */
        public function display_plugin_settings()
        {
            $atbdp_options = array(
                //'is_dev_mode' => true,
                'option_key' => 'atbdp_option',
                'page_slug' => 'aazztech_settings',
                'menu_page' => 'edit.php?post_type=at_biz_dir',
                'use_auto_group_naming' => true,
                'use_util_menu' => true, // show import and export menu
                'minimum_role' => 'manage_options',
                'layout' => 'fixed',
                'page_title' => __('Directory Settings', 'directorist'),
                'menu_label' => __('Directory Settings', 'directorist'),
                'template' => array(
                    'title' => __('Directory Settings', 'directorist'),
                    'logo' => esc_url(ATBDP_ADMIN_ASSETS . 'images/settings_icon.png'),
                    'menus' => $this->get_settings_menus(),
                ),
            );

            // initialize the option page
            new VP_Option($atbdp_options);
        }

        /**
         * Get all the menus for the Settings Page
         * @return array It returns an array of Menus
         * @since 3.0.0
         */
        function get_settings_menus()
        {
            return apply_filters('atbdp_settings_menus', array(
                /*Main Menu 1*/
                'listings' => array(
                    'name' => 'listings',
                    'title' => __('Listing Settings', 'directorist'),
                    'icon' => 'font-awesome:fa-list',
                    'menus' => $this->get_listings_settings_submenus(),
                ),
                /*Main Menu 3*/
                'search' => array(
                    'name' => 'search',
                    'title' => __('Search Settings', 'directorist'),
                    'icon' => 'font-awesome:fa-search',
                    'controls' => apply_filters('atbdp_search_settings_controls', array(
                        'search_section' => array(
                            'type' => 'section',
                            'title' => __('Search Form Settings', 'directorist'),
                            'description' => __('You can Customize Search Form related settings here. After switching any option, Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->get_search_settings_fields(),
                        ), // ends 'search_settings' section
                        'search_result' => array(
                            'type' => 'section',
                            'title' => __('Search Result Settings', 'directorist'),
                            'description' => __('You can Customize Search Result related settings here. After switching any option, Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->get_search_form_settings_fields(),
                        ), // ends 'search_settings' section
                    )),
                ),
                /*Main Menu 4*/
                'pages' => array(
                    'name' => 'pages',
                    'title' => __('Pages, Links & Views', 'directorist'),
                    'icon' => 'font-awesome:fa-line-chart',
                    'controls' => apply_filters('atbdp_pages_settings_controls', array(
                        'page_section' => array(
                            'type' => 'section',
                            'title' => __('Upgrade/Regenerate Pages', 'directorist'),
                            'description' => __('If you are an existing user of the directorist, you have to upgrade your Directorist pages shortcode.', 'directorist'),
                            'fields' => $this->get_pages_regenerate_settings_fields(),
                        ),
                        'search_section' => array(
                            'type' => 'section',
                            'title' => __('Pages, links & views Settings', 'directorist'),
                            'description' => __('You can Customize Listings related settings here. After switching any option, Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->get_pages_settings_fields(),
                        ), // ends 'pages' section

                    )),
                ),
                /*Main Menu 5*/
                'seo_menu' => array(
                    'title' => __('Titles & Metas', 'directorist'),
                    'name' => 'seo_settings',
                    'icon' => 'font-awesome:fa-bolt',
                    'controls' => apply_filters('atbdp_seo_settings_controls', array(
                        'seo_section' => array(
                            'type' => 'section',
                            'title' => __('Titles & Metas', 'directorist'),
                            'fields' => $this->get_seo_settings_fields(),
                        ),
                    )),
                ),

                /*Main Menu 5*/
                'general_menu' => array(
                    'title' => __('Currency Settings', 'directorist'),
                    'name' => 'currency_settings',
                    'icon' => 'font-awesome:fa-money',
                    'controls' => apply_filters('atbdp_currency_settings_controls', array(
                        'currency_section' => array(
                            'type' => 'section',
                            'title' => __('Currency Settings', 'directorist'),
                            'fields' => $this->get_currency_settings_fields(),
                        ),
                    )),
                ),
                /*Main Menu 6*/
                'categories_menu' => array(
                    'title' => __('Categories & Locations Page', 'directorist'),
                    'name' => 'categories_menu',
                    'icon' => 'font-awesome:fa-list-alt',
                    'controls' => apply_filters('atbdp_categories_settings_controls', array(

                        'category_section' => array(
                            'type' => 'section',
                            'title' => __('Categories Page Setting', 'directorist'),
                            'fields' => $this->get_categories_settings_fields(),
                        ),
                        'location_section' => array(
                            'type' => 'section',
                            'title' => __('Locations Page Setting', 'directorist'),
                            'fields' => $this->get_locations_settings_fields()
                        ),
                    )),
                ),
                /*Lets make the following extension menu customization by the extensions. Apply a filter on it*/
                'extensions_menu' => array(
                    'title' => __('Extensions Settings', 'directorist'),
                    'name' => 'menu_1',
                    'icon' => 'font-awesome:fa-magic',
                    'menus' => $this->get_extension_settings_submenus(),
                ),
                /*lets make the settings for email*/
                'email_menu' => array(
                    'title' => __('Emails Settings', 'directorist'),
                    'name' => 'email_menu1',
                    'icon' => 'font-awesome:fa-envelope',
                    'menus' => $this->get_email_settings_submenus(),
                ),
                /*lets make the settings for registration & login*/
                'registration_login_menu' => array(
                    'title' => __('Registration & Login', 'directorist'),
                    'name' => 'registration_login',
                    'icon' => 'font-awesome:fa-align-right',
                    'menus' => $this->get_reg_log_settings_submenus(),
                ),
                /*lets make the settings for style settngs*/
                'style_settings_menu' => array(
                    'title' => __('Style Settings', 'directorist'),
                    'name' => 'style_settings',
                    'icon' => 'font-awesome:fa-adjust',
                    'menus'=> $this->get_style_settings_submenus(),
                ),
            ));
        }
        /**
         * Get all the submenus for Style settings menu
         * @return array It returns an array of submenus
         * @since 6.2.3
         */
        public function get_style_settings_submenus()
        {
            return apply_filters('atbdp_style_settings_submenus', array(
                /*Submenu : Color Settings */
                array(
                    'title' => __('Color', 'directorist'),
                    'name' => 'color_settings',
                    'icon' => 'font-awesome:fa-home',
                    'controls' => apply_filters('atbdp_style_settings_controls', array(
                        'button_type' => array(
                            'type' => 'section',
                            'title' => __('Button Color', 'directorist'),
                            'fields' => $this->get_listings_button_type_fields(),
                        ),

                        'badge_color' => array(
                            'type' => 'section',
                            'title' => __('Badge Color', 'directorist'),
                            'fields' => $this->get_listings_badge_color_fields(),
                        ),
                        'map_marker' => array(
                            'type' => 'section',
                            'title' => __('All Listings Map Marker', 'directorist'),
                            'fields' => $this->get_listings_map_marker_color_fields(),
                        ),
                        'primary_dark' => array(
                            'type' => 'section',
                            'title' => __('Primary Color', 'directorist'),
                            'fields' => $this->get_listings_primary_dark_fields(),
                        ),
                    )),
                ),
                /*Submenu : single template settings*/
                array(
                    'title' => __('Single Template', 'directorist'),
                    'name' => 'single_template',
                    'icon' => 'font-awesome:fa-home',
                    'controls' => apply_filters('atbdp_single_template_controls', array(
                        'maximum_width' => array(
                            'type' => 'section',
                            'fields' => $this->get_single_template_max_width(),
                        ),
                        'single_temp_padding' => array(
                            'type' => 'section',
                            'title' => __('Padding (px)', 'directorist'),
                            'fields' => $this->get_single_template_padding(),
                        ),
                        'single_temp_margin' => array(
                            'type' => 'section',
                            'title' => __('Margin (px)', 'directorist'),
                            'fields' => $this->get_single_template_margin(),
                        ),
                    )),
                ),
            )
            );
        }
        /*
         * Maximum width of single template
         * @return array
         * @since 6.2.3
         * */
        public function get_single_template_max_width() {
            return apply_filters('atbdp_single_template_max_width', array(
                array(
                    'type' => 'textbox',
                    'name' => 'single_temp_max_width',
                    'label' => __('Maximum Width (px)', 'directorist'),
                    'default' => '1080',
                ),
            ));
        }
        /*
         * Padding of single template
         * @return array
         * @since 6.2.3
         * */
        public function get_single_template_padding() {
            return apply_filters('atbdp_single_template_padding', array(
                array(
                    'type' => 'textbox',
                    'name' => 'single_temp_padding_top',
                    'label' => __('Top', 'directorist'),
                    'default' => '30',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'single_temp_padding_bottom',
                    'label' => __('Bottom', 'directorist'),
                    'default' => '50',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'single_temp_padding_left',
                    'label' => __('Left', 'directorist'),
                    'default' => '4',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'single_temp_padding_right',
                    'label' => __('Right', 'directorist'),
                    'default' => '4',
                ),
            ));
        }
        /*
         * Padding of single template
         * @return array
         * @since 6.2.3
         * */
        public function get_single_template_margin() {
            return apply_filters('atbdp_single_template_margin', array(
                array(
                    'type' => 'textbox',
                    'name' => 'single_temp_margin_top',
                    'label' => __('Top', 'directorist'),
                    'default' => '4',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'single_temp_margin_bottom',
                    'label' => __('Bottom', 'directorist'),
                    'default' => '50',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'single_temp_margin_left',
                    'label' => __('Left', 'directorist'),
                    'default' => '4',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'single_temp_margin_right',
                    'label' => __('Right', 'directorist'),
                    'default' => '4',
                ),
            ));
        }
        /**
         * Get all the submenus for registration & login menu
         * @return array It returns an array of submenus
         * @since 5.1.0
         */
        public function get_reg_log_settings_submenus()
        {
            return apply_filters('atbdp_reg_log_settings_submenus', array(
                /*Submenu : Registration Settings */
                array(
                    'title' => __('Registration Form', 'directorist'),
                    'name' => 'registration_form',
                    'icon' => 'font-awesome:fa-home',
                    'controls' => apply_filters('atbdp_registration_settings_controls', array(
                        'reg_username_field' => array(
                            'type' => 'section',
                            'title' => __('Username', 'directorist'),
                            'fields' => $this->get_reg_username_field(),
                        ),
                        'reg_password_field' => array(
                            'type' => 'section',
                            'title' => __('Password', 'directorist'),
                            'fields' => $this->get_reg_password_field(),
                        ),
                        'reg_email_field' => array(
                            'type' => 'section',
                            'title' => __('Email', 'directorist'),
                            'fields' => $this->get_reg_email_field(),
                        ),
                        'reg_website_field' => array(
                            'type' => 'section',
                            'title' => __('Website', 'directorist'),
                            'fields' => $this->get_reg_website_field(),
                        ),
                        'reg_fname_field' => array(
                            'type' => 'section',
                            'title' => __('First Name', 'directorist'),
                            'fields' => $this->get_reg_fname_field(),
                        ),
                        'reg_lname_field' => array(
                            'type' => 'section',
                            'title' => __('Last Name', 'directorist'),
                            'fields' => $this->get_reg_lname_field(),
                        ),
                        'reg_bio_field' => array(
                            'type' => 'section',
                            'title' => __('About/bio', 'directorist'),
                            'fields' => $this->get_reg_bio_field(),
                        ),
                        'privacy_field' => array(
                            'type' => 'section',
                            'title' => __('Privacy Policy', 'directorist'),
                            'fields' => $this->get_privacy_field(),
                        ),
                        'terms_conditions_field' => array(
                            'type' => 'section',
                            'title' => __('Terms and Conditions', 'directorist'),
                            'fields' => $this->get_terms_conditions_field(),
                        ),
                        'signUp_button' => array(
                            'type' => 'section',
                            'title' => __('Sign Up Button', 'directorist'),
                            'fields' => $this->get_reg_signUp_button(),
                        ),
                        'login_text' => array(
                            'type' => 'section',
                            'title' => __('Login Message', 'directorist'),
                            'fields' => $this->get_reg_Login_text(),
                        ),
                    )),
                ),
                /*Submenu : login form settings*/
                array(
                    'title' => __('Login Form', 'directorist'),
                    'name' => 'login_form',
                    'icon' => 'font-awesome:fa-home',
                    'controls' => apply_filters('atbdp_login_form_settings_controls', array(
                        'log_username' => array(
                            'type' => 'section',
                            'title' => __('Username or Email Address', 'directorist'),
                            'fields' => $this->get_user_email_login(),
                        ),
                        'log_password' => array(
                            'type' => 'section',
                            'title' => __('Password', 'directorist'),
                            'fields' => $this->get_password_login(),
                        ),
                        'log_remember' => array(
                            'type' => 'section',
                            'title' => __('Remember Login Information', 'directorist'),
                            'fields' => $this->get_remember_login(),
                        ),
                        'log_loginButton' => array(
                            'type' => 'section',
                            'title' => __('Log In Button', 'directorist'),
                            'fields' => $this->get_button_login(),
                        ),
                        'log_signup' => array(
                            'type' => 'section',
                            'title' => __('Sign Up Message', 'directorist'),
                            'fields' => $this->get_signup_login(),
                        ),
                        'log_recoverPassword' => array(
                            'type' => 'section',
                            'title' => __('Recover Password', 'directorist'),
                            'fields' => $this->get_recoverPassword_login(),
                        ),
                        'login_redirection' =>  array(
                            'type' => 'select',
                            'name' => 'redirection_after_login',
                            'label' => __('Redirection after Login', 'directorist'),
                            'items' => $this->get_pages_vl_arrays(),
                            'default' => atbdp_get_option('user_dashboard', 'atbdp_general'),
                            'validation' => 'numeric',
                        ),
                    )),
                ),
            ));
        }

        /**
         * Get login text setting field for login form
         * @return array
         * @since 5.1.0
         */
        public function get_reg_Login_text()
        {
            return apply_filters('atbdp_log_signup_text_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_login',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'login_text',
                    'label' => __('Text', 'directorist'),
                    'default' => __('Already have an account? Please login', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'log_linkingmsg',
                    'label' => __('Linking Text', 'directorist'),
                    'default' => __('Here', 'directorist'),
                ),
            ));
        }

        /**
         * Get username setting field for login form
         * @return array
         * @since 5.1.0
         */
        public function get_user_email_login()
        {
            return apply_filters('atbdp_log_username_field_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'log_username',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Username or Email Address', 'directorist'),
                ),

            ));
        }

        /**
         * Get password field for login form
         * @return array
         * @since 5.1.0
         */
        public function get_password_login()
        {
            return apply_filters('atbdp_log_password_field_setting', array(

                array(
                    'type' => 'textbox',
                    'name' => 'log_password',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Password', 'directorist'),
                ),

            ));
        }

        /**
         * Get remember me text setting field for login form
         * @return array
         * @since 5.1.0
         */
        public function get_remember_login()
        {
            return apply_filters('atbdp_log_remember_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_rememberme',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'log_rememberme',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Remember Me', 'directorist'),
                ),
            ));
        }

        /**
         * Get login button text setting field for login form
         * @return array
         * @since 5.1.0
         */
        public function get_button_login()
        {
            return apply_filters('atbdp_log_login_button_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'log_button',
                    'label' => __('Text', 'directorist'),
                    'default' => __('Log In', 'directorist'),
                ),
            ));
        }

        /**
         * Get sign up setting field for login form
         * @return array
         * @since 5.1.0
         */
        public function get_signup_login()
        {
            return apply_filters('atbdp_log_signup_text_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_signup',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'reg_text',
                    'label' => __('Text', 'directorist'),
                    'default' => __('Don\'t have an account?', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'reg_linktxt',
                    'label' => __('Linking Text', 'directorist'),
                    'default' => __('Sign Up', 'directorist'),
                ),
            ));
        }

        /**
         * Get recover password setting field for login form
         * @return array
         * @since 5.1.0
         */
        public function get_recoverPassword_login()
        {
            return apply_filters('atbdp_log_rec_pass_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_recpass',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'recpass_text',
                    'label' => __('Name', 'directorist'),
                    'default' => __('Recover Password', 'directorist'),
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'recpass_desc',
                    'label' => __('Description', 'directorist'),
                    'default' => __('Lost your password? Please enter your email address. You will receive a link to create a new password via email.', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'recpass_username',
                    'label' => __('Email Label', 'directorist'),
                    'default' => __('E-mail', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'recpass_placeholder',
                    'label' => __('Username or Email Placeholder', 'directorist'),
                    'default' => __('eg. mail@example.com', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'recpass_button',
                    'label' => __('Button Text', 'directorist'),
                    'default' => __('Get New Password', 'directorist'),
                ),
            ));
        }

        /**
         *
         * /**
         * Get username setting field for registration
         * @return array
         * @since 5.1.0
         */
        public function get_reg_username_field()
        {
            return apply_filters('atbdp_reg_username_field_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'reg_username',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Username', 'directorist'),
                ),

            ));
        }

        /**
         * Get password setting field for registration
         * @return array
         * @since 5.1.0
         */
        public function get_reg_password_field()
        {
            return apply_filters('atbdp_reg_password_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_password_reg',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'reg_password',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Password', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_password_reg',
                    'label' => __('Required', 'directorist'),
                    'default' => 1,
                ),
            ));
        }

        /**
         * Get email setting field for registration
         * @return array
         * @since 5.1.0
         */
        public function get_reg_email_field()
        {
            return apply_filters('atbdp_reg_email_field_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'reg_email',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Email', 'directorist'),
                ),

            ));
        }

        /**
         * Get website setting field for registration
         * @return array
         * @since 5.1.0
         */
        public function get_reg_website_field()
        {
            return apply_filters('atbdp_reg_website_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_website_reg',
                    'label' => __('Enable', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'reg_website',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Website', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_website_reg',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get First Name setting field for registration
         * @return array
         * @since 5.1.0
         */
        public function get_reg_fname_field()
        {
            return apply_filters('atbdp_reg_fname_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_fname_reg',
                    'label' => __('Enable', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'reg_fname',
                    'label' => __('Label', 'directorist'),
                    'default' => __('First Name', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_fname_reg',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get Last Name setting field for registration
         * @return array
         * @since 5.1.0
         */
        public function get_reg_lname_field()
        {
            return apply_filters('atbdp_reg_lname_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_lname_reg',
                    'label' => __('Enable', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'reg_lname',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Last Name', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_lname_reg',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get bio setting field for registration
         * @return array
         * @since 5.1.0
         */
        public function get_reg_bio_field()
        {
            return apply_filters('atbdp_reg_bio_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_bio_reg',
                    'label' => __('Enable', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'reg_bio',
                    'label' => __('Label', 'directorist'),
                    'default' => __('About/bio', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_bio_reg',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),

            ));
        }


        /**
         * Get bio setting field for registration
         * @return array
         * @since 6.3
         */
        public function get_privacy_field()
        {
            return apply_filters('atbdp_registration_privacy_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'registration_privacy',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'registration_privacy_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('I agree to the', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'registration_privacy_label_link',
                    'label' => __('Linking Text', 'directorist'),
                    'default' => __('Privacy & Policy', 'directorist'),
                ),
            ));
        }

        /**
         * Get bio setting field for registration
         * @return array
         * @since 6.3
         */
        public function get_terms_conditions_field()
        {
            return apply_filters('atbdp_registration_terms_conditions_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'regi_terms_condition',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'regi_terms_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('I agree with all', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'regi_terms_label_link',
                    'label' => __('Linking Text', 'directorist'),
                    'default' => __('terms & conditions', 'directorist'),
                ),
            ));
        }

        /**
         * Get signup button setting field for registration
         * @return array
         * @since 5.1.0
         */
        public function get_reg_signUp_button()
        {
            return apply_filters('atbdp_reg_signUp_button_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'reg_signup',
                    'label' => __('Text', 'directorist'),
                    'default' => __('Sign Up', 'directorist'),
                ),

            ));
        }

        /**
         * Get all the submenus for listings
         * @return array It returns an array of submenus
         * @since 4.0.0
         */
        public function get_listings_settings_submenus()
        {
            return apply_filters('atbdp_general_listings_submenus', array(
                /*Submenu : General Settings*/
                array(
                    'title' => __('General Settings', 'directorist'),
                    'name' => 'general_listings',
                    'icon' => 'font-awesome:fa-sliders',
                    'controls' => apply_filters('atbdp_general_listings_controls', array(
                        'emails' => array(
                            'type' => 'section',
                            'title' => __('General Settings', 'directorist'),
                            'description' => __('You can Customize general settings here', 'directorist'),
                            'fields' => $this->get_general_listings_settings_fields(),
                        )
                    )),
                ),

                /*Submenu : Listing form */
                array(
                    'title' => __('Listings Page', 'directorist'),
                    'name' => 'listings_page',
                    'icon' => 'font-awesome:fa-archive',
                    'controls' => apply_filters('atbdp_listings_page_controls', array(
                        'emails' => array(
                            'type' => 'section',
                            'title' => __('Listings Page', 'directorist'),
                            'description' => __('You can Customize Listings page related settings here', 'directorist'),
                            'fields' => $this->get_archive_page_settings_fields(),
                        ),
                    )),
                ),

                /*Submenu : Listing form */
                array(
                    'title' => __('Single Listing', 'directorist'),
                    'name' => 'listings_form',
                    'icon' => 'font-awesome:fa-info',
                    'controls' => apply_filters('atbdp_listings_form_controls', array(
                        'emails' => array(
                            'type' => 'section',
                            'title' => __('Single Listing', 'directorist'),
                            'description' => __('You can Customize Listings Form related settings here', 'directorist'),
                            'fields' => $this->get_listings_form_settings_fields(),
                        ),
                    )),
                ),

                /*Submenu : Badge Management */
                array(
                    'title' => __('Badge Setting', 'directorist'),
                    'name' => 'badge_management',
                    'icon' => 'font-awesome:fa-certificate',
                    'controls' => apply_filters('atbdp_badge_controls', array(
                        'badges' => array(
                            'type' => 'section',
                            'title' => __('Badge Management', 'directorist'),
                            'description' => __('You can Customize Badge here', 'directorist'),
                            'fields' => $this->get_badge_settings_fields(),
                        ),
                        'popular_badge' => array(
                            'type' => 'section',
                            'title' => __('Popular Badge', 'directorist'),
                            'description' => __('You can Customize Popular Badge here', 'directorist'),
                            'fields' => $this->get_popular_badge_settings_fields(),
                        ),
                    )),
                ),
                /*Submenu : Review */
                array(
                    'title' => __('Review Setting', 'directorist'),
                    'name' => 'review_setting',
                    'icon' => 'font-awesome:fa-star',
                    'controls' => apply_filters('atbdp_review_controls', array(
                        'emails' => array(
                            'type' => 'section',
                            'title' => __('Review Setting', 'directorist'),
                            'fields' => $this->get_listings_review_settings_fields(),
                        ),
                    )),
                ),

                /*Submenu : Form */
                array(
                    'title' => __('Form Fields', 'directorist'),
                    'name' => 'form_fields_setting',
                    'icon' => 'font-awesome:fa-adjust',
                    'controls' => apply_filters('atbdp_form_fields_controls', array(
                        'title_field' => array(
                            'type' => 'section',
                            'title' => __('Title', 'directorist'),
                            'fields' => $this->get_listings_title_field_settings(),
                        ),
                        'desc_field' => array(
                            'type' => 'section',
                            'title' => __('Long Description', 'directorist'),
                            'fields' => $this->get_listings_desc_field_settings(),
                        ),
                        'tagline_field' => array(
                            'type' => 'section',
                            'title' => __('Tagline', 'directorist'),
                            'fields' => $this->get_listings_tagline_field_settings(),
                        ),
                        'pricing_field' => array(
                            'type' => 'section',
                            'title' => __('Pricing', 'directorist'),
                            'fields' => $this->get_listings_pricing_field_settings(),
                        ),
                        'views_count' => array(
                            'type' => 'section',
                            'title' => __('Views Count', 'directorist'),
                            'fields' => $this->get_listings_views_count_settings(),
                        ),
                        's_desc_field' => array(
                            'type' => 'section',
                            'title' => __('Short Description / Excerpt', 'directorist'),
                            'fields' => $this->get_listings_short_desc_field_settings(),
                        ),
                        'loc_field' => array(
                            'type' => 'section',
                            'title' => __('Location', 'directorist'),
                            'fields' => $this->get_listings_loc_field_settings(),
                        ),
                        'tag_field' => array(
                            'type' => 'section',
                            'title' => __('Tag', 'directorist'),
                            'fields' => $this->get_listings_tag_field_settings(),
                        ),
                        'cat_field' => array(
                            'type' => 'section',
                            'title' => __('Category', 'directorist'),
                            'fields' => $this->get_listings_cat_field_settings(),
                        ),
                        'contact_info' => array(
                            'type' => 'section',
                            'title' => __('Contact Information Hiding Option', 'directorist'),
                            'fields' => $this->get_listings_contact_info_settings(),
                        ),
                        'address_field' => array(
                            'type' => 'section',
                            'title' => __('Address', 'directorist'),
                            'fields' => $this->get_listings_address_field_settings(),
                        ),
                        'map_field' => array(
                            'type' => 'section',
                            'title' => __('Map', 'directorist'),
                            'fields' => $this->get_listings_map_field_settings(),
                        ),
                        'zip_field' => array(
                            'type' => 'section',
                            'title' => __('Zip/Post Code', 'directorist'),
                            'fields' => $this->get_listings_zip_field_settings(),
                        ),
                        'phone_field' => array(
                            'type' => 'section',
                            'title' => __('Phone Number', 'directorist'),
                            'fields' => $this->get_listings_phone_field_settings(),
                        ),
                        'phone_field2' => array(
                            'type' => 'section',
                            'title' => __('Phone Number 2', 'directorist'),
                            'fields' => $this->get_listings_phone_field2_settings(),
                        ),
                        'pax' => array(
                            'type' => 'section',
                            'title' => __('Fax', 'directorist'),
                            'fields' => $this->get_listings_fax_settings(),
                        ),
                        'email_field' => array(
                            'type' => 'section',
                            'title' => __('Email', 'directorist'),
                            'fields' => $this->get_listings_email_field_settings(),
                        ),
                        'website_field' => array(
                            'type' => 'section',
                            'title' => __('Website', 'directorist'),
                            'fields' => $this->get_listings_website_field_settings(),
                        ),
                        'social_field' => array(
                            'type' => 'section',
                            'title' => __('Social Info', 'directorist'),
                            'fields' => $this->get_listings_social_field_settings(),
                        ),
                        'img_field' => array(
                            'type' => 'section',
                            'title' => __('Image', 'directorist'),
                            'fields' => $this->get_listings_image_field_settings(),
                        ),
                        'video_field' => array(
                            'type' => 'section',
                            'title' => __('Video', 'directorist'),
                            'fields' => $this->get_listings_video_field_settings(),
                        ),
                        'terms_field' => array(
                            'type' => 'section',
                            'title' => __('Terms and Conditions', 'directorist'),
                            'fields' => $this->get_listings_terms_field_settings(),
                        ),
                        'privecy_field' => array(
                            'type' => 'section',
                            'title' => __('Privacy and Policy', 'directorist'),
                            'fields' => $this->get_listings_privacy_field_settings(),
                        ),
                          'guest_field' => array(
                              'type' => 'section',
                              'title' => __('Guest Submission', 'directorist'),
                              'fields' => $this->get_guest_listings_settings(),
                          ),
                        'submit_field' => array(
                              'type' => 'section',
                              'title' => __('Submit', 'directorist'),
                              'fields' => $this->get_listings_submit_settings(),
                          ),
                    )),
                ),

                /*Submenu : Deshboard */
                array(
                    'title' => __('Map Setting', 'directorist'),
                    'name' => 'map_setting',
                    'icon' => 'font-awesome:fa-map-signs',
                    'controls' => apply_filters('atbdp_dashboard_controls', array(
                        'map_settings' => array(
                            'type' => 'section',
                            'title' => __('Map Setting', 'directorist'),
                            'fields' => $this->get_listings_map_settings_fields(),
                        ),
                        'map_info_window' => array(
                            'type' => 'section',
                            'title' => __('Map Info Window Setting', 'directorist'),
                            'fields' => $this->get_listings_map_info_settings_fields(),
                        ),
                    )),
                ),

                /*Submenu : Deshboard */
                array(
                    'title' => __('User Dashboard', 'directorist'),
                    'name' => 'dashboard_setting',
                    'icon' => 'font-awesome:fa-bar-chart',
                    'controls' => apply_filters('atbdp_dashboard_controls', array(
                        'emails' => array(
                            'type' => 'section',
                            'title' => __('User Dashboard', 'directorist'),
                            'fields' => $this->get_listings_dashboard_settings_fields(),
                        ),
                    )),
                ),

                /*Submenu : Style */
                /*'style_setting'=> array(
                    'title' => __('Style Setting', 'directorist'),
                    'name' => 'style_setting',
                    'icon' => 'font-awesome:fa-adjust',
                    'controls' => apply_filters('atbdp_style_controls', array(
                        'emails' => array(
                            'type' => 'section',
                            'title' => __('Style Setting', 'directorist'),
                            'fields' => $this->get_listings_style_settings_fields(),
                        ),
                    )),
                ),*/
            ));
        }


        /**
         * Get all the settings for primary button
         * @return array It returns an array of submenus
         * @since 5.10.0
         */
        public function get_listings_primary_button_fields()
        {
            return apply_filters('atbdp_parimary_color', array(




            ));
        }

        public function get_listings_button_type_fields()
        {
            return apply_filters('atbdp_button_type', array(
                'button_type' => array(
                    'type' => 'select',
                    'name' => 'button_type',
                    'label' => __('Button Type', 'directorist'),
                    'items' => array(
                        array(
                            'value' => 'solid_primary',
                            'label' => __('Solid Primary', 'directorist'),
                        ),
                        array(
                            'value' => 'solid_secondary',
                            'label' => __('Solid Secondary', 'directorist'),
                        ),
                        array(
                            'value' => 'solid_danger',
                            'label' => __('Solid Danger', 'directorist'),
                        ),
                        array(
                            'value' => 'solid_success',
                            'label' => __('Solid Success', 'directorist'),
                        ),
                        array(
                            'value' => 'primary_outline',
                            'label' => __('Primary Outline', 'directorist'),
                        ),
                        array(
                            'value' => 'primary_outline_light',
                            'label' => __('Primary Outline Light', 'directorist'),
                        ),
                        array(
                            'value' => 'danger_outline',
                            'label' => __('Danger Outline', 'directorist'),
                        ),
                    ),

                ),
                array(
                    'type' => 'upload',
                    'name' => 'primary_example',
                    'label' => __('Button Example', 'directorist'),
                    'default' => 'https://directorist.com/wp-content/uploads/2020/02/solid-primary.png',
                ),
                array(
                    'type' => 'color',
                    'name' => 'primary_color',
                    'label' => __('Text Color', 'directorist'),
                    'default' => '#ffffff',
                ),
                array(
                    'type' => 'color',
                    'name' => 'primary_hover_color',
                    'label' => __('Text Hover Color', 'directorist'),
                    'default' => '#ffffff',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_primary_color',
                    'label' => __('Background Color', 'directorist'),
                    'default' => '#444752',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_primary_hover_color',
                    'label' => __('Background Hover Color', 'directorist'),
                    'default' => '#222222',
                ),
                array(
                    'type' => 'color',
                    'name' => 'border_primary_color',
                    'label' => __('Border Color', 'directorist'),
                    'default' => '#444752',
                ),
                array(
                    'type' => 'color',
                    'name' => 'border_primary_hover_color',
                    'label' => __('Border Hover Color', 'directorist'),
                    'default' => '#222222',
                ),
                // solid secondary color
                array(
                    'type' => 'upload',
                    'name' => 'secondary_example',
                    'label' => __('Button Example', 'directorist'),
                    'default' => 'https://directorist.com/wp-content/uploads/2020/02/solid-secondary.png',
                ),
                array(
                    'type' => 'color',
                    'name' => 'secondary_color',
                    'label' => __('Text Color', 'directorist'),
                    'default' => '#fff',
                ),
                array(
                    'type' => 'color',
                    'name' => 'secondary_hover_color',
                    'label' => __('Text Hover Color', 'directorist'),
                    'default' => '#fff',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_secondary_color',
                    'label' => __('Background Color', 'directorist'),
                    'default' => '#122069',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_secondary_hover_color',
                    'label' => __('Background Hover Color', 'directorist'),
                    'default' => '#131469',
                ),
                array(
                    'type' => 'color',
                    'name' => 'secondary_border_color',
                    'label' => __('Border Color', 'directorist'),
                    'default' => '#131469',
                ),
                array(
                    'type' => 'color',
                    'name' => 'secondary_border_hover_color',
                    'label' => __('Border Hover Color', 'directorist'),
                    'default' => '#131469',
                ),
                // solid danger color
                array(
                    'type' => 'upload',
                    'name' => 'danger_example',
                    'label' => __('Button Example', 'directorist'),
                    'default' => 'https://directorist.com/wp-content/uploads/2020/02/solid-danger.png',
                ),
                array(
                    'type' => 'color',
                    'name' => 'danger_color',
                    'label' => __('Text Color', 'directorist'),
                    'default' => '#fff',
                ),
                array(
                    'type' => 'color',
                    'name' => 'danger_hover_color',
                    'label' => __('Text Hover Color', 'directorist'),
                    'default' => '#fff',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_danger_color',
                    'label' => __('Background Color', 'directorist'),
                    'default' => '#e23636',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_danger_hover_color',
                    'label' => __('Background Hover Color', 'directorist'),
                    'default' => '#c5001e',
                ),
                array(
                    'type' => 'color',
                    'name' => 'danger_border_color',
                    'label' => __('Border Color', 'directorist'),
                    'default' => '#e23636',
                ),
                array(
                    'type' => 'color',
                    'name' => 'danger_border_hover_color',
                    'label' => __('Border Hover Color', 'directorist'),
                    'default' => '#c5001e',
                ),
                // solid success color
                array(
                    'type' => 'upload',
                    'name' => 'success_example',
                    'label' => __('Button Example', 'directorist'),
                    'default' => 'https://directorist.com/wp-content/uploads/2020/02/solid-success.png',
                ),
                array(
                    'type' => 'color',
                    'name' => 'success_color',
                    'label' => __('Text Color', 'directorist'),
                    'default' => '#fff',
                ),
                array(
                    'type' => 'color',
                    'name' => 'success_hover_color',
                    'label' => __('Text Hover Color', 'directorist'),
                    'default' => '#fff',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_success_color',
                    'label' => __('Background Color', 'directorist'),
                    'default' => '#32cc6f',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_success_hover_color',
                    'label' => __('Background Hover Color', 'directorist'),
                    'default' => '#2ba251',
                ),
                array(
                    'type' => 'color',
                    'name' => 'border_success_color',
                    'label' => __('Border Color', 'directorist'),
                    'default' => '#32cc6f',
                ),
                array(
                    'type' => 'color',
                    'name' => 'border_success_hover_color',
                    'label' => __('Border Hover Color', 'directorist'),
                    'default' => '#2ba251',
                ),
                // primary outline
                array(
                    'type' => 'upload',
                    'name' => 'priout_example',
                    'label' => __('Button Example', 'directorist'),
                    'default' => 'https://directorist.com/wp-content/uploads/2020/02/outline-primary.png',
                ),
                array(
                    'type' => 'color',
                    'name' => 'priout_color',
                    'label' => __('Text Color', 'directorist'),
                    'default' => '#444752',
                ),
                array(
                    'type' => 'color',
                    'name' => 'priout_hover_color',
                    'label' => __('Text Hover Color', 'directorist'),
                    'default' => '#444752',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_priout_color',
                    'label' => __('Background Color', 'directorist'),
                    'default' => '#fff',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_priout_hover_color',
                    'label' => __('Background Hover Color', 'directorist'),
                    'default' => '#fff',
                ),
                array(
                    'type' => 'color',
                    'name' => 'border_priout_color',
                    'label' => __('Border Color', 'directorist'),
                    'default' => '#444752',
                ),
                array(
                    'type' => 'color',
                    'name' => 'border_priout_hover_color',
                    'label' => __('Border Hover Color', 'directorist'),
                    'default' => '#9299b8',
                ),
                // primary outline light
                array(
                    'type' => 'upload',
                    'name' => 'prioutlight_example',
                    'label' => __('Button Example', 'directorist'),
                    'default' => 'https://directorist.com/wp-content/uploads/2020/02/outline-primary-light.png',
                ),
                array(
                    'type' => 'color',
                    'name' => 'prioutlight_color',
                    'label' => __('Text Color', 'directorist'),
                    'default' => '#444752',
                ),
                array(
                    'type' => 'color',
                    'name' => 'prioutlight_hover_color',
                    'label' => __('Text Hover Color', 'directorist'),
                    'default' => '#ffffff',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_prioutlight_color',
                    'label' => __('Background Color', 'directorist'),
                    'default' => '#fff',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_prioutlight_hover_color',
                    'label' => __('Background Hover Color', 'directorist'),
                    'default' => '#444752',
                ),
                array(
                    'type' => 'color',
                    'name' => 'border_prioutlight_color',
                    'label' => __('Border Color', 'directorist'),
                    'default' => '#e3e6ef',
                ),
                array(
                    'type' => 'color',
                    'name' => 'border_prioutlight_hover_color',
                    'label' => __('Border Hover Color', 'directorist'),
                    'default' => '#444752',
                ),
                // Danger outline
                array(
                    'type' => 'upload',
                    'name' => 'danout_example',
                    'label' => __('Button Example', 'directorist'),
                    'default' => 'https://directorist.com/wp-content/uploads/2020/02/outline-danger.png',
                ),
                array(
                    'type' => 'color',
                    'name' => 'danout_color',
                    'label' => __('Text Color', 'directorist'),
                    'default' => '#e23636',
                ),
                array(
                    'type' => 'color',
                    'name' => 'danout_hover_color',
                    'label' => __('Text Hover Color', 'directorist'),
                    'default' => '#fff',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_danout_color',
                    'label' => __('Background Color', 'directorist'),
                    'default' => '#fff',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_danout_hover_color',
                    'label' => __('Background Hover Color', 'directorist'),
                    'default' => '#e23636',
                ),
                array(
                    'type' => 'color',
                    'name' => 'border_danout_color',
                    'label' => __('Border Color', 'directorist'),
                    'default' => '#e23636',
                ),
                array(
                    'type' => 'color',
                    'name' => 'border_danout_hover_color',
                    'label' => __('Border Hover Color', 'directorist'),
                    'default' => '#e23636',
                ),
            ));
        }


        /**
         * Get all the settings for badge color
         * @return array It returns an array of submenus
         * @since 5.10.0
         */
        public function get_listings_badge_color_fields()
        {
            return apply_filters('atbdp_badge_color', array(
                array(
                    'type' => 'color',
                    'name' => 'open_back_color',
                    'label' => __('Open Background Color', 'directorist'),
                    'default' => '#32cc6f',
                ),
                array(
                    'type' => 'color',
                    'name' => 'closed_back_color',
                    'label' => __('Closed Background Color', 'directorist'),
                    'default' => '#e23636',
                ),
                array(
                    'type' => 'color',
                    'name' => 'featured_back_color',
                    'label' => __('Featured Background Color', 'directorist'),
                    'default' => '#fa8b0c',
                ),
                array(
                    'type' => 'color',
                    'name' => 'popular_back_color',
                    'label' => __('Popular Background Color', 'directorist'),
                    'default' => '#f51957',
                ),
                array(
                    'type' => 'color',
                    'name' => 'new_back_color',
                    'label' => __('New Background Color', 'directorist'),
                    'default' => '#122069',
                ),

            ));
        }

        /**
         * Get all the settings for primary_dark
         * @return array It returns an array of submenus
         * @since 5.10.0
         */
        public function get_listings_primary_dark_fields()
        {
            return apply_filters('atbdp_primary_dark_color', array(
                array(
                    'type' => 'color',
                    'name' => 'primary_dark_back_color',
                    'label' => __('Background Color', 'directorist'),
                    'default' => '#444752',
                ),
                array(
                    'type' => 'color',
                    'name' => 'primary_dark_border_color',
                    'label' => __('Border Color', 'directorist'),
                    'default' => '#444752',
                ),
            ));
        }

        /**
         * Get all the settings for marker color
         * @return array It returns an array of submenus
         * @since 5.10.0
         */
        public function get_listings_map_marker_color_fields()
        {
            return apply_filters('atbdp_map_marker_color', array(
                array(
                    'type' => 'color',
                    'name' => 'marker_shape_color',
                    'label' => __('Marker Shape Color', 'directorist'),
                    'default' => '#444752',
                ),
                array(
                    'type' => 'color',
                    'name' => 'marker_icon_color',
                    'label' => __('Marker Icon Color', 'directorist'),
                    'default' => '#444752',
                ),
            ));
        }

        /**
         * Get all the submenus for the email menu
         * @return array It returns an array of submenus
         * @since 3.1.0
         */
        public function get_email_settings_submenus()
        {
            return apply_filters('atbdp_email_settings_submenus', array(
                /*Submenu : Email General*/
                array(
                    'title' => __('Email General', 'directorist'),
                    'name' => 'emails_general',
                    'icon' => 'font-awesome:fa-home',
                    'controls' => apply_filters('atbdp_email_settings_controls', array(
                        'emails' => array(
                            'type' => 'section',
                            'title' => __('Email General Settings', 'directorist'),
                            'description' => __('You can Customize Email and Notification-related settings here. You can enable or disable any emails here. Here, YES means Enabled, and NO means disabled. After switching any option, Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->get_email_settings_fields(),
                        ),
                    )),
                ),
                /*Submenu : Email Templates*/
                array(
                    'title' => __('Email Templates', 'directorist'),
                    'name' => 'emails_templates',
                    'icon' => 'font-awesome:fa-envelope',
                    'controls' => apply_filters('atbdp_email_templates_settings_controls', array(
                        'general' => array(
                            'type' => 'section',
                            'title' => __('General', 'directorist'),
                            'description' => __('You can Customize Email header color here. Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->email_general(),
                        ),
                        'new_eml_templates' => array(
                            'type' => 'section',
                            'title' => __('For New Listing', 'directorist'),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->get_email_new_tmpl_settings_fields(),
                        ),
                        'publish_eml_templates' => array(
                            'type' => 'section',
                            'title' => __('For Approved/Published Listings', 'directorist'),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->get_email_pub_tmpl_settings_fields(),
                        ),
                        'edited_eml_templates' => array(
                            'type' => 'section',
                            'title' => __('For Edited Listings', 'directorist'),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->get_email_edit_tmpl_settings_fields(),
                        ),
                        'about_expire_eml_templates' => array(
                            'type' => 'section',
                            'title' => __('For About to Expire Listings', 'directorist'),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->email_about_expire_tmpl_settings_fields(),
                        ),
                        'expired_eml_templates' => array(
                            'type' => 'section',
                            'title' => __('For Expired Listings', 'directorist'),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->email_expired_tmpl_settings_fields(),
                        ),
                        'renewal_eml_templates' => array(
                            'type' => 'section',
                            'title' => __('For Renewal Listings (Remind to Renew)', 'directorist'),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->email_renewal_tmpl_settings_fields(),
                        ),
                        'renewed_eml_templates' => array(
                            'type' => 'section',
                            'title' => __('For Renewed Listings (After Renewed)', 'directorist'),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->email_renewed_tmpl_settings_fields(),
                        ),
                        'deleted_eml_templates' => array(
                            'type' => 'section',
                            'title' => __('For deleted/trashed Listings', 'directorist'),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->email_deleted_tmpl_settings_fields(),
                        ),
                        'new_order_created' => array(
                            'type' => 'section',
                            'title' => __('For New Order (Created)', 'directorist'),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->email_new_order_tmpl_settings_fields(),
                        ),
                        'offline_new_order_created' => array(
                            'type' => 'section',
                            'title' => __('For New Order (Created using Offline Bank Transfer)', 'directorist'),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->email_offline_new_order_tmpl_settings_fields(),
                        ),
                        'completed_order_created' => array(
                            'type' => 'section',
                            'title' => __('For Completed Order', 'directorist'),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->email_completed_order_tmpl_settings_fields(),
                        ),
                        'listing_contact_email' => array(
                            'type' => 'section',
                            'title' => __('For Listing contact email', 'directorist'),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->listing_contact_email(),
                        ),
                        'registration_confirmation' => array(
                            'type' => 'section',
                            'title' => __('Registration Confirmation', 'directorist'),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->registration_confirmation_email(),
                        ),
                    )),
                ),
            ));
        }

        /**
         * Get all the settings fields for the new listing email template section
         * @return array
         * @since 3.1.0
         */
        public function get_email_new_tmpl_settings_fields()
        {
            // let's define default data template
            $sub = __('[==SITE_NAME==] : Listing "==LISTING_TITLE==" Received', 'directorist');

            $tmpl = __("
Dear ==NAME==,

This email is to notify you that your listing '==LISTING_TITLE==' has been received and it is under review now.
It may take up to 24 hours to complete the review.

Thanks,
The Administrator of ==SITE_NAME==
", 'directorist');
            //create small var to highlight important texts
            $c = '<span style="color:#c71585;">'; //color start
            $e = '</span>'; // end color
            /*@todo; make this instruction translatable later*/
            $ph = <<<KAMAL
You can use the following keywords/placeholder in any of your email bodies/templates or subjects to output dynamic value. **Usage: place the placeholder name between $c == $e and $c == $e **. For Example: use **{$c}==SITE_NAME=={$e}** to output The Your Website Name etc. <br/><br/>
**{$c}==NAME=={$e}** : It outputs The listing owner's display name on the site<br/>
**{$c}==USERNAME=={$e}** : It outputs The listing owner's user name on the site<br/>
**{$c}==SITE_NAME=={$e}** : It outputs your site name<br/>
**{$c}==SITE_LINK=={$e}** : It outputs your site name with link<br/>
**{$c}==SITE_URL=={$e}** : It outputs your site url with link<br/>
**{$c}==EXPIRATION_DATE=={$e}** : It outputs Expiration date<br/>
**{$c}==CATEGORY_NAME=={$e}** : It outputs the category name that is going to expire<br/>
**{$c}==LISTING_ID=={$e}** : It outputs the listing's ID<br/>
**{$c}==RENEWAL_LINK=={$e}** : It outputs a link to renewal page<br/>
**{$c}==LISTING_TITLE=={$e}** : It outputs the listing's title<br/>
**{$c}==LISTING_LINK=={$e}** : It outputs the listing's title with link<br/>
**{$c}==LISTING_URL=={$e}** : It outputs the listing's url with link<br/>
**{$c}==ORDER_ID=={$e}** : It outputs the order id. It should be used for order related email only<br/>
**{$c}==ORDER_RECEIPT_URL=={$e}** : It outputs a link to the order receipt page. It should be used for order related email only<br/>
**{$c}==ORDER_DETAILS=={$e}** : It outputs order detailsc. It should be used for order related email only<br/>
**{$c}==TODAY=={$e}** : It outputs the current date<br/>
**{$c}==NOW=={$e}** : It outputs the current time<br/>
**{$c}==DASHBOARD_LINK=={$e}** : It outputs the user dashboard page link<br/>
**{$c}==USER_PASSWORD=={$e}** : It outputs new user's temporary passoword<br/><br/>
**Additionally, you can also use HTML tags in your template.**
KAMAL;


            return apply_filters('atbdp_email_new_tmpl_settings_fields', array(
                array(
                    'type' => 'notebox',
                    'name' => 'email_placeholder_info',
                    'label' => $c . __('You can use Placeholders to output dynamic value', 'directorist') . $e,
                    'description' => $ph,
                    'status' => 'normal',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_new_listing',
                    'label' => __('Email Subject', 'directorist'),
                    'description' => __('Edit the subject for sending to the user when a listing is submitted/received.', 'directorist'),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_new_listing',
                    'label' => __('Email Body', 'directorist'),
                    'description' => __('Edit the email template for sending to the user when a listing is submitted/received. HTML content is allowed too.', 'directorist'),
                    'default' => $tmpl,
                ),


            ));
        }

        /**
         * Get all the settings fields for the published listing email template section
         * @return array
         * @since 3.1.0
         */
        public function get_email_pub_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : Listing "==LISTING_TITLE==" published', 'directorist');
            $tmpl = __("
Dear ==NAME==,
Congratulations! Your listing '==LISTING_TITLE==' has been approved/published. Now it is publicly available at ==LISTING_URL==

Thanks,
The Administrator of ==SITE_NAME==
", 'directorist');

            return apply_filters('atbdp_email_pub_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_pub_listing',
                    'label' => __('Email Subject', 'directorist'),
                    'description' => __('Edit the subject for sending to the user when a listing is approved/published.', 'directorist'),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_pub_listing',
                    'label' => __('Email Body', 'directorist'),
                    'description' => __('Edit the email template for sending to the user when a listing is approved/published. HTML content is allowed too.', 'directorist'),
                    'default' => $tmpl,
                ),
            ));
        }

        /**
         * Get all the settings fields for the edited listing email template section
         * @return array
         * @since 3.1.0
         */
        public function get_email_edit_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : Listing "==LISTING_TITLE==" Edited', 'directorist');
            $tmpl = __("
Dear ==NAME==,
Congratulations! Your listing '==LISTING_TITLE==' has been edited. It is publicly available at ==LISTING_URL==

Thanks,
The Administrator of ==SITE_NAME==
", 'directorist');

            return apply_filters('atbdp_email_edit_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_edit_listing',
                    'label' => __('Email Subject', 'directorist'),
                    'description' => __('Edit the subject for sending to the user when a listing is edited.', 'directorist'),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_edit_listing',
                    'label' => __('Email Body', 'directorist'),
                    'description' => __('Edit the email template for sending to the user when a listing is edited. HTML content is allowed too.', 'directorist'),
                    'default' => $tmpl,
                ),
            ));
        }

        /**
         * Get all the settings fields for the renew listing email template section
         * @return array
         * @since 3.1.0
         */
        public function email_about_expire_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : Your Listing "==LISTING_TITLE==" is about to expire.', 'directorist');
            /*@todo; includes the number of days remaining to expire the listing*/
            $tmpl = __("
Dear ==NAME==,
Your listing '==LISTING_TITLE==' is about to expire. It will expire on ==EXPIRATION_DATE==. You can renew it at ==RENEWAL_LINK==

Thanks,
The Administrator of ==SITE_NAME==
", 'directorist');

            return apply_filters('atbdp_email_about_expire_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_to_expire_listing',
                    'label' => __('Email Subject', 'directorist'),
                    'description' => __('Edit the subject for sending to the user when a listing is ABOUT TO EXPIRE.', 'directorist'),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_to_expire_listing',
                    'label' => __('Email Body', 'directorist'),
                    'description' => __('Edit the email template for sending to the user when a listing is ABOUT TO EXPIRE. HTML content is allowed too.', 'directorist'),
                    'default' => $tmpl,
                ),
            ));
        }

        /**
         * Get all the settings fields for the renew listing email template section
         * @return array
         * @since 3.1.0
         */
        public function email_expired_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __("[==SITE_NAME==] : Your Listing '==LISTING_TITLE==' has expired.", 'directorist');
            $tmpl = __("
Dear ==NAME==,
Your listing '==LISTING_TITLE==' has expired on ==EXPIRATION_DATE==. You can renew it at ==RENEWAL_LINK==

Thanks,
The Administrator of ==SITE_NAME==
", 'directorist');

            return apply_filters('atbdp_email_expired_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_expired_listing',
                    'label' => __('Email Subject', 'directorist'),
                    'description' => __('Edit the subject for sending to the user when a Listing HAS EXPIRED.', 'directorist'),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_expired_listing',
                    'label' => __('Email Body', 'directorist'),
                    'description' => __('Edit the email template for sending to the user when a Listing HAS EXPIRED. HTML content is allowed too.', 'directorist'),
                    'default' => $tmpl,
                ),

            ));
        }

        /**
         * Get all the settings fields for the renew listing email template section
         * @return array
         * @since 3.1.0
         */
        public function email_renewal_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : A Reminder to Renew your listing "==LISTING_TITLE=="', 'directorist');
            $tmpl = __("
Dear ==NAME==,

We have noticed that you might have forgot to renew your listing '==LISTING_TITLE==' at ==SITE_LINK==. We would like to remind you that it expired on ==EXPIRATION_DATE==. But please don't worry.  You can still renew it by clicking this link: ==RENEWAL_LINK==.

Thanks,
The Administrator of ==SITE_NAME==
", 'directorist');

            return apply_filters('atbdp_email_renewal_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_to_renewal_listing',
                    'label' => __('Email Subject', 'directorist'),
                    'description' => __('Edit the subject for sending to the user to renew his/her listings.', 'directorist'),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_to_renewal_listing',
                    'label' => __('Email Body', 'directorist'),
                    'description' => __('Edit the email template for sending to the user to renew his/her listings. HTML content is allowed too.', 'directorist'),
                    'default' => $tmpl,
                ),


            ));
        }

        /**
         * Get all the settings fields for the renewed listing email template section
         * @return array
         * @since 3.1.0
         */
        public function email_renewed_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : Your Listing "==LISTING_TITLE==" Has Renewed', 'directorist');
            $tmpl = __("
Dear ==NAME==,

Congratulations!
Your listing '==LISTING_LINK==' with the ID #==LISTING_ID== has been renewed successfully at ==SITE_LINK==.
Your listing is now publicly viewable at ==LISTING_URL==

Thanks,
The Administrator of ==SITE_NAME==
", 'directorist');

            return apply_filters('atbdp_email_renewed_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_renewed_listing',
                    'label' => __('Email Subject', 'directorist'),
                    'description' => __('Edit the subject for sending to the user his/her listings has renewed successfully.', 'directorist'),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_renewed_listing',
                    'label' => __('Email Body', 'directorist'),
                    'description' => __('Edit the email template for sending to the user his/her listings has renewed successfully. HTML content is allowed too.', 'directorist'),
                    'default' => $tmpl,
                ),


            ));
        }

        /**
         * Get all the settings fields for the deleted listing email template section
         * @return array
         * @since 3.1.0
         */
        public function email_deleted_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : Your Listing "==LISTING_TITLE==" Has Been Deleted', 'directorist');
            $tmpl = __("
Dear ==NAME==,

Your listing '==LISTING_LINK==' with the ID #==LISTING_ID== has been deleted successfully at ==SITE_LINK==.

Thanks,
The Administrator of ==SITE_NAME==
", 'directorist');

            return apply_filters('atbdp_email_deleted_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_deleted_listing',
                    'label' => __('Email Subject', 'directorist'),
                    'description' => __('Edit the subject for sending to the user when his/her listings has deleted successfully.', 'directorist'),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_deleted_listing',
                    'label' => __('Email Body', 'directorist'),
                    'description' => __('Edit the email template for sending to the user when his/her listings has deleted successfully. HTML content is allowed too.', 'directorist'),
                    'default' => $tmpl,
                ),


            ));
        }


        /**
         * Get all the settings fields for the new order email template section
         * @return array
         * @since 3.1.0
         */
        public function email_new_order_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : Your Order (#==ORDER_ID==) Received.', 'directorist');
            $tmpl = __("
Dear ==NAME==,

Thank you very much for your order.
This email is to notify you that your order (#==ORDER_ID==) has been received. You can check your order details and progress by clicking the link below.

Order Details Page: ==ORDER_RECEIPT_URL==

Your order summery:
==ORDER_DETAILS==


NB. You need to be logged in your account to access the order details page.

Thanks,
The Administrator of ==SITE_NAME==
", 'directorist');

            return apply_filters('atbdp_new_order_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_new_order',
                    'label' => __('Email Subject', 'directorist'),
                    'description' => __('Edit the subject for sending to the user when an order is created.', 'directorist'),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_new_order',
                    'label' => __('Email Body', 'directorist'),
                    'description' => __('Edit the email template for sending to the user when an order is created.', 'directorist'),
                    'default' => $tmpl,
                ),


            ));
        }


        /**
         * Get all the settings fields for the offline new order email template section
         * @return array
         * @since 3.1.0
         */
        public function email_offline_new_order_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : Your Order (#==ORDER_ID==) Received.', 'directorist');
            $tmpl = sprintf(__("
Dear ==NAME==,

Thank you very much for your order.
This email is to notify you that your order (#==ORDER_ID==) has been received.

%s

You can check your order details and progress by clicking the link below.
Order Details Page: ==ORDER_RECEIPT_URL==

Your order summery:
==ORDER_DETAILS==


NB. You need to be logged in your account to access the order details page.

Thanks,
The Administrator of ==SITE_NAME==
", 'directorist'), get_directorist_option('bank_transfer_instruction'));

            return apply_filters('atbdp_offline_new_order_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_offline_new_order',
                    'label' => __('Email Subject', 'directorist'),
                    'description' => __('Edit the subject for sending to the user when an order is created using offline payment like bank transfer.', 'directorist'),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_offline_new_order',
                    'label' => __('Email Body', 'directorist'),
                    'description' => __('Edit the email template for sending to the user when an order is created using offline payment like bank transfer.', 'directorist'),
                    'default' => $tmpl,
                ),


            ));
        }

        /**
         * Get all the settings fields for the completed new order email template section
         * @return array
         * @since 3.1.0
         */
        public function email_completed_order_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : Congratulation! Your Order #==ORDER_ID== Completed.', 'directorist');


            $tmpl = __("
Dear ==NAME==,

Congratulation! This email is to notify you that your order #==ORDER_ID== has been completed.

You can check your order details by clicking the link below.
Order Details Page: ==ORDER_RECEIPT_URL==

Your order summery:
==ORDER_DETAILS==


NB. You need to be logged in your account to access the order details page.

Thanks,
The Administrator of ==SITE_NAME==
", 'directorist');

            return apply_filters('atbdp_completed_order_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_completed_order',
                    'label' => __('Email Subject', 'directorist'),
                    'description' => __('Edit the subject for sending to the user when an order is completed', 'directorist'),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_completed_order',
                    'label' => __('Email Body', 'directorist'),
                    'description' => __('Edit the email template for sending to the user when an order is completed.', 'directorist'),
                    'default' => $tmpl,
                ),


            ));
        }


        /**
         * Get all the settings fields for the offline new order email template section
         * @return array
         * @since 6.3.0
         */
        public function email_general()
        {
            return apply_filters('atbdp_email_general_field', array(
                array(
                    'type' => 'toggle',
                    'name' => 'allow_email_header',
                    'label' => __('Email Header', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'color',
                    'name' => 'email_header_color',
                    'label' => __('Email Header Color', 'directorist'),
                    'default' => '#8569fb',
                ),


            ));
        }      /**
         * Get all the settings fields for the offline new order email template section
         * @return array
         * @since 3.1.0
         */
        public function registration_confirmation_email()
        {
            // let's define default data
            $sub = __('Registration Confirmation!', 'directorist');

            $tmpl = __('
Hi ==USERNAME==,

Thanks for creating an account on <b>==SITE_NAME==</b>. Your username is <b>==USERNAME==</b>. You can access your account area to view listings, change your password, and more at: ==DASHBOARD_LINK==

We look forward to seeing you soon', 'directorist');

            return apply_filters('atbdp_registration_confirmation_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_registration_confirmation',
                    'label' => __('Email Subject', 'directorist'),
                    'description' => __('Edit the subject for sending to the user when listing contact message send.', 'directorist'),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_registration_confirmation',
                    'label' => __('Email Body', 'directorist'),
                    'description' => __('Note: Use ==USER_PASSWORD== to show a temporary password when password field is disable from registration page', 'directorist'),
                    'default' => $tmpl,
                ),


            ));
        }


        /**
         * Get all the settings fields for the offline new order email template section
         * @return array
         * @since 3.1.0
         */
        public function listing_contact_email()
        {
            // let's define default data
            $sub = __('==SITE_NAME== Contact via ==LISTING_TITLE==', 'directorist');

            $tmpl = __("
Dear ==NAME==,

You have received a message from your listing at ==LISTING_URL==.

Name: ==SENDER_NAME==
Email: ==SENDER_EMAIL==
Message: ==MESSAGE==
Time: ==NOW==

Thanks,
The Administrator of ==SITE_NAME==
", 'directorist');

            return apply_filters('atbdp_completed_order_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_listing_contact_email',
                    'label' => __('Email Subject', 'directorist'),
                    'description' => __('Edit the subject for sending to the user when listing contact message send.', 'directorist'),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_listing_contact_email',
                    'label' => __('Email Body', 'directorist'),
                    'description' => __('Edit the email template for sending to the user when when listing contact message send', 'directorist'),
                    'default' => $tmpl,
                ),


            ));
        }


        /**
         * Get all the settings fields for the email settings section
         * @return array
         * @since 3.1.0
         */
        public function get_email_settings_fields()
        {
            return apply_filters('atbdp_email_settings_fields', array(
                    array(
                        'type' => 'toggle',
                        'name' => 'disable_email_notification',
                        'label' => __('Disable all Email Notifications', 'directorist'),
                        'default' => '',
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'email_from_name',
                        'label' => __('Email\'s "From Name"', 'directorist'),
                        'description' => __('The name should be used as From Name in the email generated by the plugin.', 'directorist'),
                        'default' => get_option('blogname'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'email_from_email',
                        'label' => __('Email\'s "From Email"', 'directorist'),
                        'description' => __('The email should be used as From Email in the email generated by the plugin.', 'directorist'),
                        'default' => get_option('admin_email'),
                    ),
                    array(
                        'type' => 'textarea',
                        'name' => 'admin_email_lists',
                        'label' => __('Admin Email Address(es)', 'directorist'),
                        'description' => __('Enter the one or more admin email addresses (comma separated) to send notification. Eg. admin1@example.com, admin2@example.com etc', 'directorist'),
                        'default' => get_option('admin_email'),
                    ),
                    array(
                        'type' => 'multiselect',
                        'name' => 'notify_admin',
                        'label' => __('Notify the Admin when Any of the Selected Event Happens', 'directorist'),
                        'description' => __('Select the situation when you would like to send an email to the Admin', 'directorist'),
                        'items' => $this->events_to_notify_admin(),
                        'default' => $this->default_events_to_notify_admin(),
                    ),
                    array(
                        'type' => 'multiselect',
                        'name' => 'notify_user',
                        'label' => __('Notify the Listing Owner when Any of the Selected Event Happens', 'directorist'),
                        'description' => __('Select the situation when you would like to send an email to the Listing', 'directorist'),
                        'items' => $this->events_to_notify_user(),
                        'default' => $this->default_events_to_notify_user(),
                    ),
                )
            );
        }

        /**
         * Get the list of an array of notification events array to notify admin
         * @return array It returns an array of events when an admin should be notified
         * @since 3.1.0
         */
        public function events_to_notify_admin()
        {
            $events = $this->default_notifiable_events();
            return apply_filters('atbdp_events_to_notify_admin', $events);
        }

        /**
         * Get the list of an array of notification events array to notify user
         * @return array It returns an array of events when an user should be notified
         * @since 3.1.0
         */
        public function events_to_notify_user()
        {
            $events = array_merge($this->default_notifiable_events(), $this->only_user_notifiable_events());
            return apply_filters('atbdp_events_to_notify_user', $events);
        }

        /**
         * Get the default events to notify the admin.
         * @return array It returns an array of default events when an admin should be notified.
         * @since 3.1.0
         */
        public function default_events_to_notify_admin()
        {
            return apply_filters('atbdp_default_events_to_notify_admin', array(
                'order_created',
                'order_completed',
                'listing_submitted',
                'payment_received',
                'listing_published',
                'listing_deleted',
                'listing_contact_form',
                'listing_review'
            ));
        }

        /**
         * Get the default events to notify the user.
         * @return array It returns an array of default events when an user should be notified.
         * @since 3.1.0
         */
        public function default_events_to_notify_user()
        {
            return apply_filters('atbdp_default_events_to_notify_user', array(
                'order_created',
                'listing_submitted',
                'payment_received',
                'listing_published',
                'listing_to_expire',
                'listing_expired',
                'remind_to_renew',
                'listing_renewed',
                'order_completed',
                'listing_edited',
                'listing_deleted',
                'listing_contact_form',
            ));
        }

        /**
         * Get an array of events to notify both the admin and the users
         * @return array it returns an array of events
         * @since 3.1.0
         */
        private function default_notifiable_events()
        {
            return apply_filters('atbdp_default_notifiable_events', array(
                array(
                    'value' => 'order_created',
                    'label' => __('Order Created', 'directorist'),
                ),
                array(
                    'value' => 'order_completed',
                    'label' => __('Order Completed', 'directorist'),
                ),
                array(
                    'value' => 'listing_submitted',
                    'label' => __('New Listing Submitted', 'directorist'),
                ),
                array(
                    'value' => 'listing_published',
                    'label' => __('Listing Approved/Published', 'directorist'),
                ),
                array(
                    'value' => 'listing_edited',
                    'label' => __('Listing Edited', 'directorist'),
                ),
                array(
                    'value' => 'payment_received',
                    'label' => __('Payment Received', 'directorist'),
                ),
                array(
                    'value' => 'listing_deleted',
                    'label' => __('Listing Deleted', 'directorist'),
                ),
                array(
                    'value' => 'listing_contact_form',
                    'label' => __('Listing Contact Form', 'directorist'),
                ),
                array(
                    'value' => 'listing_review',
                    'label' => __('Listing Review', 'directorist'),
                ),
            ));
        }

        /**
         * Get an array of events to notify only users
         * @return array it returns an array of events
         * @since 3.1.0
         */
        private function only_user_notifiable_events()
        {
            return apply_filters('atbdp_only_user_notifiable_events', array(
                array(
                    'value' => 'listing_to_expire',
                    'label' => __('Listing nearly Expired', 'directorist'),
                ),
                array(
                    'value' => 'listing_expired',
                    'label' => __('Listing Expired', 'directorist'),
                ),
                array(
                    'value' => 'remind_to_renew',
                    'label' => __('Remind to renew', 'directorist'),
                ),
            ));
        }

        /**
         * Get all the pages in an array where each page is an array of key:value:id and key:label:name
         *
         * Example : array(
         *                  array('value'=> 1, 'label'=> 'page_name'),
         *                  array('value'=> 50, 'label'=> 'page_name'),
         *          )
         * @return array page names with key value pairs in a multi-dimensional array
         * @since 3.0.0
         */
        function get_pages_vl_arrays()
        {
            $pages = get_pages();
            $pages_options = array();
            if ($pages) {
                foreach ($pages as $page) {
                    $pages_options[] = array('value' => $page->ID, 'label' => $page->post_title);
                }
            }

            return $pages_options;
        }


        /**
         * Get all the submenus for the extension menu
         * @return array It returns an array of submenus
         * @since 3.0.0
         */
        function get_extension_settings_submenus()
        {
            return apply_filters('atbdp_extension_settings_submenus', array(
                'submenu_1' => array(
                    'title' => __('Extensions General', 'directorist'),
                    'name' => 'extensions_switch',
                    'icon' => 'font-awesome:fa-home',
                    'controls' => apply_filters('atbdp_extension_settings_controls', array(
                        'extensions' => array(
                            'type' => 'section',
                            'title' => __('Extensions General Settings', 'directorist'),
                            'description' => __('You can Customize Extensions-related settings here. You can enable or disable any extensions here. Here, YES means Enabled, and NO means disabled. After switching any option, Do not forget to save the changes.', 'directorist'),
                            'fields' => $this->get_extension_settings_fields(),
                        ),
                    )),
                ),
            ));
        }

        /**
         * @return array
         */
        function get_currency_settings_fields()
        {
            return apply_filters('atbdp_currency_settings_fields', array(
                    array(
                        'type' => 'notebox',
                        'name' => 'g_currency_note',
                        'label' => __('Note About This Currency Settings:', 'directorist'),
                        'description' => __('This currency settings lets you customize how you would like to display price amount in your website. However, you can accept currency in a different currency. Therefore, for accepting currency in a different currency, Go to Gateway Settings Tab.', 'directorist'),
                        'status' => 'info',
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'g_currency',
                        'label' => __('Currency Name', 'directorist'),
                        'description' => __('Enter the Name of the currency eg. USD or GBP etc.', 'directorist'),
                        'default' => 'USD',
                    ),
                    /*@todo; lets user use space as thousand separator in future. @see: https://docs.oracle.com/cd/E19455-01/806-0169/overview-9/index.html
                    */
                    array(
                        'type' => 'textbox',
                        'name' => 'g_thousand_separator',
                        'label' => __('Thousand Separator', 'directorist'),
                        'description' => __('Enter the currency thousand separator. Eg. , or . etc.', 'directorist'),
                        'default' => ',',
                    ),

                    array(
                        'type' => 'toggle',
                        'name' => 'allow_decimal',
                        'label' => __('Allow Decimal', 'directorist'),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'g_decimal_separator',
                        'label' => __('Decimal Separator', 'directorist'),
                        'description' => __('Enter the currency decimal separator. Eg. "." or ",". Default is "."', 'directorist'),
                        'default' => '.',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'g_currency_position',
                        'label' => __('Currency Position', 'directorist'),
                        'description' => __('Select where you would like to show the currency symbol. Default is before. Eg. $5', 'directorist'),
                        'default' => array(
                            'before',
                        ),
                        'items' => array(
                            array(
                                'value' => 'before',
                                'label' => __('$5 - Before', 'directorist'),
                            ),
                            array(
                                'value' => 'after',
                                'label' => __('After - 5$', 'directorist'),
                            ),
                        ),
                    ),
                )
            );
        }

        /**
         * @return array
         * @since 4.6.0
         */
        function get_seo_settings_fields()
        {
            return apply_filters('atbdp_seo_settings_fields', array(
                    array(
                        'type' => 'toggle',
                        'name' => 'overwrite_by_yoast',
                        'label' => __('Disable Overwrite by Yoast', 'directorist'),
                        'description' => __('Here Yes means Directorist pages will use titles & metas settings from bellow. Otherwise it will use titles & metas settings from Yoast.', 'directorist'),
                        'default' => 0,
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'add_listing_page_meta_title',
                        'label' => __('Add Listing Page Meta Title', 'directorist'),
                        'description' => __('Default the title of the page set as frontpage.', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'add_listing_page_meta_desc',
                        'label' => __('Add Listing Page Meta Description', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'all_listing_meta_title',
                        'label' => __('All Listing Page Meta Title', 'directorist'),
                        'description' => __('Default the title of the page set as frontpage.', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'all_listing_meta_desc',
                        'label' => __('All Listing Page Meta Description', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'dashboard_meta_title',
                        'label' => __('User Dashboard Page Meta Title', 'directorist'),
                        'description' => __('Default the title of the page set as frontpage.', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'dashboard_meta_desc',
                        'label' => __('Dashboard Page Meta Description', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'author_profile_meta_title',
                        'label' => __('Author Page Meta Title', 'directorist'),
                        'description' => __('Default the title of the page set as frontpage.', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'author_page_meta_desc',
                        'label' => __('Author Page Meta Description', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'category_meta_title',
                        'label' => __('Category Page Meta Title', 'directorist'),
                        'description' => __('Default the title of the page set as frontpage.', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'category_meta_desc',
                        'label' => __('Category Page Meta Description', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'single_category_meta_title',
                        'label' => __('Single Category Page Meta Title', 'directorist'),
                        'description' => __('Default the title of the category.', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'single_category_meta_desc',
                        'label' => __('Single Category Page Meta Description', 'directorist'),
                        'description' => __('Leave it blank to set category\'s description as meta description of this page', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'all_locations_meta_title',
                        'label' => __('All Locations Page Meta Title', 'directorist'),
                        'description' => __('Default the title of the page set as frontpage.', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'all_locations_meta_desc',
                        'label' => __('All Locations Page Meta Description', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'single_locations_meta_title',
                        'label' => __('Single Location Page Meta Title', 'directorist'),
                        'description' => __('Default the title of the location.', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'single_locations_meta_desc',
                        'label' => __('Single Locations Page Meta Description', 'directorist'),
                        'description' => __('Leave it blank to set location\'s description as meta description of this page', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'registration_meta_title',
                        'label' => __('Registration Page Meta Title', 'directorist'),
                        'description' => __('Default the title of the page set as frontpage.', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'registration_meta_desc',
                        'label' => __('Registration Page Meta Description', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'login_meta_title',
                        'label' => __('Login Page Meta Title', 'directorist'),
                        'description' => __('Default the title of the page set as frontpage.', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'login_meta_desc',
                        'label' => __('Login Page Meta Description', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'homepage_meta_title',
                        'label' => __('Search Home Page Meta Title', 'directorist'),
                        'description' => __('Default the title of the page set as frontpage.', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'homepage_meta_desc',
                        'label' => __('Search Home Page Meta Description', 'directorist'),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'meta_title_for_search_result',
                        'label' => __('Search Result Page Meta Title', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'searched_value',
                                'label' => __('From User Search', 'directorist'),
                            ),
                            array(
                                'value' => 'custom',
                                'label' => __('Custom', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'searched_value',
                            'label' => __('From User Search', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_result_meta_title',
                        'label' => __('Custom Meta Title', 'directorist'),
                        'description' => __('Default the title of the page set as frontpage.', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_result_meta_desc',
                        'label' => __('Search Result Page Meta Description', 'directorist'),
                    ),

                )
            );
        }


        /**
         * Get all the settings fields for the listings page section
         * @return array
         * @since 4.0.0
         */
        function get_archive_page_settings_fields()
        {
            $business_hours = '(Requires <a style="color: red" href="https://directorist.com/product/directorist-business-hours/" target="_blank">Business Hours</a> extension)';
            return apply_filters('atbdp_archive_settings_fields', array(

                    'display_listings_header' => array(
                        'type' => 'toggle',
                        'name' => 'display_listings_header',
                        'label' => __('Display Header', 'directorist'),
                        'default' => 1,
                    ),
                    'all_listing_title' => array(
                        'type' => 'textbox',
                        'name' => 'all_listing_header_title',
                        'label' => __('Header Title', 'directorist'),
                        'default' => __('Items Found', 'directorist'),
                    ),
                    'listing_filters_button' => array(
                        'type' => 'toggle',
                        'name' => 'listing_filters_button',
                        'label' => __('Display Filters Button', 'directorist'),
                        'default' => 1,
                    ),
                    'listing_filters_icon' => array(
                        'type' => 'toggle',
                        'name' => 'listing_filters_icon',
                        'label' => __('Display Filters Icon', 'directorist'),
                        'default' => 1,
                    ),

                    'listings_filter_button_text' => array(
                        'type' => 'textbox',
                        'name' => 'listings_filter_button_text',
                        'label' => __('Filters Button Text', 'directorist'),
                        'default' => __('Filters', 'directorist'),
                    ),
                    'listings_display_filter' => array(
                        'type' => 'select',
                        'name' => 'listings_display_filter',
                        'label' => __('Open Filter Fields', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'overlapping',
                                'label' => __('Overlapping', 'directorist'),
                            ),
                            array(
                                'value' => 'sliding',
                                'label' => __('Sliding', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'sliding',
                            'label' => __('Sliding', 'directorist'),
                        ),
                    ),
                    'listing_filters_fields' => array(
                        'type' => 'checkbox',
                        'name' => 'listing_filters_fields',
                        'label' => __('Filter Fields', 'directorist'),
                        'validation' => 'minselected[0]|maxselected[15]',
                        'items' => array(
                            array(
                                'value' => 'search_text',
                                'label' => __('Text', 'directorist'),
                            ),
                            array(
                                'value' => 'search_category',
                                'label' => __('Category', 'directorist'),
                            ),
                            array(
                                'value' => 'search_location',
                                'label' => __('Location', 'directorist'),
                            ),
                            array(
                                'value' => 'search_price',
                                'label' => __('Price (Min - Max)', 'directorist'),
                            ),
                            array(
                                'value' => 'search_price_range',
                                'label' => __('Price Range', 'directorist'),
                            ),
                            array(
                                'value' => 'search_rating',
                                'label' => __('Rating', 'directorist'),
                            ),
                            array(
                                'value' => 'search_tag',
                                'label' => __('Tag', 'directorist'),
                            ),
                            array(
                                'value' => 'search_open_now',
                                'label' => sprintf(__('Open Now %s', 'directorist'), !class_exists('BD_Business_Hour') ? $business_hours : '')),
                            array(
                                'value' => 'search_custom_fields',
                                'label' => __('Custom Fields', 'directorist'),
                            ),
                            array(
                                'value' => 'search_website',
                                'label' => __('Website', 'directorist'),
                            ),
                            array(
                                'value' => 'search_email',
                                'label' => __('Email', 'directorist'),
                            ),
                            array(
                                'value' => 'search_phone',
                                'label' => __('Phone', 'directorist'),
                            ),
                            array(
                                'value' => 'search_fax',
                                'label' => __('Fax', 'directorist'),
                            ),
                            array(
                                'value' => 'search_zip_code',
                                'label' => __('Zip/Post Code', 'directorist'),
                            ),
                            array(
                                'value' => 'radius_search',
                                'label' => __('Radius Search', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'search_text',
                            'search_category',
                            'search_location',
                            'search_price',
                            'search_price_range',
                            'search_rating',
                            'search_tag',
                            'search_custom_fields',
                            'radius_search'
                        ),
                    ),
                    'listing_location_fields' => array(
                        'type' => 'select',
                        'name' => 'listing_location_address',
                        'label' => __('Location Source for Search', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'listing_location',
                                'label' => __('Display from Listing Location', 'directorist'),
                            ),
                            array(
                                'value' => 'map_api',
                                'label' => __('Display From Map API', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'map_api',
                            'label' => __('Display From Map API', 'directorist'),
                        ),
                    ),
                    'listing_tags_field' => array(
                        'type' => 'select',
                        'name' => 'listing_tags_field',
                        'label' => __('Tags Filter Source', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'category_based_tags',
                                'label' => __('Category Based Tags', 'directorist'),
                            ),
                            array(
                                'value' => 'all_tags',
                                'label' => __('All Tags', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'all_tags',
                            'label' => __('All Tags', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'listing_default_radius_distance',
                        'label' => __('Default Radius Distance', 'directorist'),
                        'min' => '0',
                        'max' => '1000',
                        'step' => '1',
                        'default' => '0',
                        'validation' => 'numeric',
                    ),
                    'listings_filters_button' => array(
                        'type' => 'checkbox',
                        'name' => 'listings_filters_button',
                        'label' => __('Filter Buttons', 'directorist'),
                        'validation' => 'minselected[0]|maxselected[5]',
                        'items' => array(
                            array(
                                'value' => 'reset_button',
                                'label' => __('Reset', 'directorist'),
                            ),
                            array(
                                'value' => 'apply_button',
                                'label' => __('Apply', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'reset_button',
                            'apply_button',
                        ),
                    ),
                    'listings_reset_text' => array(
                        'type' => 'textbox',
                        'name' => 'listings_reset_text',
                        'label' => __('Reset Filters Button text', 'directorist'),
                        'default' => __('Reset Filters', 'directorist'),
                    ),
                    'listings_apply_text' => array(
                        'type' => 'textbox',
                        'name' => 'listings_apply_text',
                        'label' => __('Apply Filters Button text', 'directorist'),
                        'default' => __('Apply Filters', 'directorist'),
                    ),
                    'listings_search_text_placeholder' => array(
                        'type' => 'textbox',
                        'name' => 'listings_search_text_placeholder',
                        'label' => __('Search Bar Placeholder', 'directorist'),
                        'default' => __('What are you looking for?', 'directorist'),
                    ),
                    'listings_category_placeholder' => array(
                        'type' => 'textbox',
                        'name' => 'listings_category_placeholder',
                        'label' => __('Category Placeholder', 'directorist'),
                        'default' => __('Select a category', 'directorist'),
                    ),
                    'listings_location_placeholder' => array(
                        'type' => 'textbox',
                        'name' => 'listings_location_placeholder',
                        'label' => __('Location Placeholder', 'directorist'),
                        'default' => __('location', 'directorist'),
                    ),
                    'display_sort_by' => array(
                        'type' => 'toggle',
                        'name' => 'display_sort_by',
                        'label' => __('Display "Sort By" Dropdown', 'directorist'),
                        'default' => 1,
                    ),
                    'sort_by_text' => array(
                        'type' => 'textbox',
                        'name' => 'sort_by_text',
                        'label' => __('"Sort By" Text', 'directorist'),
                        'default' => __('Sort By', 'directorist'),
                    ),
                    'listings_sort_by_items' => array(
                        'type' => 'checkbox',
                        'name' => 'listings_sort_by_items',
                        'label' => __('"Sort By" Dropdown', 'directorist'),
                        'validation' => 'minselected[0]|maxselected[10]',
                        'items' => array(
                            array(
                                'value' => 'a_z',
                                'label' => __('A to Z (title)', 'directorist'),
                            ),
                            array(
                                'value' => 'z_a',
                                'label' => __('Z to A (title)', 'directorist'),
                            ),
                            array(
                                'value' => 'latest',
                                'label' => __('Latest listings', 'directorist'),
                            ),
                            array(
                                'value' => 'oldest',
                                'label' => __('Oldest listings', 'directorist'),
                            ),
                            array(
                                'value' => 'popular',
                                'label' => __('Popular listings', 'directorist'),
                            ),
                            array(
                                'value' => 'price_low_high',
                                'label' => __('Price (low to high)', 'directorist'),
                            ),
                            array(
                                'value' => 'price_high_low',
                                'label' => __('Price (high to low)', 'directorist'),
                            ),
                            array(
                                'value' => 'random',
                                'label' => __('Random listings', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'a_z',
                            'z_a',
                            'latest',
                            'oldest',
                            'popular',
                            'price_low_high',
                            'price_high_low',
                            'random'
                        ),
                    ),
                    'display_view_as' => array(
                        'type' => 'toggle',
                        'name' => 'display_view_as',
                        'label' => __('Display "View As" Dropdown', 'directorist'),
                        'default' => 1,
                    ),
                    'view_as_text' => array(
                        'type' => 'textbox',
                        'name' => 'view_as_text',
                        'label' => __('"View As" Text', 'directorist'),
                        'default' => __('View As', 'directorist'),
                    ),
                    'listings_view_as_items' => array(
                        'type' => 'checkbox',
                        'name' => 'listings_view_as_items',
                        'label' => __('"View As" Dropdown', 'directorist'),
                        'validation' => 'minselected[0]|maxselected[3]',
                        'items' => array(
                            array(
                                'value' => 'listings_grid',
                                'label' => __('Grid', 'directorist'),
                            ),
                            array(
                                'value' => 'listings_list',
                                'label' => __('List', 'directorist'),
                            ),
                            array(
                                'value' => 'listings_map',
                                'label' => __('Map', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'listings_grid',
                            'listings_list',
                            'listings_map'
                        ),
                    ),
                    'default_listing_view' => array(
                        'type' => 'select',
                        'name' => 'default_listing_view',
                        'label' => __('Default View', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'grid',
                                'label' => __('Grid', 'directorist'),
                            ),
                            array(
                                'value' => 'list',
                                'label' => __('List', 'directorist'),
                            ),
                            array(
                                'value' => 'map',
                                'label' => __('Map', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'grid',
                            'label' => __('Grid', 'directorist'),
                        ),
                    ),
                    'grid_view_as' => array(
                        'type' => 'select',
                        'name' => 'grid_view_as',
                        'label' => __('Grid View', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'masonry_grid',
                                'label' => __('Masonry', 'directorist'),
                            ),
                            array(
                                'value' => 'normal_grid',
                                'label' => __('Normal', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'normal_grid',
                            'label' => __('Normal', 'directorist'),
                        ),
                    ),
                    'all_listing_columns' => array(
                        'type' => 'slider',
                        'name' => 'all_listing_columns',
                        'label' => __('Number of Columns', 'directorist'),
                        'min' => '1',
                        'max' => '5',
                        'step' => '1',
                        'default' => '3',
                        'validation' => 'numeric|minlength[1]',
                    ),
                    'order_listing_by' => array(
                        'type' => 'select',
                        'name' => 'order_listing_by',
                        'label' => __('Listings Order By', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'title',
                                'label' => __('Title', 'directorist'),
                            ),
                            array(
                                'value' => 'date',
                                'label' => __('Date', 'directorist'),
                            ),
                            array(
                                'value' => 'price',
                                'label' => __('Price', 'directorist'),
                            ),
                            array(
                                'value' => 'rand',
                                'label' => __('Random', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'date',
                            'label' => __('Date', 'directorist'),
                        ),
                    ),
                    'sort_listing_by' => array(
                        'type' => 'select',
                        'name' => 'sort_listing_by',
                        'label' => __('Listings Sort By', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'asc',
                                'label' => __('Ascending', 'directorist'),
                            ),
                            array(
                                'value' => 'desc',
                                'label' => __('Descending', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'desc',
                            'label' => __('Descending', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_preview_image',
                        'label' => __('Show Preview Image', 'directorist'),
                        'description' => __('Hide/show preview image from all listing page.', 'directorist'),
                        'default' => 'px',
                    ),

                    'preview_image_quality' => array(
                        'type' => 'select',
                        'name' => 'preview_image_quality',
                        'label' => __('Image Quality', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'medium',
                                'label' => __('Medium', 'directorist'),
                            ),
                            array(
                                'value' => 'large',
                                'label' => __('Large', 'directorist'),
                            ),
                            array(
                                'value' => 'full',
                                'label' => __('Full', 'directorist'),
                            ),
                        ),
                        'default' =>  array(
                            'value' => 'large',
                            'label' => __('Large', 'directorist'),
                        ),
                    ),

                    'way_to_show_preview' => array(
                        'type' => 'select',
                        'name' => 'way_to_show_preview',
                        'label' => __('Image Size', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'full',
                                'label' => __('Original', 'directorist'),
                            ),
                            array(
                                'value' => 'cover',
                                'label' => __('Fill with Container', 'directorist'),
                            ),
                            array(
                                'value' => 'contain',
                                'label' => __('Fit with Container', 'directorist'),
                            ),
                        ),
                        'default' =>  array(
                            'value' => 'cover',
                            'label' => __('Fill with Container', 'directorist'),
                        ),
                    ),

                    array(
                        'type' => 'slider',
                        'name' => 'crop_width',
                        'label' => __('Container Width', 'directorist'),
                        'min' => '1',
                        'max' => '1200',
                        'step' => '1',
                        'default' => '350',
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'crop_height',
                        'label' => __('Container Height', 'directorist'),
                        'min' => '1',
                        'max' => '1200',
                        'step' => '1',
                        'default' => '260',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'prv_container_size_by',
                        'label' => __('Container Size By', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'px',
                                'label' => __('Pixel', 'directorist'),
                            ),
                            array(
                                'value' => 'ratio',
                                'label' => __('Ratio', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'px',
                            'label' => __('Pixel', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'prv_background_type',
                        'label' => __('Background', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'blur',
                                'label' => __('Blur', 'directorist'),
                            ),
                            array(
                                'value' => 'color',
                                'label' => __('Custom Color', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'blur',
                            'label' => __('Blur', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'color',
                        'name' => 'prv_background_color',
                        'label' => __('Select Color', 'directorist'),
                        'default' => 'gainsboro',
                    ),
                    array(
                        'type' => 'upload',
                        'name' => 'default_preview_image',
                        'label' => __('Default Preview Image', 'directorist'),
                        'default' => ATBDP_PUBLIC_ASSETS . 'images/grid.jpg',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'info_display_in_single_line',
                        'label' => __('Display Each Grid Info on Single Line', 'directorist'),
                        'description' => __('Here Yes means display all the informations (i.e. title, tagline, excerpt etc.) of grid view on single line', 'directorist'),
                        'default' => '0',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_title',
                        'label' => __('Display Title', 'directorist'),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'enable_tagline',
                        'label' => __('Display Tagline', 'directorist'),
                        'default' => 0,
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'enable_excerpt',
                        'label' => __('Display Excerpt', 'directorist'),
                        'default' => 0,
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'excerpt_limit',
                        'label' => __('Excerpt Words Limit', 'directorist'),
                        'min' => '5',
                        'max' => '200',
                        'step' => '1',
                        'default' => '20',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_readmore',
                        'label' => __('Display Excerpt Readmore', 'directorist'),
                        'default' => '0',
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'readmore_text',
                        'label' => __('Read More Text', 'directorist'),
                        'default' => __('Read More', 'directorist'),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_price',
                        'label' => __('Display Price', 'directorist'),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_email',
                        'label' => __('Display Email', 'directorist'),
                        'default' => '0',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_web_link',
                        'label' => __('Display Web Link', 'directorist'),
                        'default' => '0',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_contact_info',
                        'label' => __('Display Contact Information', 'directorist'),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'address_location',
                        'label' => __('Address', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'location',
                                'label' => __('Display From Location', 'directorist'),
                            ),
                            array(
                                'value' => 'contact',
                                'label' => __('Display From Contact Information', 'directorist'),
                            ),
                        ),
                        'description' => __('Choose which address you want to show on listings page', 'directorist'),
                        'default' => array(
                            'value' => 'contact',
                            'label' => __('Contact Information', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_publish_date',
                        'label' => __('Display Publish Date', 'directorist'),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'publish_date_format',
                        'label' => __('Publish Date Format', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'time_ago',
                                'label' => __('Number of Days Ago', 'directorist'),
                            ),
                            array(
                                'value' => 'publish_date',
                                'label' => __('Standard Date Format', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'time_ago',
                            'label' => __('Number of Days Ago', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_category',
                        'label' => __('Display Category', 'directorist'),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_mark_as_fav',
                        'label' => __('Display Mark as Favourite', 'directorist'),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_view_count',
                        'label' => __('Display View Count', 'directorist'),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_author_image',
                        'label' => __('Display Author Image', 'directorist'),
                        'default' => '1',
                    ),

                    array(
                        'type' => 'toggle',
                        'name' => 'paginate_all_listings',
                        'label' => __('Paginate Listings', 'directorist'),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'all_listing_page_items',
                        'label' => __('Listings Per Page', 'directorist'),
                        'min' => '1',
                        'max' => '100',
                        'step' => '1',
                        'default' => '6',
                        'validation' => 'numeric|minlength[1]',
                    ),

                )
            );
        }

        /**
         * Get all the settings fields for the categories page section
         * @return array
         * @since 4.0.0
         */
        function get_categories_settings_fields()
        {
            return apply_filters('atbdp_categories_settings_fields', array(

                    array(
                        'type' => 'select',
                        'name' => 'display_categories_as',
                        'label' => __('Default View', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'grid',
                                'label' => __('Grid', 'directorist'),
                            ),
                            array(
                                'value' => 'list',
                                'label' => __('List', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'grid',
                            'label' => __('Grid', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'categories_column_number',
                        'label' => __('Number of  Columns', 'directorist'),
                        'description' => __('Set how many columns to display on categories page.', 'directorist'),
                        'min' => '1',
                        'max' => '5',
                        'step' => '1',
                        'default' => '4',
                        'validation' => 'numeric|minlength[1]',
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'categories_depth_number',
                        'label' => __('Sub-category Depth', 'directorist'),
                        'description' => __('Set how many sub-categories to display.', 'directorist'),
                        'min' => '1',
                        'max' => '15',
                        'step' => '1',
                        'default' => '2',
                        'validation' => 'numeric|minlength[1]',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'order_category_by',
                        'label' => __('Categories Order By', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'id',
                                'label' => __('ID', 'directorist'),
                            ),
                            array(
                                'value' => 'count',
                                'label' => __('Count', 'directorist'),
                            ),
                            array(
                                'value' => 'name',
                                'label' => __('Name', 'directorist'),
                            ),
                            array(
                                'value' => 'slug',
                                'label' => __('Slug', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'id',
                            'label' => __('ID', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sort_category_by',
                        'label' => __('Categories Sort By', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'asc',
                                'label' => __('Ascending', 'directorist'),
                            ),
                            array(
                                'value' => 'desc',
                                'label' => __('Descending', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'asc',
                            'label' => __('Ascending', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_listing_count',
                        'label' => __('Display Listing Count', 'directorist'),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'hide_empty_categories',
                        'label' => __('Hide Empty Categories', 'directorist'),
                        'default' => 0,
                    ),

                )
            );
        }

        /**
         * Get all the settings fields for the categories page section
         * @return array
         * @since 4.0.0
         */
        function get_locations_settings_fields()
        {
            return apply_filters('atbdp_locations_settings_fields', array(
                    array(
                        'type' => 'select',
                        'name' => 'display_locations_as',
                        'label' => __('Default View', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'grid',
                                'label' => __('Grid', 'directorist'),
                            ),
                            array(
                                'value' => 'list',
                                'label' => __('List', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'grid',
                            'label' => __('Grid', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'locations_column_number',
                        'label' => __('Number of  Columns', 'directorist'),
                        'description' => __('Set how many columns to display on locations page.', 'directorist'),
                        'min' => '1',
                        'max' => '5',
                        'step' => '1',
                        'default' => '4',
                        'validation' => 'numeric|minlength[1]',
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'locations_depth_number',
                        'label' => __('Sub-location Depth', 'directorist'),
                        'description' => __('Set how many sub-locations to display.', 'directorist'),
                        'min' => '1',
                        'max' => '15',
                        'step' => '1',
                        'default' => '2',
                        'validation' => 'numeric|minlength[1]',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'order_location_by',
                        'label' => __('Locations Order By', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'id',
                                'label' => __('ID', 'directorist'),
                            ),
                            array(
                                'value' => 'count',
                                'label' => __('Count', 'directorist'),
                            ),
                            array(
                                'value' => 'name',
                                'label' => __('Name', 'directorist'),
                            ),
                            array(
                                'value' => 'slug',
                                'label' => __('Slug', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'id',
                            'label' => __('ID', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sort_location_by',
                        'label' => __('Locations Sort By', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'asc',
                                'label' => __('Ascending', 'directorist'),
                            ),
                            array(
                                'value' => 'desc',
                                'label' => __('Descending', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'asc',
                            'label' => __('Ascending', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_location_listing_count',
                        'label' => __('Display Listing Count', 'directorist'),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'hide_empty_locations',
                        'label' => __('Hide Empty Locations', 'directorist'),
                        'default' => 0,
                    ),

                )
            );
        }


        /**
         * Get all the settings fields for the search settings section
         * @return array
         * @since 3.0.0
         */
        function get_search_settings_fields()
        {
            $business_hours = '(Requires <a style="color: red" href="https://aazztech.com/product/directorist-business-hours/" target="_blank">Business Hours</a> extension)';
            return apply_filters('atbdp_search_settings_fields', array(
                    array(
                        'type' => 'textbox',
                        'name' => 'search_title',
                        'label' => __('Search Bar Title', 'directorist'),
                        'description' => __('Enter the title for search bar on Home Page.', 'directorist'),
                        'default' => atbdp_get_option('search_title', 'atbdp_general'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_subtitle',
                        'label' => __('Search Bar Sub-title', 'directorist'),
                        'default' => atbdp_get_option('search_subtitle', 'atbdp_general'),
                    ),
                    'search_border_show' => array(
                        'type' => 'toggle',
                        'name' => 'search_border',
                        'label' => __('Search Bar Border', 'directorist'),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'search_tsc_fields',
                        'label' => __('Search Fields', 'directorist'),
                        'validation' => 'minselected[0]|maxselected[3]',
                        'items' => array(
                            array(
                                'value' => 'search_text',
                                'label' => __('Text', 'directorist'),
                            ),
                            array(
                                'value' => 'search_category',
                                'label' => __('Category', 'directorist'),
                            ),
                            array(
                                'value' => 'search_location',
                                'label' => __('Location', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'search_text', 'search_category', 'search_location'
                        ),
                    ),
                    'search_location_address' => array(
                        'type' => 'select',
                        'name' => 'search_location_address',
                        'label' => __('Location Source for Search Field', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'listing_location',
                                'label' => __('Display from Listing Location', 'directorist'),
                            ),
                            array(
                                'value' => 'map_api',
                                'label' => __('Display from Map API', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'map_api',
                            'label' => __('Display From Map API', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'require_search_text',
                        'label' => __('Required Text Field', 'directorist'),
                        'default' => 0,
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'require_search_category',
                        'label' => __('Required Category Field', 'directorist'),
                        'default' => 0,
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'require_search_location',
                        'label' => __('Required Location Field', 'directorist'),
                        'default' => 0,
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_placeholder',
                        'label' => __('Search Bar Placeholder', 'directorist'),
                        'default' => __('What are you looking for?', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_category_placeholder',
                        'label' => __('Category Placeholder', 'directorist'),
                        'default' => __('Select a category', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_location_placeholder',
                        'label' => __('Location Placeholder', 'directorist'),
                        'default' => __('location', 'directorist'),
                    ),
                    'display_more_filter' => array(
                        'type' => 'toggle',
                        'name' => 'search_more_filter',
                        'label' => __('Display More Filters', 'directorist'),
                        'default' => 1,
                    ),
                    'display_more_filter_icon' => array(
                        'type' => 'toggle',
                        'name' => 'search_more_filter_icon',
                        'label' => __('Display More Filters Icon', 'directorist'),
                        'default' => 1,
                    ),
                    'display_search_button' => array(
                        'type' => 'toggle',
                        'name' => 'search_button',
                        'label' => __('Display Search Button', 'directorist'),
                        'default' => 1,
                    ),
                    'display_search_button_icon' => array(
                        'type' => 'toggle',
                        'name' => 'search_button_icon',
                        'label' => __('Display Search Button Icon', 'directorist'),
                        'default' => 1,
                    ),
                    'listings_display_filter' => array(
                        'type' => 'select',
                        'name' => 'home_display_filter',
                        'label' => __('Open Filter Fields', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'overlapping',
                                'label' => __('Overlapping', 'directorist'),
                            ),
                            array(
                                'value' => 'sliding',
                                'label' => __('Sliding', 'directorist'),
                            ),
                            array(
                                'value' => 'always_open',
                                'label' => __('Always Open', 'directorist'),
                            )
                        ),
                        'default' => array(
                            'value' => 'sliding',
                            'label' => __('Sliding', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'search_more_filters_fields',
                        'label' => __('Filter Fields', 'directorist'),
                        'validation' => 'minselected[0]|maxselected[12]',
                        'items' => array(
                            array(
                                'value' => 'search_price',
                                'label' => __('Price (Min - Max)', 'directorist'),
                            ),
                            array(
                                'value' => 'search_price_range',
                                'label' => __('Price Range', 'directorist'),
                            ),
                            array(
                                'value' => 'search_rating',
                                'label' => __('Rating', 'directorist'),
                            ),
                            array(
                                'value' => 'search_tag',
                                'label' => __('Tag', 'directorist'),
                            ),
                            array(
                                'value' => 'search_open_now',
                                'label' => sprintf(__('Open Now %s', 'directorist'), !class_exists('BD_Business_Hour') ? $business_hours : '')),
                            array(
                                'value' => 'search_custom_fields',
                                'label' => __('Custom Fields', 'directorist'),
                            ),
                            array(
                                'value' => 'search_website',
                                'label' => __('Website', 'directorist'),
                            ),
                            array(
                                'value' => 'search_email',
                                'label' => __('Email', 'directorist'),
                            ),
                            array(
                                'value' => 'search_phone',
                                'label' => __('Phone', 'directorist'),
                            ),
                            array(
                                'value' => 'search_fax',
                                'label' => __('Fax', 'directorist'),
                            ),
                            array(
                                'value' => 'search_zip_code',
                                'label' => __('Zip/Post Code', 'directorist'),
                            ),
                            array(
                                'value' => 'radius_search',
                                'label' => __('Radius Search', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'search_price', 'search_price_range', 'search_rating', 'search_tag', 'search_custom_fields', 'radius_search'
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'search_filters',
                        'label' => __('Filters Button', 'directorist'),
                        'validation' => 'minselected[0]|maxselected[2]',
                        'items' => array(
                            array(
                                'value' => 'search_reset_filters',
                                'label' => __('Reset', 'directorist'),
                            ),
                            array(
                                'value' => 'search_apply_filters',
                                'label' => __('Apply', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'search_reset_filters', 'search_apply_filters'
                        ),
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'search_default_radius_distance',
                        'label' => __('Default Radius Distance', 'directorist'),
                        'min' => '0',
                        'max' => '750',
                        'step' => '1',
                        'default' => '0',
                        'validation' => 'numeric',
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_listing_text',
                        'label' => __('Search Button Text', 'directorist'),
                        'default' => __('Search Listing', 'directorist')
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_more_filters',
                        'label' => __('More Filters Button Text', 'directorist'),
                        'default' => __('More Filters', 'directorist')
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_reset_text',
                        'label' => __('Reset Filters Button Text', 'directorist'),
                        'default' => __('Reset Filters', 'directorist')
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_apply_filter',
                        'label' => __('Apply Filters Button Text', 'directorist'),
                        'default' => __('Apply Filters', 'directorist')
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'show_popular_category',
                        'label' => __('Display Popular Categories', 'directorist'),
                        'default' => '0',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'show_connector',
                        'label' => __('Display Connector', 'directorist'),
                        'description' => __('Whether to display a connector between Search Bar and Popular Categories.', 'directorist'),
                        'default' => '0',
                    ),

                    array(
                        'type' => 'textbox',
                        'name' => 'connectors_title',
                        'label' => __('Connector Text', 'directorist'),
                        'default' => __('Or', 'directorist'),
                    ),

                    array(
                        'type' => 'textbox',
                        'name' => 'popular_cat_title',
                        'label' => __('Popular Categories Title', 'directorist'),
                        'default' => __('Browse by popular categories', 'directorist'),
                    ),

                    array(
                        'type' => 'slider',
                        'name' => 'popular_cat_num',
                        'label' => __('Number of Popular Categories', 'directorist'),
                        'min' => '1',
                        'max' => '30',
                        'step' => '1',
                        'default' => '10',
                        'validation' => 'numeric|minlength[1]',
                    ),
                    'search_home_background' => array(
                        'type' => 'upload',
                        'name' => 'search_home_bg',
                        'label' => __('Search Page Background', 'directorist'),
                    ),

                )
            );
        }

        /**
         * Get all the settings fields for the listings search result section
         * @return array
         * @since 4.0.0
         */
        function get_search_form_settings_fields()
        {
            $business_hours = '(Requires <a style="color: red" href="https://directorist.com/product/directorist-business-hours/" target="_blank">Business Hours</a> extension)';
            return apply_filters('atbdp_search_result_settings_fields', array(
                    array(
                        'type' => 'toggle',
                        'name' => 'search_header',
                        'label' => __('Display Header', 'directorist'),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'search_result_filters_button_display',
                        'label' => __('Display Filters Button', 'directorist'),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_result_filter_button_text',
                        'label' => __('Filters Button Text', 'directorist'),
                        'default' => __('Filters', 'directorist'),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'search_result_display_filter',
                        'label' => __('Open Filter Fields', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'overlapping',
                                'label' => __('Overlapping', 'directorist'),
                            ),
                            array(
                                'value' => 'sliding',
                                'label' => __('Sliding', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'sliding',
                            'label' => __('Sliding', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'search_result_filters_fields',
                        'label' => __('Filter Fields', 'directorist'),
                        'validation' => 'minselected[0]|maxselected[25]',
                        'items' => array(
                            array(
                                'value' => 'search_text',
                                'label' => __('Text', 'directorist'),
                            ),
                            array(
                                'value' => 'search_category',
                                'label' => __('Category', 'directorist'),
                            ),
                            array(
                                'value' => 'search_location',
                                'label' => __('Location', 'directorist'),
                            ),
                            array(
                                'value' => 'search_price',
                                'label' => __('Price (Min - Max)', 'directorist'),
                            ),
                            array(
                                'value' => 'search_price_range',
                                'label' => __('Price Range', 'directorist'),
                            ),
                            array(
                                'value' => 'search_rating',
                                'label' => __('Rating', 'directorist'),
                            ),
                            array(
                                'value' => 'search_tag',
                                'label' => __('Tag', 'directorist'),
                            ),
                            array(
                                'value' => 'search_open_now',
                                'label' => sprintf(__('Open Now %s', 'directorist'), !class_exists('BD_Business_Hour') ? $business_hours : '')),
                            array(
                                'value' => 'search_custom_fields',
                                'label' => __('Custom Fields', 'directorist'),
                            ),
                            array(
                                'value' => 'search_website',
                                'label' => __('Website', 'directorist'),
                            ),
                            array(
                                'value' => 'search_email',
                                'label' => __('Email', 'directorist'),
                            ),
                            array(
                                'value' => 'search_phone',
                                'label' => __('Phone', 'directorist'),
                            ),
                            array(
                                'value' => 'search_fax',
                                'label' => __('Fax', 'directorist'),
                            ),
                            array(
                                'value' => 'search_zip_code',
                                'label' => __('Zip/Post Code', 'directorist'),
                            ),
                            array(
                                'value' => 'radius_search',
                                'label' => __('Radius Search', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'search_text',
                            'search_category',
                            'search_location',
                            'search_price',
                            'search_price_range',
                            'search_rating',
                            'search_tag',
                            'search_custom_fields',
                            'radius_search'
                        ),
                    ),
                    'sresult_location_address' => array(
                        'type' => 'select',
                        'name' => 'sresult_location_address',
                        'label' => __('Location Source for Search', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'listing_location',
                                'label' => __('Display from Listing Location', 'directorist'),
                            ),
                            array(
                                'value' => 'map_api',
                                'label' => __('Display From Map API', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'map_api',
                            'label' => __('Display From Map API', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'sresult_default_radius_distance',
                        'label' => __('Default Radius Distance', 'directorist'),
                        'min' => '0',
                        'max' => '1000',
                        'step' => '1',
                        'default' => '0',
                        'validation' => 'numeric',
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'search_result_filters_button',
                        'label' => __('Filters Button', 'directorist'),
                        'validation' => 'minselected[0]|maxselected[5]',
                        'items' => array(
                            array(
                                'value' => 'reset_button',
                                'label' => __('Reset', 'directorist'),
                            ),
                            array(
                                'value' => 'apply_button',
                                'label' => __('Apply', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'reset_button',
                            'apply_button',
                        ),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'sresult_reset_text',
                        'label' => __('Reset Filters Button text', 'directorist'),
                        'default' => __('Reset Filters', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'sresult_apply_text',
                        'label' => __('Apply Filters Button text', 'directorist'),
                        'default' => __('Apply Filters', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_result_search_text_placeholder',
                        'label' => __('Search Bar Placeholder', 'directorist'),
                        'default' => __('What are you looking for?', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_result_category_placeholder',
                        'label' => __('Category Placeholder', 'directorist'),
                        'default' => __('Select a category', 'directorist'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_result_location_placeholder',
                        'label' => __('Location Placeholder', 'directorist'),
                        'default' => __('Select a location', 'directorist'),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'search_view_as',
                        'label' => __('Display "View As" Dropdown', 'directorist'),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_viewas_text',
                        'label' => __('"View As" Text', 'directorist'),
                        'default' => __('View As', 'directorist'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'search_view_as_items',
                        'label' => __('"View As" Dropdown', 'directorist'),
                        'validation' => 'minselected[0]|maxselected[3]',
                        'items' => array(
                            array(
                                'value' => 'listings_grid',
                                'label' => __('Grid', 'directorist'),
                            ),
                            array(
                                'value' => 'listings_list',
                                'label' => __('List', 'directorist'),
                            ),
                            array(
                                'value' => 'listings_map',
                                'label' => __('Map', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'listings_grid',
                            'listings_list',
                            'listings_map'
                        ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'search_sort_by',
                        'label' => __('Display "Sort By" Dropdown', 'directorist'),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_sortby_text',
                        'label' => __('"Sort By" Text', 'directorist'),
                        'default' => __('Sort By', 'directorist'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'search_sort_by_items',
                        'label' => __('"Sort By" Dropdown', 'directorist'),
                        'validation' => 'minselected[0]|maxselected[10]',
                        'items' => array(
                            array(
                                'value' => 'a_z',
                                'label' => __('A to Z (title)', 'directorist'),
                            ),
                            array(
                                'value' => 'z_a',
                                'label' => __('Z to A (title)', 'directorist'),
                            ),
                            array(
                                'value' => 'latest',
                                'label' => __('Latest listings', 'directorist'),
                            ),
                            array(
                                'value' => 'oldest',
                                'label' => __('Oldest listings', 'directorist'),
                            ),
                            array(
                                'value' => 'popular',
                                'label' => __('Popular listings', 'directorist'),
                            ),
                            array(
                                'value' => 'price_low_high',
                                'label' => __('Price (low to high)', 'directorist'),
                            ),
                            array(
                                'value' => 'price_high_low',
                                'label' => __('Price (high to low)', 'directorist'),
                            ),
                            array(
                                'value' => 'random',
                                'label' => __('Random listings', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'a_z',
                            'z_a',
                            'latest',
                            'oldest',
                            'popular',
                            'price_low_high',
                            'price_high_low',
                            'random'
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'search_order_listing_by',
                        'label' => __('Order By', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'title',
                                'label' => __('Title', 'directorist'),
                            ),
                            array(
                                'value' => 'date',
                                'label' => __('Date', 'directorist'),
                            ),
                            array(
                                'value' => 'price',
                                'label' => __('Price', 'directorist'),
                            ),
                            array(
                                'value' => 'rand',
                                'label' => __('Random', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'date',
                            'label' => __('Date', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'search_sort_listing_by',
                        'label' => __('Sort By', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'asc',
                                'label' => __('Ascending', 'directorist'),
                            ),
                            array(
                                'value' => 'desc',
                                'label' => __('Descending', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'desc',
                            'label' => __('Descending', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'search_listing_columns',
                        'label' => __('Number of Columns', 'directorist'),
                        'min' => '1',
                        'max' => '5',
                        'step' => '1',
                        'default' => '3',
                        'validation' => 'numeric|minlength[1]',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'paginate_search_results',
                        'label' => __('Paginate Search Result', 'directorist'),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'search_posts_num',
                        'label' => __('Search Results Per Page', 'directorist'),
                        'min' => '1',
                        'max' => '100',
                        'step' => '1',
                        'default' => atbdp_get_option('search_posts_num', 'atbdp_general', 6),
                        'validation' => 'numeric|minlength[1]',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'radius_search_unit',
                        'label' => __('Radius Search Unit', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'miles',
                                'label' => __('Miles', 'directorist'),
                            ),
                            array(
                                'value' => 'kilometers',
                                'label' => __('Kilometers', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'miles',
                            'label' => __('Miles', 'directorist'),
                        ),
                    ),
                )
            );
        }

        /**
         * Get all the settings fields for the listings settings section
         * @return array
         * @since 4.0.0
         */
        function get_badge_settings_fields()
        {
            return apply_filters('atbdp_badge_settings_fields', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_new_badge_cart',
                    'label' => __('Display New Badge', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'new_badge_text',
                    'label' => __('New Badge Text', 'directorist'),
                    'default' => __('New', 'directorist'),
                ),
                array(
                    'type' => 'slider',
                    'name' => 'new_listing_day',
                    'label' => __('New Badge Duration in Days', 'directorist'),
                    'min' => '1',
                    'max' => '100',
                    'step' => '1',
                    'default' => '3',
                    'validation' => 'numeric|minlength[1]',
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_feature_badge_cart',
                    'label' => __('Display Featured Badge', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'feature_badge_text',
                    'label' => __('Featured Badge Text', 'directorist'),
                    'default' => __('Featured', 'directorist'),
                ),
            ));
        }

        /**
         * Get all the settings fields for the listings settings section
         * @return array
         * @since 4.0.0
         */
        function get_popular_badge_settings_fields()
        {
            return apply_filters('atbdp_badge_settings_fields', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_popular_badge_cart',
                    'label' => __('Display Popular Badge', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'popular_badge_text',
                    'label' => __('Popular Badge Text', 'directorist'),
                    'default' => __('Popular', 'directorist'),
                ),
                array(
                    'type' => 'select',
                    'name' => 'listing_popular_by',
                    'label' => __('Popular Based on', 'directorist'),
                    'items' => array(
                        array(
                            'value' => 'view_count',
                            'label' => __('View Count', 'directorist'),
                        ),
                        array(
                            'value' => 'average_rating',
                            'label' => __('Average Rating', 'directorist'),
                        ),
                        array(
                            'value' => 'both_view_rating',
                            'label' => __('Both', 'directorist'),
                        ),
                    ),
                    'default' => array(
                        'value' => 'view_count',
                        'label' => __('View Count', 'directorist'),
                    ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'views_for_popular',
                    'label' => __('Threshold in Views Count', 'directorist'),
                    'default' => 5,
                ),
                array(
                    'type' => 'slider',
                    'name' => 'average_review_for_popular',
                    'label' => __('Threshold in Average Ratings (equal or grater than)', 'directorist'),
                    'min' => '.5',
                    'max' => '4.5',
                    'step' => '.5',
                    'default' => '4',
                    'validation' => 'numeric|minlength[1]',
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'count_loggedin_user',
                    'label' => __('Count Logged-in User View', 'directorist'),
                    'default' => 0,
                ),

            ));
        }


        /**
         * Get title settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_title_field_settings()
        {
            $req_title = atbdp_get_option('title_field_setting', 'atbdp_general', 'yes');
            return apply_filters('atbdp_title_field_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'title_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Title', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_title',
                    'label' => __('Required', 'directorist'),
                    'default' => atbdp_yes_to_bool($req_title),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_title_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'title_placeholder',
                    'label' => __('Placeholder', 'directorist'),
                    'default' => __('Enter a title', 'directorist'),
                ),
            ));
        }

        /**
         * Get all the settings fields for description section
         * @return array
         * @since 4.7.2
         */
        public function get_listings_desc_field_settings()
        {
            return apply_filters('atbdp_desc_field_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'long_details_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Long Details', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_long_details',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_desc_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get category settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_cat_field_settings()
        {
            return apply_filters('atbdp_cat_field_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'category_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Select Category', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_category',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'cat_placeholder',
                    'label' => __('Placeholder', 'directorist'),
                    'default' => __('Select Category', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'multiple_cat_for_user',
                    'label' => __('Multi Category for User', 'directorist'),
                    'default' => 1,
                ),
                /*array(
                    'type' => 'select',
                    'name' => 'display_cat_for',
                    'label' => __( 'Display For', 'directorist' ),
                    'items' => array(
                        array(
                            'value' => 'users',
                            'label' => __('Users', 'directorist'),
                        ),
                        array(
                            'value' => 'none',
                            'label' => __('None', 'directorist'),
                        ),
                    ),
                    'default' => array(
                        'value' => 'users',
                        'label' => __('Users', 'directorist'),
                    ),
                ),*/
            ));
        }

        /**
         * Get location settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_loc_field_settings()
        {
            return apply_filters('atbdp_loc_field_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'location_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Location', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_location',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'loc_placeholder',
                    'label' => __('Placeholder', 'directorist'),
                    'default' => __('Select Location', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_loc_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'multiple_loc_for_user',
                    'label' => __('Multi Location for User', 'directorist'),
                    'default' => 1,
                ),
            ));
        }

        /**
         * Get tag settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_tag_field_settings()
        {
            return apply_filters('atbdp_tag_field_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'tag_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Tag', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'tag_placeholder',
                    'label' => __('Placeholder', 'directorist'),
                    'default' => __('Select or insert new tags separated by a comma, or space', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'create_new_tag',
                    'label' => __('Allow Creating New Tag', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_tags',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_tag_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get tagline settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_tagline_field_settings()
        {
            return apply_filters('atbdp_tagline_field_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_tagline_field',
                    'label' => __('Enable', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'tagline_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Tagline', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'tagline_placeholder',
                    'label' => __('Placeholder', 'directorist'),
                    'default' => __('Your Listing\'s motto or tag-line', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_tagline_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get pricing settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_pricing_field_settings()
        {
            return apply_filters('atbdp_pricing_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_pricing_field',
                    'label' => __('Enable Price', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'pricing_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Pricing', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'price_label',
                    'label' => __('Price Label', 'directorist'),
                    'default' => __('Price', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'price_placeholder',
                    'label' => __('Placeholder', 'directorist'),
                    'default' => __('Price of this listing. Eg. 100', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_price',
                    'label' => __('Required Price', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_price_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_price_range_field',
                    'label' => __('Enable Price Range', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'price_range_label',
                    'label' => __('Price Range Label', 'directorist'),
                    'default' => __('Price Range', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'price_range_placeholder',
                    'label' => __('Placeholder', 'directorist'),
                    'default' => __('Select Price Range', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_price_range',
                    'label' => __('Required Price Range', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_price_range_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),
            ));
        }

        public function get_listings_views_count_settings()
        {
            return apply_filters('atbdp_views_count_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_views_count',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'views_count_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Views Count', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_views_count_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 1,
                ),
            ));
        }

        /**
         * Get excerpt settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_short_desc_field_settings()
        {
            return apply_filters('atbdp_short_desc_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_excerpt_field',
                    'label' => __('Enable', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'excerpt_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Short Description/Excerpt', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'excerpt_placeholder',
                    'label' => __('Placeholder', 'directorist'),
                    'default' => __('Short Description or Excerpt', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_excerpt',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_short_desc_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get contact info settings field
         * @return array
         * @since 5.3.3
         */
        public function get_listings_contact_info_settings()
        {
            return apply_filters('atbdp_contact_info_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_contact_hide',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'contact_hide_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Check it to hide Contact Information for this listing', 'directorist'),
                ),
            ));
        }
        /**
         * Get address settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_address_field_settings()
        {
            return apply_filters('atbdp_address_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_address_field',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'address_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Address', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'address_placeholder',
                    'label' => __('Placeholder', 'directorist'),
                    'default' => __('Listing address eg. New York, USA', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_address',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_address_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get phone number settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_phone_field_settings()
        {
            return apply_filters('atbdp_phone_field_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_phone_field',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'phone_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Phone', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'phone_placeholder',
                    'label' => __('Placeholder', 'directorist'),
                    'default' => __('Phone Number', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_phone_number',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_phone_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get phone number settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_phone_field2_settings()
        {
            return apply_filters('atbdp_phone_field2_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_phone_field2',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'phone_label2',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Phone 2', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'phone_placeholder2',
                    'label' => __('Placeholder', 'directorist'),
                    'default' => __('Phone Number 2', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_phone_number2',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_phone2_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get phone number settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_fax_settings()
        {
            return apply_filters('atbdp_fax_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_fax',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'fax_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Fax', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'fax_placeholder',
                    'label' => __('Placeholder', 'directorist'),
                    'default' => __('Enter Fax', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_fax',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_fax_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get email settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_email_field_settings()
        {
            return apply_filters('atbdp_email_field_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_email_field',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'email_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Email', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'email_placeholder',
                    'label' => __('Placeholder', 'directorist'),
                    'default' => __('Enter Email', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_email',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_email_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get website settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_website_field_settings()
        {
            return apply_filters('atbdp_website_field_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_website_field',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'website_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Website', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'website_placeholder',
                    'label' => __('Placeholder', 'directorist'),
                    'default' => __('Listing Website eg. http://example.com', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_website',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_website_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get website settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_zip_field_settings()
        {
            return apply_filters('atbdp_zip_field_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_zip_field',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'zip_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Zip/Post Code', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'zip_placeholder',
                    'label' => __('Placeholder', 'directorist'),
                    'default' => __('Enter Zip/Post Code', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_zip',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_zip_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get social info settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_social_field_settings()
        {
            return apply_filters('atbdp_social_field_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_social_info_field',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'social_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Social Information', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_social_info',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_social_info_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get map settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_map_field_settings()
        {
            return apply_filters('atbdp_map_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_map_field',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),

                array(
                    'type' => 'toggle',
                    'name' => 'display_map_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get  image settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_image_field_settings()
        {
            return apply_filters('atbdp_image_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_gallery_field',
                    'label' => __('Enable Gallery Image', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'gallery_label',
                    'label' => __('Select Files Label', 'directorist'),
                    'default' => __('Select Files', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_gallery_img',
                    'label' => __('Required Gallery', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'slider',
                    'name' => 'max_gallery_image_limit',
                    'label' => __('Max Image Limit', 'directorist'),
                    'min' => '1',
                    'max' => '100',
                    'step' => '1',
                    'default' => '5',
                    'description' => __('Here 0 means unlimited.', 'directorist'),

                ),
                array(
                    'type' => 'slider',
                    'name' => 'max_gallery_upload_size_per_file',
                    'label' => __('Max Upload Size Per Image in MB', 'directorist'),
                    'min' => '0',
                    'max' => '100',
                    'step' => '.5',
                    'default' => '0',
                    'description' => __('Here 0 means unlimited.', 'directorist'),

                ),
                array(
                    'type' => 'slider',
                    'name' => 'max_gallery_upload_size',
                    'label' => __('Total Upload Size in MB', 'directorist'),
                    'min' => '.5',
                    'max' => '100',
                    'step' => '.5',
                    'default' => '2',
                    'description' => __('Here 0 means unlimited.', 'directorist'),

                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_glr_img_for',
                    'label' => __('Gallery Image Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get  video settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_video_field_settings()
        {
            return apply_filters('atbdp_video_term_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_video_field',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'video_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('Video Url', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'video_placeholder',
                    'label' => __('Placeholder', 'directorist'),
                    'default' => __('Only YouTube & Vimeo URLs.', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_video',
                    'label' => __('Required', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_video_for',
                    'label' => __('Only For Admin Use', 'directorist'),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get  term & condition settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_privacy_field_settings()
        {
            return apply_filters('atbdp_privacy_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'listing_privacy',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'privacy_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('I agree to the', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'privacy_label_link',
                    'label' => __('Linking Text', 'directorist'),
                    'default' => __('Privacy & Policy', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_privacy',
                    'label' => __('Required', 'directorist'),
                    'default' => 1,
                    'description' => __('Here YES means users must agree to before submitting a listing from frontend.', 'directorist'),
                ),
            ));
        }
        /**
         * Get  term & condition settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_terms_field_settings()
        {
            return apply_filters('atbdp_video_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'listing_terms_condition',
                    'label' => __('Enable', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'terms_label',
                    'label' => __('Label', 'directorist'),
                    'default' => __('I agree with all', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'terms_label_link',
                    'label' => __('Linking Text', 'directorist'),
                    'default' => __('terms & conditions', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_terms_conditions',
                    'label' => __('Required', 'directorist'),
                    'default' => 1,
                    'description' => __('Here YES means users must agree to before submitting a listing from frontend.

', 'directorist'),
                ),
            ));
        }

        /**
         * Get  term & condition settings field
         * @return array
         * @since 6.3.0
         */
        public function get_listings_submit_settings()
        {
            return apply_filters('atbdp_listings_submit_settings', array(
                array(
                    'type' => 'toggle',
                    'name' => 'preview_enable',
                    'label' => __('Enable Preview Mode', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'submit_label',
                    'label' => __('Submit listing label', 'directorist'),
                    'default' => __('Save & Preview', 'directorist'),
                ),
            ));
        }


        /**
         * Get  term & condition settings field
         * @return array
         * @since 4.7.2
         */
        public function get_guest_listings_settings()
        {
            return apply_filters('atbdp_guest_listings_settings', array(
                array(
                    'type' => 'toggle',
                    'name' => 'guest_listings',
                    'label' => __('Guest Listing Submission', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'guest_email',
                    'label' => __('Guest Email label', 'directorist'),
                    'default' => __('Your Email', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'guest_email_placeholder',
                    'label' => __('Guest Email Placeholder', 'directorist'),
                    'default' => __('example@gmail.com', 'directorist'),
                ),
            ));
        }

        function get_listings_dashboard_settings_fields()
        {
            return apply_filters('atbdp_dashboard_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'my_listing_tab',
                    'label' => __('Display My Listing Tab', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'my_listing_tab_text',
                    'label' => __('"My Listing" Tab Label', 'directorist'),
                    'default' => __('My Listing', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'user_listings_pagination',
                    'label' => __('Listings Pagination', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'slider',
                    'name' => 'user_listings_per_page',
                    'label' => __('Listings Per Page', 'directorist'),
                    'min' => '1',
                    'max' => '30',
                    'step' => '1',
                    'default' => '9',

                ),
                array(
                    'type' => 'toggle',
                    'name' => 'my_profile_tab',
                    'label' => __('Display My Profile Tab', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'my_profile_tab_text',
                    'label' => __('"My Profile" Tab Label', 'directorist'),
                    'default' => __('My Profile', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'fav_listings_tab',
                    'label' => __('Display Favourite Listings Tab', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'fav_listings_tab_text',
                    'label' => __('"Favourite Listings" Tab Label', 'directorist'),
                    'default' => __('Favorite Listings', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'submit_listing_button',
                    'label' => __('Display Submit Listing Button', 'directorist'),
                    'default' => 1,
                ),
            ));
        }

        function get_listings_map_settings_fields()
        {
            return apply_filters('atbdp_map_field_setting', array(
                array(
                    'type' => 'select',
                    'name' => 'select_listing_map',
                    'label' => __('Select Map', 'directorist'),
                    'items' => array(
                        array(
                            'value' => 'google',
                            'label' => __('Google Map', 'directorist'),
                        ),
                        array(
                            'value' => 'openstreet',
                            'label' => __('OpenStreetMap', 'directorist'),
                        ),
                    ),
                    'default' => array(
                        'value' => 'openstreet',
                        'label' => __('OpenStreetMap', 'directorist'),
                    ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'map_api_key',
                    'label' => __('Google Map API key', 'directorist'),
                    'description' => sprintf(__('Please replace it by your own API. It\'s required to use Google Map. You can find detailed information %s.', 'directorist'), '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"> <strong style="color: red;">here</strong> </a>'),

                ),
                array(
                    'type' => 'textbox',
                    'name' => 'default_latitude',
                    'label' => __('Default Latitude', 'directorist'),
                    'description' => sprintf(__('You can find it %s.', 'directorist'), '<a href="https://www.maps.ie/coordinates.html" target="_blank"> <strong style="color: red;">here</strong> </a>'),
                    'default' => '40.7127753',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'default_longitude',
                    'label' => __('Default Longitude', 'directorist'),
                    'description' => sprintf(__('You can find it %s.', 'directorist'), '<a href="https://www.maps.ie/coordinates.html" target="_blank"> <strong style="color: red;">here</strong> </a>'),
                    'default' => '-74.0059728',
                ),
                array(
                    'type' => 'slider',
                    'name' => 'map_zoom_level',
                    'label' => __('Zoom Level for Single Listing', 'directorist'),
                    'description' => __('Here 0 means 100% zoom-out. 22 means 100% zoom-in. Minimum Zoom Allowed = 1. Max Zoom Allowed = 22.', 'directorist'),
                    'min' => '1',
                    'max' => '22',
                    'step' => '1',
                    'default' => '16',

                ),
                array(
                    'type' => 'slider',
                    'name' => 'map_view_zoom_level',
                    'label' => __('Zoom Level for Map View', 'directorist'),
                    'description' => __('Here 0 means 100% zoom-out. 18 means 100% zoom-in. Minimum Zoom Allowed = 1. Max Zoom Allowed = 22.', 'directorist'),
                    'min' => '1',
                    'max' => '18',
                    'step' => '1',
                    'default' => '1',

                ),
                array(
                    'type' => 'slider',
                    'name' => 'listings_map_height',
                    'label' => __('Map Height', 'directorist'),
                    'description' => __('In pixel.', 'directorist'),
                    'min' => '5',
                    'max' => '1200',
                    'step' => '5',
                    'default' => '350',

                ),
            ));
        }

        function get_listings_map_info_settings_fields()
        {
            return apply_filters('atbdp_map_info_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_map_info',
                    'label' => __('Display Map Info Window', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_image_map',
                    'label' => __('Display Preview Image', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_title_map',
                    'label' => __('Display Title', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_address_map',
                    'label' => __('Display Address', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_direction_map',
                    'label' => __('Display Get Direction', 'directorist'),
                    'default' => 1,
                ),
            ));
        }

        /**
         * Get all the settings fields for the listings settings section
         * @return array
         * @since 4.0.0
         */
        function get_listings_review_settings_fields()
        {
            $e_review = atbdp_get_option('enable_review', 'atbdp_general', 'yes');
            return apply_filters('atbdp_review_settings_fields', array(
                array(
                    'type' => 'toggle',
                    'name' => 'enable_review',
                    'label' => __('Enable Reviews & Rating', 'directorist'),
                    'default' => atbdp_yes_to_bool($e_review),
                ),

                array(
                    'type' => 'toggle',
                    'name' => 'enable_owner_review',
                    'label' => __('Enable Owner Review', 'directorist'),
                    'description' => __('Allow a listing owner to post a review on his/her own listing.', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'approve_immediately',
                    'label' => __('Approve Immediately?', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'review_approval_text',
                    'label' => __('Approval Notification Text', 'directorist'),
                    'default' => __('We have received your review. It requires approval.', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'enable_reviewer_img',
                    'label' => __('Enable Reviewer Image', 'directorist'),
                    'description' => __('Allow to display image of reviewer on single listing page.', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'enable_reviewer_content',
                    'label' => __('Enable Reviewer content', 'directorist'),
                    'description' => __('Allow to display content of reviewer on single listing page.', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'required_reviewer_content',
                    'label' => __('Required Reviewer content', 'directorist'),
                    'description' => __('Allow to Require the content of reviewer on single listing page.', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'slider',
                    'name' => 'review_num',
                    'label' => __('Number of Reviews', 'directorist'),
                    'description' => __('Enter how many reviews to show on Single listing page.', 'directorist'),
                    'min' => '1',
                    'max' => '20',
                    'step' => '1',
                    'default' => '5',
                    'validation' => 'numeric|minlength[1]',
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'guest_review',
                    'label' => __('Guest Review Submission', 'directorist'),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get all the settings fields for the listings settings section
         * @return array
         * @since 3.0.0
         */
        function get_listings_form_settings_fields()
        {
            return apply_filters('atbdp_single_listings_settings_fields', array(
                array(
                    'type' => 'toggle',
                    'name' => 'disable_single_listing',
                    'label' => __('Disable Single Listing View', 'directorist'),
                    'default' => 0,
                ),
                'single_listing_template' => array(
                    'type' => 'select',
                    'name' => 'single_listing_template',
                    'label' => __('Template', 'directorist'),
                    'items' => array(
                        array(
                            'value' => 'current_theme_template',
                            'label' => __('Current Theme Template (used for posts)', 'directorist'),
                        ),
                        array(
                            'value' => 'directorist_template',
                            'label' => __('Directorist Custom Template', 'directorist'),
                        ),
                    ),

                    'default' => array(
                        'value' => 'current_theme_template',
                        'label' => __('Current Theme Template (used for posts)', 'directorist'),
                    ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'atbdp_listing_slug',
                    'label' => __('Listing Slug', 'directorist'),
                    'default' => 'directory',
                ),
                array(
                    'type' => 'select',
                    'name' => 'edit_listing_redirect',
                    'label' => __('Redirect after Editing a Listing', 'directorist'),
                    'items' => array(
                        array(
                            'value' => 'view_listing',
                            'label' => __('Frontend of the Listing', 'directorist'),
                        ),
                        array(
                            'value' => 'dashboard',
                            'label' => __('User Dashboard', 'directorist'),
                        ),
                    ),
                    'description' => __('Select where user will be redirected after editing a listing on the frontend.', 'directorist'),

                    'default' => array(
                        'value' => 'view_listing',
                        'label' => __('View Listing', 'directorist'),
                    ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'listing_details_text',
                    'label' => __('Section Title of Listing Details', 'directorist'),
                    'default' => __('Listing Details', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'tags_section_lable',
                    'label' => __('Section Title of Tags', 'directorist'),
                    'default' => __('Tags', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'custom_section_lable',
                    'label' => __('Section Title of Custom Fields', 'directorist'),
                    'default' => __('Features', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'listing_location_text',
                    'label' => __('Section Title of Location', 'directorist'),
                    'default' => __('Location', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'contact_info_text',
                    'label' => __('Section Title of Contact Info', 'directorist'),
                    'default' => __('Contact Information', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'contact_listing_owner',
                    'label' => __('Section Title of Contact Owner', 'directorist'),
                    'default' => __('Contact Listing Owner', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'atbd_video_title',
                    'label' => __('Section Title of Video', 'directorist'),
                    'default' => __('Video', 'directorist'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'atbd_author_info_title',
                    'label' => __('Section Title of Author Info', 'directorist'),
                    'default' => __('Author Info', 'directorist'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_back_link',
                    'label' => __('Show Back Link', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'dsiplay_slider_single_page',
                    'label' => __('Show Slider Image', 'directorist'),
                    'description' => __('Hide/show slider image from single listing page.', 'directorist'),
                    'default' => 1,
                ),
                'single_slider_image_size' => array(
                    'type' => 'select',
                    'name' => 'single_slider_image_size',
                    'label' => __('Slider Image Size', 'directorist'),
                    'items' => array(
                        array(
                            'value' => 'cover',
                            'label' => __('Fill with Container', 'directorist'),
                        ),
                        array(
                            'value' => 'contain',
                            'label' => __('Fit with Container', 'directorist'),
                        ),
                    ),
                    'default' =>  array(
                        'value' => 'cover',
                        'label' => __('Fill with Container', 'directorist'),
                    ),
                ),
                'single_slider_background_type' => array(
                    'type' => 'select',
                    'name' => 'single_slider_background_type',
                    'label' => __('Slider Background Type', 'directorist'),
                    'items' => array(
                        array(
                            'value' => 'blur',
                            'label' => __('Blur', 'directorist'),
                        ),
                        array(
                            'value' => 'custom-color',
                            'label' => __('Custom Color', 'directorist'),
                        ),
                    ),
                    'default' =>  array(
                        'value' => 'custom-color',
                        'label' => __('Custom Color', 'directorist'),
                    ),
                ),
                array(
                    'type' => 'color',
                    'name' => 'single_slider_background_color',
                    'label' => __('Slider Background Color', 'directorist'),
                    'default' => '#fff',
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'dsiplay_thumbnail_img',
                    'label' => __('Show Slider Thumbnail', 'directorist'),
                    'description' => __('Hide/show slider thumbnail from single listing page.', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'slider',
                    'name' => 'gallery_crop_width',
                    'label' => __('Image Width', 'directorist'),
                    'min' => '1',
                    'max' => '1200',
                    'step' => '1',
                    'default' => '740',

                ),

                array(
                    'type' => 'slider',
                    'name' => 'gallery_crop_height',
                    'label' => __('Image Height', 'directorist'),
                    'min' => '1',
                    'max' => '1200',
                    'step' => '1',
                    'default' => '580',

                ),
                array(
                    'type' => 'toggle',
                    'name' => 'enable_social_share',
                    'label' => __('Display Social Share Button', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'enable_favourite',
                    'label' => __('Display Favourite Button', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'enable_report_abuse',
                    'label' => __('Display Report Abuse', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'disable_list_price',
                    'label' => __('Disable Listing Price', 'directorist'),
                    'default' => 0,
                ),
                'enable_single_location_taxonomy' => array(
                    'type' => 'toggle',
                    'name' => 'enable_single_location_taxonomy',
                    'label' => __('Display Location', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'enable_single_tag',
                    'label' => __('Display Tag', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'disable_contact_info',
                    'label' => __('Disable Contact Information', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'address_map_link',
                    'label' => __('Address Linked with Map', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'disable_contact_owner',
                    'label' => __('Disable Contact Listing Owner Form', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'select',
                    'name' => 'user_email',
                    'label' => __('Email Send to', 'directorist'),
                    'description' => __('Email recipient for receiving email from Contact Listing Owner Form.', 'directorist'),
                    'items' => array(
                        array(
                            'value' => 'author',
                            'label' => __('Author Email', 'directorist'),
                        ),
                        array(
                            'value' => 'listing_email',
                            'label' => __('Listing\'s Email', 'directorist'),
                        ),
                    ),
                    'default' => array(
                        'value' => 'author',
                        'label' => __('Author Email', 'directorist'),
                    ),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'use_nofollow',
                    'label' => __('Use rel="nofollow" in Website Link', 'directorist'),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'disable_map',
                    'label' => __('Disable Google Map', 'directorist'),
                    'default' => 0,
                ),

                array(
                    'type' => 'toggle',
                    'name' => 'atbd_video_url',
                    'label' => __('Display Listing Video', 'directorist'),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'enable_rel_listing',
                    'label' => __('Display Related Listings', 'directorist'),
                    'default' => 4,
                ),
                array(
                    'type' => 'select',
                    'name' => 'rel_listings_logic',
                    'label' => __('Based on', 'directorist'),
                    'description' => __('Display related listings based on category and/or tag.', 'directorist'),
                    'items' => array(
                        array(
                            'value' => 'OR',
                            'label' => __('Category or Tag', 'directorist'),
                        ),
                        array(
                            'value' => 'AND',
                            'label' => __('Category and Tag', 'directorist'),
                        ),
                    ),
                    'default' => array(
                        'value' => 'OR',
                        'label' => __('Category or Tag', 'directorist'),
                    ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'rel_listing_title',
                    'label' => __('Related Listings Title', 'directorist'),
                    'default' => __('Related Listings', 'directorist'),
                ),
                array(
                    'type' => 'slider',
                    'name' => 'rel_listing_num',
                    'label' => __('Number of Related Listings', 'directorist'),
                    'min' => '1',
                    'max' => '10',
                    'step' => '1',
                    'default' => '5',
                    'validation' => 'numeric|minlength[1]',
                ),
                array(
                    'type' => 'slider',
                    'name' => 'rel_listing_column',
                    'label' => __('Columns of Related Listings', 'directorist'),
                    'min' => '1',
                    'max' => '10',
                    'step' => '1',
                    'default' => '2',
                    'validation' => 'numeric|minlength[1]',
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'fix_listing_double_thumb',
                    'label' => __('Fix Repeated Thumbnail of Single Listing', 'directorist'),
                    'default' => 1,
                ),


            ));
        }

        /**
         * Get all the settings fields for the listings settings section
         * @return array
         * @since 3.0.0
         */
        function get_general_listings_settings_fields()
        {
            // BACKWARD COMPATIBILITY:  OLD SETTINGS DATA that should be adapted by using them as default value, will be removed in future
            $s_p_cat = atbdp_get_option('show_popular_category', 'atbdp_general', 'yes');
            $e_p_list = atbdp_get_option('enable_pop_listing', 'atbdp_general', 'yes');
            $e_r_list = atbdp_get_option('enable_rel_listing', 'atbdp_general', 'yes');

            return apply_filters('atbdp_all_listings_settings_fields', array(
                    'new_listing_status' => array(
                        'type' => 'select',
                        'name' => 'new_listing_status',
                        'label' => __('New Listing\'s Default Status', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'publish',
                                'label' => __('Published', 'directorist'),
                            ),
                            array(
                                'value' => 'pending',
                                'label' => __('Pending', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'pending',
                            'label' => __('Pending', 'directorist'),
                        ),
                    ),
                    'edit_listing_status' => array(
                        'type' => 'select',
                        'name' => 'edit_listing_status',
                        'label' => __('Edited Listing\'s Default Status', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'publish',
                                'label' => __('Published', 'directorist'),
                            ),
                            array(
                                'value' => 'pending',
                                'label' => __('Pending', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'pending',
                            'label' => __('Pending', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'fix_js_conflict',
                        'label' => __('Fix Conflict with Bootstrap JS', 'directorist'),
                        'description' => __('If you use a theme that uses Bootstrap Framework especially Bootstrap JS, then Check this setting to fix any conflict with theme bootstrap js.', 'directorist'),
                        'default' => 0,
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'font_type',
                        'label' => __('Icon Library', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'font',
                                'label' => __('Font Awesome', 'directorist'),
                            ),
                            array(
                                'value' => 'line',
                                'label' => __('Line Awesome', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'line',
                            'label' => __('Line Awesome', 'directorist'),
                        ),
                    ),

                    array(
                        'type' => 'slider',
                        'name' => 'listing_expire_in_days',
                        'label' => __('Default Listing Expires in Days', 'directorist'),
                        'description' => __('Set it to 0 to keep it alive forever.', 'directorist'),
                        'min' => '0',
                        'max' => '730',
                        'step' => '1',
                        'default' => 365,
                        'validation' => 'numeric',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'can_renew_listing',
                        'label' => __('Can User Renew Listing?', 'directorist'),
                        'description' => __('Here YES means users can renew their listings.', 'directorist'),
                        'default' => 1,
                    ),

                    array(
                        'type' => 'slider',
                        'name' => 'email_to_expire_day',
                        'label' => __('When to send expire notice', 'directorist'),
                        'description' => __('Select the days before a listing expires to send an expiration reminder email', 'directorist'),
                        'min' => '1',
                        'max' => '120',
                        'step' => '1',
                        'default' => '7',
                    ),

                    array(
                        'type' => 'slider',
                        'name' => 'email_renewal_day',
                        'label' => __('When to send renewal reminder', 'directorist'),
                        'description' => __('Select the days after a listing expires to send a renewal reminder email', 'directorist'),
                        'min' => '1',
                        'max' => '120',
                        'step' => '1',
                        'default' => '7',
    
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'delete_expired_listing',
                        'label' => __('Delete/Trash Expired Listings', 'directorist'),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'delete_expired_listings_after',
                        'label' => __('Delete/Trash Expired Listings After (days) of Expiration', 'directorist'),
                        'min' => '0',
                        'max' => '180',
                        'step' => '1',
                        'default' => 15,
                        'validation' => 'numeric',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'deletion_mode',
                        'label' => __('Delete or Trash Expired Listings', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'force_delete',
                                'label' => __('Delete Permanently', 'directorist'),
                            ),
                            array(
                                'value' => 'trash',
                                'label' => __('Move to Trash', 'directorist'),
                            ),
                        ),
                        'description' => __('Choose the Default actions after a listing reaches its deletion threshold.', 'directorist'),
                        /*@todo; later add option to make listing status hidden or invalid for expired listing, so that admin may retain expired listings without having them deleted after the deletion threshold */
                        'default' => array(
                            'value' => 'trash',
                            'label' => __('Move to Trash', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'paginate_author_listings',
                        'label' => __('Paginate Author Listings', 'directorist'),
                        'default' => '1',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'display_author_email',
                        'label' => __('Author Email', 'directorist'),
                        'items' => array(
                            array(
                                'value' => 'public',
                                'label' => __('Display', 'directorist'),
                            ),
                            array(
                                'value' => 'logged_in',
                                'label' => __('Display only for Logged in Users', 'directorist'),
                            ),

                            array(
                                'value' => 'none_to_display',
                                'label' => __('Hide', 'directorist'),
                            ),
                        ),
                        'default' => array(
                            'value' => 'public',
                            'label' => __('Display', 'directorist'),
                        ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'author_cat_filter',
                        'label' => __('Show Category Filter on Author Page', 'directorist'),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'atbdp_enable_cache',
                        'label' => __('Enable Cache', 'directorist'),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'atbdp_reset_cache',
                        'label' => __('Reset Cache', 'directorist'),
                        'default' => '0',
                    ),
                )
            );
        }


        /**
         * @since 5.0
         */
        function get_pages_regenerate_settings_fields()
        {
            return apply_filters('atbdp_regenerate_pages_settings_fields', array(
                array(
                    'type' => 'toggle',
                    'name' => 'shortcode-updated',
                    'label' => __('Upgrade/Regenerate Pages', 'directorist'),
                    'validation' => 'numeric',

                ),
            ));
        }

        /**
         * Get all the settings fields for the pages settings section
         * @return array
         * @since 3.0.0
         */
        function get_pages_settings_fields()
        {
            return apply_filters('atbdp_pages_settings_fields', array(
                    array(
                        'type' => 'select',
                        'name' => 'add_listing_page',
                        'label' => __('Add Listing Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(), // eg. array( array('value'=> 123, 'label'=> 'page_name') );
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_add_listing]</strong>'),
                        'default' => atbdp_get_option('add_listing_page', 'atbdp_general'),
                        'validation' => 'numeric',

                    ),

                    array(
                        'type' => 'select',
                        'name' => 'all_listing_page',
                        'label' => __('All Listings Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_all_listing]</strong>'),

                        'default' => atbdp_get_option('all_listing_page', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),
                    'single_listing_page' => array(
                        'type' => 'select',
                        'name' => 'single_listing_page',
                        'label' => __('Single Listing Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcodes can be used for the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_listing_top_area][directorist_listing_tags][directorist_listing_custom_fields][directorist_listing_video][directorist_listing_map][directorist_listing_contact_information][directorist_listing_contact_owner][directorist_listing_author_info][directorist_listing_review][directorist_related_listings]</strong>'),
                        'default' => atbdp_get_option('single_listing_page', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'user_dashboard',
                        'label' => __('Dashboard Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_user_dashboard]</strong>'),
                        'default' => atbdp_get_option('user_dashboard', 'atbdp_general'),
                        'validation' => 'numeric',

                    ),

                    array(
                        'type' => 'select',
                        'name' => 'author_profile_page',
                        'label' => __('User Profile Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_author_profile]</strong>'),
                        'default' => atbdp_get_option('author_profile', 'atbdp_general'),
                        'validation' => 'numeric',

                    ),

                    array(
                        'type' => 'select',
                        'name' => 'all_categories_page',
                        'label' => __('All Categories Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_all_categories]</strong>'),

                        'default' => atbdp_get_option('all_categories', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'single_category_page',
                        'label' => __('Single Category Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_category]</strong>'),

                        'default' => atbdp_get_option('single_category_page', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'all_locations_page',
                        'label' => __('All Locations Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_all_locations]</strong>'),

                        'default' => atbdp_get_option('all_locations', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'single_location_page',
                        'label' => __('Single Location Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_location]</strong>'),

                        'default' => atbdp_get_option('single_location_page', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'single_tag_page',
                        'label' => __('Single Tag Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_tag]</strong>'),
                        'default' => atbdp_get_option('single_tag_page', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'custom_registration',
                        'label' => __('Registration Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_custom_registration]</strong>'),
                        'default' => atbdp_get_option('custom_registration', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'user_login',
                        'label' => __('Login Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_user_login]</strong>'),
                        'default' => atbdp_get_option('user_login', 'atbdp_general'),
                        'validation' => 'numeric',

                    ),

                    array(
                        'type' => 'select',
                        'name' => 'search_listing',
                        'label' => __('Listing Search Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_search_listing]</strong>'),
                        'default' => atbdp_get_option('search_listing', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'search_result_page',
                        'label' => __('Listing Search Result Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_search_result]</strong>'),
                        'default' => atbdp_get_option('search_result_page', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'checkout_page',
                        'label' => __('Checkout Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_checkout]</strong>'),
                        'default' => '',
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'payment_receipt_page',
                        'label' => __('Payment/Order Receipt Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_payment_receipt]</strong>'),
                        'default' => '',
                        'validation' => 'numeric',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'transaction_failure_page',
                        'label' => __('Transaction Failure Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<strong style="color: #ff4500;">[directorist_transaction_failure]</strong>'),
                        'default' => '',
                        'validation' => 'numeric',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'privacy_policy',
                        'label' => __('Privacy Policy Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'default' => '',
                        'validation' => 'numeric',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'terms_conditions',
                        'label' => __('Terms & Conditions Page', 'directorist'),
                        'items' => $this->get_pages_vl_arrays(),
                        'default' => '',
                        'validation' => 'numeric',
                    ),

                )
            );
        }

        /**
         * Get all the settings fields for the extension settings section
         * @return array
         * @since 3.0.0
         */
        function  get_extension_settings_fields()
        {
            return apply_filters('atbdp_extension_settings_fields', array(
                    'extension_promotion_set' => array(
                        'type' => 'notebox',
                        'name' => 'extension_promotion',
                        'label' => __('Need more Features?', 'directorist'),
                        'description' => sprintf(__('You can add new features and expand the functionality of the plugin even more by using extensions. %s', 'directorist'), $this->extension_url),
                        'status' => 'warning',
                    ),
                )
            );
        }


    } // ends ATBDP_Settings_Manager
endif;
