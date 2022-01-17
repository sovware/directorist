<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;

$filter_btn_html = $listings->display_search_filter_icon() ? sprintf( '<span class="%s-filter"></span> %s', atbdp_icon_type(), $listings->filter_button_text() ) : $listings->filter_button_text();
?>

<div class="directorist-header-bar">

	<div class="<?php Helper::directorist_container_fluid(); ?>">

		<div class="directorist-listings-header">

			<div class="directorist-listings-header__left">

				<?php if ( $listings->display_search_form() ): ?>
					<a href="#" class="directorist-btn directorist-btn-sm directorist-btn-px-15 directorist-btn-outline-primary directorist-filter-btn"><?php echo wp_kses_post( $filter_btn_html ); ?></a>
				<?php endif; ?>

				<h3 class="directorist-header-found-title"><?php echo $listings->item_found_text(); ?></h3>
			</div>

			<?php if ( $listings->display_viewas_dropdown() || $listings->display_sortby_dropdown() ): ?>

				<div class="directorist-listings-header__right">
					<?php
					if ( $listings->display_viewas_dropdown() ) {
						$listings->viewas_dropdown_template();
					}

					if ( $listings->display_sortby_dropdown() ) {
						$listings->sortby_dropdown_template();
					}
					?>
				</div>

			<?php endif; ?>

		</div>

		<?php if ( $listings->display_search_form() ) { ?>
			<div class="<?php echo ( 'overlapping' == $listings->filter_open_method() ) ? 'directorist-search-float' : 'directorist-search-slide'; ?>">
				<?php $listings->search_form_template();?>
			</div>
		<?php } ?>

	</div>

</div>