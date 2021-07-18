<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.0.5.1
 */

$all_tags = get_terms( ATBDP_TAGS, array( 'hide_empty' => 0 ) );
$current_tag_ids = $listing_form->add_listing_tag_ids();
$placeholder = ! empty( $data['placeholder'] ) ? $data['placeholder'] : '';
?>

<div class="directorist-form-group directorist-form-tag-field">

	<?php $listing_form->field_label_template( $data, 'at_biz_dir-tags' ); ?>

	<select name="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" id="at_biz_dir-tags" data-placeholder="<?php echo esc_attr( $placeholder ); ?>" <?php echo $data['type'] == 'multiple' ? 'multiple="multiple"' : ''; echo !empty( $data['allow_new'] ) ? 'data-allow_new="'. $data['allow_new'] .'"' : '';  echo !empty( $data['max'] ) ? 'data-max="'. $data['max'] .'"' : ''; ?>>

		<?php 
		if ($data['type'] != 'multiple') {
			echo '<option></option>';
		}

		foreach ($all_tags as $tag) {
			$current = in_array($tag->term_id, $current_tag_ids) ? true : false;
			?>
			<option <?php selected( $current, true, true ); ?> value='<?php echo esc_attr($tag->name); ?>'><?php echo esc_html($tag->name) ?></option>
			<?php
		}
		?>

	</select>

	<?php $listing_form->field_description_template( $data ); ?>

</div>