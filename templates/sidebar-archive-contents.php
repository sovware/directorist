<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div <?php $listings->wrapper_class(); $listings->data_atts(); ?>>
	<?php
		$listings->directory_type_nav_template();
	?>
	<div class="listing-with-sidebar">
		<div class="listing-with-sidebar__searchform">
			<?php
				$listings->basic_search_form_template();
			?>
		</div>
	
		<div class="listing-with-sidebar__sidebar">
			<?php
				$listings->advance_search_form_template();
			?>
		</div>
		<div class="listing-with-sidebar__listing">
			<?php
				$listings->header_bar_template();
				$listings->archive_view_template();
			?>
		</div>
	</div>

</div>