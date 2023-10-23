<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
$label = $section_data['label'] ?? '';
$id    = str_replace(' ', '-', strtolower( $label ) );
?>
<div class="directorist-form-section directorist-content-module multistep-wizard__single" id="add-listing-content-<?php echo esc_attr( $id ?? '' ); ?>">
	<div class="directorist-content-module__title">
		<?php echo ! empty( $section_data['icon'] ) ?  directorist_icon( $section_data['icon'] ) : ''; ?>
		<h4><?php echo esc_html( $section_data['label'] );?></h4>
	</div>

	<div class="directorist-content-module__contents">

		<?php 
			foreach ( $section_data['fields'] as $field ) {
				$listing_form->field_template( $field );
			}
		?>

	</div>
</div>