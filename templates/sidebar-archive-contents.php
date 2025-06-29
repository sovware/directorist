<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.2
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div <?php $listings->wrapper_class(); $listings->data_atts(); ?>>
	<div class="listing-with-sidebar">
		<div class="directorist-container">
			<div class="listing-with-sidebar__wrapper">
				<div class="listing-with-sidebar__type-nav">
					<?php
						$listings->directory_type_nav_template();
					?>
				</div>

				<?php if( ! $listings->hide_top_search_bar_on_sidebar_layout() ) : ?>

					<div class="listing-with-sidebar__searchform">
						<?php
							$listings->basic_search_form_template();
						?>
					</div>

				<?php endif; ?>

				<?php if( $listings->header ) : ?>
				
					<div class="listing-with-sidebar__header">
						<?php
							$listings->header_bar_template();
						?>
					</div>

				<?php endif; ?>

				<div class="listing-with-sidebar__contents">
					<?php if( isset( $searchform->form_data[1]['fields'] ) && ! empty( $searchform->form_data[1]['fields'] ) ) : ?>
						<aside class="listing-with-sidebar__sidebar <?php echo esc_attr( $listings->sidebar_class() ); ?>">
							<?php
								$listings->advance_search_form_template();
							?>
						</aside>
					<?php endif; ?>
					<section class="listing-with-sidebar__listing">
						<?php
							$listings->archive_view_template();
						?>
					</section>
				</div>
			</div>
		</div>
	</div>

</div>