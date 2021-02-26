<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-contents" style="<?php echo $searchform->background_img_style(); ?>">

	<div class="<?php Helper::directorist_container_fluid(); ?>">

		<?php do_action('directorist_search_listing_before_title'); ?>

		<?php if ( $searchform->show_title_subtitle && ( $searchform->search_bar_title || $searchform->search_bar_sub_title ) ): ?>

			<div class="directorist-search-top">

				<?php if ( $searchform->search_bar_title ): ?>
					<h2 class="directorist-search-top__title"><?php echo esc_html( $searchform->search_bar_title ); ?></h2>
				<?php endif; ?>

				<?php if ( $searchform->search_bar_sub_title ): ?>
					<p class="directorist-search-top__subtitle"><?php echo esc_html( $searchform->search_bar_sub_title ); ?></p>
				<?php endif; ?>
				
			</div>

		<?php endif; ?>

		<form action="<?php echo esc_url( ATBDP_Permalink::get_search_result_page_link() ); ?>" class="directorist-search-form">

			<div class="directorist-search-form-wrap <?php echo esc_attr( $searchform->border_class() ); ?>">

				<?php $searchform->directory_type_nav_template(); ?>

				<input type="hidden" name="directory_type" id="listing_type" value="<?php echo esc_attr( $searchform->listing_type_slug() ); ?>">

				<div class="directorist-search-form-box">

					<div class="directorist-search-form-top directorist-flex directorist-align-center directorist-search-form-inline">

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
							<div class="<?php Helper::search_filter_class( $searchform->more_filters_display ); ?>">
								<?php $searchform->advanced_search_form_fields_template();?>
							</div>
							<?php
						}
					}
					?>

				</div>

			</div>

		</form>

		<?php do_action('directorist_search_listing_after_search_bar'); ?>

		<?php $searchform->top_categories_template(); ?>
		
	</div>
	
</div>