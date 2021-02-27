<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-header-bar">

	<div class="<?php Helper::directorist_container_fluid(); ?>">

		<div class="directorist-listings-header">

			<?php if ( $listings->has_listings_header() ): ?>

				<div class="directorist-listings-header__left">

					<?php if ( $listings->has_filters_button ): ?>
						<a href="#" class="directorist-btn directorist-btn-sm directorist-btn-px-15 directorist-btn-outline-primary directorist-filter-btn"><?php echo $listings->filter_btn_html(); ?></a>
					<?php endif; ?>

					<?php if ( $listings->header_title ): ?>
						<h3 class="directorist-header-found-title"><?php echo $listings->item_found_title(); ?></h3>
					<?php endif; ?>
				</div>

			<?php endif; ?>

			<?php if ( $listings->has_header_toolbar() ): ?>

				<div class="directorist-listings-header__right">
					<?php
					if ( $listings->display_viewas_dropdown ) {
						$listings->viewas_dropdown_template();
					}

					if ( $listings->display_sortby_dropdown ) {
						$listings->sortby_dropdown_template();
					}
					?>
				</div>

			<?php endif; ?>

		</div>

		<?php if ( $listings->advanced_filter ) { ?>
			<div class="<?php Helper::search_filter_class( $listings->filters_display ); ?>">
				<?php $listings->search_form_template();?>
			</div>
		<?php } ?>

	</div>

</div>