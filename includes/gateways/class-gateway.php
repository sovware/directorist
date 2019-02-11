<?php
/**
 * Gateway
 *
 * @package       directorist
 * @subpackage    directorist/includes/gateways
 * @copyright     Copyright 2018. AazzTech
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GNU Public License
 * @since         3.1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * ATBDP_Gateway Class
 *
 * @since    3.1.0
 * @access   public
 */

class ATBDP_Gateway{
    private $extension_url = '';
    public function __construct()
    {
        // add monetization menu

        add_filter('atbdp_settings_menus', array($this, 'add_monetization_menu'));

        // add gateway submenu
        add_filter('atbdp_monetization_settings_submenus', array($this, 'gateway_settings_submenu'), 10, 1);

        $this->extension_url = sprintf("<a target='_blank' href='%s'>%s</a>", esc_url(admin_url('edit.php?post_type=at_biz_dir&page=atbdp-extension')), __('Checkout Other Payment Gateways & Extensions', ATBDP_TEXTDOMAIN));

    }

    /**
     * Add Monetization menu
     * @param array $menus The array of menus
     * @return array It returns the new array of menus.
     */
    public function add_monetization_menu($menus)
    {
        $menus['monetization_menu'] = array(
            'title' => __('Monetization', ATBDP_TEXTDOMAIN),
            'name' => 'monetization_menu',
            'icon' => 'font-awesome:fa-money',
            'menus' => $this->get_monetization_settings_submenus(),
        );
        return $menus;
    }


