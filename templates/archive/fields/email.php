<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;
?>

<div class="directorist-listing-card-email">
	<?php $listings->print_icon(); ?>
	<?php $listings->print_label(); ?>
	<a target="_top" href="mailto:<?php echo esc_attr( $listings->field_value() );?>"><?php echo esc_html( $listings->field_value() ) ;?></a>
</div>