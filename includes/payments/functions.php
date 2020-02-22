<?php
/**
 * Directorist Payment Functions
 *
 * @package     Directorist
 * @subpackage  Payment
 * @copyright   Copyright (c) 2018, AazzTech
 * @license     http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @since       3.1.0
 */


/*
 * Placeholder functions to work as blue prints. These functions to be extended with features as we work on.*/
/*IT WORKS LIKE AN INTERFACE*/

/**
 * @param string $status
 * @return string
 */
function atbdp_get_payment_status($status = '')
{
    return $status;
}

/**
 * @param $payment_id
 * @param $status
 */
function atbdp_update_payment_status($payment_id, $status)
{

}

function atbdp_purchase_form_required_fields()
{
    return array();
}

/**
 * Get all the payment statuses.
 *
 * @since    3.1.0
 *
 * @return   array    $statuses    A list of available payment status.
 */
function atbdp_get_payment_statuses()
{

    $statuses = array(
        'created' => __("Created", 'directorist'),
        'pending' => __("Pending", 'directorist'),
        'completed' => __("Completed", 'directorist'),
        'failed' => __("Failed", 'directorist'),
        'cancelled' => __("Cancelled", 'directorist'),
        'refunded' => __("Refunded", 'directorist'),
    );

    return apply_filters('atbdp_payment_statuses', $statuses);

}

/**
 * Get order bulk actions array.
 *
 * @since    3.1.0
 *
 * @return   array    $actions    An array of bulk list of order history status.
 */
function atbdp_get_payment_bulk_actions()
{

    $actions = array(
        'set_to_created' => __("Set Status to Created", 'directorist'),
        'set_to_pending' => __("Set Status to Pending", 'directorist'),
        'set_to_completed' => __("Set Status to Completed", 'directorist'),
        'set_to_failed' => __("Set Status to Failed", 'directorist'),
        'set_to_cancelled' => __("Set Status to Cancelled", 'directorist'),
        'set_to_refunded' => __("Set Status to Refunded", 'directorist'),
    );

    return apply_filters('atbdp_order_bulk_actions', $actions);

}


/**
 * Returns a nicely formatted amount.
 *
 * @since    3.1.0
 *
 * @param    string $amount Price amount to format
 * @param    bool $decimals Whether to use decimals or not. Useful when set to false for non-currency numbers.
 * @param    array $currency_settings Currency Settings. If we do not provide currency settings
 *                                            then it uses the general currency settings used to display formatted pricing
 *                                            on the front end. However, we can provide new currency settings array
 *                                            with 4 items currency name, thousand and decimal separators and
 *                                            the position of the currency symbol.
 *
 * @return   string     $amount               Newly formatted amount or Price Not Available
 */
function atbdp_format_amount( $amount, $decimals = true, $currency_settings = array() ) {
    !is_array($currency_settings) || extract($currency_settings); // if it is array then extract it. Using the magic of OR CONDITION's FLOW

    $currency       = ! empty( $currency )              ? $currency : get_directorist_option('g_currency', 'USD') ;
    $thousands_sep  = ! empty( $thousands_separator )   ? $thousands_separator:  get_directorist_option('g_thousand_separator', ',');
    $decimal_sep    = ! empty( $decimal_separator )     ? $decimal_separator:  get_directorist_option('g_decimal_separator', '.');

    // Format the amount
    if( $decimal_sep == ',' && false !== ( $sep_found = strpos( $amount, $decimal_sep ) ) ) {
        $whole = substr( $amount, 0, $sep_found );
        $part = substr( $amount, $sep_found + 1, ( strlen( $amount ) - 1 ) );
        $amount = $whole . '.' . $part;
    }

    // Strip , from the amount (if set as the thousands separator)
    if( $thousands_sep == ',' && false !== ( $found = strpos( $amount, $thousands_sep ) ) ) {
        $amount = str_replace( ',', '', $amount );
    }

    // Strip ' ' from the amount (if set as the thousands separator)
    if( $thousands_sep == ' ' && false !== ( $found = strpos( $amount, $thousands_sep ) ) ) {
        $amount = str_replace( ' ', '', $amount );
    }

    if( empty( $amount ) ) {
        $amount = 0;
    }

    if( $decimals ) {
        $decimals  = atbdp_currency_decimal_count( 2, $currency );
    } else {
        $decimals = 0;
    }

    $formatted = number_format( (float)$amount, $decimals, $decimal_sep, $thousands_sep );

    return apply_filters( 'atbdp_format_amount', $formatted, $amount, $decimals, $decimal_sep, $thousands_sep );

}

