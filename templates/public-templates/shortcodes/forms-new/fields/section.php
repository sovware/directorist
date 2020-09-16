<div class="atbd_content_module">
	<div class="atbd_content_module_title_area">
		<div class="atbd_area_title">
			<h4><?php echo esc_html( $section_data['label'] );?></h4>
		</div>
	</div>
	<div class="atbdb_content_module_contents">
		<?php
		foreach ($section_data['fields'] as $field){
			Directorist_Listing_Forms->add_listing_field_template( $field );
		}
		?>
	</div>
</div>