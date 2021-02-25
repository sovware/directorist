<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if( is_admin() || $data['value'] ) {
	return;
}
?>

<div class="directorist-form-group directorist-form-listing-type">

	<?php $listing_form->field_label_template( $data );?>

	<div class="directorist-form-listing-type__single directorist-radio directorist-radio-circle">

		<input id="directorist-form-listing-type__general" type="radio" class="atbdp_radio_input" name="listing_type" value="general" checked>
		<label for="directorist-form-listing-type__general" class="directorist-form-listing-type__general directorist-radio__label"><?php echo esc_attr( $data['general_label'] ); ?></label>

	</div>

	<div class="directorist-form-listing-type__single directorist-radio directorist-radio-circle">

		<input id="directorist-form-listing-type__featured" type="radio" class="atbdp_radio_input" name="listing_type" value="featured">
		<label for="directorist-form-listing-type__featured" class="directorist-form-listing-type__featured directorist-radio__label">
			<?php echo esc_attr( $data['featured_label'] ); ?>
			<small class="atbdp_make_str_green"><?php echo esc_html( $listing_form->featured_listing_description() ) ;?></small>
		</label>

	</div>

</div>