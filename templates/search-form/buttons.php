<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-advanced-filter__action directorist-flex directorist-justify-content-end">
	
	<?php if ( $searchform->has_reset_filters_button ): ?>
		<a href="#" class="directorist-btn directorist-btn-sm directorist-btn-outline-dark" id="atbdp_reset"><?php echo esc_html( $searchform->reset_filters_text ); ?></a>
	<?php endif; ?>

	<?php if ( $searchform->has_apply_filters_button ): ?>
		<button type="submit" class="directorist-btn directorist-btn-sm directorist-btn-dark"><?php echo esc_html( $searchform->apply_filters_text ); ?></button>
	<?php endif; ?>

</div>