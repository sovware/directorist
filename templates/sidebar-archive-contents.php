<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div <?php $listings->wrapper_class(); $listings->data_atts(); ?>>
	<div class="listing-with-sidebar">
		<div class="directorist-container">
			<div class="listing-with-sidebar__type-nav">
				<?php
					$listings->directory_type_nav_template();
				?>
			</div>

			<?php if( ! $listings->hide_top_search_bar_on_sidebar_layout() ) : ?>

				<div class="listing-with-sidebar__searchform">
					<div class="directorist-search-contents directorist-contents-wrap">
						<?php
							$listings->basic_search_form_template();
						?>
					</div>
				</div>

			<?php endif; ?>

			<div class="listing-with-sidebar__header">
				<?php
					$listings->header_bar_template();
				?>
			</div>
			<div class="listing-with-sidebar__contents">
				<div class="listing-with-sidebar__sidebar <?php echo esc_attr( $listings->sidebar_class() ); ?>">
					<div class="directorist-search-contents directorist-contents-wrap">
						<?php
							$listings->advance_search_form_template();
						?>
					</div>
				</div>
				<div class="listing-with-sidebar__listing">
					<?php
						$listings->archive_view_template();
					?>
				</div>
			</div>
		</div>
	</div>

</div>