<?php
if ( !class_exists('ATBDP_Settings_Manager' ) ):
class ATBDP_Settings_Manager {

    private $extension_url = '';

    public function __construct()
    {
        // the safest hook to use is after_setup_theme, since Vafpress Framework may exists in Theme or Plugin
        add_action( 'after_setup_theme', array($this, 'display_plugin_settings') );
        $this->extension_url = sprintf("<a target='_blank' href='%s'>%s</a>", esc_url(admin_url('edit.php?post_type=at_biz_dir&page=atbdp-extension')), __('Checkout Awesome Extensions', ATBDP_TEXTDOMAIN));
    }

    /**
     * It displays the settings page of the plugin using VafPress framework
     * @since 3.0.0
     * @return void
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
            'page_title' => __('Directory settings', ATBDP_TEXTDOMAIN),
            'menu_label' => __('Directory settings', ATBDP_TEXTDOMAIN),
            'template' => array(
                'title' => __('Directory Settings', ATBDP_TEXTDOMAIN),
                'logo' => esc_url(ATBDP_ADMIN_ASSETS . 'images/settings_icon.png'),
                'menus' => $this->get_settings_menus(),
            ),
        );

        // initialize the option page
        new VP_Option($atbdp_options);
    }

    /**
     * Get all the menus for the Settings Page
     * @since 3.0.0
     * @return array It returns an array of Menus
     */
    function get_settings_menus(){
        return apply_filters('atbdp_settings_menus', array(
            /*Main Menu 1*/
            'general_menu' => array(
                'title' => __('General settings', ATBDP_TEXTDOMAIN),
                'name' => 'menu_1',
                'icon' => 'font-awesome:fa-magic',
                'menus' => $this->get_general_settings_submenus(),
            ),
            /*Main Menu 2*/
            'permalink_menu' => array(
                'name' => 'permalinks',
                'title' => __('Permalinks settings', ATBDP_TEXTDOMAIN),
                'icon' => 'font-awesome:fa-link',
                'controls' => apply_filters('atbdp_permalink_settings_controls', array(
                    'permalink_section' => array(
                        'type' => 'section',
                        'title' => __('Slugs & Permalinks', ATBDP_TEXTDOMAIN),
                        'fields' => $this->get_permalink_settings_fields()
                    ), // ends section
                ) ),
            ),
            /*Main Menu 3*/
            'search' => array(
                'name' => 'search',
                'title' => __('Search settings', ATBDP_TEXTDOMAIN),
                'icon' => 'font-awesome:fa-search',
                'controls' => apply_filters('atbdp_search_settings_controls', array(
                    'search_section' => array(
                        'type' => 'section',
                        'title' => __('Search Settings', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Listing Search related settings here. After switching any option, Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields' => $this->get_search_settings_fields(),
                    ), // ends 'search_settings' section
                ) ),
            ),
            /*Main Menu 4*/
            'listings' => array(
                'name' => 'listings',
                'title' => __('Listings settings', ATBDP_TEXTDOMAIN),
                'icon' => 'font-awesome:fa-list',
                'menus' => $this->get_listings_settings_submenus(),
            ),
            /*Main Menu 5*/
            'pages' => array(
                'name' => 'pages',
                'title' => __('Pages, links & views', ATBDP_TEXTDOMAIN),
                'icon' => 'font-awesome:fa-line-chart',
                'controls' => apply_filters('atbdp_pages_settings_controls', array(
                    'search_section' => array(
                        'type' => 'section',
                        'title' => __('Pages, links & views Settings', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Listings related settings here. After switching any option, Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields' => $this->get_pages_settings_fields(),
                    ), // ends 'pages' section
                )),
            ),
            /*Lets make the following extension menu customization by the extensions. Apply a filter on it*/
            'extensions_menu' => array(
                'title' => __('Extensions Settings', ATBDP_TEXTDOMAIN),
                'name' => 'menu_1',
                'icon' => 'font-awesome:fa-magic',
                'menus' => $this->get_extension_settings_submenus(),
            ),
            'email_menu' => array(
                'title' => __('Emails Settings', ATBDP_TEXTDOMAIN),
                'name' => 'email_menu1',
                'icon' => 'font-awesome:fa-envelope',
                'menus' => $this->get_email_settings_submenus(),
            ),
        ));
    }

    /**
     * Get all the submenus for listings
     * @since 3.1.0 ( new)
     * @return array It returns an array of submenus
     */
    public function get_listings_settings_submenus() {
        return apply_filters('atbdp_general_listings_submenus', array(
            /*Submenu : General Listings*/
            array(
                'title' => __('General Listings', ATBDP_TEXTDOMAIN),
                'name' => 'general_listings',
                'icon' => 'font-awesome:fa-sliders',
                'controls' => apply_filters('atbdp_general_listings_controls', array(
                    'emails' => array(
                        'type' => 'section',
                        'title' => __('General Listings', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize all Listings related settings here', ATBDP_TEXTDOMAIN),
                        'fields' => $this->get_general_listings_settings_fields(),
                    ),
                )),
            ),

            /*Submenu : Listing form */
            array(
                'title' => __('Listings Form', ATBDP_TEXTDOMAIN),
                'name' => 'listings_form',
                'icon' => 'font-awesome:fa-wpforms',
                'controls' => apply_filters('atbdp_listings_form_controls', array(
                    'emails' => array(
                        'type' => 'section',
                        'title' => __('Listing Form', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Listings Form related settings here', ATBDP_TEXTDOMAIN),
                        'fields' => $this->get_listings_form_settings_fields(),
                    ),
                )),
            ),
        ));
    }

    /**
     * Get all the submenus for the email menu
     * @since 3.1.0
     * @return array It returns an array of submenus
     */
    public function get_email_settings_submenus()
    {
        return apply_filters('atbdp_email_settings_submenus', array(
            /*Submenu : Email General*/
            array(
                'title' => __('Email General', ATBDP_TEXTDOMAIN),
                'name' => 'emails_general',
                'icon' => 'font-awesome:fa-home',
                'controls' => apply_filters('atbdp_email_settings_controls', array(
                    'emails' => array(
                        'type' => 'section',
                        'title' => __('Email General Settings', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Email and Notification-related settings here. You can enable or disable any emails here. Here, YES means Enabled, and NO means disabled. After switching any option, Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields' => $this->get_email_settings_fields(),
                    ),
                )),
            ),
            /*Submenu : Email Templates*/
            array(
                'title' => __('Email Templates', ATBDP_TEXTDOMAIN),
                'name' => 'emails_templates',
                'icon' => 'font-awesome:fa-envelope',
                'controls' => apply_filters('atbdp_email_templates_settings_controls', array(
                    'new_eml_templates' => array(
                        'type' => 'section',
                        'title' => __('For New Listing', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields' => $this->get_email_new_tmpl_settings_fields(),
                    ),
                    'publish_eml_templates' => array(
                        'type' => 'section',
                        'title' => __('For Approved/Published Listings', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields' => $this->get_email_pub_tmpl_settings_fields(),
                    ),
                    'edited_eml_templates' => array(
                        'type' => 'section',
                        'title' => __('For Edited Listings', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields' => $this->get_email_edit_tmpl_settings_fields(),
                    ),
                    'about_expire_eml_templates' => array(
                        'type' => 'section',
                        'title' => __('For About to Expire Listings', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields' => $this->email_about_expire_tmpl_settings_fields(),
                    ),
                    'expired_eml_templates' => array(
                        'type' => 'section',
                        'title' => __('For Expired Listings', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields' => $this->email_expired_tmpl_settings_fields(),
                    ),
                    'renewal_eml_templates' => array(
                        'type' => 'section',
                        'title' => __('For Renewal Listings (Remind to Renew)', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields' => $this->email_renewal_tmpl_settings_fields(),
                    ),
                    'renewed_eml_templates' => array(
                        'type' => 'section',
                        'title' => __('For Renewed Listings (After Renewed)', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields' => $this->email_renewed_tmpl_settings_fields(),
                    ),
                    'deleted_eml_templates' => array(
                        'type' => 'section',
                        'title' => __('For deleted/trashed Listings', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields' => $this->email_deleted_tmpl_settings_fields(),
                    ),
                    'new_order_created' => array(
                        'type' => 'section',
                        'title' => __('For New Order (Created)', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields' => $this->email_new_order_tmpl_settings_fields(),
                    ),
                    'offline_new_order_created' => array(
                        'type' => 'section',
                        'title' => __('For New Order (Created using Offline Bank Transfer)', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields' => $this->email_offline_new_order_tmpl_settings_fields(),
                    ),
                    'completed_order_created' => array(
                        'type' => 'section',
                        'title' => __('For Completed Order', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields' => $this->email_completed_order_tmpl_settings_fields(),
                    ),
                    'listing_contact_email' => array(
                        'type' => 'section',
                        'title' => __('For Listing contact email', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields' => $this->listing_contact_email(),
                    ),
                )),
            ),
        ));
    }

    /**
     * Get all the settings fields for the new listing email template section
     * @since 3.1.0
     * @return array
     */
    public function get_email_new_tmpl_settings_fields()
    {
        // let's define default data template
        $sub = __('[==SITE_NAME==] : Listing "==LISTING_TITLE==" Received', ATBDP_TEXTDOMAIN);

        $tmpl = __("
Dear ==NAME==,

This email is to notify you that your listing '==LISTING_TITLE==' has been received and it is under review now. 
It may take up to 24 hours to complete the review.

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);
        //create small var to highlight important texts
        $c='<span style="color:#c71585;">'; //color start
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
**{$c}==NOW=={$e}** : It outputs the current time<br/><br/>
**Additionally, you can also use HTML tags in your template.**
KAMAL;


        return apply_filters('atbdp_email_new__tmpl_settings_fields', array(
                array(
                    'type' => 'notebox',
                    'name' => 'email_placeholder_info',
                    'label' => $c.__('You can use Placeholders to output dynamic value', ATBDP_TEXTDOMAIN).$e,
                    'description' => $ph,
                    'status' => 'normal',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_new_listing',
                    'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the subject for sending to the user when a listing is submitted/received.', ATBDP_TEXTDOMAIN),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_new_listing',
                    'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the email template for sending to the user when a listing is submitted/received. HTML content is allowed too.', ATBDP_TEXTDOMAIN),
                    'default' => $tmpl,
                ),


        ));
    }

    /**
     * Get all the settings fields for the published listing email template section
     * @since 3.1.0
     * @return array
     */
    public function get_email_pub_tmpl_settings_fields()
    {
        // let's define default data
        $sub = __('[==SITE_NAME==] : Listing "==LISTING_TITLE==" published', ATBDP_TEXTDOMAIN);
        $tmpl = __("
Dear ==NAME==,
Congratulations! Your listing '==LISTING_TITLE==' has been approved/published. Now it is publicly available at ==LISTING_URL==

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

        return apply_filters('atbdp_email_pub_tmpl_settings_fields', array(
            array(
                'type' => 'textbox',
                'name' => 'email_sub_pub_listing',
                'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the subject for sending to the user when a listing is approved/published.', ATBDP_TEXTDOMAIN),
                'default' => $sub,
            ),
            array(
                'type' => 'textarea',
                'name' => 'email_tmpl_pub_listing',
                'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the email template for sending to the user when a listing is approved/published. HTML content is allowed too.', ATBDP_TEXTDOMAIN),
                'default' => $tmpl,
            ),
        ));
    }

    /**
     * Get all the settings fields for the edited listing email template section
     * @since 3.1.0
     * @return array
     */
    public function get_email_edit_tmpl_settings_fields()
    {
        // let's define default data
        $sub = __('[==SITE_NAME==] : Listing "==LISTING_TITLE==" Edited', ATBDP_TEXTDOMAIN);
        $tmpl = __("
Dear ==NAME==,
Congratulations! Your listing '==LISTING_TITLE==' has been edited. It is publicly available at ==LISTING_URL==

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

        return apply_filters('atbdp_email_edit_tmpl_settings_fields', array(
            array(
                'type' => 'textbox',
                'name' => 'email_sub_edit_listing',
                'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the subject for sending to the user when a listing is edited.', ATBDP_TEXTDOMAIN),
                'default' => $sub,
            ),
            array(
                'type' => 'textarea',
                'name' => 'email_tmpl_edit_listing',
                'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the email template for sending to the user when a listing is edited. HTML content is allowed too.', ATBDP_TEXTDOMAIN),
                'default' => $tmpl,
            ),
        ));
    }

    /**
     * Get all the settings fields for the renew listing email template section
     * @since 3.1.0
     * @return array
     */
    public function email_about_expire_tmpl_settings_fields()
    {
        // let's define default data
        $sub = __('[==SITE_NAME==] : Your Listing "==LISTING_TITLE==" is about to expire.', ATBDP_TEXTDOMAIN);
        /*@todo; includes the number of days remaining to expire the listing*/
        $tmpl = __("
Dear ==NAME==,
Your listing '==LISTING_TITLE==' is about to expire. It will expire on ==EXPIRATION_DATE==. You can renew it at ==RENEWAL_LINK==

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

        return apply_filters('atbdp_email_about_expire_tmpl_settings_fields', array(
            array(
                'type' => 'slider',
                'name' => 'email_to_expire_day',
                'label' => __( 'When to send expire notice', ATBDP_TEXTDOMAIN ),
                'description' => __( 'Select the days before a listing expires to send an expiration reminder email', ATBDP_TEXTDOMAIN ),
                'min' => '1',
                'max' => '120',
                'step' => '1',
                'default' => '7',
                'validation' => 'required',
            ),
            array(
                'type' => 'textbox',
                'name' => 'email_sub_to_expire_listing',
                'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the subject for sending to the user when a listing is ABOUT TO EXPIRE.', ATBDP_TEXTDOMAIN),
                'default' => $sub,
            ),
            array(
                'type' => 'textarea',
                'name' => 'email_tmpl_to_expire_listing',
                'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the email template for sending to the user when a listing is ABOUT TO EXPIRE. HTML content is allowed too.', ATBDP_TEXTDOMAIN),
                'default' => $tmpl,
            ),
        ));
    }

    /**
     * Get all the settings fields for the renew listing email template section
     * @since 3.1.0
     * @return array
     */
    public function email_expired_tmpl_settings_fields()
    {
        // let's define default data
        $sub = __("[==SITE_NAME==] : Your Listing '==LISTING_TITLE==' has expired.",ATBDP_TEXTDOMAIN);
        $tmpl = __("
Dear ==NAME==,
Your listing '==LISTING_TITLE==' has expired on ==EXPIRATION_DATE==. You can renew it at ==RENEWAL_LINK==

Thanks,
The Administrator of ==SITE_NAME==
",ATBDP_TEXTDOMAIN);

        return apply_filters('atbdp_email_expired_tmpl_settings_fields', array(
            array(
                'type' => 'textbox',
                'name' => 'email_sub_expired_listing',
                'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the subject for sending to the user when a Listing HAS EXPIRED.', ATBDP_TEXTDOMAIN),
                'default' => $sub,
            ),
            array(
                'type' => 'textarea',
                'name' => 'email_tmpl_expired_listing',
                'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the email template for sending to the user when a Listing HAS EXPIRED. HTML content is allowed too.', ATBDP_TEXTDOMAIN),
                'default' => $tmpl,
            ),

        ));
    }

    /**
     * Get all the settings fields for the renew listing email template section
     * @since 3.1.0
     * @return array
     */
    public function email_renewal_tmpl_settings_fields()
    {
        // let's define default data
        $sub = __('[==SITE_NAME==] : A Reminder to Renew your listing "==LISTING_TITLE=="', ATBDP_TEXTDOMAIN);
        $tmpl = __("
Dear ==NAME==,

We have noticed that you might have forgot to renew your listing '==LISTING_TITLE==' at ==SITE_LINK==. We would like to remind you that it expired on ==EXPIRATION_DATE==. But please don't worry.  You can still renew it by clicking this link: ==RENEWAL_LINK==.

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

        return apply_filters('atbdp_email_renewal_tmpl_settings_fields', array(
            array(
                'type' => 'slider',
                'name' => 'email_renewal_day',
                'label' => __( 'When to send renewal reminder', ATBDP_TEXTDOMAIN ),
                'description' => __( 'Select the days after a listing expires to send a renewal reminder email', ATBDP_TEXTDOMAIN ),
                'min' => '1',
                'max' => '120',
                'step' => '1',
                'default' => '7',
                'validation' => 'required',
            ),
            array(
                'type' => 'textbox',
                'name' => 'email_sub_to_renewal_listing',
                'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the subject for sending to the user to renew his/her listings.', ATBDP_TEXTDOMAIN),
                'default' => $sub,
            ),
            array(
                'type' => 'textarea',
                'name' => 'email_tmpl_to_renewal_listing',
                'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the email template for sending to the user to renew his/her listings. HTML content is allowed too.', ATBDP_TEXTDOMAIN),
                'default' => $tmpl,
            ),


        ));
    }

    /**
     * Get all the settings fields for the renewed listing email template section
     * @since 3.1.0
     * @return array
     */
    public function email_renewed_tmpl_settings_fields()
    {
        // let's define default data
        $sub = __('[==SITE_NAME==] : Your Listing "==LISTING_TITLE==" Has Renewed', ATBDP_TEXTDOMAIN);
        $tmpl = __("
Dear ==NAME==,

Congratulations!
Your listing '==LISTING_LINK==' with the ID #==LISTING_ID== has been renewed successfully at ==SITE_LINK==.
Your listing is now publicly viewable at ==LISTING_URL==

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

        return apply_filters('atbdp_email_renewed_tmpl_settings_fields', array(
            array(
                'type' => 'textbox',
                'name' => 'email_sub_renewed_listing',
                'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the subject for sending to the user his/her listings has renewed successfully.', ATBDP_TEXTDOMAIN),
                'default' => $sub,
            ),
            array(
                'type' => 'textarea',
                'name' => 'email_tmpl_renewed_listing',
                'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the email template for sending to the user his/her listings has renewed successfully. HTML content is allowed too.', ATBDP_TEXTDOMAIN),
                'default' => $tmpl,
            ),


        ));
    }

    /**
     * Get all the settings fields for the deleted listing email template section
     * @since 3.1.0
     * @return array
     */
    public function email_deleted_tmpl_settings_fields()
    {
        // let's define default data
        $sub = __('[==SITE_NAME==] : Your Listing "==LISTING_TITLE==" Has Been Deleted', ATBDP_TEXTDOMAIN);
        $tmpl = __("
Dear ==NAME==,

Your listing '==LISTING_LINK==' with the ID #==LISTING_ID== has been deleted successfully at ==SITE_LINK==.

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

        return apply_filters('atbdp_email_deleted_tmpl_settings_fields', array(
            array(
                'type' => 'textbox',
                'name' => 'email_sub_deleted_listing',
                'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the subject for sending to the user when his/her listings has deleted successfully.', ATBDP_TEXTDOMAIN),
                'default' => $sub,
            ),
            array(
                'type' => 'textarea',
                'name' => 'email_tmpl_deleted_listing',
                'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the email template for sending to the user when his/her listings has deleted successfully. HTML content is allowed too.', ATBDP_TEXTDOMAIN),
                'default' => $tmpl,
            ),


        ));
    }


    /**
     * Get all the settings fields for the new order email template section
     * @since 3.1.0
     * @return array
     */
    public function email_new_order_tmpl_settings_fields()
    {
        // let's define default data
        $sub = __('[==SITE_NAME==] : Your Order (#==ORDER_ID==) Received.', ATBDP_TEXTDOMAIN);
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
", ATBDP_TEXTDOMAIN);

        return apply_filters('atbdp_new_order_tmpl_settings_fields', array(
            array(
                'type' => 'textbox',
                'name' => 'email_sub_new_order',
                'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the subject for sending to the user when an order is created.', ATBDP_TEXTDOMAIN),
                'default' => $sub,
            ),
            array(
                'type' => 'textarea',
                'name' => 'email_tmpl_new_order',
                'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the email template for sending to the user when an order is created.', ATBDP_TEXTDOMAIN),
                'default' => $tmpl,
            ),


        ));
    }


    /**
     * Get all the settings fields for the offline new order email template section
     * @since 3.1.0
     * @return array
     */
    public function email_offline_new_order_tmpl_settings_fields()
    {
        // let's define default data
        $sub = __('[==SITE_NAME==] : Your Order (#==ORDER_ID==) Received.', ATBDP_TEXTDOMAIN);
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
", ATBDP_TEXTDOMAIN), get_directorist_option('bank_transfer_instruction'));

        return apply_filters('atbdp_offline_new_order_tmpl_settings_fields', array(
            array(
                'type' => 'textbox',
                'name' => 'email_sub_offline_new_order',
                'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the subject for sending to the user when an order is created using offline payment like bank transfer.', ATBDP_TEXTDOMAIN),
                'default' => $sub,
            ),
            array(
                'type' => 'textarea',
                'name' => 'email_tmpl_offline_new_order',
                'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the email template for sending to the user when an order is created using offline payment like bank transfer.', ATBDP_TEXTDOMAIN),
                'default' => $tmpl,
            ),


        ));
    }

    /**
     * Get all the settings fields for the offline new order email template section
     * @since 3.1.0
     * @return array
     */
    public function email_completed_order_tmpl_settings_fields()
    {
        // let's define default data
        $sub = __('[==SITE_NAME==] : Congratulation! Your Order #==ORDER_ID== Completed.', ATBDP_TEXTDOMAIN);


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
", ATBDP_TEXTDOMAIN);

        return apply_filters('atbdp_completed_order_tmpl_settings_fields', array(
            array(
                'type' => 'textbox',
                'name' => 'email_sub_completed_order',
                'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the subject for sending to the user when an order is completed', ATBDP_TEXTDOMAIN),
                'default' => $sub,
            ),
            array(
                'type' => 'textarea',
                'name' => 'email_tmpl_completed_order',
                'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the email template for sending to the user when an order is completed.', ATBDP_TEXTDOMAIN),
                'default' => $tmpl,
            ),


        ));
    }


    /**
     * Get all the settings fields for the offline new order email template section
     * @since 3.1.0
     * @return array
     */
    public function listing_contact_email()
    {
        // let's define default data
        $sub = __('[==SITE_NAME==] Contact via "[==LISTING_TITLE==]"', ATBDP_TEXTDOMAIN);

        $tmpl = __("
Dear [==NAME==],

You have received a reply from your listing at ==LISTING_URL==.

Name: ==SENDER_NAME==
Email: ==SENDER_EMAIL==
Message: ==MESSAGE==
Time: ==NOW==

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

        return apply_filters('atbdp_completed_order_tmpl_settings_fields', array(
            array(
                'type' => 'textbox',
                'name' => 'email_sub_listing_contact_email',
                'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the subject for sending to the user when listing contact message send.', ATBDP_TEXTDOMAIN),
                'default' => $sub,
            ),
            array(
                'type' => 'textarea',
                'name' => 'email_tmpl_listing_contact_email',
                'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                'description' => __('Edit the email template for sending to the user when when listing contact message send', ATBDP_TEXTDOMAIN),
                'default' => $tmpl,
            ),


        ));
    }


    /**
     * Get all the settings fields for the email settings section
     * @since 3.1.0
     * @return array
     */
    public function get_email_settings_fields()
    {
        return apply_filters('atbdp_email_settings_fields', array(
                array(
                    'type' => 'toggle',
                    'name' => 'disable_email_notification',
                    'label' => __('Disable all Email Notifications', ATBDP_TEXTDOMAIN),
                    'description' => __('Set this option to YES to DISABLE ALL EMAIL NOTIFICATIONS. Default is NO which means Email notification is enabled by default.', ATBDP_TEXTDOMAIN),
                    'default' => '',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'email_from_name',
                    'label' => __('Email\'s "From Name"', ATBDP_TEXTDOMAIN),
                    'description' => __('Which name should be used as FROM NAME in the email generated by the plugin in different situations like when an order is made etc.?', ATBDP_TEXTDOMAIN),
                    'default' => get_option('blogname'),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'email_from_email',
                    'label' => __('Email\'s "From Email"', ATBDP_TEXTDOMAIN),
                    'description' => __('Which email should be used as FROM Email in the email generated by the plugin in different situations like when an order is made etc.?', ATBDP_TEXTDOMAIN),
                    'default' => get_option('admin_email'),
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'admin_email_lists',
                    'label' => __('Admin Email Address(es)', ATBDP_TEXTDOMAIN),
                    'description' => __('Enter the one or more admin email addresses to send notification. You can add more than one email addresses separating them by COMMAS. Eg. admin1@example.com, admin2@example.com etc', ATBDP_TEXTDOMAIN),
                    'default' => get_option('admin_email'),
                ),
                array(
                    'type' => 'multiselect',
                    'name' => 'notify_admin',
                    'label' => __('Notify the Admin when any of the selected event happens', ATBDP_TEXTDOMAIN),
                    'description' => __('Select the situation when you would like to send an email to the Admin', ATBDP_TEXTDOMAIN),
                    'items' => $this->events_to_notify_admin(),
                    'default' => $this->default_events_to_notify_admin(),
                ),
                array(
                    'type' => 'multiselect',
                    'name' => 'notify_user',
                    'label' => __('Notify the Listing Owner when any of the selected event happens', ATBDP_TEXTDOMAIN),
                    'description' => __('Select the situation when you would like to send an email to the Listing', ATBDP_TEXTDOMAIN),
                    'items' => $this->events_to_notify_user(),
                    'default' => $this->default_events_to_notify_user(),
                ),
            )
        );
    }

    /**
     * Get the list of an array of notification events array to notify admin
     * @since 3.1.0
     * @return array It returns an array of events when an admin should be notified
     */
    public function events_to_notify_admin()
    {
        $events = array_merge($this->default_notifiable_events(), $this->only_admin_notifiable_events());
        return apply_filters('atbdp_events_to_notify_admin', $events);
    }

    /**
     * Get the list of an array of notification events array to notify user
     * @since 3.1.0
     * @return array It returns an array of events when an user should be notified
     */
    public function events_to_notify_user()
    {
        $events = array_merge($this->default_notifiable_events(), $this->only_user_notifiable_events());
        return apply_filters('atbdp_events_to_notify_user', $events);
    }

    /**
     * Get the default events to notify the admin.
     * @since 3.1.0
     * @return array It returns an array of default events when an admin should be notified.
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
        ));
    }

    /**
     * Get the default events to notify the user.
     * @since 3.1.0
     * @return array It returns an array of default events when an user should be notified.
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
     * @since 3.1.0
     * @return array it returns an array of events
     */
    private function default_notifiable_events()
     {
        return apply_filters('atbdp_default_notifiable_events', array(
            array(
                'value' => 'order_created',
                'label' => __('Order Created', ATBDP_TEXTDOMAIN),
            ),
            array(
                'value' => 'order_completed',
                'label' => __('Order Completed', ATBDP_TEXTDOMAIN),
            ),
            array(
                'value' => 'listing_submitted',
                'label' => __('New Listing Submitted', ATBDP_TEXTDOMAIN),
            ),
            array(
                'value' => 'listing_published',
                'label' => __('Listing Approved/Published', ATBDP_TEXTDOMAIN),
            ),
            array(
                'value' => 'listing_edited',
                'label' => __('Listing Edited', ATBDP_TEXTDOMAIN),
            ),
            array(
                'value' => 'payment_received',
                'label' => __('Payment Received', ATBDP_TEXTDOMAIN),
            ),
            array(
                'value' => 'listing_deleted',
                'label' => __('Listing Deleted', ATBDP_TEXTDOMAIN),
            ),
            array(
                'value' => 'listing_contact_form',
                'label' => __('Listing contact form', ATBDP_TEXTDOMAIN),
            ),
        ));
    }

    /**
     * Get an array of events to notify only the admin
     * @since 3.1.0
     * @return array it returns an array of events
     */
    private function only_admin_notifiable_events()
    {
        return apply_filters('atbdp_only_admin_notifiable_events', array(
                array(
                    'value' => 'listing_owner_contacted',
                    'label' => __('Listing owner is contacted', ATBDP_TEXTDOMAIN),
                ),
        ));
    }

    /**
     * Get an array of events to notify only users
     * @since 3.1.0
     * @return array it returns an array of events
     */
    private function only_user_notifiable_events()
    {
       return apply_filters('atbdp_only_user_notifiable_events', array(
                array(
                    'value' => 'listing_to_expire',
                    'label' => __('Listing nearly Expired', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'value' => 'listing_expired',
                    'label' => __('Listing Expired', ATBDP_TEXTDOMAIN),
                ),
               array(
                   'value' => 'remind_to_renew',
                   'label' => __('Remind to renew', ATBDP_TEXTDOMAIN),
               ),
                array(
                    'value' => 'listing_renewed',
                    'label' => __('Listing Renewed', ATBDP_TEXTDOMAIN),
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
     * @since 3.0.0
     * @return array page names with key value pairs in a multi-dimensional array
     */
    function get_pages_vl_arrays() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[] = array( 'value'=>$page->ID, 'label'=> $page->post_title);
            }
        }

        return $pages_options;
    }

    /**
     * Get all the submenus for the General Settings menu
     * @since 3.0.0
     * @return array It returns an array of submenus
     */
    function get_general_settings_submenus(){
        return apply_filters('atbdp_general_settings_submenus', array(
            'submenu_1' => array(
                'title' => __( 'Home', ATBDP_TEXTDOMAIN),
                'name' => 'submenu_1',
                'icon' => 'font-awesome:fa-home',
                'controls' => apply_filters('atbdp_general_settings_controls', array(
                    'general_section' => array(
                        'type'          => 'section',
                        'title'         => __('General Settings', ATBDP_TEXTDOMAIN),
                        'description'   => __('You can Customize General settings here. After switching any option, Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields'        => $this->get_general_settings_fields(),
                    ), // ends general settings section
                    'currency_section' => array(
                        'type'          => 'section',
                        'title'         => __('Currency Settings', ATBDP_TEXTDOMAIN),
                        'description'   => __('This currency settings lets you customize how you would like to display price amount in your website. Note: You can accept currency in different currency. So, For payment related Currency Settings, Go to Gateway Settings Tab.', ATBDP_TEXTDOMAIN),
                        'fields'        => $this->get_currency_settings_fields(),
                    ), // ends general settings section
                )),
            ),

        ) );
    }

    /**
     * Get all the submenus for the extension menu
     * @since 3.0.0
     * @return array It returns an array of submenus
     */
    function get_extension_settings_submenus(){

        return apply_filters('atbdp_extension_settings_submenus', array(
            'submenu_1' => array(
                'title' => __('Extensions General', ATBDP_TEXTDOMAIN),
                'name' => 'extensions_switch',
                'icon' => 'font-awesome:fa-home',
                'controls' => apply_filters('atbdp_extension_settings_controls', array(
                    'extensions' => array(
                        'type' => 'section',
                        'title' => __('Extensions General Settings', ATBDP_TEXTDOMAIN),
                        'description' => __('You can Customize Extensions-related settings here. You can enable or disable any extensions here. Here, YES means Enabled, and NO means disabled. After switching any option, Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields' => $this->get_extension_settings_fields(),
                    ),
                )),
            ),
        ));
    }

    /**
     * Get all the settings fields for the general settings section
     * @since 3.0.0
     * @return array
     */
    function get_general_settings_fields(){
        /*ADAPTED FOR BACKWARD COMPATIBILITY*/
        $fix_b_js = atbdp_get_option('fix_js_conflict', 'atbdp_general', 'no'); // fix bootstrap js conflict

        return apply_filters('atbdp_general_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'map_api_key',
                    'label' => __( 'Google Map API key', ATBDP_TEXTDOMAIN ),
                    'description' => sprintf(__( 'You need to enter your google map api key in order to display google map. You can find your map api key and detailed information %s. or you can search in google', ATBDP_TEXTDOMAIN ), '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"> <strong style="color: red;">here</strong> </a>'),
                    'default' => atbdp_get_option('map_api_key', 'atbdp_general'),
                ),

                array(
                    'type' => 'slider',
                    'name' => 'map_zoom_level',
                    'label' => __( 'Google Map Zoom Level', ATBDP_TEXTDOMAIN ),
                    'description' => __( 'You can adjust the zoom level of the map. 0 means 100% zoom-out. 22 means 100% zoom-in. Minimum Zoom Allowed = 1. Max Zoom Allowed = 22. Default is 16. ', ATBDP_TEXTDOMAIN ),
                    'min' => '1',
                    'max' => '22',
                    'step' => '1',
                    'default' => '16',

                ),
                array(
                    'type' => 'toggle',
                    'name' => 'disable_map',
                    'label' => __( 'Disable Google Map', ATBDP_TEXTDOMAIN ),
                    'description' => __( 'Set it YES to disable Google map on your website. If you leave it off, then you or your user can disable google map on individual listing too.', ATBDP_TEXTDOMAIN ),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'disable_sharing',
                    'label' => __( 'Disable Social Sharing links', ATBDP_TEXTDOMAIN ),
                    'description' => __( 'Set it YES to disable Social Sharing links on a Single Listing Details Page.', ATBDP_TEXTDOMAIN ),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'disable_contact_info',
                    'label' => __( 'Disable Contact Information', ATBDP_TEXTDOMAIN ),
                    'description' => __( 'Set it YES to disable Contact Information on a Single Listing Details page.', ATBDP_TEXTDOMAIN ),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'disable_list_price',
                    'label' => __( 'Disable Listing Price', ATBDP_TEXTDOMAIN ),
                    'description' => __( 'Set it YES to disable Price field. However, if you do not disable price globally here, you can also disable pricing per listing during adding a listing.', ATBDP_TEXTDOMAIN ),
                    'default' => 0,
                ),

                array(
                    'type' => 'toggle',
                    'name' => 'fix_js_conflict',
                    'label' => __('Fix Conflict with Bootstrap JS', ATBDP_TEXTDOMAIN),
                    'description' => __('If you use a theme that uses Bootstrap Framework especially Bootstrap JS, then Check this setting to fix any conflict with theme bootstrap js.', ATBDP_TEXTDOMAIN),
                    'default' => atbdp_yes_to_bool($fix_b_js),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'exclude_bootstrap_css',
                    'label' => __('Do not include Plugin\'s Bootstrap CSS in the Front-End', ATBDP_TEXTDOMAIN),
                    'description' => __('You can turn this option YES to disable Bootstrap CSS in the Front-End if your theme is using bootstrap CSS already and you are facing problem.', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'exclude_admin_bootstrap_css',
                    'label' => __('Do not include Plugin\'s Bootstrap CSS in the Backend', ATBDP_TEXTDOMAIN),
                    'description' => __('You can turn this option YES to disable Bootstrap CSS in the Admin Area of Directorist. It is better to keep this option turned off to load bootstrap css in the admin area of this plugin, unless you have a good reason to turn it YES', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),

            )
        );
    }

    /**
     * @return array
     */
    function get_currency_settings_fields(){
        return apply_filters('atbdp_currency_settings_fields', array(
                array(
                    'type' => 'notebox',
                    'name' => 'g_currency_note',
                    'label' => __('Note About This Currency Settings:', ATBDP_TEXTDOMAIN),
                    'description' => __('This currency settings lets you customize how you would like to display price amount in your website. However, you can accept currency in a different currency. Therefore, for accepting currency in a different currency, Go to Gateway Settings Tab.', ATBDP_TEXTDOMAIN),
                    'status' => 'info',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'g_currency',
                    'label' => __( 'Currency Name', ATBDP_TEXTDOMAIN ),
                    'description' => __( 'Enter the Name of the currency eg. USD or GBP etc.', ATBDP_TEXTDOMAIN ),
                    'default' => 'USD',
                    'validation' => 'required',
                ),
                /*@todo; lets user use space as thousand separator in future. @see: https://docs.oracle.com/cd/E19455-01/806-0169/overview-9/index.html
                */
                array(
                    'type' => 'textbox',
                    'name' => 'g_thousand_separator',
                    'label' => __( 'Thousand Separator', ATBDP_TEXTDOMAIN ),
                    'description' => __( 'Enter the currency thousand separator. Eg. , or . etc.', ATBDP_TEXTDOMAIN ),
                    'default' => ',',
                    'validation' => 'required',
                ),

                array(
                    'type' => 'textbox',
                    'name' => 'g_decimal_separator',
                    'label' => __('Decimal Separator', ATBDP_TEXTDOMAIN),
                    'description' => __('Enter the currency decimal separator. Eg. "." or ",". Default is "."', ATBDP_TEXTDOMAIN),
                    'default' => '.',
                ),
                array(
                    'type' => 'select',
                    'name' => 'g_currency_position',
                    'label' => __('Currency Position', ATBDP_TEXTDOMAIN),
                    'description' => __('Select where you would like to show the currency symbol. Default is before. Eg. $5', ATBDP_TEXTDOMAIN),
                    'default' => array(
                        'before',
                    ),
                    'items' => array(
                        array(
                            'value' => 'before',
                            'label' => __('$5 - Before', ATBDP_TEXTDOMAIN),
                        ),
                        array(
                            'value' => 'after',
                            'label' => __('After - 5$', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                ),
            )
        );
    }

    /**
     * Get all the settings fields for the permalink settings section
     * @since 3.0.0
     * @return array
     */
    function get_permalink_settings_fields(){
        return apply_filters('atbdp_permalink_settings_fields', array(
                    array(
                        'type' => 'notebox',
                        'label' => __('Notice about slugs:', ATBDP_TEXTDOMAIN),
                        'description' => __('Slugs must contain only alpha-numeric characters, underscores or dashes. All slugs must be unique and different.', ATBDP_TEXTDOMAIN),
                        'status' => 'warning',
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'atbdp_listing_slug',
                        'label' => __('Listing slug', ATBDP_TEXTDOMAIN),
                        'default' => 'at_biz_dir',
                        'validation' => 'required',
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'atbdp_cat_slug',
                        'label' => __('Category slug', ATBDP_TEXTDOMAIN),
                        'default' => ATBDP_CATEGORY,
                        'validation' => 'required',
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'atbdp_loc_slug',
                        'label' => __('Location slug', ATBDP_TEXTDOMAIN),
                        'default' => ATBDP_LOCATION,
                        'validation' => 'required',
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'atbdp_tag_slug',
                        'label' => __('Tag slug', ATBDP_TEXTDOMAIN),
                        'default' => ATBDP_TAGS,
                        'validation' => 'required',
                    ),

                    array(
                        'type' => 'notebox',
                        'label' => __('Tips & Troubleshooting:', ATBDP_TEXTDOMAIN),
                        'description' => __('NOTE: If changing this option does not work, then do not worry. Just go to "WordPress Dashboard>Settings>Permalinks" and just click save. It should work fine now.', ATBDP_TEXTDOMAIN),
                        'status' => 'info',
                    ),

            )
        );
    }

    /**
         * Get all the settings fields for the search settings section
         * @since 3.0.0
         * @return array
         */
        function get_search_settings_fields(){
            return apply_filters('atbdp_search_settings_fields', array(
                    array(
                        'type' => 'textbox',
                        'name' => 'search_title',
                        'label' => __('Search Bar Title', ATBDP_TEXTDOMAIN),
                        'description' => __( 'Enter the title for search bar on Home Page. Eg. Find the Best Places to Be', ATBDP_TEXTDOMAIN ),
                        'default' => atbdp_get_option('search_title', 'atbdp_general'),
                    ),

                    array(
                        'type' => 'textbox',
                        'name' => 'search_subtitle',
                        'label' => __('Search Bar Sub-title', ATBDP_TEXTDOMAIN),
                        'description' => __( 'Enter the sub-title for search bar on Home Page. Eg. All the top locations – from restaurants and clubs, to cinemas, galleries, and more..', ATBDP_TEXTDOMAIN ),
                        'default' => atbdp_get_option('search_subtitle', 'atbdp_general'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_placeholder',
                        'label' => __('Search Bar Placeholder', ATBDP_TEXTDOMAIN),
                        'description' => __( 'Enter the placeholder text for the search field. Eg. What are you looking for?', ATBDP_TEXTDOMAIN ),
                        'default' => atbdp_get_option('search_placeholder', 'atbdp_general'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'no_result_found_text',
                        'label' => __('Text For "Search - No Result Found"', ATBDP_TEXTDOMAIN),
                        'description' => __( 'Enter the text that you would like to display when a search finds zero results. Eg. Sorry, No Matched Results Found !', ATBDP_TEXTDOMAIN ),
                        'default' => __( 'Sorry, No Matched Results Found !', ATBDP_TEXTDOMAIN ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'paginate_search_results',
                        'label' => __('Paginate Search Result', ATBDP_TEXTDOMAIN),
                        'description' => __('If you do not want to show pagination on search result page, turn it off.', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'search_posts_num',
                        'label' => __('Search Results per page', ATBDP_TEXTDOMAIN),
                        'description' => __( 'Enter how many listings you would like to show per page on your listing search result page. Eg. 6. Default is 6. If pagination is turned off, then this option value will limit the total listings to display', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '100',
                        'step' => '1',
                        'default' => atbdp_get_option('search_posts_num', 'atbdp_general'),
                        'validation' => 'numeric|minlength[1]',
                    ),

                )
            );
        }


    /**
     * Get all the settings fields for the listings settings section
     * @since 3.0.0
     * @return array
     */
    function get_listings_form_settings_fields() {
        return apply_filters('atbdp_listings_settings_fields' , array(
            array(
                'type' => 'toggle',
                'name' => 'listing_terms_condition',
                'label' => __('Required Terms & Conditions', ATBDP_TEXTDOMAIN),
                'description' => __('Here YES means restrict user to submit listing from front end without checking it.', ATBDP_TEXTDOMAIN),
                'default' => 1,
            ),
            array(
                'type' => 'textarea',
                'name' => 'listing_terms_condition_text',
                'label' => __('Custom Terms & Conditions', ATBDP_TEXTDOMAIN),
                'description' => __('You may include and any of your business policy here.', ATBDP_TEXTDOMAIN),
                'default' => 'Replace it with your own Terms and Conditions',
            ),
            array(
                'type' => 'select',
                'name' => 'new_listing_status',
                'label' => __( 'New Listing\'s Default status', ATBDP_TEXTDOMAIN ),
                'items' => array(
                    array(
                        'value' => 'publish',
                        'label' => __('Published', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'value' => 'pending',
                        'label' => __('Pending', ATBDP_TEXTDOMAIN),
                    ),
                ),
                'description' => __( 'Choose the Default Listing Status for a new listing when a user submits it from the Front End', ATBDP_TEXTDOMAIN ),

                'default' => array(
                    'value' => 'publish',
                    'label' => __('Published', ATBDP_TEXTDOMAIN),
                ),
            ),
            array(
                'type' => 'select',
                'name' => 'edit_listing_status',
                'label' => __( 'Edited Listing\'s Default Status', ATBDP_TEXTDOMAIN ),
                'items' => array(
                    array(
                        'value' => 'publish',
                        'label' => __('Published', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'value' => 'pending',
                        'label' => __('Pending', ATBDP_TEXTDOMAIN),
                    ),
                ),
                'description' => __( 'Select the Default Listing Status for Edited listing when a user edits it on the front end.', ATBDP_TEXTDOMAIN ),

                'default' => array(
                    'value' => 'publish',
                    'label' => __('Published', ATBDP_TEXTDOMAIN),
                ),
            ),
            array(
                'type' => 'toggle',
                'name' => 'enable_report_abuse',
                'label' => __('Enable Report Abuse', ATBDP_TEXTDOMAIN),
                'description' => __('Check this to enable Report Abuse.', ATBDP_TEXTDOMAIN),
                'default' => 1,
            ),
            array(
                'type' => 'toggle',
                'name' => 'atbd_video_url',
                'label' => __('Enable Video', ATBDP_TEXTDOMAIN),
                'description' => __('Allow users to add videos for their listings.', ATBDP_TEXTDOMAIN),
                'default' => 1,
            ),
            array(
                'type' => 'textbox',
                'name' => 'atbd_video_title',
                'label' => __('Video Label', ATBDP_TEXTDOMAIN),
                'description' => __('Enter video label for the single listing page.', ATBDP_TEXTDOMAIN),
                'default' => __('Video', ATBDP_TEXTDOMAIN),
            ),

        ));
    }
    /**
     * Get all the settings fields for the listings settings section
     * @since 3.0.0
     * @return array
     */
    function get_general_listings_settings_fields(){
        // BACKWARD COMPATIBILITY:  OLD SETTINGS DATA that should be adapted by using them as default value, will be removed in future
        $s_p_cat = atbdp_get_option('show_popular_category', 'atbdp_general', 'yes');
        $e_p_list = atbdp_get_option('enable_pop_listing', 'atbdp_general', 'yes');
        $e_r_list = atbdp_get_option('enable_rel_listing', 'atbdp_general', 'yes');

        return apply_filters('atbdp_listings_settings_fields', array(
                array(
                    'type' => 'slider',
                    'name' => 'listing_expire_in_days',
                    'label' => __('Default Listing Expires in Days', ATBDP_TEXTDOMAIN),
                    'description' => __( 'Set how many days after publishing a listing, you would like to expire a listing by default ? Set it to 0 to keep it alive forever.', ATBDP_TEXTDOMAIN ),
                    'min' => '0',
                    'max' => '730',
                    'step' => '1',
                    'default' => 365,
                    'validation' => 'numeric',
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'can_renew_listing',
                    'label' => __('Can User Renew Listing?', ATBDP_TEXTDOMAIN),
                    'description' => __('Here YES means users can renew their listings. NO means users can not renew their listings. Default is YES.', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'delete_expired_listing',
                    'label' => __('Delete/Trash Expired Listings', ATBDP_TEXTDOMAIN),
                    'description' => __('Here YES means expired listings will be deleted (after threshold of course). NO means expired listings will not be deleted. Default is YES.', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'slider',
                    'name' => 'delete_expired_listings_after',
                    'label' => __('Delete/Trash Expired Listings After (days) of Expiration', ATBDP_TEXTDOMAIN),
                    'description' => __( 'Set how many days after the expiration of a listing you would like the listings gets tashed/deleted. Set it 0 to delete/trash expired listings immediately.(N.B. This option depends on the "Delete/Trash Expired Listings" option', ATBDP_TEXTDOMAIN ),
                    'min' => '0',
                    'max' => '180',
                    'step' => '1',
                    'default' => 15,
                    'validation' => 'numeric',
                ),
                array(
                    'type' => 'select',
                    'name' => 'deletion_mode',
                    'label' => __( 'Delete or Trash Expired Listings', ATBDP_TEXTDOMAIN ),
                    'items' => array(
                        array(
                            'value' => 'force_delete',
                            'label' => __('Delete Permanently', ATBDP_TEXTDOMAIN),
                        ),
                        array(
                            'value' => 'trash',
                            'label' => __('Move to Trash', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    'description' => __( 'Choose the Default actions after a listing reaches its deletion threshold. Default action is to trash them.', ATBDP_TEXTDOMAIN ),
                    /*@todo; later add option to make listing status hidden or invalid for expired listing, so that admin may retain expired listings without having them deleted after the deletion threshold */
                    'default' => array(
                        'value' => 'trash',
                        'label' => __('Move to Trash', ATBDP_TEXTDOMAIN),
                    ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'all_listing_title',
                    'label' => __('Title for all listing page', ATBDP_TEXTDOMAIN),
                    'description' => __( 'Enter a title for the page where all listings will be shown using the shortcode [all_listing] . Eg. All Listings/ Items.', ATBDP_TEXTDOMAIN ),
                    'default' => atbdp_get_option('all_listing_title', 'atbdp_general'),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'paginate_all_listings',
                    'label' => __('Paginate Listings On "All listings" Page', ATBDP_TEXTDOMAIN),
                    'description' => __('If you do not want to show pagination on all listings page, turn it off.', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),

                array(
                    'type' => 'slider',
                    'name' => 'all_listing_page_items',
                    'label' => __('Listings Per Page on All listing page', ATBDP_TEXTDOMAIN),
                    'description' => __( 'Set how many listings you would like to show per page on the All Listings page. Eg. 6. Default is 6. If pagination is off, then this number will be the total listings to show.', ATBDP_TEXTDOMAIN),
                    'min' => '1',
                    'max' => '30',
                    'step' => '1',
                    'default' => '6',
                    'validation' => 'numeric|minlength[1]',
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'show_popular_category',
                    'label' => __('Show popular category on the search page', ATBDP_TEXTDOMAIN),
                    'description' => __('You can show popular category on search page or you can hide it here.', ATBDP_TEXTDOMAIN),
                    'default' => atbdp_yes_to_bool($s_p_cat),
                ),

                array(
                    'type' => 'textbox',
                    'name' => 'popular_cat_title',
                    'label' => __('Popular Category Title', ATBDP_TEXTDOMAIN),
                    'description' => __( 'Enter the title for popular category on listing search page eg. Browse by popular categories', ATBDP_TEXTDOMAIN ),
                    'default' => __('Browse by popular categories', ATBDP_TEXTDOMAIN),
                ),

                array(
                    'type' => 'slider',
                    'name' => 'popular_cat_num',
                    'label' => __('Number of Popular Category', ATBDP_TEXTDOMAIN),
                    'description' => __( 'Set how many popular categories you would like to show on your listing main search page. Eg. 10. Default is 10', ATBDP_TEXTDOMAIN),
                    'min' => '1',
                    'max' => '30',
                    'step' => '1',
                    'default' => '10',
                    'validation' => 'numeric|minlength[1]',
                ),

                array(
                    'type' => 'toggle',
                    'name' => 'enable_pop_listing',
                    'label' => __('Enable popular listings on Single Listing page', ATBDP_TEXTDOMAIN),
                    'description' => __('Choose whether you want to display popular listings on Single listing details page or not. Default is YES.', ATBDP_TEXTDOMAIN),
                    'default' => atbdp_yes_to_bool($e_p_list),
                ),

                array(
                    'type' => 'slider',
                    'name' => 'pop_listing_num',
                    'label' => __('Number of Popular Listings', ATBDP_TEXTDOMAIN),
                    'description' => __( 'Set how many popular listings you would like to show on your website. Eg. 5. Default is 5.', ATBDP_TEXTDOMAIN),
                    'min' => '1',
                    'max' => '30',
                    'step' => '1',
                    'default' => '5',
                    'validation' => 'numeric|minlength[1]',
                ),


                array(
                    'type' => 'toggle',
                    'name' => 'enable_rel_listing',
                    'label' => __('Enable related listings on Single Listing page', ATBDP_TEXTDOMAIN),
                    'description' => __('Choose whether you want to display related listings on Single listing details page or not. Default is YES.', ATBDP_TEXTDOMAIN),
                    'default' => atbdp_yes_to_bool($e_r_list),
                ),

                array(
                    'type' => 'slider',
                    'name' => 'rel_listing_num',
                    'label' => __('Number of Related Listings', ATBDP_TEXTDOMAIN),
                    'description' => __( 'Set how many related listings you would like to show on your website. Eg. 2. Default is 2.', ATBDP_TEXTDOMAIN),
                    'min' => '1',
                    'max' => '10',
                    'step' => '1',
                    'default' => '2',
                    'validation' => 'numeric|minlength[1]',
                ),


            )
        );
    }

    /**
     * Get all the settings fields for the listings settings section
     * @since 3.0.0
     * @return array
     */
    function get_listings_settings_fields(){
        // BACKWARD COMPATIBILITY:  OLD SETTINGS DATA that should be adapted by using them as default value, will be removed in future
        $s_p_cat = atbdp_get_option('show_popular_category', 'atbdp_general', 'yes');
        $e_p_list = atbdp_get_option('enable_pop_listing', 'atbdp_general', 'yes');
        $e_r_list = atbdp_get_option('enable_rel_listing', 'atbdp_general', 'yes');

        return apply_filters('atbdp_listings_settings_fields', array(
                    array(
                        'type' => 'slider',
                        'name' => 'listing_expire_in_days',
                        'label' => __('Default Listing Expires in Days', ATBDP_TEXTDOMAIN),
                        'description' => __( 'Set how many days after publishing a listing, you would like to expire a listing by default ? Set it to 0 to keep it alive forever.', ATBDP_TEXTDOMAIN ),
                        'min' => '0',
                        'max' => '730',
                        'step' => '1',
                        'default' => 365,
                        'validation' => 'numeric',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'can_renew_listing',
                        'label' => __('Can User Renew Listing?', ATBDP_TEXTDOMAIN),
                        'description' => __('Here YES means users can renew their listings. NO means users can not renew their listings. Default is YES.', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                array(
                        'type' => 'toggle',
                        'name' => 'listing_terms_condition',
                        'label' => __('Required Terms & Conditions', ATBDP_TEXTDOMAIN),
                        'description' => __('Here YES means restrict user to submit listing from front end without checking it.', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                array(
                        'type' => 'textarea',
                        'name' => 'listing_terms_condition_text',
                        'label' => __('Custom Terms & Conditions', ATBDP_TEXTDOMAIN),
                        'description' => __('You may include and any of your business policy here.', ATBDP_TEXTDOMAIN),
                        'default' => 'Replace it with your own Terms and Conditions',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'delete_expired_listing',
                        'label' => __('Delete/Trash Expired Listings', ATBDP_TEXTDOMAIN),
                        'description' => __('Here YES means expired listings will be deleted (after threshold of course). NO means expired listings will not be deleted. Default is YES.', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'delete_expired_listings_after',
                        'label' => __('Delete/Trash Expired Listings After (days) of Expiration', ATBDP_TEXTDOMAIN),
                        'description' => __( 'Set how many days after the expiration of a listing you would like the listings gets tashed/deleted. Set it 0 to delete/trash expired listings immediately.(N.B. This option depends on the "Delete/Trash Expired Listings" option', ATBDP_TEXTDOMAIN ),
                        'min' => '0',
                        'max' => '180',
                        'step' => '1',
                        'default' => 15,
                        'validation' => 'numeric',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'deletion_mode',
                        'label' => __( 'Delete or Trash Expired Listings', ATBDP_TEXTDOMAIN ),
                        'items' => array(
                            array(
                                'value' => 'force_delete',
                                'label' => __('Delete Permanently', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'trash',
                                'label' => __('Move to Trash', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'description' => __( 'Choose the Default actions after a listing reaches its deletion threshold. Default action is to trash them.', ATBDP_TEXTDOMAIN ),
/*@todo; later add option to make listing status hidden or invalid for expired listing, so that admin may retain expired listings without having them deleted after the deletion threshold */
                        'default' => array(
                            'value' => 'trash',
                            'label' => __('Move to Trash', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'new_listing_status',
                        'label' => __( 'New Listing\'s Default status', ATBDP_TEXTDOMAIN ),
                        'items' => array(
                            array(
                                'value' => 'publish',
                                'label' => __('Published', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'pending',
                                'label' => __('Pending', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'description' => __( 'Choose the Default Listing Status for a new listing when a user submits it from the Front End', ATBDP_TEXTDOMAIN ),

                        'default' => array(
                            'value' => 'publish',
                            'label' => __('Published', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'edit_listing_status',
                        'label' => __( 'Edited Listing\'s Default Status', ATBDP_TEXTDOMAIN ),
                        'items' => array(
                            array(
                                'value' => 'publish',
                                'label' => __('Published', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'pending',
                                'label' => __('Pending', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'description' => __( 'Select the Default Listing Status for Edited listing when a user edits it on the front end.', ATBDP_TEXTDOMAIN ),

                        'default' => array(
                            'value' => 'publish',
                            'label' => __('Published', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'all_listing_title',
                        'label' => __('Title for all listing page', ATBDP_TEXTDOMAIN),
                        'description' => __( 'Enter a title for the page where all listings will be shown using the shortcode [all_listing] . Eg. All Listings/ Items.', ATBDP_TEXTDOMAIN ),
                        'default' => atbdp_get_option('all_listing_title', 'atbdp_general'),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'paginate_all_listings',
                        'label' => __('Paginate Listings On "All listings" Page', ATBDP_TEXTDOMAIN),
                        'description' => __('If you do not want to show pagination on all listings page, turn it off.', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),

                    array(
                        'type' => 'slider',
                        'name' => 'all_listing_page_items',
                        'label' => __('Listings Per Page on All listing page', ATBDP_TEXTDOMAIN),
                        'description' => __( 'Set how many listings you would like to show per page on the All Listings page. Eg. 6. Default is 6. If pagination is off, then this number will be the total listings to show.', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '30',
                        'step' => '1',
                        'default' => '6',
                        'validation' => 'numeric|minlength[1]',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'show_popular_category',
                        'label' => __('Show popular category on the search page', ATBDP_TEXTDOMAIN),
                        'description' => __('You can show popular category on search page or you can hide it here.', ATBDP_TEXTDOMAIN),
                        'default' => atbdp_yes_to_bool($s_p_cat),
                    ),

                    array(
                        'type' => 'textbox',
                        'name' => 'popular_cat_title',
                        'label' => __('Popular Category Title', ATBDP_TEXTDOMAIN),
                        'description' => __( 'Enter the title for popular category on listing search page eg. Browse by popular categories', ATBDP_TEXTDOMAIN ),
                        'default' => __('Browse by popular categories', ATBDP_TEXTDOMAIN),
                    ),

                    array(
                        'type' => 'slider',
                        'name' => 'popular_cat_num',
                        'label' => __('Number of Popular Category', ATBDP_TEXTDOMAIN),
                        'description' => __( 'Set how many popular categories you would like to show on your listing main search page. Eg. 10. Default is 10', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '30',
                        'step' => '1',
                        'default' => '10',
                        'validation' => 'numeric|minlength[1]',
                    ),

                    array(
                        'type' => 'toggle',
                        'name' => 'enable_pop_listing',
                        'label' => __('Enable popular listings on Single Listing page', ATBDP_TEXTDOMAIN),
                        'description' => __('Choose whether you want to display popular listings on Single listing details page or not. Default is YES.', ATBDP_TEXTDOMAIN),
                        'default' => atbdp_yes_to_bool($e_p_list),
                    ),

                    array(
                        'type' => 'slider',
                        'name' => 'pop_listing_num',
                        'label' => __('Number of Popular Listings', ATBDP_TEXTDOMAIN),
                        'description' => __( 'Set how many popular listings you would like to show on your website. Eg. 5. Default is 5.', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '30',
                        'step' => '1',
                        'default' => '5',
                        'validation' => 'numeric|minlength[1]',
                    ),


                    array(
                        'type' => 'toggle',
                        'name' => 'enable_rel_listing',
                        'label' => __('Enable related listings on Single Listing page', ATBDP_TEXTDOMAIN),
                        'description' => __('Choose whether you want to display related listings on Single listing details page or not. Default is YES.', ATBDP_TEXTDOMAIN),
                        'default' => atbdp_yes_to_bool($e_r_list),
                    ),

                    array(
                        'type' => 'slider',
                        'name' => 'rel_listing_num',
                        'label' => __('Number of Related Listings', ATBDP_TEXTDOMAIN),
                        'description' => __( 'Set how many related listings you would like to show on your website. Eg. 2. Default is 2.', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '10',
                        'step' => '1',
                        'default' => '2',
                        'validation' => 'numeric|minlength[1]',
                    ),


            )
        );
    }

    /**
     * Get all the settings fields for the pages settings section
     * @since 3.0.0
     * @return array
     */
    function get_pages_settings_fields(){
        return apply_filters('atbdp_pages_settings_fields', array(
                    array(
                            'type' => 'select',
                            'name' => 'add_listing_page',
                            'label' => __('Add Listing Page ID', ATBDP_TEXTDOMAIN),
                            'items' => $this->get_pages_vl_arrays(), // eg. array( array('value'=> 123, 'label'=> 'page_name') );
                            'description' => sprintf(__( 'Select your add listing page ( where you used %s shortcode ) ID here', ATBDP_TEXTDOMAIN ), '<strong style="color: #ff4500;">[add_listing]</strong>'),
                            'default' => atbdp_get_option('add_listing_page', 'atbdp_general'),
                            'validation' => 'required|numeric',

                        ),

                    array(
                        'type' => 'select',
                        'name' => 'all_listing_page',
                        'label' => __( 'Add All Listings Page ID', ATBDP_TEXTDOMAIN ),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__( 'Select your All Listings  page ( where you used %s shortcode ) ID here.', ATBDP_TEXTDOMAIN ), '<strong style="color: #ff4500;">[all_listing]</strong>'),

                        'default' => atbdp_get_option('all_listing_page', 'atbdp_general'),
                        'validation' => 'required|numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'user_dashboard',
                        'label' =>  __( 'Add dashboard page ID', ATBDP_TEXTDOMAIN ),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__( 'Select your add dashboard page ( where you used %s shortcode ) ID here', ATBDP_TEXTDOMAIN ), '<strong style="color: #ff4500;">[user_dashboard]</strong>'),
                        'default' => atbdp_get_option('user_dashboard', 'atbdp_general'),
                        'validation' => 'required|numeric',

                    ),

                    array(
                        'type' => 'select',
                        'name' => 'custom_registration',
                        'label' =>  __(  'Add registration page ID', ATBDP_TEXTDOMAIN ),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__( 'Select your registration page ( where you used %s  shortcode ) ID here', ATBDP_TEXTDOMAIN ), '<strong style="color: #ff4500;">[custom_registration]</strong>'),
                        'default' => atbdp_get_option('custom_registration', 'atbdp_general'),
                        'validation' => 'required|numeric',

                    ),

                    array(
                        'type' => 'select',
                        'name' => 'search_listing',
                        'label' =>  __( 'Add Listing Search page ID', ATBDP_TEXTDOMAIN ),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__( 'Select your Listing Search page ( where you used %s shortcode ) ID here. This is generally used in a home page.', ATBDP_TEXTDOMAIN ), '<strong style="color: #ff4500;">[search_listing]</strong>'),
                        'default' => atbdp_get_option('search_listing', 'atbdp_general'),
                        'validation' => 'required|numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'search_result_page',
                        'label' =>  __( 'Add Listing Search Result page ID', ATBDP_TEXTDOMAIN ),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__( 'Please Select your Listing Search Result page ( where you used %s shortcode ) ID here. This page is used to show listing search results but this page generally should be excluded from the menu.', ATBDP_TEXTDOMAIN ),'<strong style="color: #ff4500;">[search_result]</strong>'),
                        'default' => atbdp_get_option('search_result_page', 'atbdp_general'),
                        'validation' => 'required|numeric',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'checkout_page',
                        'label' =>  __( 'Add Checkout page ID', ATBDP_TEXTDOMAIN ),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__( 'Please Select your Checkout page ( where you used %s shortcode ) ID here. This page is used to show checkout information but this page generally should be excluded from the menu.', ATBDP_TEXTDOMAIN ),'<strong style="color: #ff4500;">[directorist_checkout]</strong>'),
                        'default' => '',
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'payment_receipt_page',
                        'label' =>  __( 'Add Payment/Order Receipt page ID', ATBDP_TEXTDOMAIN ),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__( 'Please Select your Payment/Order Receipt page ( where you used %s shortcode ) ID here. This page is used to show order receipt information but this page generally should be excluded from the menu.', ATBDP_TEXTDOMAIN ),'<strong style="color: #ff4500;">[directorist_payment_receipt]</strong>'),
                        'default' => '',
                        'validation' => 'numeric',
                    ),
                array(
                    'type' => 'select',
                    'name' => 'transaction_failure_page',
                    'label' =>  __( 'Add Transaction Failure page ID', ATBDP_TEXTDOMAIN ),
                    'items' => $this->get_pages_vl_arrays(),
                    'description' => sprintf(__( 'Please Select your Transaction Failure page ( where you used %s shortcode ) ID here. This page is used to show Transaction Failure information but this page generally should be excluded from the menu.', ATBDP_TEXTDOMAIN ),'<strong style="color: #ff4500;">[transaction_failure]</strong>'),
                    'default' => '',
                    'validation' => 'numeric',
                ),



            )
        );
    }

    /**
     * Get all the settings fields for the extension settings section
     * @since 3.0.0
     * @return array
     */
    function get_extension_settings_fields(){
        /*BACKWARD Compatibility: $e_review. It may be removed in future release*/
        $e_review = atbdp_get_option('enable_review', 'atbdp_general', 'yes');
            return apply_filters('atbdp_extension_settings_fields', array(
                    array(
                        'type' => 'notebox',
                        'name' => 'extension_promotion',
                        'label' => __('Need more Features?', ATBDP_TEXTDOMAIN),
                        'description' => sprintf(__('You can add new features and expand the functionality of the plugin even more by using extensions. %s', ATBDP_TEXTDOMAIN), $this->extension_url),
                        'status' => 'warning',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'enable_review',
                        'label' => __('Enable Reviews & Rating', ATBDP_TEXTDOMAIN),
                        'description' => __('Choose whether you want to display reviews form on Single listing details page or not. Default is YES.', ATBDP_TEXTDOMAIN),
                        'default' => atbdp_yes_to_bool($e_review),
                    ),

                    array(
                        'type' => 'toggle',
                        'name' => 'enable_owner_review',
                        'label' => __('Enable Owner Review', ATBDP_TEXTDOMAIN),
                        'description' => __('Choose whether you want to allow a listing OWNER to post a review on his/her OWN listing on Single listing details page or not. Default is YES.', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),

                    array(
                        'type' => 'slider',
                        'name' => 'review_num',
                        'label' => __('Number of Reviews', ATBDP_TEXTDOMAIN),
                        'description' => __( 'Enter how many reviews you would like to show on your website. Eg. 5. Default is 5. It will work if Review option is enabled.', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '20',
                        'step' => '1',
                        'default' => '5',
                        'validation' => 'numeric|minlength[1]',
                    ),

            )
        );
    }




    } // ends ATBDP_Settings_Manager
endif;
