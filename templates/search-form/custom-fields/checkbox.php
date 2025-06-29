<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( $value == '' ) {
	$value = [];
}
?>

<div class="directorist-search-field directorist-search-form-dropdown directorist-form-group">
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
			<div class="directorist-flex directorist-flex-wrap directorist-checkbox-wrapper">

				<?php
				if ( $data['options']['options'] ) {
					foreach ( $data['options']['options'] as $option ) {
						$uniqid = $option['option_value'] . '-' .wp_rand();
						?>

						<div class="directorist-checkbox directorist-checkbox-primary">
							<input <?php checked( in_array( $option[ 'option_value' ], $value ) ); ?> type="checkbox" id="<?php echo esc_attr( $uniqid ); ?>" name="custom_field[<?php echo esc_attr( $data['field_key'] ); ?>][]" value="<?php echo esc_attr( $option['option_value'] ); ?>">
							<label class="directorist-checkbox__label" for="<?php echo esc_attr( $uniqid ); ?>"><?php echo esc_html( $option['option_label'] ); ?></label>
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