    /**
     * It registers the monetization submenu
     * @return array it returns an array of submenus
     */
    public function get_monetization_settings_submenus()
    {
        return apply_filters('atbdp_monetization_settings_submenus', array(
            'monetization_submenu1' => array(
                'title' => __( 'Monetization Settings', ATBDP_TEXTDOMAIN),
                'name' => 'monetization_submenu1',
                'icon' => 'font-awesome:fa-home',
                'controls' => apply_filters('atbdp_monetization_settings_controls', array(
                    'monetization_section' => array(
                        'type'          => 'section',
                        'title'         => __('Monetization General Settings', ATBDP_TEXTDOMAIN),
                        'description'   => __('You can Customize Monetization settings here. After switching any option, Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                        'fields'        => $this->get_monetization_settings_fields(),
                    ), // ends monetization settings section
                    //class_exists('ATBDP_Pricing_Plans') ? '' :
                    'featured_listing_section' => array(
                        'type'          => 'section',
                        'title'         => __('Monetize by Featured Listing', ATBDP_TEXTDOMAIN),
                        'description'   => __('You can Customize featured listing related settings here', ATBDP_TEXTDOMAIN),
                        'fields'        => $this->get_featured_listing_settings_fields(),
                    ), // ends monetization settings section
                    'monetize_by_subscription' => array(
                        'type'          => 'section',
                        'title'         => __('Monetize by Listing Plans', ATBDP_TEXTDOMAIN),
                        'fields'        => $this->get_monetize_by_subscription_fields(),
                    ), // ends monetization settings section

                )),
            ),

        ) );
    }


    /**
     * It register the settings fields for monetization submenu
     * @return array It returns an array of settings fields arrays
     */
    public function get_monetization_settings_fields()
    {
        return apply_filters('atbdp_monetization_settings_fields', array(
                array(
                    'type' => 'toggle',
                    'name' => 'enable_monetization',
                    'label' => __('Enable Monetization Feature', ATBDP_TEXTDOMAIN),
                    'description' => __('Choose whether you want to monetize your site or not. Monetization features will let you accept payment from your users if they submit listing based on different criteria. Default is NO.', ATBDP_TEXTDOMAIN),
                    'default' => '',
                ),


            )
        );
    }


    /**
     * It registers the settings fields of featured listings
     * @return array It returns an array of featured settings fields arrays
     */
    public function get_featured_listing_settings_fields()
    {
        return apply_filters('atbdp_monetization_settings_fields', array(
                array(
                    'type' => 'toggle',
                    'name' => 'enable_featured_listing',
                    'label' => __('Monetize by Featured Listing', ATBDP_TEXTDOMAIN),
                    'description' => __('You can enabled this option to collect payment from your user for making their listing featured. Settings this option to NO will disable all sorts of monetization features. This setting gives you a quick way to turn monetization on or off instantly. Default is NO.', ATBDP_TEXTDOMAIN),
                    'default' => '',
                ),

                array(
                    'type' => 'textbox',
                    'name' => 'featured_listing_title',
                    'label' => __('Title', ATBDP_TEXTDOMAIN),
                    'description' => __('You can set the title for featured listing to show on the ORDER PAGE', ATBDP_TEXTDOMAIN),
                    'default' => __('Featured', ATBDP_TEXTDOMAIN),
                ),

                array(
                    'type' => 'textarea',
                    'name' => 'featured_listing_desc',
                    'label' => __('Description', ATBDP_TEXTDOMAIN),
                    'description' => __('You can set some description for your user for upgrading to featured listing.', ATBDP_TEXTDOMAIN),
                    'default' => __('You can make your listing featured. A Featured listing will appear on top of other listings.', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'featured_listing_price',
                    'label' => __('Price in ', ATBDP_TEXTDOMAIN) . atbdp_get_payment_currency(),
                    'description' => __('Set the price you want to charge a user if he/she wants to upgrade his/her listing to featured listing. Note: you can change the currency settings under the gateway settings', ATBDP_TEXTDOMAIN),
                    'default' => 19.99,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'show_featured_ribbon',
                    'label' => __('Show Featured Ribbon/Text', ATBDP_TEXTDOMAIN),
                    'description' => __('Set this option to YES to show Featured Ribbon/Label besides featured listings. . Default is YES', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),

            )
        );
    }


    /**
     * It registers the settings fields of promoting subscription
     * @return array It returns an array of promoting subscription settings fields arrays
     */
    public function get_monetize_by_subscription_fields()
    {
        $pricing_plan = '<a style="color: red" href="https://aazztech.com/product/directorist-pricing-plans" target="_blank">Pricing Plans</a>';
        return apply_filters('atbdp_monetization_by_subscription_settings_fields', array(
                array(
                    'type' => 'notebox',
                    'name' => 'monetization_promotion',
                    'description' => sprintf(__('Monetize your website by selling listing plans using %s extension.', ATBDP_TEXTDOMAIN), $pricing_plan),
                ),
            )
        );
    }


    /**
     * It register the gateway settings submenu
     * @param array $submenus       Array of Submenus
     * @return array                It returns gateway submenu
     */
    function gateway_settings_submenu($submenus){
        $submenus['gateway_submenu'] =  array(
            'title' => __('Gateways Settings', ATBDP_TEXTDOMAIN),
            'name' => 'gateway_general',
            'icon' => 'font-awesome:fa-money',
            'controls' => apply_filters('atbdp_gateway_settings_controls', array(
                'gateways' => array(
                    'type' => 'section',
                    'title' => __('Gateway General Settings', ATBDP_TEXTDOMAIN),
                    'description' => __('You can Customize Gateway-related settings here. You can enable or disable any gateways here. Here, YES means Enabled, and NO means disabled. After switching any option, Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                    'fields' => $this->get_gateway_settings_fields(),
                ),
            )),
        );
        return $submenus;
    }

    /**
     * It register gateway settings fields
     * @return array It returns an array of gateway settings fields
     */
    function get_gateway_settings_fields(){

        return apply_filters('atbdp_gateway_settings_fields', array(
                array(
                    'type' => 'toggle',
                    'name' => 'enable_offline_payment',
                    'label' => __('Enable Offline Payment', ATBDP_TEXTDOMAIN),
                    'description' => __('Choose whether you want to accept offline Payment or not. Default is YES.', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'notebox',
                    'name' => 'paypal_gateway_promotion',
                    'label' => __('Need more gateways?', ATBDP_TEXTDOMAIN),
                    'description' => sprintf(__('You can use different payment gateways to process payment including PayPal. %s', ATBDP_TEXTDOMAIN), $this->extension_url),
                    'status' => 'warning',
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'gateway_test_mode',
                    'label' => __('Enable Test Mode', ATBDP_TEXTDOMAIN),
                    'description' => __('If you enable Test Mode, then no real transaction will occur. If you want to test the payment system of your website then you can set this option enabled. NOTE: Your payment gateway must support test mode eg. they should provide you a sandbox account to test. Otherwise, use only offline gateway to test.', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'checkbox',
                    'name' => 'active_gateways',
                    'label' => __('Active Gateways', ATBDP_TEXTDOMAIN),
                    'description' => __('Check the gateway(s) you would like to use to collect payment from your users. A user will be use any of the active gateways during the checkout process ', ATBDP_TEXTDOMAIN),
                    'items' => apply_filters('atbdp_active_gateways', array(
                        array(
                            'value' => 'bank_transfer',
                            'label' => __('Bank Transfer (Offline Gateway)', ATBDP_TEXTDOMAIN),
                        ),
                    )),

                    'default' => array(
                        'bank_transfer',
                    ),
                ),

                array(
                    'type' => 'select',
                    'name' => 'default_gateway',
                    'label' => __('Default Gateway', ATBDP_TEXTDOMAIN),
                    'description' => __('Select the default gateway you would like to show as a selected gateway on the checkout page', ATBDP_TEXTDOMAIN),
                    'items' => apply_filters('atbdp_default_gateways', array(
                        array(
                            'value' => 'bank_transfer',
                            'label' => __('Bank Transfer (Offline Gateway)', ATBDP_TEXTDOMAIN),
                        ),
                    )),

                    'default' => array(
                        'bank_transfer',
                    ),
                ),
                /*@todo; think whether it is good to list online payments here or in separate tab when a new payment gateway is added*/


                array(
                    'type' => 'notebox',
                    'name' => 'payment_currency_note',
                    'label' => __('Note About This Currency Settings:', ATBDP_TEXTDOMAIN),
                    'description' => __('This currency settings lets you customize how you would like to accept payment from your user/customer and how to display pricing on the order form/history.', ATBDP_TEXTDOMAIN),
                    'status' => 'info',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'payment_currency',
                    'label' => __( 'Currency Name', ATBDP_TEXTDOMAIN ),
                    'description' => __( 'Enter the Name of the currency eg. USD or GBP etc.', ATBDP_TEXTDOMAIN ),
                    'default' => 'USD',
                    'validation' => 'required',
                ),
                /*@todo; lets user use space as thousand separator in future. @see: https://docs.oracle.com/cd/E19455-01/806-0169/overview-9/index.html
                */
                array(
                    'type' => 'textbox',
                    'name' => 'payment_thousand_separator',
                    'label' => __( 'Thousand Separator', ATBDP_TEXTDOMAIN ),
                    'description' => __( 'Enter the currency thousand separator. Eg. , or . etc.', ATBDP_TEXTDOMAIN ),
                    'default' => ',',
                    'validation' => 'required',
                ),

                array(
                    'type' => 'textbox',
                    'name' => 'payment_decimal_separator',
                    'label' => __('Decimal Separator', ATBDP_TEXTDOMAIN),
                    'description' => __('Enter the currency decimal separator. Eg. "." or ",". Default is "."', ATBDP_TEXTDOMAIN),
                    'default' => '.',
                ),
                array(
                    'type' => 'select',
                    'name' => 'payment_currency_position',
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



    static function gateways_markup()
    {
        $active_gateways = get_directorist_option('active_gateways', array());
        $default_gw = get_directorist_option('default_gateway', 'bank_transfer');
        if (empty($active_gateways)) return ''; // if the gateways are empty, vail out.
        $markup = '<ul>';
        foreach ($active_gateways as $gw_name){
                $title = get_directorist_option($gw_name.'_title');
                $desc = get_directorist_option($gw_name.'_description');
                $desc = !empty($desc) ? "<p class='text-muted'>{$desc}</p>" : '';
                $checked = ($gw_name == $default_gw) ? ' checked': '';

                $format = <<<KAMAL
                <li class="list-group-item">
                    <div class="gateway_list">
                        <label for="##GATEWAY##">
                            <input type="radio" id="##GATEWAY##" name="payment_gateway" value="##GATEWAY##" ##CHECKED##>##LABEL##
                        </label>
                    </div>
                    ##DESC##
                </li>
KAMAL;
                $search = array("##GATEWAY##", "##LABEL##", "##DESC##", "##CHECKED##");
                $replace = array($gw_name, $title, $desc, $checked);
                $markup  .= str_replace($search, $replace , $format);
                /*@todo; Add a settings to select a default payment method.*/
        }
        $markup .= '</ul>';

        return $markup;
    }

}


