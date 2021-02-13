<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="atbd_tab-content">
	
	<?php
	foreach ( $dashboard->dashboard_tabs() as $key => $value ) {

		echo $value['content'];

		if (!empty($value['after_content_hook'])) {
			do_action($value['after_content_hook']);
		}
	}

	do_action( 'directorist_after_dashboard_contents', $dashboard );
	?>

</div>