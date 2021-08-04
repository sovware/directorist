<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.5
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="directorist-search-field">
	<div class="directorist-select directorist-search-category">

		<select name="in_cat" id="<?php echo !empty($searchform->category_id) ? esc_attr($searchform->category_id) : "notEmptyId"; ?>" class="<?php echo esc_attr($searchform->category_class); ?>" data-placeholder="<?php echo esc_attr($data['placeholder']); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true">
			<?php
				echo '<option value="">Select Category</option>';
				echo $searchform->categories_fields;
			?>
		</select>

	</div>
</div>