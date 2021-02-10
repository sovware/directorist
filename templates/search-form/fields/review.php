<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-field">
	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>
	<div class="directorist-select" id="directorist-review-select-js">
		<select name='search_by_rating' <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> data-isSearch="true">
			<?php
				foreach ( $searchform->rating_field_data() as $option ) {
					printf('<option value="%s"%s>%s</option>', $option['value'], $option['selected'], $option['label']);
				}
			?>
		</select>
	</div>
</div>