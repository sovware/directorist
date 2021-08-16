<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.5.3
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$html  = str_repeat( '<span class="directorist-price-active">'.$currency.'</span>' , $active_items );
$html .= str_repeat( '<span>'.$currency.'</span>' , ( 4-$active_items ) );
?>

<span class="directorist-listing-price-range directorist-tooltip" data-label="<?php echo esc_attr( $price_range_text ); ?>"><?php echo $html;?></span>