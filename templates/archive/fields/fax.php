<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.8
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;
?>

<div class="directorist-listing-card-fax">
	<?php $listings->print_icon(); ?>
	<?php $listings->print_label(); ?>
	<a href="tel:<?php Helper::formatted_tel( $listings->field_value() ); ?>"><?php echo esc_html( $listings->field_value() ); ?></a>
</div>