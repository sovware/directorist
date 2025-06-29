<?php
/**
 * @author  wpWax
 * @since   8.0
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="directorist-add-listing-form__privacy">
	<?php
	do_action( 'atbdp_before_terms_and_conditions_font' )
	?>

	<div class="directorist-form-privacy directorist-form-terms directorist-checkbox">

		<input id="directorist_submit_privacy_policy" type="checkbox" name="privacy_policy" <?php checked( $data['privacy_checked'] ?? '' ); ?>>

		<label for="directorist_submit_privacy_policy" class="directorist-checkbox__label"><?php echo wp_kses_post( strip_tags( $data['text'], '<a><strong><em>' ) ); ?></label>

		<?php if ( $data['required'] ): ?>
			<span class="directorist-form-required"> *</span>
		<?php endif; ?>

	</div>


</div>