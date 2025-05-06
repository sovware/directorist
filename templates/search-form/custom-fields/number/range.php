<?php
/**
 * @author  wpWax
 * @since   8.0
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$min_value = !empty($data['options']['min_value']) ? $data['options']['min_value'] : 1;
$max_value = !empty($data['options']['max_value']) ? $data['options']['max_value'] : 100;

$default_min_value = $min_value;
$default_max_value = $max_value;


if (!empty($value) && strpos($value, '-') !== false) {
    list($min_value, $max_value) = explode('-', $value);
} 
?>

<div class="directorist-search-field directorist-search-field-text_range">
    <?php if ( !empty($data['label']) ): ?>
        <label class="directorist-search-field__label"><?php echo esc_html( $data['label'] ); ?></label>
    <?php endif; ?>
    <div class="directorist-custom-range-slider">
        <div class="directorist-custom-range-slider__slide" step="<?php echo esc_attr( ! empty( $data['options']['step'] ) ? absint( $data['options']['step'] ) : 1 ); ?>" aria-label="Range" min-value="<?php echo esc_attr($default_min_value); ?>" max-value="<?php echo esc_attr($default_max_value); ?>"></div>
        <div class="directorist-custom-range-slider__wrap">
            <div class="directorist-custom-range-slider__value">
                <label for="directorist-custom-range-slider__value__min__number" class="directorist-custom-range-slider__label"><?php esc_html_e( 'Min', 'directorist' ); ?></label>
                <input type="number" placeholder="Min" value="<?php echo esc_attr( $min_value ) ?>" name="directorist-custom-range-slider__value__min" id="directorist-custom-range-slider__value__min__number" class="directorist-custom-range-slider__text directorist-custom-range-slider__value__min">
            </div>
            <div class="directorist-custom-range-slider__value">
                <label for="directorist-custom-range-slider__value__max__number" class="directorist-custom-range-slider__label"><?php esc_html_e( 'Max', 'directorist' ); ?></label>
                <input type="number" placeholder="Max" value="<?php echo esc_attr( $max_value ) ?>" name="directorist-custom-range-slider__value__max" id="directorist-custom-range-slider__text directorist-custom-range-slider__value__max__number" class="directorist-custom-range-slider__value__max">
            </div>
            <input type="hidden" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]" class="directorist-custom-range-slider__range" value="<?php echo esc_attr( $min_value ) ?>-<?php echo esc_attr( $max_value ) ?>">
        </div>
    </div>

</div>