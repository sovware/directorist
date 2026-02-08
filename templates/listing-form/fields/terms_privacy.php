<?php
/**
 * @author  wpWax
 * @since   8.0
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

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
<div class="directorist-add-listing-form__privacy"<?php echo $conditional_logic_attr; ?>>
    <?php
    do_action( 'atbdp_before_terms_and_conditions_font' )
    ?>

    <div class="directorist-form-privacy directorist-form-terms directorist-checkbox">

        <input id="directorist_submit_privacy_policy" type="checkbox" name="privacy_policy" <?php checked( $data['privacy_checked'] ?? '' ); ?>>

        <label for="directorist_submit_privacy_policy" class="directorist-checkbox__label"><?php echo wp_kses_post( strip_tags( $data['text'], '<a><strong><em>' ) ); ?></label>

        <?php if ( $data['required'] ) : ?>
            <span class="directorist-form-required"> *</span>
        <?php endif; ?>

    </div>


</div>