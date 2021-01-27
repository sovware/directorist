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

<div class="directorist-archive-header-bar">

	<div class="<?php Helper::directorist_container(); ?>">

		<div class="directorist-archive-header-contents">
			
			<?php if ($listings->has_listings_header()) { ?>
				<div class="atbd_generic_header_title">
					<?php if ($listings->has_filters_button) { ?>
						<a href="#" class="more-filter btn btn-outline btn-outline-primary">
							<?php if ($listings->has_filters_icon) { ?>
								<span class="<?php atbdp_icon_type(true); ?>-filter"></span>
							<?php } ?>
							<?php echo $listings->filter_button_text; ?>
						</a>
						<?php
					}
					
					if (!empty($listings->header_title)) {
						echo $listings->item_found_title();
					}
					?>
				</div>
				<?php
			}
			
			if ($listings->has_header_toolbar()) { ?>
				<div class="atbd_listing_action_btn btn-toolbar" role="toolbar">
					<?php
					if ($listings->display_viewas_dropdown) {
						$listings->viewas_dropdown_template();
					}
					
					if ($listings->display_sortby_dropdown) {
						$listings->sortby_dropdown_template();
					}
					?>
				</div>
				<?php
			}
			?>
		</div>

		<div class="<?php $listings->filter_container_class(); ?>">
			<?php $listings->advanced_search_form_template();?>
		</div>
	</div>

</div>