<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;
?>

<div class="directorist-listing-card-zip">
	<?php $listings->print_icon(); ?>
	<?php $listings->print_label(); ?>
	<?php $listings->print_value(); ?>
</div>