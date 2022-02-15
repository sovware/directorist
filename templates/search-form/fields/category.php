<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.5.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;
	$selected_item = $searchform::get_selected_category_option_data();
?>
<div class="directorist-search-field">
	<div class="directorist-select directorist-search-category">
		<select name="in_cat" id="<?php echo !empty($searchform->category_id) ? esc_attr($searchform->category_id) : "notEmptyId".uniqid(); ?>" class="<?php echo esc_attr($searchform->category_class); ?>" data-placeholder="<?php echo esc_attr($data['placeholder']); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true" data-selected-id="<?php echo $selected_item['id'] ?>" data-selected-label="<?php echo $selected_item['label'] ?>">
			<?php
				echo '<option value="">Select Category</option>';
				echo $searchform->categories_fields;
			?>
		</select>

	</div>
</div>