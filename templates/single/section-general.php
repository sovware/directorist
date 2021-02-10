<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="atbd_content_module <?php echo esc_attr( $class );?>" <?php $listing->section_id( $id ); ?>>

	<div class="atbd_content_module_title_area">
		<h4><?php directorist_icon( $icon );?><?php echo esc_html( $label );?></h4>
	</div>

	<div class="atbdb_content_module_contents">
		<?php
		foreach ( $section_data['fields'] as $field ){
			$listing->field_template( $field );
		}
		?>
	</div>
	
</div>