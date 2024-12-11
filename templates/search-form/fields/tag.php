<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$tag_source = 'all_tags';
$tag_terms  = $searchform->listing_tag_terms( $tag_source );
$in_tag     = ! empty( $_REQUEST['in_tag'] ) ? $_REQUEST['in_tag'] : '';

if ( is_array( $in_tag ) ) {
	$in_tag		= array_map( 'sanitize_text_field', wp_unslash( $in_tag) );
} else {
	$in_tag 	= array_map( 'sanitize_text_field', explode( ',', wp_unslash( $in_tag ) ) );
}

if ( !$tag_terms ) {
	return;
}
?>
<div class="directorist-search-field directorist-search-form-dropdown directorist-form-group <?php echo esc_attr( $empty_label ); ?>">
	<div class="directorist-search-basic-dropdown directorist-search-field__input">

		<?php if ( !empty($data['label']) ): ?>
			<label class="directorist-search-field__label directorist-search-basic-dropdown-label">
				<span class="directorist-search-basic-dropdown-selected-prefix"></span>
				<?php echo esc_html( $data['label'] ); ?>
				<span class="directorist-search-basic-dropdown-selected-count"></span>
				<?php directorist_icon( 'fas fa-chevron-down' ); ?>	
			</label>
		<?php endif; ?>
		<div class="directorist-search-basic-dropdown-content">
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
	</div>
	<div class="directorist-search-field__btn directorist-search-field__btn--clear">
		<?php directorist_icon( 'fas fa-times-circle' ); ?>	
	</div>

</div>