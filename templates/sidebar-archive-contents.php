<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div <?php $listings->wrapper_class(); $listings->data_atts(); ?>>
	<div class="listing-with-sidebar">
		<div class="listing-with-sidebar__type-nav">
			<?php
				$listings->directory_type_nav_template();
			?>
		</div>
		<div class="listing-with-sidebar__searchform">
			<div class="directorist-search-contents">
				<?php
					$listings->basic_search_form_template();
				?>
			</div>
		</div>
	
		<div class="listing-with-sidebar__sidebar">
			<div class="directorist-search-contents">
				<?php
					$listings->advance_search_form_template();
				?>
			</div>
		</div>
		<div class="listing-with-sidebar__listing">
			<?php
				$listings->header_bar_template();
				$listings->archive_view_template();
			?>
		</div>
	</div>

</div>