<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
$source = !empty( $data['tags_filter_source'] ) ? $data['tags_filter_source'] : '';
$tag_source = ( $source == 'category_based_tags' ) ? 'cat_based' : 'all_tags';

if ( !empty($data['label']) ): ?>
	<label><?php echo esc_html( $data['label'] ); ?></label>
<?php endif; ?>

<div class="bads-tags">
	<?php
	$rand = rand();
	foreach ($searchform->listing_tag_terms($tag_source) as $term) {
		$id = $rand . $term->term_id;
		?>
		<div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
			<input type="checkbox" class="custom-control-input" name="in_tag[]" value="<?php echo esc_attr( $term->term_id ); ?>" id="<?php echo esc_attr( $id ); ?>" <?php checked( !empty($_GET['in_tag']) && in_array($term->term_id, $_GET['in_tag']) ); ?>>
			<span class="check--select"></span>
			<label for="<?php echo esc_attr( $id ); ?>" class="custom-control-label"><?php echo esc_html( $term->name ); ?></label>
		</div>
	<?php } ?>
</div>
<a href="#" class="more-or-less"><?php esc_html_e('Show More', 'directorist'); ?></a>