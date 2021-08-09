<?php
/**
 * Comment from markup class.
 *
 * @package wpWax\Directorist
 * @subpackage Review
 * @since 7.x
 */
namespace wpWax\Directorist\Review;

defined( 'ABSPATH' ) || die();

class Markup {

	public static function get_rating( Builder $builder ) {
		ob_start();
		if ( $builder->is_rating_type_criteria() ) :

			foreach ( $builder->get_rating_criteria() as $key => $label ) :
				$name     = 'rating[' . $key . ']';
				$selected = isset( $_REQUEST['rating'], $_REQUEST['rating'][ $key ] ) ? $_REQUEST['rating'][ $key ] : '';
				?>
				<div class="directorist-review-criteria__single">
					<label class="directorist-review-criteria__single__label"><?php echo esc_html( $label ); ?></label>
					<select required="required" name="<?php echo esc_attr( $name ); ?>" class="directorist-review-criteria-select">
						<option value=""><?php esc_html_e( 'Rate...', 'directorist' ); ?></option>
						<option <?php selected( $selected, '1' ); ?> value="1"><?php esc_html_e( 'Very poor', 'directorist' ); ?></option>
						<option <?php selected( $selected, '2' ); ?> value="2"><?php esc_html_e( 'Not that bad', 'directorist' ); ?></option>
						<option <?php selected( $selected, '3' ); ?> value="3"><?php esc_html_e( 'Average', 'directorist' ); ?></option>
						<option <?php selected( $selected, '4' ); ?> value="4"><?php esc_html_e( 'Good', 'directorist' ); ?></option>
						<option <?php selected( $selected, '5' ); ?> value="5"><?php esc_html_e( 'Perfect', 'directorist' ); ?></option>
					</select>
				</div><!-- ends: .directorist-review-criteria__one -->
				<?php
			endforeach;

		else :

			$name     = 'rating';
			$selected = isset( $_REQUEST['rating'] ) ? $_REQUEST['rating'] : '';
			?>
			<div class="directorist-review-criteria__single">
				<label class="directorist-review-criteria__single__label"><?php esc_html_e( 'Rating', 'directorist' ); ?></label>
				<select required="required" name="<?php echo esc_attr( $name ); ?>" class="directorist-review-criteria-select">
					<option value=""><?php esc_html_e( 'Rate...', 'directorist' ); ?></option>
					<option <?php selected( $selected, '1' ); ?> value="1"><?php esc_html_e( 'Very poor', 'directorist' ); ?></option>
					<option <?php selected( $selected, '2' ); ?> value="2"><?php esc_html_e( 'Not that bad', 'directorist' ); ?></option>
					<option <?php selected( $selected, '3' ); ?> value="3"><?php esc_html_e( 'Average', 'directorist' ); ?></option>
					<option <?php selected( $selected, '4' ); ?> value="4"><?php esc_html_e( 'Good', 'directorist' ); ?></option>
					<option <?php selected( $selected, '5' ); ?> value="5"><?php esc_html_e( 'Perfect', 'directorist' ); ?></option>
				</select>
			</div><!-- ends: .directorist-review-criteria__one -->
			<?php

		endif;
		return ob_get_clean();
	}

	public static function get_attachments_uploader( Builder $builder ) {
		$uid = uniqid( 'directorist-' );

		ob_start();
		?>
		<div class="directorist-form-group directorist-review-media-upload">
			<input class="directorist-review-images" type="file" accept="<?php echo implode( ',', $builder->get_accepted_attachments_types() ); ?>" name="review_attachments[]" id="<?php echo $uid; ?>" multiple="multiple">
			<label for="<?php echo $uid; ?>">
				<i class="far fa-image"></i>
				<span><?php esc_html_e( 'Add a photo', 'diretorist' ); ?></span>
			</label>
			<div class="directorist-review-img-gallery"></div>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function get_rating_stars( $rating = 0, $base_rating = 5 ) {
		$rating = max( 0, min( 5, $rating ) );

		if ( is_float( $rating ) ) {
			$rating = floor( $rating );
		}

		return str_repeat( '<i class="fas fa-star"></i>', $rating ) . str_repeat( '<i class="far fa-star"></i>', ( $base_rating - $rating ) );
	}

	public static function show_rating_stars( $rating = 0, $base_rating = 5 ) {
		echo self::get_rating_stars( $rating, $base_rating );
	}
}
