<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-form-section directorist-content-module">

	<div class="directorist-content-module__title">
		<h4><?php echo esc_html( $section_data['label'] );?></h4>
	</div>

	<div class="directorist-content-module__contents">

		<?php foreach ( $section_data['fields'] as $field ) {
			$listing_form->field_template( $field );
		}
		?>

	</div>

</div>