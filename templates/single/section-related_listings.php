<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$related = $listing->get_related_listings( $section_data );

if ( !$related->have_posts() ) {
	return;
}
?>

<section class="directorist-related-listing <?php echo esc_attr( $class );?>" <?php $listing->section_id( $id ); ?>>

	<div class="directorist-related-listing-header">

		<h3 class="directorist-related-listing-header__title"><?php echo esc_html( $label );?></h3>

	</div>

	<div class='directorist-swiper directorist-swiper-related-listing' data-sw-items='3' data-sw-margin='30' data-sw-loop='true' data-sw-perslide='1' data-sw-speed='1000' data-sw-autoplay='true' data-sw-responsive='{
		"0": {"slidesPerView": "1"},
		"768": {"slidesPerView": "2"},
		"1200": {"slidesPerView": "<?php echo esc_attr( $section_data['similar_listings_number_of_columns'] ?? 3 ); ?>"}
	}'>
		<div class='swiper-wrapper'>
			<?php foreach ( $related->post_ids() as $listing_id ): ?>
				<div class='swiper-slide'>
					<?php $related->loop_template( 'grid', $listing_id ); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<div class='directorist-swiper__navigation'>
			<div class='directorist-swiper__nav directorist-swiper__nav--prev directorist-swiper__nav--prev-related'><?php directorist_icon('las la-angle-left')?></div>
			<div class='directorist-swiper__nav directorist-swiper__nav--next directorist-swiper__nav--next-related'><?php directorist_icon('las la-angle-right')?></div>
		</div>

		<div class='directorist-swiper__pagination directorist-swiper__pagination--related'></div>
	</div>

</section>