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

	<?php directorist_icon( 'las la-tags' ); ?>

	<span><?php echo $listing->get_cat_list(); ?></span>

</div>