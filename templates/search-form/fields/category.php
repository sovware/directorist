<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.4
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="directorist-search-field">
	<div class="directorist-select directorist-search-category">

		<select name="in_cat" id="<?php echo esc_attr($searchform->category_id); ?>" class="<?php echo esc_attr($searchform->category_class); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true">

			<option value=""><?php echo esc_html($data['placeholder']); ?></option>

			<?php echo $searchform->categories_fields; ?>

		</select>

	</div>
</div>