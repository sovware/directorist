<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$source = !empty( $data['tags_filter_source'] ) ? $data['tags_filter_source'] : '';
$tag_source = ( $source == 'category_based_tags' ) ? 'cat_based' : 'all_tags';
?>

<div class="directorist-search-field directorist-flex directorist-align-center directorist-justify-content-between">

	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>

	<div class="directorist-search-tags directorist-flex">
		<?php
		$rand = rand();
		foreach ($searchform->listing_tag_terms($tag_source) as $term) {
			$id = $rand . $term->term_id;
			?>

			<div class="directorist-checkbox directorist-checkbox-primary">
				<input type="checkbox" name="in_tag[]" value="<?php echo esc_attr( $term->term_id ); ?>" id="<?php echo esc_attr( $id ); ?>" <?php checked( !empty($_GET['in_tag']) && in_array($term->term_id, $_GET['in_tag']) ); ?>>
				<label for="<?php echo esc_attr( $id ); ?>" class="directorist-checkbox__label"><?php echo esc_html( $term->name ); ?></label>
			</div>

			<?php
		}
		?>
	</div>
	<a href="#" class="more-or-less"><?php esc_html_e( 'Show More', 'directorist' ); ?></a>

</div>