<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group" class="directorist-excerpt-field">
	<?php $form->add_listing_label_template( $data );?>

	<textarea name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="form-control" cols="30" rows="5" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>"><?php echo esc_textarea( $data['value'] ); ?></textarea>
	<input type="hidden" id="has_excerpt" value="<?php echo esc_attr( $excerpt ); ?>">

	<?php $form->add_listing_description_template( $data ); ?>
</div>