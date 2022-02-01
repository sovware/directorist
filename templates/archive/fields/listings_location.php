<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;
?>

<div class="directorist-listing-card-location">

	<?php $listings->print_icon(); ?>
	<?php $listings->print_label(); ?>

	<div class="directorist-listing-card-location-list">
		<?php echo $listings->get_location_html(); ?>
	</div>

</div>