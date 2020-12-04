<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
if ( !empty($data['label']) ): ?>
	<label><?php echo esc_html( $data['label'] ); ?></label>
<?php endif; ?>

<div class="search-form-field">
    <textarea name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="form-control" rows="<?php echo (int) $data['rows']; ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> ></textarea>
</div>