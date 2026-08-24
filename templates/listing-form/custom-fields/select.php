<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.9.4
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Get conditional logic attributes using centralized method
$conditional_logic_attr = $listing_form->get_conditional_logic_attributes( $data );
$options                = ! empty( $data['options'] ) && is_array( $data['options'] ) ? $data['options'] : [];
$has_empty_option       = in_array( '', wp_list_pluck( $options, 'option_value' ), true );
$show_empty_option      = empty( $data['required'] ) && ! $has_empty_option;
$placeholder            = ! empty( $data['placeholder'] ) ? $data['placeholder'] : __( 'Select...', 'directorist' );
?>

<div class="directorist-form-group directorist-custom-field-select"<?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?>>

    <?php $listing_form->field_label_template( $data );?>

    <?php if ( ! empty( $options ) ) : ?>

        <select name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" <?php $listing_form->required( $data ); ?>>

            <?php if ( $show_empty_option ) : ?>

                <option value="" <?php selected( '', $data['value'] ); ?>><?php echo esc_html( $placeholder ); ?></option>

            <?php endif; ?>

            <?php foreach ( $options as $value ) : ?>

                <option value="<?php echo esc_attr( $value['option_value'] )?>" <?php selected( $value['option_value'], $data['value'] ); ?>><?php echo esc_attr( $value['option_label'] )?></option>

            <?php endforeach ?>

        </select>

    <?php endif; ?>

    <?php $listing_form->field_description_template( $data ); ?>

</div>
