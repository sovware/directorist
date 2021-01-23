<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */

if ( !$has_custom_fields || !$plan_custom_field ) {
	return;
}
?>
<div class="atbd_content_module atbd_custom_fields_contents">
	<div class="atbd_content_module_title_area">
		<div class="atbd_area_title">
			<h4>
				<span class="<?php atbdp_icon_type(true); ?>-bars atbd_area_icon"></span><?php echo esc_html($section_title); ?>
			</h4>
		</div>
	</div>
	<div class="atbdb_content_module_contents">
		<ul class="atbd_custom_fields">
			<?php foreach ($custom_field_data as $custom_field): ?>
				<li>
					<div class="atbd_custom_field_title"><p><?php echo esc_html($custom_field['title']); ?></p></div>
					<div class="atbd_custom_field_content">
						<div style="margin-bottom: 10px;"><?php echo $custom_field['value'];?></div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>