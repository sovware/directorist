<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-archive-items directorist-archive-map-view">

	<?php do_action( 'directorist_before_map_listings_loop' ); ?>

	<?php $listings->render_map(); ?>

	<?php
	if ( $listings->show_pagination ) {

		do_action( 'directorist_before_listings_pagination' );

		$listings->pagination();

		do_action( 'directorist_after_listings_pagination' );
	}
	?>

	<?php do_action( 'directorist_after_map_listings_loop' ); ?>

</div>