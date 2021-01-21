<?php
/*This file will contain most common actions that will help other developer extends / modify our plugin settings or design */
function atbdp_after_new_listing_button()
{
    do_action('atbdp_after_new_listing_button');
}


function atbdp_create_picvacyAndTerms_pages()
{

    $create_permission = apply_filters('atbdp_create_required_pages', true);
    if ($create_permission) {
        $options = get_option('atbdp_option');
        $page_exists = get_option('atbdp_picvacyAndTerms_pages');
        // $op_name is the page option name in the database.
        // if we do not have the page id assigned in the settings with the given page option name, then create an page
        // and update the option.
        $id = array();
        $Old_terms = get_directorist_option('listing_terms_condition_text');
        $pages = apply_filters('atbdp_create_picvacyAndTerms_pages', array(
            'privacy_policy' => array(
                'title' => __('Privacy Policy', 'directorist'),
                'content' => '<!-- wp:heading -->
<h2>Who we are</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our website address is: ' . home_url() . '.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>What personal data we collect and why we collect it</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>Comments</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>When visitors leave comments on the site we collect the data shown in the registration form.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>An anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. After approval of your comment, your profile picture is visible to the public in the context of your comment.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Media</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Contact forms</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>Embedded content from other websites</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Articles on this site may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>These websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction with the embedded content if you have an account and are logged in to that website.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Analytics</h3>
<!-- /wp:heading -->

<!-- wp:heading -->
<h2>Who we share your data with</h2>
<!-- /wp:heading -->

<!-- wp:heading -->
<h2>How long we retain your data</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>For users that register on our website (if any), we also store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>What rights you have over your data</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Where we send your data</h2>
<!-- /wp:heading -->

<!-- wp:heading -->
<h2>Your contact information</h2>
<!-- /wp:heading -->

<!-- wp:heading -->
<h2>Additional information</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>How we protect your data</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>What data breach procedures we have in place</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>What third parties we receive data from</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>What automated decision making and/or profiling we do with user data</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>Industry regulatory disclosure requirements</h3>
<!-- /wp:heading -->'
            ),
            'terms_conditions' => array(
                'title' => __('Terms and Conditions', 'directorist'),
                'content' => $Old_terms
            ),
        ));
        if (!$page_exists) {
            foreach ($pages as $op_name => $page_settings) {
                $id = wp_insert_post(
                    array(
                        'post_title' => $page_settings['title'],
                        'post_content' => $page_settings['content'],
                        'post_status' => 'publish',
                        'post_type' => 'page',
                        'comment_status' => 'closed'
                    )
                );
                $options[$op_name] = (int)$id;
            }
        }
        // if we have new options then lets update the options with new option values.
        if ($id) {
            update_option('atbdp_picvacyAndTerms_pages', 1);
            update_option('atbdp_option', $options);
        };
    }
}

// fire up the functionality for one time
/* if (!get_option('atbdp_picvacyAndTerms_pages')) {
    add_action('wp_loaded', 'atbdp_create_picvacyAndTerms_pages');
} */

function atbdp_handle_attachment($file_handler, $post_id, $set_thu = false)
{
    // check to make sure its a successful upload
    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

    $attach_id = media_handle_upload($file_handler, $post_id);
    if (is_numeric($attach_id)) {
        update_post_meta($post_id, '_atbdp_listing_images', $attach_id);
    }
    return $attach_id;
}

function atbdp_get_preview_button()
{
    $preview_enable = get_directorist_option('preview_enable', 1);
    if (!empty($preview_enable)){
        if (isset($_GET['redirect'])) {
            $payment = isset($_GET['payment']) ? $_GET['payment'] : '';
            $id = isset($_GET['p']) ? $_GET['p'] : '';
            $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : get_the_ID();
            $edited = isset($_GET['edited']) ? $_GET['edited'] : '';
            $id = empty($id) ? $post_id : $id;
            if (empty($payment)){
                $url = add_query_arg(array('p' => $id, 'post_id' => $id, 'reviewed' => 'yes', 'edited' => $edited ? 'yes' : 'no'), $_GET['redirect']);
            }else{
                $url = add_query_arg(array('atbdp_listing_id' => $id, 'reviewed' => 'yes'), $_GET['redirect']);
            }
            return '<a href="' . esc_url($url) . '" class="btn btn-success">' . apply_filters('atbdp_listing_preview_btn_text',  esc_html__(' Continue','directorist') )  . '</a>';
        }
    }
}

