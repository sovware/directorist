  
<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
$has_value = false;
foreach ( $section_data['fields'] as $field ) {
	$value = Directorist\Helper::get_widget_value( $listing->id, $field );

	if( 'tag' === $field['widget_name'] ) {
		$tags = get_the_terms( $listing->id, ATBDP_TAGS );
		if( $tags ) {
			$value = true;
		}
	}
	if( 'map' === $field['widget_name'] ) {
		$address = get_post_meta( $listing->id, '_address', true );
		if( $address ) {
			$value = true;
		}
	}
	if( $value ) {
		$has_value = true;
	}
}
if( $has_value ) {
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
<?php }