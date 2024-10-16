<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.5.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-advanced-filter__action directorist-flex directorist-align-center directorist-justify-content-between flex-wrap">

	<?php if ( $searchform->has_apply_filters_button ): ?>
		<button type="submit" class="directorist-btn directorist-btn-sm directorist-btn-submit"><?php echo esc_html( $searchform->apply_filters_text ); ?></button>
	<?php endif; ?>

	<?php if ( $searchform->has_reset_filters_button ): ?>
		<button type="submit" class="directorist-btn-reset-js"><?php echo esc_html( $searchform->reset_filters_text ); ?></button>
	<?php endif; ?>

</div>