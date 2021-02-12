<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<span class="directorist-listing-price"><?php echo Helper::formatted_price( $price ); ?></span>