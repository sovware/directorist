<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.2.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-archive directorist-archive-map-view">

	<?php
	$listings->render_map();

	if ( $listings->show_pagination ) {
		$listings->pagination();
	}
	?>

</div>