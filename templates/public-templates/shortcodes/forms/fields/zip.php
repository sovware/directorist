<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group" id="directorist-zip-field">
	<?php if ( ! empty( $label ) ) : ?>
		<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo esc_html( $label ); ?>:<?php echo ! empty( $required ) ? Directorist_Listing_Forms::instance()->add_listing_required_html() : ''; ?></label>
	<?php endif; ?>

	<input type="text" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" class="form-control" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" <?php echo ! empty( $required ) ? 'required="required"' : ''; ?> >

</div>
