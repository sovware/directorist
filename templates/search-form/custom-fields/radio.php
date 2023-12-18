<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-field directorist-search-form-dropdown directorist-form-group">
	<div class="directorist-search-basic-dropdown directorist-search-field__input">
		<?php if ( !empty($data['label']) ): ?>
			<label class="directorist-search-field__label directorist-search-basic-dropdown-label">
				<?php echo esc_html( $data['label'] ); ?>
				<span class="directorist-search-basic-dropdown-selected-item"></span>
				<?php directorist_icon( 'fas fa-chevron-down' ); ?>	
			</label>
		<?php endif; ?>
		<div class="directorist-search-basic-dropdown-content">
			<div class="directorist-flex directorist-flex-wrap directorist-radio-wrapper">

				<?php
				if ( $data['options']['options'] ) {
					foreach ( $data['options']['options'] as $option ){
						$uniqid = $option['option_value'] . '-' .wp_rand();
						?>

						<div class="directorist-radio directorist-radio-circle">
							<input <?php checked(  $value === $option[ 'option_value' ] ); ?> type="radio" id="<?php echo esc_attr( $uniqid ); ?>" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>]" value="<?php echo esc_attr( $option['option_value'] ); ?>">
							<label class="directorist-radio__label" for="<?php echo esc_attr( $uniqid ); ?>"><?php echo esc_html( $option['option_label'] ); ?></label>
						</div>

						<?php
					}
				}
				?>
			</div>
		</div>
	</div>
	<div class="directorist-search-field__btn directorist-search-field__btn--clear">
		<?php directorist_icon( 'fas fa-times-circle' ); ?>	
	</div>
</div>