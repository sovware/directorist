<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-contents directorist-contents-wrap" data-atts="<?php echo esc_attr( $searchform->get_atts_data() ); ?>">

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

		<form action="<?php echo esc_url( ATBDP_Permalink::get_search_result_page_link() ); ?>" class="directorist-search-form" data-atts="<?php echo esc_attr( $searchform->get_atts_data() ); ?>">

			<div class="directorist-search-form-wrap directorist-search-form__wrap <?php echo esc_attr( $searchform->border_class() ); ?>">

				<?php $searchform->directory_type_nav_template(); ?>

				<input type="hidden" name="directory_type" class="listing_type" value="<?php echo esc_attr( $searchform->listing_type_slug() ); ?>">

				<?php Helper::get_template( 'search-form/form-box', ['searchform' =>  $searchform] ); ?>

			</div>

		</form>

		<?php do_action('directorist_search_listing_after_search_bar'); ?>

		<?php $searchform->top_categories_template(); ?>

	</div>

</div>