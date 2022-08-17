<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( empty( $listing->get_location_list() ) ) {
	return;
}
?>

<div class="directorist-info-item directorist-listing-location">

	<?php directorist_icon( 'las la-map-marker' ); ?>

	<span><?php echo wp_kses_post( $listing->get_location_list() ); ?></span>

</div>