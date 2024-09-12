<?php
/**
 * @author  wpWax
 * @since   8.0
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// $submission_form_fields = get_term_meta( 15, 'submission_form_fields', true );
// 		e_var_dump( $submission_form_fields );
?>
<div class="directorist-add-listing-form__action">

	<div class="directorist-add-listing-form__publish">
		<div class="directorist-add-listing-form__publish__icon">
			<?php directorist_icon( 'fas fa-paper-plane' ); ?>
		</div>
		<h2 class="directorist-add-listing-form__publish__title">
			<?php esc_html_e( 'You are about to publish', 'directorist' ); ?>
		</h2>
		<p class="directorist-add-listing-form__publish__subtitle">
			<?php esc_html_e( 'Are you sure you want to publish this listing?', 'directorist' ); ?>
		</p>
	</div>

	<?php
	do_action( 'atbdp_before_terms_and_conditions_font' )
	?>

	<div class="directorist-form-privacy directorist-form-terms directorist-checkbox">

		<input id="directorist_submit_privacy_policy" type="checkbox" name="privacy_policy" <?php checked( $data['privacy_checked'] ?? '' ); ?> <?php echo $data['required'] ? 'required="required"' : ''; ?>>

		<label for="directorist_submit_privacy_policy" class="directorist-checkbox__label"><?php echo wp_kses_post( $data['label'] ); ?></label>

		<?php if ( $data['required'] ): ?>
			<span class="directorist-form-required"> *</span>
		<?php endif; ?>

	</div>


</div>