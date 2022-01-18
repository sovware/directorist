<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;
?>

<div class="directorist-archive-map-view">

	<?php
	$listings->render_map();

	if ( $listings->display_pagination() ) {
		$listings->pagination_template();
	}
	?>

</div>