<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;

if ( !$listings->field_value() ) {
	return;
}
?>

<div class="directorist-listing-card-text">
	<?php $listings->print_icon(); ?>
	<?php $listings->print_label(); ?>
	<?php $listings->print_value(); ?>
</div>