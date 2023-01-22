<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.5
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( $value == '' ) {
	$value = [];
}
?>

<div class="directorist-search-field directorist-form-group directorist-custom-field-checkbox">

	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>

	<div class="directorist-flex directorist-flex-wrap directorist-checkbox-wrapper">

		<?php
		foreach ( $data['options'] as $option ) {
			$id = preg_replace( "/[^\w]+/", "-", $option['option_value'] );
			?>

			<div class="directorist-checkbox directorist-checkbox-primary">
				<input <?php checked( in_array( $option[ 'option_value' ], $value ) ); ?> type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="custom-checkbox" class="custom-checkbox" value="<?php echo esc_attr( $option['option_value'] ); ?>">
				<label class="directorist-checkbox__label" for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $option['option_label'] ); ?></label>
			</div>

			<?php
		}
		?>

	</div>

</div>