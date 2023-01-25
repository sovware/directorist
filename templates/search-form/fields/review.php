<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.5
 */

if ( ! defined( 'ABSPATH' ) ) exit;
e_var_dump( $data );
?>

<div class="directorist-search-field">
	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>
	<div class="directorist-select">
		<select name='search_by_rating' <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> class="directorist-review-selectbox" data-placeholder="<?php echo esc_attr( sprintf( _x( 'Select %s', 'Rating search select placeholder', 'directorist' ), $data['label'] ) ); ?> " data-isSearch="true">
			<?php
				foreach ( $searchform->rating_field_data() as $option ) {
					printf( '<option value="%s" %s>%s</option>', esc_attr( $option['value'] ), esc_attr( $option['selected'] ), esc_html( $option['label'] ) );
				}
			?>
		</select>
	</div>
</div>