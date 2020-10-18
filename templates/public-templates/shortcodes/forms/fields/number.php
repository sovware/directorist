<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

global $post;
$id = $post->ID ? $post->ID : '';
?>
<div class="form-group" id="directorist-number-field">
	<?php if ( ! empty( $label ) ) : ?>
		<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo esc_html( $label ); ?>:<?php echo ! empty( $required ) ? Directorist_Listing_Forms::instance()->add_listing_required_html() : ''; ?></label>
	<?php endif; ?>

	<input type="number" name="custom_field[<?php echo esc_attr( $id ); ?>] <?php echo esc_attr( $field_key ); ?>" class="form-control directory_field" placeholder="<?php echo esc_attr( $placeholder ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php echo ! empty( $required ) ? 'required="required"' : ''; ?> >

	<p> <?php echo esc_attr( $description ); ?> </p>
</div>
