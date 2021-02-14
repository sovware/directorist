<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="atbd_content_module">

	<div class="atbd_area_title">
		<h4><?php echo esc_html( $section_data['label'] );?></h4>
	</div>

	<div class="atbdb_content_module_contents">

		<?php foreach ( $section_data['fields'] as $field ) {
			$listing_form->field_template( $field );
		}
		?>
		
	</div>

</div>