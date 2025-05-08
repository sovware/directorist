<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.4.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div <?php $listings->wrapper_html_attributes(); ?>>
	<div class="listing-with-sidebar">
		<div class="directorist-container">
			<div class="listing-with-sidebar__wrapper">
				<div class="listing-with-sidebar__type-nav">
					<?php $listings->directory_type_nav_template(); ?>
				</div>

				<?php if ( $listings->should_display_basic_search_form() ) : ?>
					<div class="listing-with-sidebar__searchform">
						<?php $listings->basic_search_form_template(); ?>
					</div>
				<?php endif; ?>

				<?php if ( $listings->header ) : ?>
					<div class="listing-with-sidebar__header">
						<?php $listings->header_bar_template(); ?>
					</div>
				<?php endif; ?>

				<div class="listing-with-sidebar__contents">
					<?php if ( $listings->should_display_advance_search_form() ) : ?>
						<aside class="listing-with-sidebar__sidebar <?php echo esc_attr( $listings->sidebar_class() ); ?>">
							<?php $listings->advance_search_form_template(); ?>
						</aside>
					<?php endif; ?>

					<section class="listing-with-sidebar__listing">
						<?php $listings->archive_view_template(); ?>
					</section>
				</div>
			</div>
		</div>
	</div>
</div>
