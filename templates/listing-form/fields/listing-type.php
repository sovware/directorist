<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( is_admin() || ! empty( $data['value'] ) ) {
    return;
}

$field_type     = ! empty( $data['type'] ) ? $data['type'] : 'radio';
$general_label  = ! empty( $data['general_label'] ) ? $data['general_label'] : __( 'General', 'directorist' );
$featured_label = ! empty( $data['featured_label'] ) ? $data['featured_label'] : __( 'Featured', 'directorist' );

// Get conditional logic attributes using centralized method
$conditional_logic_attr = $listing_form->get_conditional_logic_attributes( $data );
?>

<div class="directorist-form-group directorist-form-listing-type"<?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?>>

    <?php $listing_form->field_label_template( $data );?>

    <?php if ( 'checkbox' === $field_type && empty( $data['general_label'] ) ) : ?>
        <div class="directorist-form-listing-type__single directorist-checkbox">

            <input id="directorist-form-listing-type__featured" type="checkbox" class="atbdp_radio_input" name="listing_type" value="featured">
            <label for="directorist-form-listing-type__featured" class="directorist-form-listing-type__featured directorist-checkbox__label">
                <?php echo esc_html( $featured_label ); ?>
            </label>

        </div>
    <?php else : ?>
    <div class="directorist-form-listing-type__single directorist-radio directorist-radio-circle">

        <input id="directorist-form-listing-type__general" type="radio" class="atbdp_radio_input" name="listing_type" value="general" checked>
        <label for="directorist-form-listing-type__general" class="directorist-form-listing-type__general directorist-radio__label"><?php echo esc_html( $general_label ); ?></label>

    </div>

    <div class="directorist-form-listing-type__single directorist-radio directorist-radio-circle">

        <input id="directorist-form-listing-type__featured" type="radio" class="atbdp_radio_input" name="listing_type" value="featured">
        <label for="directorist-form-listing-type__featured" class="directorist-form-listing-type__featured directorist-radio__label">
            <?php echo esc_html( $featured_label ); ?>
            <small class="atbdp_make_str_green"><?php echo esc_html( ! empty( $data['featured_description'] ) ? $data['featured_description'] : __( 'Promote your listing to the top of search results and listings pages for a specific duration, with an additional payment.', 'directorist' ) ); ?></small>
        </label>

    </div>
    <?php endif; ?>

</div>
