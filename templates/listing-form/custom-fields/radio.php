<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="form-group directorist-radio-field">

	<?php $listing_form->field_label_template( $data );?>

	<div class="form-control">

		<?php if( !empty( $data['options'] ) ) : ?>

			<?php foreach ( $data['options'] as $option ): ?>

				<?php $uniqid = $option['option_value'] . '-' .wp_rand();  ?>
				
				<div>
					<input type="radio" id="<?php echo esc_attr( $uniqid ); ?>" name="<?php echo esc_attr( $data['field_key'] ); ?>" value="<?php echo esc_attr( $option['option_value'] ); ?>" <?php checked( $option['option_value'], $data['value'] ); ?>>
					<label for="<?php echo esc_attr( $uniqid ); ?>"><?php echo esc_html( $option['option_label'] ); ?></label>
				</div>

			<?php endforeach; ?>

		<?php endif; ?>

	</div>

	<?php $listing_form->field_description_template( $data ); ?>
</div>
