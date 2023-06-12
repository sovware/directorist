<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div <?php $listings->wrapper_class(); $listings->data_atts(); ?>>
	<div class="directorist-archive-contents__top">
		<?php
			$listings->mobile_view_filter_template();
			$listings->directory_type_nav_template();
			$listings->header_bar_template();
			$listings->full_search_form_template();
		?>
	</div>
	<div class="directorist-archive-contents__listings">
		<?php
			$listings->archive_view_template();
		?>
	</div>

</div>