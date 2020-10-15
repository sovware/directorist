<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<div class="form-group" id="atbdp_listing_title">
	<?php if (!empty($label)): ?>
		<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo esc_html( $label )?>:<?php echo !empty($required) ? Directorist_Listing_Forms::instance()->add_listing_required_html() : '';?></label>
	<?php endif; ?>
    <input type="text" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" value="<?php echo esc_attr( $value ); ?>" class="form-control directory_field" placeholder="<?php echo esc_attr( $placeholder ); ?>" <?php echo !empty($required) ? 'required="required"' : ''; ?>>
</div>