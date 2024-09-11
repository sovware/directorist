<?php
/**
 * @author  wpWax
 * @since   8.0
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?>
<div class="directorist-form-privacy directorist-form-terms directorist-checkbox">

	<input id="directorist_submit_privacy_policy" type="checkbox" name="privacy_policy" <?php checked( $privacy_checked ); ?> <?php echo $data['required'] ? 'required="required"' : ''; ?>>

	<label for="directorist_submit_privacy_policy" class="directorist-checkbox__label"><?php echo wp_kses_post( $data['label'] ); ?></label>

	<?php if ( $data['required'] ): ?>
		<span class="directorist-form-required"> *</span>
	<?php endif; ?>

</div>