/**
 * Returns a nicely formatted currency amount.
 *
 * @since    3.1.0
 *
 * @param    string $amount Price amount to format
 * @param    bool $decimals Whether or not to use decimals. Useful when set to false for non-currency numbers.
 * @return   string                 Newly formatted amount or Price Not Available
 */

function atbdp_format_payment_amount($amount, $decimals = true)
{
    $decimals = get_directorist_option('allow_decimal', 1);
    return atbdp_format_amount($amount, $decimals, atbdp_get_payment_currency_settings());

}


/**
 * Get the directory's payment currency settings if available. Otherwise, it returns the general currency settings
 *
 * @since    3.1.0
 * @return   array    $currency_settings    Currency settings array that contains currency name, thousand and decimal separators
 */
function atbdp_get_payment_currency_settings()
{

    // Get the payment currency settings, and use the general currency settings if the payment currency setting is empty.
    $currency_settings = array(
        'currency' => get_directorist_option('payment_currency', get_directorist_option('g_currency', 'USD')),
        'thousands_separator' => get_directorist_option('payment_thousand_separator', get_directorist_option('g_thousand_separator', ',')),
        'decimal_separator' => get_directorist_option('payment_decimal_separator', get_directorist_option('g_decimal_separator', '.')),
        'position' => get_directorist_option('payment_currency_position', get_directorist_option('g_currency_position', 'before')),
    );

    return apply_filters('atbdp_payment_currency_settings', $currency_settings); // return the currency settings array

}


/**
 * Some currencies do not support for decimal. So, It Set the number of decimal places to 0 for RIAL, JPY, TWD, HUF currency. and it sets given decimal place for all other currency except the ones mentioned already.
 *
 * @see https://developer.paypal.com/docs/classic/mass-pay/integration-guide/currency_codes/
 * @since    3.1.0
 *
 * @param    int $decimals Number of decimal places.
 * @param    string $currency Payment currency.
 * @return   int       It returns the number of decimal place
 */
function atbdp_currency_decimal_count($decimals = 2, $currency = 'USD')
{
    /*Remove Decimal from the following currency as they do not support decimal*/
    switch ($currency) {
        case 'RIAL' :
        case 'SAR' :
        case 'JPY' :
        case 'TWD' :
        case 'HUF' :
            $decimals = 0;
            break;
    }

    return apply_filters('atbdp_currency_decimal_count', $decimals, $currency);

}


/**
 * Add currency symbol to the given price according to payment currency settings
 *
 * @since    3.1.0
 *
 * @param    string|int|float $price Paid Amount.
 * @return   string              Formatted amount with currency.
 */
function atbdp_payment_currency_filter($price = '')
{

    return atbdp_currency_filter($price, atbdp_get_payment_currency_settings());

}


/**
 * Formats the currency display.
 *
 * @since    3.1.0
 *
 * @param    string|int|float $price Paid Amount.
 * @param    array $currency_settings Currency Settings.
 * @return   string    $formatted            Formatted amount with currency.
 */
