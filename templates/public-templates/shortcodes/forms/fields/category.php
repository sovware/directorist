<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

//$cat_fields           = Directorist_Listing_Forms::add_listing_cat_fields();
$display_multiple_cat = '';
?>

<div class="form-group" id="directorist-categories-field atbdp_categories">
	<?php if ( ! empty( $label ) ) : ?>
		<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo esc_html( $label ); ?>:<?php echo ! empty( $required ) ? Directorist_Listing_Forms::instance()->add_listing_required_html() : ''; ?></label>
	<?php endif; ?>

	<select name="admin_category_select[]" id="at_biz_dir-categories" class="form-control"<?php echo $display_multiple_cat ? ' multiple="multiple"' : ''; ?>>
			
		<?php
		if ( ! $display_multiple_cat ) {
			printf( '<option>%s</option>', $cat_placeholder );
		}

		//echo $cat_fields;
		?>
	</select>

</div>
