<?php
/**
 * @author  wpWax
 * @since   8.0
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$options = directorist_calculate_number_options( $data );
?>

<div class="directorist-search-field directorist-search-field__number">

	<?php if ( !empty($data['label']) ): ?>
		<label class="directorist-search-field__label"><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>

	<div class="directorist-flex directorist-flex-wrap directorist-radio-wrapper">

		<?php
		if ( $options['radio'] ) {
			foreach ( $options['radio'] as $option ){
				$uniqid = $option['start'] . '-' . wp_rand();
                $option_value  = ( $option['start'] === $option['end'] ) ? $option['start'] : $option['start'] . '-' . $option['end'];
				?>

				<div class="directorist-radio directorist-radio-circle">
					<input  <?php checked(  $value === $option_value ); ?> type="radio" id="<?php echo esc_attr( $uniqid ); ?>" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]" value="<?php echo esc_attr( $option_value ); ?>">
					<label class="directorist-radio__label" for="<?php echo esc_attr( $uniqid ); ?>"><?php echo esc_html( $option_value ); ?></label>
				</div>

				<?php
			}
		}
		?>
	</div>

</div>