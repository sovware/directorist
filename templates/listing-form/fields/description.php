<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$maxlength = $data['max'] ?? '';

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

<div class="directorist-form-group directorist-form-description-field"<?php echo $conditional_logic_attr; ?>>

    <?php
    $listing_form->field_label_template( $data );

    if ( 'textarea' === $data['type'] ) {
        ?>
        <textarea name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" rows="8" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" maxlength="<?php echo esc_attr( $maxlength ); ?>" <?php $listing_form->required( $data ); ?>><?php echo esc_html( $data['value'] ); ?></textarea>
        <?php
    } else {
        wp_editor(
            wp_kses_post( $data['value'] ),
            $data['field_key'],
            apply_filters(
                'atbdp_add_listing_wp_editor_settings',
                [
                    'media_buttons' => false,
                    'quicktags'     => true,
                    'editor_height' => 200,
                    'tinymce'       => array(
                        'plugins'    => 'lists,link,wordpress,paste,textcolor,fullscreen,hr',
                    ),
                ]
            )
        );
    }

    $listing_form->field_description_template( $data );
    ?>

    <div id="directorist_listing_description_indicator"></div>

</div>