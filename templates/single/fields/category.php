<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( empty( $listing->get_cat_list() ) ) {
	return;
}
?>

<div class="directorist-info-item directorist-listing-category">

	<span class="<?php atbdp_icon_type( true );?>-tags"></span>

	<span><?php echo $listing->get_cat_list(); ?></span>
	
</div>