<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-archive-items directorist-archive-map-view">
	<div class="directorist-container-fluid">
		<?php
			$listings->render_map();

			if ( $listings->show_pagination ) {
				$listings->pagination();
			}
		?>
	</div>

</div>