<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

?>

<div class="form-group" id="directorist-select-field">
	<?php if ( ! empty( $label ) ) : ?>
		<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo esc_html( $label ); ?>:<?php echo ! empty( $required ) ? Directorist_Listing_Forms::instance()->add_listing_required_html() : ''; ?></label>
	<?php endif; ?>

	<select name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" class="form-control" <?php echo ! empty( $required ) ? 'required="required"' : ''; ?>>
		<option value="volvo">Volvo</option>
		<option value="saab">Saab</option>
		<option value="mercedes">Mercedes</option>
		<option value="audi">Audi</option>
	</select>

	<p> <?php echo esc_attr( $description ); ?> </p>
</div>
