<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
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

<div class="directorist-form-group directorist-custom-field-radio"<?php echo $conditional_logic_attr; ?>>

    <?php $listing_form->field_label_template( $data );?>

        <?php if ( ! empty( $data['options'] ) ) : ?>

            <?php foreach ( $data['options'] as $option ) : ?>

                <?php $uniqid = $option['option_value'] . '-' . wp_rand();  ?>

                <div class="directorist-radio directorist-radio-circle directorist-mb-10">
                    <input type="radio" id="<?php echo esc_attr( $uniqid ); ?>" name="<?php echo esc_attr( $data['field_key'] ); ?>" value="<?php echo esc_attr( $option['option_value'] ); ?>" <?php checked( $option['option_value'], $data['value'] ); ?>>
                    <label for="<?php echo esc_attr( $uniqid ); ?>" class="directorist-radio__label"><?php echo esc_html( $option['option_label'] ); ?></label>
                </div>

            <?php endforeach; ?>

            <a href="#" class="directorist-custom-field-btn-more"><?php esc_html_e( 'See More', 'directorist' ); ?></a>

        <?php endif; ?>

    <?php $listing_form->field_description_template( $data ); ?>
    
</div>
