<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$conditional_logic_attr = $searchform->get_conditional_logic_attributes( $data );
$field_key              = ! empty( $data['field_key'] ) ? $data['field_key'] : ( ! empty( $data['widget_name'] ) ? $data['widget_name'] : 'zip' );
$lat                    = ! empty( $_REQUEST['zip_cityLat'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['zip_cityLat'] ) ) : '';
$lng                    = ! empty( $_REQUEST['zip_cityLng'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['zip_cityLng'] ) ) : '';
?>

<div class="directorist-search-field directorist-form-group directorist-zipcode-search <?php echo esc_attr( $empty_label ); ?>"<?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?>>

    <?php if ( ! empty( $data['label'] ) ) : ?>
        <label class="directorist-search-field__label" for="<?php echo esc_attr( $field_key ); ?>"><?php echo esc_attr( $data['label'] ); ?></label>
    <?php endif; ?>

    <input class="<?php echo esc_attr( $searchform->zip_code_class() ); ?> directorist-search-field__input" id="<?php echo esc_attr( $field_key ); ?>" type="text" name="<?php echo esc_attr( $field_key ); ?>" value="<?php echo esc_attr( $value ); ?>" autocomplete="off" placeholder="<?php echo esc_attr( $data['placeholder'] ?? '' ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>

    <div class="directorist-country directorist-search-country" style="display: none"></div>
    <input type="hidden" class="zip-cityLat" name="zip_cityLat" value="<?php echo esc_attr( $lat ) ?>" />
    <input type="hidden" class="zip-cityLng" name="zip_cityLng" value="<?php echo esc_attr( $lng ) ?>" />

    <div class="directorist-search-field__btn directorist-search-field__btn--clear">
        <?php directorist_icon( 'fas fa-times-circle' ); ?> 
    </div>
    
</div>