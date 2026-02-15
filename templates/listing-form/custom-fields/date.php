<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Get conditional logic attributes using centralized method
$conditional_logic_attr = $listing_form->get_conditional_logic_attributes( $data );
?>

<div class="directorist-form-group directorist-custom-filed-date"<?php echo $conditional_logic_attr; ?>>

    <?php $listing_form->field_label_template( $data );?>

    <input type="date" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" value="<?php echo esc_attr( $data['value'] ); ?>" <?php $listing_form->required( $data ); ?>>

    <?php $listing_form->field_description_template( $data );?>

</div>