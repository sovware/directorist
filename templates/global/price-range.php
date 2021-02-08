<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

$html  = str_repeat( '<span class="atbd_active">'.$currency.'</span>' , $active_items );
$html .= str_repeat( '<span>'.$currency.'</span>' , ( 4-$active_items ) );
?>

<span class="atbd_meta atbd_listing_average_pricing atbd_tooltip"><?php echo $html;?></span>