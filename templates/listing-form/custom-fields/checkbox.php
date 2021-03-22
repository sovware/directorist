<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="directorist-form-group directorist-custom-field-checkbox">

	<?php $listing_form->field_label_template( $data );?>
		<?php if( !empty( $data['options'] ) ) : ?>

			<?php foreach ( $data['options'] as $option ): ?>

				<?php $uniqid = $option['option_value'] . '-' .wp_rand();  ?>

				<div class="directorist-checkbox directorist-mb-10">
					<input type="checkbox" id="<?php echo esc_attr( $uniqid ); ?>" name="<?php echo esc_attr( $data['field_key'] ); ?>[]" value="<?php echo esc_attr( $option['option_value'] ); ?>" <?php echo in_array( $option['option_value'], $data['value'] ) ? 'checked="checked"' : '' ; ?>>
					<label for="<?php echo esc_attr( $uniqid ); ?>" class="directorist-checkbox__label"><?php echo esc_html( $option['option_label'] ); ?></label>
				</div>

			<?php endforeach; ?>

			<a href="#" class="directorist-custom-field-btn-more"><?php esc_html_e( 'See More', 'directorist' ); ?></a>

		<?php endif; ?>
	<?php $listing_form->field_description_template( $data ); ?>

</div>
