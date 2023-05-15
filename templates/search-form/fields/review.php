<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-field">
	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>
	

	<div class="directorist-checkbox directorist-checkbox-primary">
		<?php foreach ( $searchform->rating_field_data() as $option ) { ?>
			<input type="checkbox" name="search_by_rating[<?php echo esc_attr( $option['value'] ); ?>]" value="<?php echo esc_attr( $option['value'] ); ?>" id="search_by_rating[<?php echo esc_attr( $option['value'] ); ?>]" <?php echo esc_attr( $option['checked'] ); ?>>
			<label for="search_by_rating[<?php echo esc_attr( $option['value'] ); ?>]" class="directorist-checkbox__label"><?php echo esc_html( $option['label'] ); ?></label>
		<?php } ?>
	</div>
</div>