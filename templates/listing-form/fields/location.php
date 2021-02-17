<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$multiple = $data['type'] == 'multiple' ? 'multiple="multiple"' : '';
$max = !empty( $data['max'] ) ? 'max="'. esc_attr( $data['max'] ) .'"' : '';
?>

<div class="directorist-form-group directorist-form-location-field">

	<?php $listing_form->field_label_template( $data ); ?>
	<div class="directorist-select directorist-select-multi" id="directorist-location-select" data-isSearch="false" data-multiSelect='[]' data-max="15">
		<select name="<?php echo esc_attr( $data['field_key'] ); ?>" id="directorist-location-select-items" <?php echo $multiple; ?> <?php echo $max; ?>>

			<?php
			if ( $data['type'] != 'multiple' ) {
				printf('<option>%s</option>', __( 'Select Location', 'directorist' ) );
			}

			echo $listing_form->add_listing_location_fields();
			?>

		</select>
	</div>

	<?php $listing_form->field_description_template( $data ); ?>

</div>
