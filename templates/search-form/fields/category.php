<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
	$selected_item = $searchform::get_selected_category_option_data();
	$additional_class = empty( $data['label'] ) ? ' input-is-noLabel' : '';
?>
<div class="directorist-search-field directorist-form-group<?php echo esc_attr( $additional_class ); ?>">
	<div class="directorist-select directorist-search-category directorist-search-field__input">

		<?php if ( ! empty( $data['label'] ) ) : ?>
			<label class="directorist-search-field__label"><?php echo esc_attr( $data['label'] ); ?></label>
		<?php endif; ?>

		<select name="in_cat" class="<?php echo esc_attr($searchform->category_class); ?>" data-placeholder="<?php echo esc_attr( $data['placeholder'] ?? '' ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true" data-selected-id="<?php echo esc_attr( $selected_item['id'] ); ?>" data-selected-label="<?php echo esc_attr( $selected_item['label'] ); ?>">
			<?php
				echo '<option value="">' . esc_html__( 'Select Category', 'directorist' ) . '</option>';

				if ( empty( $data['lazy_load'] ) ) {
					echo directorist_kses( $searchform->categories_fields, 'form_input' );
				}
			?>
		</select>

	</div>
	<div class="directorist-search-field__btn directorist-search-field__btn--clear">
		<?php directorist_icon( 'fas fa-times-circle' ); ?>	
	</div>
</div>