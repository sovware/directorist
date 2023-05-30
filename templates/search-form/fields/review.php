<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-field">
	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>
	

	<div class="directorist-checkbox directorist-checkbox-primary">
		<?php foreach ( $searchform->rating_field_data() as $option ) { 
			$uniqid = $option['value'] . '_' .wp_rand();
		?>
			<input type="checkbox" name="search_by_rating_<?php echo esc_attr( $option['value'] ); ?>" value="<?php echo esc_attr( $option['value'] ); ?>" id="<?php echo esc_attr( $uniqid ); ?>" <?php echo esc_attr( $option['checked'] ); ?>>
			<label for="<?php echo esc_attr( $uniqid ); ?>" class="directorist-checkbox__label">
				<?php 
					directorist_icon( 'fas fa-star', true, 'star-empty' );
					directorist_icon( 'fas fa-star', true, 'star-empty' );
					directorist_icon( 'fas fa-star', true, 'star-empty' );
					directorist_icon( 'fas fa-star', true, 'star-empty' );
					directorist_icon( 'fas fa-star', true, 'star-empty' );

					// // Rating
					// $rating           = $option['value'];

					// // Icons
					// $icon_empty_star = directorist_icon( 'fas fa-star', false, 'star-empty' );
					// $icon_full_star  = directorist_icon( 'fas fa-star', false, 'star-full' );

					// // Stars
					// $star_1 = ( $rating >= 1) ? $icon_full_star : $icon_empty_star;

					// $star_2 = ( $rating >= 2) ? $icon_full_star : $icon_empty_star;

					// $star_3 = ( $rating >= 3) ? $icon_full_star : $icon_empty_star;

					// $star_4 = ( $rating >= 4) ? $icon_full_star : $icon_empty_star;

					// $star_5 = ( $rating >= 5 ) ? $icon_full_star : $icon_empty_star;

					// $review_stars = "{$star_1}{$star_2}{$star_3}{$star_4}{$star_5}";

					// echo $review_stars;
				?>
			</label>
		<?php } ?>
	</div>
</div>