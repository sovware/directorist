<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

global $post;
$id = $post->ID ? $post->ID : '';
?>

<div class="form-group" id="directorist-textarea-field">
	<?php if ( ! empty( $label ) ) : ?>
		<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo esc_html( $label ); ?>:<?php echo ! empty( $required ) ? Directorist_Listing_Forms::instance()->add_listing_required_html() : ''; ?></label>
	<?php endif; ?>

	<textarea  class="form-control directory_field textarea" name="custom_field[<?php echo esc_attr( $id ); ?>] <?php echo esc_attr( $field_key ); ?>" rows="<?php echo (int) esc_attr( $rows ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" <?php echo ! empty( $required ) ? 'required="required"' : ''; ?> > <?php echo esc_attr( $value ); ?> </textarea>

	<p> <?php echo esc_attr( $description ); ?> </p>
</div>
