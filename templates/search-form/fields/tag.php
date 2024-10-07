<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$tag_source = 'all_tags';
$tag_terms  = $searchform->listing_tag_terms( $tag_source );
$in_tag     = ! empty( $_REQUEST['in_tag'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['in_tag'] ) ) : array();

if ( !$tag_terms ) {
	return;
}
?>

<div class="directorist-search-field">

	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>

	<div class="directorist-search-tags directorist-flex">
		<?php
		$rand = rand();
		foreach ( $tag_terms as $term ) {
			$id = $rand . $term->term_id;
			?>

			<div class="directorist-checkbox directorist-checkbox-primary">
				<input type="checkbox" name="in_tag[]" value="<?php echo esc_attr( $term->term_id ); ?>" id="<?php echo esc_attr( $id ); ?>" <?php checked( !empty($_REQUEST['in_tag']) && in_array($term->term_id, $in_tag) ); ?>>
				<label for="<?php echo esc_attr( $id ); ?>" class="directorist-checkbox__label"><?php echo esc_html( $term->name ); ?></label>
			</div>

			<?php
		}
		?>
	</div>
	<a href="#" class="directorist-btn-ml"><?php esc_html_e( 'Show More', 'directorist' ); ?></a>

</div>