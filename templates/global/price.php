<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<span class="directorist-listing-price"><?php echo wp_kses_post( Helper::formatted_price( $price ) ); ?></span>