<?php
/**
 * Comment from markup class.
 *
 * @package Directorist\Review
 * @since 7.1.0
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Markup {

	public static function get_rating( $selected = 0 ) {
		ob_start();
		?>
		<div class="directorist-review-criteria__single">
			<label class="directorist-review-criteria__single__label"><?php esc_html_e( 'Rating', 'directorist' ); ?> <span class="required">*</span></label>
			<select required="required" name="rating" class="directorist-review-criteria-select">
				<option value=""><?php esc_html_e( 'Rate...', 'directorist' ); ?></option>
				<option <?php selected( $selected, '1' ); ?> value="1"><?php esc_html_e( 'Very poor', 'directorist' ); ?></option>
				<option <?php selected( $selected, '2' ); ?> value="2"><?php esc_html_e( 'Not that bad', 'directorist' ); ?></option>
				<option <?php selected( $selected, '3' ); ?> value="3"><?php esc_html_e( 'Average', 'directorist' ); ?></option>
				<option <?php selected( $selected, '4' ); ?> value="4"><?php esc_html_e( 'Good', 'directorist' ); ?></option>
				<option <?php selected( $selected, '5' ); ?> value="5"><?php esc_html_e( 'Perfect', 'directorist' ); ?></option>
			</select>
		</div>
		<?php
		$html = ob_get_clean();

		return apply_filters( 'directorist/review/rating_html', $html );
	}

	public static function get_rating_stars( $rating = 0, $base_rating = 5 ) {
		$rating = max( 0, min( $base_rating, $rating ) );

		if ( is_float( $rating ) ) {
			$rating = floor( $rating );
		}

		return str_repeat( '<i class="fas fa-star"></i>', $rating ) . str_repeat( '<i class="far fa-star"></i>', ( $base_rating - $rating ) );
	}

	public static function show_rating_stars( $rating = 0, $base_rating = 5 ) {
		echo self::get_rating_stars( $rating, $base_rating );
	}
}
