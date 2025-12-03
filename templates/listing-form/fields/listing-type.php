<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( is_admin() || $data['value'] ) {
    return;
}

// Get conditional logic data attributes using helper method
$conditional_logic_attr = $listing_form->get_conditional_logic_attributes( $data );

// Additional fallback check - try multiple locations for conditional logic data
if ( empty( $conditional_logic_attr ) ) {
    $conditional_logic = null;
    
    // Check conditional_logic_data first
    if ( ! empty( $data['conditional_logic_data'] ) ) {
        $conditional_logic = is_string( $data['conditional_logic_data'] ) 
            ? json_decode( $data['conditional_logic_data'], true ) 
            : $data['conditional_logic_data'];
    }
    // Check options.conditional_logic
    elseif ( ! empty( $data['options']['conditional_logic'] ) ) {
        $conditional_logic = $data['options']['conditional_logic'];
    }
    // Check direct conditional_logic key
    elseif ( ! empty( $data['conditional_logic'] ) ) {
        $conditional_logic = $data['conditional_logic'];
    }
    
    // If we found conditional logic and it's enabled, create attributes
    if ( ! empty( $conditional_logic ) && is_array( $conditional_logic ) && ! empty( $conditional_logic['enabled'] ) ) {
        $conditional_logic_attr = ' data-conditional-logic="' . esc_attr( wp_json_encode( $conditional_logic ) ) . '"';
        $conditional_logic_attr .= ' data-field-key="' . esc_attr( $data['field_key'] ?? '' ) . '"';
    }
}
?>

<div class="directorist-form-group directorist-form-listing-type"<?php echo $conditional_logic_attr; ?>>

    <?php $listing_form->field_label_template( $data );?>

    <div class="directorist-form-listing-type__single directorist-radio directorist-radio-circle">

        <input id="directorist-form-listing-type__general" type="radio" class="atbdp_radio_input" name="listing_type" value="general" checked>
        <label for="directorist-form-listing-type__general" class="directorist-form-listing-type__general directorist-radio__label"><?php echo esc_attr( $data['general_label'] ); ?></label>

    </div>

    <div class="directorist-form-listing-type__single directorist-radio directorist-radio-circle">

        <input id="directorist-form-listing-type__featured" type="radio" class="atbdp_radio_input" name="listing_type" value="featured">
        <label for="directorist-form-listing-type__featured" class="directorist-form-listing-type__featured directorist-radio__label">
            <?php echo esc_html( $data['featured_label'] ); ?>
            <small class="atbdp_make_str_green"><?php echo esc_html( ! empty( $data['featured_description'] ) ? $data['featured_description'] : __( 'Promote your listing to the top of search results and listings pages for a specific duration, with an additional payment.', 'directorist' ) ); ?></small>
        </label>

    </div>

</div>