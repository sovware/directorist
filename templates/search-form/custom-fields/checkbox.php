<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.4.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( $value == '' ) {
	$value = [];
}
?>

<div class="directorist-search-field">

	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>

	<div class="directorist-flex directorist-flex-wrap directorist-checkbox-wrapper">

		<?php
		foreach ( $data['options'] as $option ) {
			$uniqid = $option['option_value'] . '-' .wp_rand();
			?>

			<div class="directorist-checkbox directorist-checkbox-primary">
				<input <?php checked( in_array( $option[ 'option_value' ], $value ) ); ?> type="checkbox" id="<?php echo esc_attr( $uniqid ); ?>" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>][]" value="<?php echo esc_attr( $option['option_value'] ); ?>">
				<label class="directorist-checkbox__label" for="<?php echo esc_attr( $uniqid ); ?>"><?php echo esc_html( $option['option_label'] ); ?></label>
			</div>

			<?php
		}
		?>

	</div>

</div>