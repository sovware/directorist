<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

global $post;
$id = $post->ID ? $post->ID : '';
?>

<div class="form-group" id="directorist-color-field">
	<?php if ( ! empty( $label ) ) : ?>
		<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo esc_html( $label ); ?>:<?php echo ! empty( $required ) ? Directorist_Listing_Forms::instance()->add_listing_required_html() : ''; ?></label>
	<?php endif; ?>
	<script>
		jQuery(document).ready(function ($) {
			$('.my-color-field2').wpColorPicker().empty();
		});
	</script>
	<input type="color" name="custom_field[<?php echo esc_attr( $id ); ?>] <?php echo esc_attr( $field_key ); ?>" class="my-color-field2 form-control directory_field" value="<?php echo esc_attr( $value ); ?>" <?php echo ! empty( $required ) ? 'required="required"' : ''; ?> >
	<p> <?php echo esc_attr( $description ); ?> </p>
</div>
