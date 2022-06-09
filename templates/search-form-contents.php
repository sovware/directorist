<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.2.2
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$search_form = directorist()->search_form;
?>

<div class="directorist-search-contents" data-atts='<?php echo esc_attr( $search_form->get_atts_data() ); ?>' style="<?php echo $search_form->background_img_style(); ?>">

	<div class="<?php Helper::directorist_container_fluid(); ?>">

		<?php do_action('directorist_search_listing_before_title'); ?>

		<?php if ( $search_form->display_title_area() ): ?>

			<div class="directorist-search-top">

				<?php if ( $search_form->search_bar_title_label() ): ?>
					<h2 class="directorist-search-top__title"><?php echo esc_html( $search_form->search_bar_title_label() ); ?></h2>
				<?php endif; ?>

				<?php if ( $search_form->search_bar_subtitle_label() ): ?>
					<p class="directorist-search-top__subtitle"><?php echo esc_html( $search_form->search_bar_subtitle_label() ); ?></p>
				<?php endif; ?>

			</div>

		<?php endif; ?>

		<form action="<?php echo esc_url( ATBDP_Permalink::get_search_result_page_link() ); ?>" class="directorist-search-form" data-atts="<?php echo esc_attr( $searchform->get_atts_data() ); ?>">

			<div class="directorist-search-form-wrap <?php echo esc_attr( $search_form->border_class() ); ?>">

				<?php $search_form->directory_type_nav_template(); ?>

				<input type="hidden" name="directory_type" class="listing_type" value="<?php echo esc_attr( $search_form->listing_type_slug() ); ?>">

				<?php Helper::get_template( 'search-form/form-box' ); ?>

			</div>

		</form>

		<?php do_action('directorist_search_listing_after_search_bar'); ?>

		<?php $search_form->top_categories_template(); ?>

	</div>

</div>