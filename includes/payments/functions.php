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
function atbdp_format_amount($amount, $decimals = true, $currency_settings = array())
{
    $decimals = get_directorist_option('allow_decimal', 1);
    !is_array($currency_settings) || extract($currency_settings); // if it is array then extract it. Using the magic of OR CONDITION's FLOW

    $currency = !empty($currency) ? $currency : get_directorist_option('g_currency', 'USD');
    $thousands_sep = !empty($thousands_separator) ? $thousands_separator : get_directorist_option('g_thousand_separator', ',');
    $decimal_sep = !empty($decimal_separator) ? $decimal_separator : get_directorist_option('g_decimal_separator', '.');

    // Format the amount
    if ($decimal_sep == ',' && false !== ($sep_found = strpos($amount, $decimal_sep))) {
        $whole = substr($amount, 0, $sep_found);
        $part = substr($amount, $sep_found + 1, (strlen($amount) - 1));
        $amount = $whole . '.' . $part;
    }

    // Strip , from the amount (if set as the thousands separator)
    if ($thousands_sep == ',' && false !== ($found = strpos($amount, $thousands_sep))) {
        $amount = str_replace(',', '', $amount);
    }
    // Strip . from the amount (if set as the thousands separator)
    if ($thousands_sep == '.' && false !== ($found = strpos($amount, $thousands_sep))) {
        $amount = str_replace('.', '.', $amount);
    }
    // Strip ' ' from the amount (if set as the thousands separator)
    if ($thousands_sep == ' ' && false !== ($found = strpos($amount, $thousands_sep))) {
        $amount = str_replace(' ', '', $amount);
    }

    if (empty($amount)) {
        $amount = 0;
    }

    if ($decimals) {
        $decimals = atbdp_currency_decimal_count(2, $currency);
    } else {
        $decimals = 0;
    }

    /**
     * @since 5.7.1 skip formatted data in order to deliver the actual amount for featured listing
     */
    // $formatted = number_format( $amount, $decimals, $decimal_sep, '' );

    return apply_filters('atbdp_format_amount', $amount, $amount, $decimals, $decimal_sep, $thousands_sep);

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
        case "USD" :
        case "AUD" :
        case "NZD" :
        case "CAD" :
        case "ARP" :
        case "HKD" :
        case "SGD" :
            $symbol = '&#36;';
            break;
        case "JPY" :
            $symbol = '&yen;';
            break;
        case "RUPEE" :
        case "MUR" :
        case "PKR" :
            $symbol = '&#8360;';
            break;
        case "INR" :
            $symbol = '&#8377;';
            break;
        case "MXN" :
        case "PHP" :
            $symbol = '&#8369;'; // Mexican and Philippine Peso Sign
            break;
        case "BDT" :
            $symbol = '&#2547;';
            break;
        case "DZD" :
            $symbol = '&#65020;';
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
