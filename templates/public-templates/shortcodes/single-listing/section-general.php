<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<div class="atbd_content_module <?php echo esc_attr( $class );?>" <?php echo $id ? 'id="'.$id.'"' : '';?>>
	<div class="atbd_content_module_title_area">
		<div class="atbd_area_title">
			<h4><?php directorist_icon( $icon );?><?php echo esc_html( $label );?></h4>
		</div>
	</div>
	<div class="atbdb_content_module_contents">
		<?php
		foreach ( $section_data['fields'] as $field ){
			$listing->field_template( $field );
		}
		?>
	</div>
</div>