/**
 * @param string $plugin
 * @return array plugin data
 * @since 6.2.3
 */

function atbdp_get_plugin_data($plugin)
{
    $plugins = get_plugins();
    foreach ($plugins as $key => $data) {
        if ($plugin === $key) {
            return $data;
        }
    }
}

function atbdp_is_extension_active()
{
    if (class_exists('BD_Business_Hour') || class_exists('DCL_Base') || class_exists('Listings_fAQs') || class_exists('BD_Gallery') || class_exists('BD_Google_Recaptcha') || class_exists('BD_Map_View') || class_exists('Directorist_Paypal_Gateway') || class_exists('Post_Your_Need') || class_exists('ATBDP_Pricing_Plans') || class_exists('BD_Slider_Carousel') || class_exists('Directorist_Social_Login') || class_exists('Directorist_Stripe_Gateway') || class_exists('DWPP_Pricing_Plans') || class_exists('Directorist_Mark_as_Sold') || class_exists('Directorist_Live_Chat') || class_exists('BD_Booking') || class_exists('ATDListingCompare')) {
        return true;
    } else {
        return false;
    }
}

function atbdp_extend_extension_settings_submenus($default)
{
    if ( apply_filters( 'atbdp_extension_license_settings_init', atbdp_is_extension_active() ) ) {
        $array_license = array(
            'title' => __('Activate License', 'directorist'),
            'name' => 'extensions_license',
            'icon' => 'font-awesome:fa-id-card',
            'controls' => apply_filters('atbdp_license_settings_controls', array(
                array(
                    'type' => 'notebox',
                    'name' => 'businedfssdfss_hours_license',
                    'description' => sprintf(__('Enter your extension license keys here to receive updates for purchased extensions. Click %s to know more about licensing.', 'directorist'), '<a target="_blank" href="https://directorist.com/documentation/extensions/license">here</a>'),
                    'status' => 'info',
                ),

            )),
        );
        array_push($default, $array_license);
    }
    return $default;
}

add_filter('atbdp_extension_settings_submenus', 'atbdp_extend_extension_settings_submenus');

/**
 * @since 6.3.5
 * @return URL if current theme has the file return the actual file path otherwise return false
 */

 if ( !function_exists('atbdp_get_file_path') ){
     function atbdp_get_theme_file( $path = null ) {
        $file_path = get_theme_file_path( $path );
        if( file_exists( $file_path ) ){
            return $file_path;
        }else{
            return false;
        }
     }
 }

 
/**
 * @since 6.6.5
 * @return URL if current theme has the file return the actual file path otherwise return false
 */

