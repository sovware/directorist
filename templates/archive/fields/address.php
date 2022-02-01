<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;
?>

<div class="directorist-listing-card-address">
	<?php $listings->print_icon(); ?>
	<?php $listings->print_label(); ?>
	<?php $listings->print_value(); ?>
</div>