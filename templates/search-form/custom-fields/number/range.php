<?php
/**
 * @author  wpWax
 * @since   8.0
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$min_val   = $data['options']['min_value'] ?? 1;
$max_value = $data['options']['max_value'] ?? 100;
?>

<div class="directorist-search-field directorist-custom-range-slider">
    <div id="directorist-custom-range-slider__slide" step="<?php echo esc_attr( ! empty( $data['options']['step'] ) ? $data['options']['step'] : 1 ); ?>"></div>
    <div class="directorist-custom-range-slider__wrap">
        <div class="directorist-custom-range-slider__value">
            <input type="number" placeholder="Min" value="<?php echo esc_attr( $min_val ) ?>" name="directorist-custom-range-slider__value__min" id="directorist-custom-range-slider__value__min" class="directorist-custom-range-slider__value__min">
        </div>
        <div class="directorist-custom-range-slider__value">
            <input type="number" placeholder="Max" value="<?php echo esc_attr( $max_value ) ?>" name="directorist-custom-range-slider__value__max" id="directorist-custom-range-slider__value__max" class="directorist-custom-range-slider__value__max">
        </div>
        <input type="hidden" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]" id="directorist-custom-range-slider__range" value="<?php echo esc_attr( $min_val ) ?>-<?php echo esc_attr( $max_value ) ?>">
    </div>

</div>