function atbdp_currency_filter($price = '', $currency_settings = array())
{

    !is_array($currency_settings) || extract($currency_settings); // if it is an array then extract it. Using the magic of OR CONDITION's FLOW
    $currency = !empty($currency) ? $currency : get_directorist_option('g_currency', 'USD');
    $position = !empty($position) ? $position : get_directorist_option('g_currency_position', 'before');

    $negative = $price < 0;

    if ($negative) {
        $price = substr($price, 1); // Remove proceeding "-" -
    }

    $symbol = atbdp_currency_symbol($currency);

    if ($position == 'before') {

        switch ($currency) {
            case "GBP" :
            case "BRL" :
            case "EUR" :
            case "USD" :
            case "AUD" :
            case "CAD" :
            case "HKD" :
            case "MXN" :
            case "NZD" :
            case "SGD" :
            case "JPY" :
                $formatted = $symbol . $price;
                break;
            default :
                $formatted = $currency . ' ' . $price;
                break;
        }

        $formatted = apply_filters('atbdp_' . strtolower($currency) . '_currency_filter_before', $formatted, $currency, $price);

    } else {

        switch ($currency) {
            case "GBP" :
            case "BRL" :
            case "EUR" :
            case "USD" :
            case "AUD" :
            case "CAD" :
            case "HKD" :
            case "MXN" :
            case "SGD" :
            case "JPY" :
                $formatted = $price . $symbol;
                break;
            default :
                $formatted = $price . ' ' . $currency;
                break;
        }

        $formatted = apply_filters('atbdp_' . strtolower($currency) . '_currency_filter_after', $formatted, $currency, $price);

    }

    if ($negative) {
        // Prepend the mins sign before the currency sign
        $formatted = '-' . $formatted;
    }

    return $formatted;

}

/**
 * Given a currency determine the symbol to use. If no currency given, site default is used.
 * If no symbol is determine, the currency string is returned.
 *
 * @since    1.0.0
 *
 * @param    string $currency The currency string.
 * @return   string                 The symbol to use for the currency.
 */
