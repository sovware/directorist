<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$all_tags = get_terms( ATBDP_TAGS, array( 'hide_empty' => 0 ) );
$current_tag_ids = $listing_form->add_listing_tag_ids();
$max = !empty( $data['max'] ) ? 'max="'. esc_attr( $data['max'] ) .'"' : '';
?>

<div class="form-group directorist-tag-field">

	<?php $listing_form->field_label_template( $data, 'at_biz_dir-tags' ); ?>

	<select name="<?php echo esc_attr( $data['field_key'] ); ?>" class="form-control" id="at_biz_dir-tags" multiple="multiple" <?php echo $max; ?>>

		<?php
		foreach ($all_tags as $tag) {
			$current = in_array( $tag->term_id, $current_tag_ids ) ? true : false;
			?>
			<option <?php selected( $current, true, true ); ?> value='<?php echo esc_attr( $tag->name ); ?>'><?php echo esc_html( $tag->name ); ?></option>
			<?php
		}
		?>

	</select>

	<?php $listing_form->field_description_template( $data ); ?>

</div>
