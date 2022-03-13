<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.5.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$search_form = directorist()->search_form;
?>
<div class="directorist-search-field">
	<div class="directorist-select directorist-search-category">

		<select name="in_cat" id="<?php echo !empty($search_form->category_id) ? esc_attr($search_form->category_id) : "notEmptyId".uniqid(); ?>" class="<?php echo esc_attr($search_form->category_class); ?>" data-placeholder="<?php echo esc_attr($data['placeholder']); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true">
			<?php
				echo '<option value="">Select Category</option>';
				echo $search_form->categories_fields;
			?>
		</select>

	</div>
</div>