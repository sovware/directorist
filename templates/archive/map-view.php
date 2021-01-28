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

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="directorist" class="atbd_wrapper">
	<div class="atbdp-divider"></div>

	<?php
	$listings->map_template();

	if ( $listings->show_pagination ) {
		$listings->pagination();
	}
	?>
</div>