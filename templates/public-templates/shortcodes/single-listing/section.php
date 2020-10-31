<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<div class="atbd_content_module">
	<div class="atbd_content_module_title_area">
		<div class="atbd_area_title">
			<h4><span class="<?php atbdp_icon_type(true);?>-<?php echo esc_attr( $section_data['icon'] );?>"></span><?php echo esc_html( $section_data['label'] );?></h4>
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