if(!function_exists('atbdp_country_code_to_name')){
    function atbdp_country_code_to_name(){
       $country_code_to_name = array(
           'AF' => 'Afghanistan',
           'AL' => 'Albania',
           'DZ' => 'Algeria',
           'AS' => 'American Samoa',
           'AD' => 'Andorra',
           'AO' => 'Angola',
           'AI' => 'Anguilla',
           'AQ' => 'Antarctica',
           'AG' => 'Antigua And Barbuda',
           'AR' => 'Argentina',
           'AM' => 'Armenia',
           'AW' => 'Aruba',
           'AU' => 'Australia',
           'AT' => 'Austria',
           'AZ' => 'Azerbaijan',
           'BS' => 'Bahamas, The',
           'BH' => 'Bahrain',
           'BD' => 'Bangladesh',
           'BB' => 'Barbados',
           'BY' => 'Belarus',
           'BE' => 'Belgium',
           'BZ' => 'Belize',
           'BJ' => 'Benin',
           'BM' => 'Bermuda',
           'BT' => 'Bhutan',
           'BO' => 'Bolivia',
           'BA' => 'Bosnia And Herzegovina',
           'BW' => 'Botswana',
           'BV' => 'Bouvet Island',
           'BR' => 'Brazil',
           'IO' => 'British Indian Ocean Territory',
           'BN' => 'Brunei',
           'BG' => 'Bulgaria',
           'BF' => 'Burkina Faso',
           'MM' => 'Burma',
           'BI' => 'Burundi',
           'KH' => 'Cambodia',
           'CM' => 'Cameroon',
           'CA' => 'Canada',
           'CV' => 'Cape Verde',
           'KY' => 'Cayman Islands',
           'CF' => 'Central African Republic',
           'TD' => 'Chad',
           'CL' => 'Chile',
           'CN' => 'China',
           'CX' => 'Christmas Island',
           'CC' => 'Cocos (keeling) Islands',
           'CO' => 'Colombia',
           'KM' => 'Comoros',
           'CG' => 'Congo (brazzaville) ',
           'CD' => 'Congo (kinshasa)',
           'CK' => 'Cook Islands',
           'CR' => 'Costa Rica',
           'CI' => 'CÔte D’ivoire',
           'HR' => 'Croatia',
           'CU' => 'Cuba',
           'CW' => 'CuraÇao',
           'CY' => 'Cyprus',
           'CZ' => 'Czech Republic',
           'DK' => 'Denmark',
           'DJ' => 'Djibouti',
           'DM' => 'Dominica',
           'DO' => 'Dominican Republic',
           'EC' => 'Ecuador',
           'EG' => 'Egypt',
           'SV' => 'El Salvador',
           'GQ' => 'Equatorial Guinea',
           'ER' => 'Eritrea',
           'EE' => 'Estonia',
           'ET' => 'Ethiopia',
           'FK' => 'Falkland Islands (islas Malvinas)',
           'FO' => 'Faroe Islands',
           'FJ' => 'Fiji',
           'FI' => 'Finland',
           'FR' => 'France',
           'GF' => 'French Guiana',
           'PF' => 'French Polynesia',
           'TF' => 'French Southern And Antarctic Lands',
           'GA' => 'Gabon',
           'GM' => 'Gambia, The',
           'GE' => 'Georgia',
           'DE' => 'Germany',
           'GH' => 'Ghana',
           'GI' => 'Gibraltar',
           'GR' => 'Greece',
           'GL' => 'Greenland',
           'GD' => 'Grenada',
           'GP' => 'Guadeloupe',
           'GU' => 'Guam',
           'GT' => 'Guatemala',
           'GG' => 'Guernsey',
           'GN' => 'Guinea',
           'GW' => 'Guinea-bissau',
           'GY' => 'Guyana',
           'HT' => 'Haiti',
           'HM' => 'Heard Island And Mcdonald Islands',
           'HN' => 'Honduras',
           'HK' => 'Hong Kong',
           'HU' => 'Hungary',
           'IS' => 'Iceland',
           'IN' => 'India',
           'ID' => 'Indonesia',
           'IR' => 'Iran',
           'IQ' => 'Iraq',
           'IE' => 'Ireland',
           'IM' => 'Isle Of Man',
           'IL' => 'Israel',
           'IT' => 'Italy',
           'JM' => 'Jamaica',
           'JP' => 'Japan',
           'JE' => 'Jersey',
           'JO' => 'Jordan',
           'KZ' => 'Kazakhstan',
           'KE' => 'Kenya',
           'KI' => 'Kiribati',
           'KP' => 'Korea, North',
           'KR' => 'Korea, South',
           'KW' => 'Kuwait',
           'KG' => 'Kyrgyzstan',
           'LA' => 'Laos',
           'LV' => 'Latvia',
           'LB' => 'Lebanon',
           'LS' => 'Lesotho',
           'LR' => 'Liberia',
           'LY' => 'Libya',
           'LI' => 'Liechtenstein',
           'LT' => 'Lithuania',
           'LU' => 'Luxembourg',
           'MO' => 'Macau',
           'MK' => 'Macedonia',
           'MG' => 'Madagascar',
           'MW' => 'Malawi',
           'MY' => 'Malaysia',
           'MV' => 'Maldives',
           'ML' => 'Mali',
           'MT' => 'Malta',
           'MH' => 'Marshall Islands',
           'MQ' => 'Martinique',
           'MR' => 'Mauritania',
           'MU' => 'Mauritius',
           'YT' => 'Mayotte',
           'MX' => 'Mexico',
           'FM' => 'Micronesia, Federated States Of',
           'MD' => 'Moldova',
           'MC' => 'Monaco',
           'MN' => 'Mongolia',
           'ME' => 'Montenegro',
           'MS' => 'Montserrat',
           'MA' => 'Morocco',
           'MZ' => 'Mozambique',
           'NA' => 'Namibia',
           'NR' => 'Nauru',
           'NP' => 'Nepal',
           'NL' => 'Netherlands',
           'NC' => 'New Caledonia',
           'NZ' => 'New Zealand',
           'NI' => 'Nicaragua',
           'NE' => 'Niger',
           'NG' => 'Nigeria',
           'NU' => 'Niue',
           'NF' => 'Norfolk Island',
           'MP' => 'Northern Mariana Islands',
           'NO' => 'Norway',
           'OM' => 'Oman',
           'PK' => 'Pakistan',
           'PW' => 'Palau',
           'PA' => 'Panama',
           'PG' => 'Papua New Guinea',
           'PY' => 'Paraguay',
           'PE' => 'Peru',
           'PH' => 'Philippines',
           'PN' => 'Pitcairn Islands',
           'PL' => 'Poland',
           'PT' => 'Portugal',
           'PR' => 'Puerto Rico',
           'QA' => 'Qatar',
           'RE' => 'Reunion',
           'RO' => 'Romania',
           'RU' => 'Russia',
           'RW' => 'Rwanda',
           'BL' => 'Saint Barthelemy',
           'SH' => 'Saint Helena, Ascension, And Tristan Da Cunha',
           'KN' => 'Saint Kitts And Nevis',
           'LC' => 'Saint Lucia',
           'MF' => 'Saint Martin',
           'PM' => 'Saint Pierre And Miquelon',
           'VC' => 'Saint Vincent And The Grenadines',
           'WS' => 'Samoa',
           'SM' => 'San Marino',
           'ST' => 'Sao Tome And Principe',
           'SA' => 'Saudi Arabia',
           'SN' => 'Senegal',
           'RS' => 'Serbia',
           'SC' => 'Seychelles',
           'SL' => 'Sierra Leone',
           'SG' => 'Singapore',
           'SX' => 'Sint Maarten',
           'SK' => 'Slovakia',
           'SI' => 'Slovenia',
           'SB' => 'Solomon Islands',
           'SO' => 'Somalia',
           'ZA' => 'South Africa',
           'GS' => 'South Georgia And South Sandwich Islands',
           'SS' => 'South Sudan',
           'ES' => 'Spain',
           'LK' => 'Sri Lanka',
           'SD' => 'Sudan',
           'SR' => 'Suriname',
           'SZ' => 'Swaziland',
           'SE' => 'Sweden',
           'CH' => 'Switzerland',
           'SY' => 'Syria',
           'TW' => 'Taiwan',
           'TJ' => 'Tajikistan',
           'TZ' => 'Tanzania',
           'TH' => 'Thailand',
           'TL' => 'Timor-leste',
           'TG' => 'Togo',
           'TK' => 'Tokelau',
           'TO' => 'Tonga',
           'TT' => 'Trinidad And Tobago',
           'TN' => 'Tunisia',
           'TR' => 'Turkey',
           'TM' => 'Turkmenistan',
           'TC' => 'Turks And Caicos Islands',
           'TV' => 'Tuvalu',
           'UG' => 'Uganda',
           'UA' => 'Ukraine',
           'AE' => 'United Arab Emirates',
           'GB' => 'United Kingdom',
           'US' => 'United States',
           'UY' => 'Uruguay',
           'UZ' => 'Uzbekistan',
           'VU' => 'Vanuatu',
           'VA' => 'Vatican City',
           'VE' => 'Venezuela',
           'VN' => 'Vietnam',
           'VG' => 'Virgin Islands, British',
           'VI' => 'Virgin Islands, United States ',
           'WF' => 'Wallis And Futuna',
           'EH' => 'Western Sahara',
           'YE' => 'Yemen',
           'ZM' => 'Zambia',
           'ZW' => 'Zimbabwe');
          return $country_code_to_name; 
    }
}


