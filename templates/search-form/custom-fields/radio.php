<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.3.2
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-field">

	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>

	<?php
	foreach ( $data['options'] as $option ){
		$uniqid = $option['option_value'] . '-' .wp_rand();
		?>

		<div class="directorist-radio directorist-radio-circle">
			<input <?php checked(  $value === $option[ 'option_value' ] ); ?> type="radio" id="<?php echo esc_attr( $uniqid ); ?>" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]" value="<?php echo esc_attr( $option['option_value'] ); ?>">
			<label class="directorist-radio__label" for="<?php echo esc_attr( $uniqid ); ?>"><?php echo esc_html( $option['option_label'] ); ?></label>
		</div>

		<?php
	}
	?>

</div>