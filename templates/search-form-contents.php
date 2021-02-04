<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="" style="<?php echo $searchform->background_img_style(); ?>">

	<div class="<?php Helper::directorist_container_fluid(); ?>">

		<?php if ( $searchform->show_title_subtitle && ( $searchform->search_bar_title || $searchform->search_bar_sub_title ) ): ?>

			<div class="atbd_search_title_area">

				<?php if ( $searchform->search_bar_title ): ?>
					<h2 class="title"><?php echo esc_html( $searchform->search_bar_title ); ?></h2>
				<?php endif; ?>

				<?php if ( $searchform->search_bar_sub_title ): ?>
					<p class="sub_title"><?php echo esc_html( $searchform->search_bar_sub_title ); ?></p>
				<?php endif; ?>

			</div>

		<?php endif; ?>

		<form action="<?php echo esc_url( ATBDP_Permalink::get_search_result_page_link() ); ?>" class="atbd_ads-form">

			<div class="atbd_seach_fields_wrapper <?php echo esc_attr( $searchform->border_class() ); ?>">

				<?php $searchform->listing_type_template(); ?>

				<input type="hidden" name="directory_type" id="listing_type" value="<?php echo esc_attr( $searchform->listing_type_slug() ); ?>">

				<div class="atbdp-whole-search-form">

					<div class="row atbdp-search-form atbdp-search-form-inline">

						<?php
						foreach ( $searchform->form_data[0]['fields'] as $field ){
							$searchform->field_template( $field );
						}
						if ( $searchform->more_filters_display !== 'always_open' ){
							$searchform->more_buttons_template();
						}
						?>

					</div>

					<?php
					if ( $searchform->more_filters_display == 'always_open' ){
						$searchform->advanced_search_form_fields_template();
					}
					else {
						if ($searchform->has_more_filters_button) { ?>
							<div class="<?php echo ('overlapping' === $searchform->more_filters_display ) ? 'ads_float' : 'ads_slide' ?>">
								<?php $searchform->advanced_search_form_fields_template();?>
							</div>
							<?php
						}
					}
					?>
					
				</div>

			</div>

		</form>

		<?php $searchform->top_categories_template(); ?>

	</div>
</div>