<?php
/**
 * Comment from markup class.
 *
 * @package Directorist\Review
 * @since 7.7.0
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Markup {

	public static function get_rating( $selected = 0, $comment = null ) {
		ob_start();
		?>
		<div class="directorist-review-criteria__single">
			<label class="directorist-review-criteria__single__label"><?php esc_html_e( 'Rating', 'directorist' ); ?> <span class="required">*</span></label>
			<select required="required" name="rating" class="directorist-review-criteria-select">
				<option value=""><?php esc_html_e( 'Rate...', 'directorist' ); ?></option>
				<option <?php selected( $selected, '1' ); ?> value="1"><?php esc_html_e( '1', 'directorist' ); ?></option>
				<option <?php selected( $selected, '2' ); ?> value="2"><?php esc_html_e( '2', 'directorist' ); ?></option>
				<option <?php selected( $selected, '3' ); ?> value="3"><?php esc_html_e( '3', 'directorist' ); ?></option>
				<option <?php selected( $selected, '4' ); ?> value="4"><?php esc_html_e( '4', 'directorist' ); ?></option>
				<option <?php selected( $selected, '5' ); ?> value="5"><?php esc_html_e( '5', 'directorist' ); ?></option>
			</select>
		</div>
		<?php
		$html = ob_get_clean();

		return apply_filters( 'directorist/review/rating_html', $html, $comment );
	}

	private static function convert_to_num( $input ) {
		
		$numericValue = 0;
		if ( strpos( $input, '.' ) !== false ) {
			$numericValue = floatval( $input );
		} else {
			$numericValue = intval( $input );
		}
		return $numericValue;
	}

	public static function get_rating_stars( $rating = 0, $base_rating = 5 ) {
		$rating = max( 0, min( $base_rating, $rating ) );

		$empty_star = '';
		$full_star = '';
		$counter = 0;
		$rating = self::convert_to_num( $rating );
		$rounded_rating = floor( $rating );

		$full_star = str_repeat( directorist_icon( 'fas fa-star', false, 'star-full' ), $rounded_rating );

		while( $counter <= $base_rating - ( $rounded_rating + 1 ) ) {
			$empty_star .= directorist_icon( 'far fa-star', false, ( ( $counter === 0 ) && ( is_float( $rating  ) ) ) ? 'star-empty directorist_fraction_star' : 'star-empty' );
			$counter++;
		}
		
		return $full_star . $empty_star;
	}

	public static function show_rating_stars( $rating = 0, $base_rating = 5 ) {
		echo wp_kses_post( self::get_rating_stars( $rating, $base_rating ) );
	}
}
