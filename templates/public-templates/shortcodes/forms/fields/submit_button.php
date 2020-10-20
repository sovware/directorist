<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group" id="directorist-submit_button-field">
	<?php if ( ! empty( $label ) ) : ?>
		<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo esc_html( $label ); ?>:<?php echo ! empty( $required ) ? Directorist_Listing_Forms::instance()->add_listing_required_html() : ''; ?></label>
	<?php endif; ?>

	<div class="btn_wrap list_submit">
		<button type="submit" class="btn btn-primary btn-lg listing_submit_btn"><?php echo ! empty( get_query_var('atbdp_listing_id', 0) ) ? esc_html__( 'Preview Changes', 'directorist' ) : $label; ?></button>
	</div>

</div>
