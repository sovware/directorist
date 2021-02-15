<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$value = explode( ',', $data['value'] );
?>

<div class="form-group directorist-checkbox-field">

	<?php $listing_form->field_label_template( $data );?>
	
	<div class="form-control">

		<?php if( !empty( $data['options'] ) ) : ?>

			<?php foreach ( $data['options'] as $option ): ?>
				
				<?php $uniqid = $option['option_value'] . '-' .wp_rand();  ?>

				<div>
					<input type="checkbox" id="<?php echo esc_attr( $uniqid ); ?>" name="<?php echo esc_attr( $data['field_key'] ); ?>" value="<?php echo esc_attr( $option['option_value'] ); ?>" <?php echo in_array( $option['option_value'], $value ) ? 'checked="checked"' : '' ; ?>>
					<label for="<?php echo esc_attr( $uniqid ); ?>"><?php echo esc_html( $option['option_label'] ); ?></label>		
				</div>

			<?php endforeach; ?>

		<?php endif; ?>

	</div>

	<?php $listing_form->field_description_template( $data ); ?>

</div>
