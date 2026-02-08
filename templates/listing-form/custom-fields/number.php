<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
$data_min  = $data['min_value'] ?? '';
$data_max  = $data['max_value'] ?? '';
$data_step = max( 0, floatval( $data['step'] ?? 1 ) );

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

<div class="directorist-form-group directorist-custom-field-number"<?php echo $conditional_logic_attr; ?>>

    <?php $listing_form->field_label_template( $data );?>

    <div class="directorist-form-group__with-prefix">

        <?php if ( ! empty( $data['prepend'] ) ) : ?>
            <span class="directorist-form-group__prefix directorist-form-group__prefix--start"><?php echo esc_html( $data['prepend'] ); ?></span>
        <?php endif; ?>

        <input type="number" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" value="<?php echo esc_attr( $data['value'] ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" step="<?php echo esc_attr( $data_step ); ?>" min="<?php echo esc_attr( $data_min ); ?>" max="<?php echo esc_attr( $data_max ); ?>" <?php $listing_form->required( $data ); ?>>

        <?php if ( ! empty( $data['append'] ) ) : ?>
            <span class="directorist-form-group__prefix directorist-form-group__prefix--end"><?php echo esc_html( $data['append'] ); ?></span>
        <?php endif; ?>

    </div>

    <?php $listing_form->field_description_template( $data ); ?>

</div>