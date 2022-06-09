<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.2.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$search_form = directorist()->search_form;
$selected_item = $search_form::get_selected_category_option_data();
?>

<div class="directorist-search-field">
	<div class="directorist-select directorist-search-category">

		<select name="in_cat" class="<?php echo esc_attr($search_form->category_class()); ?>" data-placeholder="<?php echo esc_attr($data['placeholder']); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true" data-selected-id="<?php echo $selected_item['id'] ?>" data-selected-label="<?php echo $selected_item['label'] ?>">
			<?php
				echo '<option value="">' . __( 'Select Category', 'directorist' ) . '</option>';
				echo $search_form->categories_fields();
			?>
		</select>

	</div>
</div>