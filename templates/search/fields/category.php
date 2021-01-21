<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<div class="single_search_field search_category">
	<select name="in_cat" id="<?php echo esc_attr($searchform->category_id); ?>" class="<?php echo esc_attr($searchform->category_class); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>
		<option value=""><?php echo esc_html($data['placeholder']); ?></option>
		<?php echo $searchform->categories_fields; ?>
	</select>
</div>