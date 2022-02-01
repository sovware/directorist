<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;
?>

<div class="directorist-listing-card-number">
	<?php $listings->print_icon(); ?>
	<?php $listings->print_label(); ?>
	<?php $listings->print_value(); ?>
</div>