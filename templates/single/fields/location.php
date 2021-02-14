<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( empty( $listing->get_location_list() ) ) {
	return;
}
?>

<div class="directorist-info-item directorist-listing-location">

	<span class="<?php atbdp_icon_type( true );?>-map-marker"></span>
	
	<span><?php echo $listing->get_location_list(); ?></span>
	
</div>