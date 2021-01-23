<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-check form-group directorist-hide-owner-field">
	<input type="checkbox" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="form-check-input" <?php checked($data['value'], 'on'); ?> >
	<label class="form-check-label" for="<?php echo esc_attr( $data['field_key'] ); ?>"><?php echo esc_html( $data['label'] ); ?></label>
</div>