<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$multiple = $data['type'] == 'multiple' ? 'multiple="multiple"' : '';
$max = !empty( $data['max'] ) ? 'max="'. esc_attr( $data['max'] ) .'"' : '';
?>

<div class="directorist-form-group directorist-form-categories-field">

	<?php $listing_form->field_label_template( $data );?>

	<div class="directorist-select directorist-select-multi" id="directorist-category-select" data-isSearch="false" data-multiSelect="['apple', 'banana', 'orange', 'mango']" data-default="['banana']" data-max="15">
		<input type="hidden">
		<!-- <select name="admin_category_select[]" id="directorist-category-select-items" <?php //echo $multiple; ?> <?php //echo $max; ?>>

			<?php
			//if ( $data['type'] != 'multiple' ) {
				//printf( '<option>%s</option>', __( 'Select Category', 'directorist' ) );
			//}

			//echo $listing_form->add_listing_cat_fields();
			?>

		</select> -->
	</div>

</div>

<div class="directorist-form-group atbdp_category_custom_fields"></div>