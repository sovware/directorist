<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$max = !empty( $data['max'] ) ? 'max="'. esc_attr( $data['max'] ) .'"' : '';
?>

<div class="directorist-form-group directorist-form-description-field">

	<?php
	$listing_form->field_label_template( $data );

	if ( 'textarea' === $data['type'] ) {
		?>
		<textarea <?php echo $max; ?> name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" rows="8" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php $listing_form->required( $data ); ?>><?php echo esc_attr( $data['value'] ); ?></textarea>
		<?php
	}
	else {
		wp_editor(
			wp_kses_post( $data['value'] ),
			$data['field_key'],
			apply_filters(
				'atbdp_add_listing_wp_editor_settings',
				array(
					'media_buttons' => false,
					'quicktags'     => true,
					'editor_height' => 200,
				)
			)
		);
	}

	$listing_form->field_description_template( $data );
	?>

	<div id="directorist_listing_description_indicator"></div>

</div>