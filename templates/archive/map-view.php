<?php
/**
 * @template_description1
 *
 * This template can be overridden by copying it to yourtheme/directorist/ @template_description2
 *
 * @author  wpWax
 * @since   6.6
 * @version 6.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-archive-map-view">

	<?php
	$listings->render_map();

	if ( $listings->show_pagination ) {
		$listings->pagination();
	}
	?>

</div>