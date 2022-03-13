<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.5.6
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$search_form = directorist()->search_form;
?>

<div class="directorist-search-contents" data-atts='<?php echo esc_attr( $search_form->get_atts_data() ); ?>' style="<?php echo $search_form->background_img_style(); ?>">

	<div class="<?php Helper::directorist_container_fluid(); ?>">

		<?php do_action('directorist_search_listing_before_title'); ?>

		<?php if ( $search_form->show_title_subtitle && ( $search_form->search_bar_title || $search_form->search_bar_sub_title ) ): ?>

			<div class="directorist-search-top">

				<?php if ( $search_form->search_bar_title ): ?>
					<h2 class="directorist-search-top__title"><?php echo esc_html( $search_form->search_bar_title ); ?></h2>
				<?php endif; ?>

				<?php if ( $search_form->search_bar_sub_title ): ?>
					<p class="directorist-search-top__subtitle"><?php echo esc_html( $search_form->search_bar_sub_title ); ?></p>
				<?php endif; ?>

			</div>

		<?php endif; ?>

		<form action="<?php echo esc_url( ATBDP_Permalink::get_search_result_page_link() ); ?>" class="directorist-search-form">

			<div class="directorist-search-form-wrap <?php echo esc_attr( $search_form->border_class() ); ?>">

				<?php $search_form->directory_type_nav_template(); ?>

				<input type="hidden" name="directory_type" class="listing_type" value="<?php echo esc_attr( $search_form->listing_type_slug() ); ?>">

				<div class="directorist-search-form-box">

					<div class="directorist-search-form-top directorist-flex directorist-align-center directorist-search-form-inline">

						<?php
						foreach ( $search_form->form_data[0]['fields'] as $field ){
							$search_form->field_template( $field );
						}
						if ( $search_form->more_filters_display !== 'always_open' ){
							$search_form->more_buttons_template();
						}
						?>

					</div>

					<?php
					if ( $search_form->more_filters_display == 'always_open' ){
						$search_form->advanced_search_form_fields_template();
					}
					else {
						if ($search_form->has_more_filters_button) { ?>
							<div class="<?php Helper::search_filter_class( $search_form->more_filters_display ); ?>">
								<?php $search_form->advanced_search_form_fields_template();?>
							</div>
							<?php
						}
					}
					?>

				</div>

			</div>

		</form>

		<?php do_action('directorist_search_listing_after_search_bar'); ?>

		<?php $search_form->top_categories_template(); ?>

	</div>

</div>