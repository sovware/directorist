<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$selected_item   = $searchform::get_selected_category_option_data();
$all_terms       = $searchform->all_terms( ATBDP_CATEGORY );
$current_term_id = $searchform->current_term_id( ATBDP_CATEGORY );
?>

<div class="directorist-search-field">
	<div class="directorist-select directorist-search-category">
		<select name="in_cat" class="<?php echo esc_attr($searchform->category_class); ?>" data-placeholder="<?php echo esc_attr($data['placeholder']); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true" data-selected-id="<?php echo esc_attr( $selected_item['id'] ); ?>" data-selected-label="<?php echo esc_attr( $selected_item['label'] ); ?>">
			<?php
				echo '<option value="">' . esc_html__( 'Select Category', 'directorist' ) . '</option>';

				foreach ( $all_terms as $term ) {
					$selected     = ( $term->term_id == $current_term_id ) ? "selected" : '';
					$custom_field = in_array( $term->term_id, $searchform->assign_to_category()['assign_to_cat'] ) ? true : '';

					printf( '<option data-custom-field="%s" value="%s" %s>%s</option>', esc_attr( $term->custom_field ), esc_attr( $term->term_id ), esc_attr( $selected ), esc_html( $term->name ) );
				}
			?>
		</select>

	</div>
</div>