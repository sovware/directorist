<?php
/**
 * @template_description1
 *
 * This template can be overridden by copying it to yourtheme/directorist/ @template_description2
 *
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-header-bar">

	<div class="<?php Helper::directorist_container(); ?>">

		<div class="directorist-header-bar-contents">

			<?php if ( $listings->has_listings_header() ): ?>

				<div class="directorist-header-bar-left">

					<?php if ( $listings->has_filters_button ): ?>
						<a href="#" class="directorist-filter-btn"><?php echo $listings->filter_btn_html(); ?></a>
					<?php endif; ?>

					<?php if ( $listings->header_title ): ?>
						<h3 class="directorist-header-found-title"><?php  echo $listings->item_found_title(); ?></h3>
					<?php endif; ?>
				</div>

			<?php endif; ?>

			<?php if ( $listings->has_header_toolbar() ): ?>

				<div class="directorist-header-bar-right">
					<?php
					if ($listings->display_viewas_dropdown) {
						$listings->viewas_dropdown_template();
					}
					
					if ($listings->display_sortby_dropdown) {
						$listings->sortby_dropdown_template();
					}
					?>
				</div>

			<?php endif; ?>
			
		</div>

		<div class="<?php $listings->filter_container_class(); ?>">
			<?php $listings->advanced_search_form_template();?>
		</div>
	</div>

</div>