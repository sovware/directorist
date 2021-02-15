<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$max = !empty( $data['max'] ) ? 'max="'. esc_attr( $data['max'] ) .'"' : '';
?>

<div class="form-group directorist-textarea-field">

	<?php $listing_form->field_label_template( $data );?>

	<textarea <?php echo $max; ?> name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="form-control" rows="<?php echo (int) $data['rows']; ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php $listing_form->required( $data ); ?>><?php echo wp_kses_post( $data['value'] ); ?></textarea>

	<?php $listing_form->field_description_template( $data );?>

</div>