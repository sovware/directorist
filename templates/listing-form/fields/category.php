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

<div class="form-group directorist-categories-field">

	<?php $listing_form->field_label_template( $data );?>

	<select name="admin_category_select[]" id="at_biz_dir-categories" class="form-control" <?php echo $multiple; ?> <?php echo $max; ?>>

		<?php
		if ( $data['type'] != 'multiple' ) {
			printf( '<option>%s</option>', __( 'Select Category', 'directorist' ) );
		}

		echo $listing_form->add_listing_cat_fields();
		?>
	</select>

</div>

<div class="form-group atbdp_category_custom_fields"></div>