function atbdp_currency_symbol($currency = '')
{

    switch ($currency) {
        case "GBP" :
        case "POUND" :
        case "pound" :
            $symbol = '&pound;';
            break;
        case "BRL" :
            $symbol = 'R&#36;';
            break;
        case "EUR" :
            $symbol = '&euro;';
            break;
        case "JPY" :
            $symbol = '&yen;';
            break;
        case "RUPEE" :
        case "INR" :
            $symbol = '&#8377;';
            break;
        case "PHP" :
            $symbol = '&#8369;'; // Mexican and Philippine Peso Sign
            break;
        case "BDT" :
            $symbol = '&#2547;';
            break;
        case "ALL" :
            $symbol = '&#76;&#101;&#107;';
            break;
        case "AFN" :
            $symbol = '&#1547;';
            break;
        case "USD" :
        case "AUD" :
        case "NZD" :
        case "CAD" :
        case "ARP" :
        case "HKD" :
        case "SGD" :
        case "ARS" :
        case "BSD" :
        case "BBD" :
        case "BMD" :
        case "BND" :
        case "KYD" :
        case "CLP" :
        case "COP" :
        case "XCD" :
        case "SVC" :
        case "FJD" :
        case "GYD" :
        case "LRD" :
        case "MXN" :
        case "NAD" :
        case "SBD" :
        case "SRD" :
        case "TVD" :
        case "CUC" :
        case "CVE" :
            $symbol = '&#36;';
            break;
        case "AWG" :
            $symbol = '&#402;';
            break;
        case "AZN" :
            $symbol = '&#1084;&#1072;&#1085;';
            break;
        case "BYN" :
            $symbol = '&#66;&#114;';
            break;
        case "BZD" :
            $symbol = '&#66;&#90;&#36;';
            break;
        case "BOB" :
            $symbol = 'Bs.';
            break;
        case "BAM" :
            $symbol = '&#75;&#77;';
            break;
        case "BWP" :
            $symbol = '&#80;';
            break;
        case "BGN" :
            $symbol = '&#1083;&#1074;';
            break;
        case "KHR" :
            $symbol = '&#6107;';
            break;
        case "CNY" :
            $symbol = '&#165;';
            break;
        case "CRC" :
            $symbol = '&#8353;';
            break;
        case "HRK" :
            $symbol = '&#107;&#110;';
            break;
        case "CUP" :
            $symbol = '&#8369;';
            break;
        case "CZK" :
            $symbol = '&#75;&#269;';
            break;
        case "DKK" :
            $symbol = '&#107;&#114;';
            break;
        case "DOP" :
            $symbol = '&#82;&#68;&#36;';
            break;
        case "FKP" :
        case "GIP" :
        case "GGP" :
        case "IMP" :
        case "JEP" :
        case "SHP" :
        case "SSP" :
            $symbol = '&#163;';
            break;
        case "EGP" :
            $symbol = 'E&#163;';
            break;
        case "GHS" :
            $symbol = '&#x20b5;';
            break;
        case "GTQ" :
            $symbol = '&#81;';
            break;
        case "HNL" :
            $symbol = '&#76;';
            break;
        case "HUF" :
            $symbol = '&#70;&#116;';
            break;
        case "ISK" :
            $symbol = '&#107;&#114;';
            break;
        case "IDR" :
            $symbol = '&#82;&#112;';
            break;
        case "IRR" :
        case "SAR" :
        case "YER" :
            $symbol = '&#65020;';
            break;
        case "ILS" :
            $symbol = '&#8362;';
            break;
        case "JMD" :
            $symbol = '&#74;&#36;';
            break;
        case "KZT" :
            $symbol = '&#8376;';
            break;
        case "KPW" :
        case "KRW" :
            $symbol = '&#8361;';
            break;
        case "KGS" :
            $symbol = '&#1083;&#1074;';
            break;
        case "LAK" :
            $symbol = '&#8365;';
            break;
        case "MKD" :
            $symbol = '&#1076;&#1077;&#1085;';
            break;
        case "MYR" :
            $symbol = '&#82;&#77;';
            break;
        case "MNT" :
            $symbol = '&#8366;';
            break;
        case "MZN" :
            $symbol = '&#77;&#84;';
            break;
        case "NPR" :
        case "PKR" :
        case "SCR" :
        case "LKR" :
        case "MUR" :
            $symbol = '&#8360;';
            break;
        case "ANG" :
            $symbol = '&#402;';
            break;
        case "NIO" :
            $symbol = '&#67;&#36;';
            break;
        case "NGN" :
            $symbol = '&#8358;';
            break;
        case "NOK" :
            $symbol = '&#107;&#114;';
            break;
        case "PAB" :
            $symbol = '&#66;&#47;&#46;';
            break;
        case "PYG" :
            $symbol = '&#8370;';
            break;
        case "PEN" :
            $symbol = '&#83;&#47;&#46;';
            break;
        case "PLN" :
            $symbol = '&#122;&#322;';
            break;
        case "RON" :
            $symbol = '&#108;&#101;&#105;';
            break;
        case "RUB" :
            $symbol = '&#8381;';
            break;
        case "RSD" :
            $symbol = '&#x434;&#x438;&#x43d;.';
            break;
        case "SOS" :
            $symbol = 'Sh';
            break;
        case "ZAR" :
            $symbol = '&#82;';
            break;
        case "SEK" :
            $symbol = '&#107;&#114;';
            break;
        case "CHF" :
            $symbol = '&#67;&#72;&#70;';
            break;
        case "TWD" :
            $symbol = '&#78;&#84;&#36;';
            break;
        case "THB" :
            $symbol = '&#3647;';
            break;
        case "TTD" :
            $symbol = '&#84;&#84;&#36;';
            break;
        case "UAH" :
            $symbol = '&#8372;';
            break;
        case "UYU" :
            $symbol = '&#36;&#85;';
            break;
        case "VEF" :
            $symbol = '&#66;&#115;';
            break;
        case "VND" :
            $symbol = '&#8363;';
            break;
        case "ZWD" :
            $symbol = '&#90;&#36;';
            break;
        case "UZS" :
            $symbol = 'UZS';
            break;
        case "TRY" :
            $symbol = '&#8378;';
            break;
        case "AED" :
            $symbol = '&#x62f;.&#x625;';
            break;
        case "AMD" :
            $symbol = 'AMD';
            break;
        case "AOA" :
            $symbol = 'Kz';
            break;
        case "BHD" :
            $symbol = '.&#x62f;.&#x628;';
            break;
        case "BIF" :
        case "CDF" :
        case "DJF" :
        case "GNF" :
        case "KMF" :
        case "RWF" :
            $symbol = 'Fr';
            break;
        case "BTC" :
            $symbol = '&#3647;';
            break;
        case "BTN" :
            $symbol = 'Nu.';
            break;
        case "BWP" :
            $symbol = 'P';
            break;
        case "BYR" :
        case "ETB" :
            $symbol = 'Br';
            break;
        case "DZD" :
            $symbol = '&#x62f;.&#x62c;';
            break;
        case "ERN" :
            $symbol = 'Nfk';
            break;
        case "GEL" :
            $symbol = '&#x20be;';
            break;
        case "GMD" :
            $symbol = 'D';
            break;
        case "HTG" :
            $symbol = 'G';
            break;
        case "IQD" :
            $symbol = '&#x639;.&#x62f;';
            break;
        case "IRT" :
            $symbol = '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;';
            break;
        case "JOD" :
            $symbol = '&#x62f;.&#x627;';
            break;
        case "KES" :
            $symbol = 'KSh';
            break;
        case "KWD" :
            $symbol = '&#x62f;.&#x643;';
            break;
        case "LBP" :
            $symbol = '&#x644;.&#x644;';
            break;
        case "LSL" :
        case "MDL" :
        case "SZL" :
            $symbol = 'L';
            break;
        case "LYD" :
            $symbol = '&#x644;.&#x62f;';
            break;
        case "MAD" :
            $symbol = '&#x62f;.&#x645;.';
            break;
        case "MGA" :
            $symbol = 'Ar';
            break;
        case "MMK" :
            $symbol = 'K';
            break;
        case "MOP" :
            $symbol = 'MOP&#36;';
            break;
        case "MRU" :
            $symbol = 'UM';
            break;
        case "MVR" :
            $symbol = '.&#x783;';
            break;
        case "MWK" :
            $symbol = 'MK';
            break;
        case "OMR" :
            $symbol = '&#x631;.&#x639;.';
            break;
        case "PGK" :
            $symbol = 'K';
            break;
        case "PRB" :
            $symbol = '&#x440;.';
            break;
        case "QAR" :
            $symbol = '&#x631;.&#x642;';
            break;
        case "RMB" :
            $symbol = '&yen;';
            break;
        case "SDG" :
            $symbol = '&#x62c;.&#x633;.';
            break;
        case "SLL" :
            $symbol = 'Le';
            break;
        case "STN" :
            $symbol = 'Db';
            break;
        case "SYP" :
            $symbol = '&#x644;.&#x633;';
            break;
        case "TJS" :
            $symbol = '&#x405;&#x41c;';
            break;
        case "TMT" :
            $symbol = 'm';
            break;
        case "TND" :
            $symbol = '&#x62f;.&#x62a;';
            break;
        case "TOP" :
            $symbol = 'T&#36;';
            break;
        case "TZS" :
            $symbol = 'TSh';
            break;
        case "UGX" :
            $symbol = 'USh';
            break;
        case "VES" :
            $symbol = 'Bs.S';
            break;
        case "VUV" :
            $symbol = 'VT';
            break;
        case "WST" :
            $symbol = 'T';
            break;
        case "XAF" :
            $symbol = 'FCFA';
            break;
        case "XOF" :
            $symbol = 'CFA';
            break;
        case "XPF" :
            $symbol = '&#8355;';
            break;
        case "ZMW" :
            $symbol = 'ZK';
            break;
        default :
            $symbol = $currency;
            break;
    }

    return apply_filters('atbdp_currency_symbol', $symbol, $currency);

}


/**
 * Retrieve the payment status in localized format.
 *
 * @since    3.1.0
 *
 * @param    string $status Payment status.
 * @return   string    $status    Localized payment status.
 */
function atbdp_get_payment_status_i18n($status)
{

    $statuses = atbdp_get_payment_statuses();
    return array_key_exists($status, $statuses) ? $statuses[$status] : __('Invalid', 'directorist');

}

/**
 * Get the directory's set payment currency
 *
 * @since    3.1.0
 * @return   string    The currency code.
 */
function atbdp_get_payment_currency()
{
    $cs = atbdp_get_payment_currency_settings();
    return !empty($cs['currency']) ? strtoupper($cs['currency']) : 'USD';
}
