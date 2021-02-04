<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-advanced-filter__action">
	
	<?php if ($searchform->has_reset_filters_button): ?>
		<a href="#" class="btn btn-outline btn-outline-primary btn-sm" id="atbdp_reset"><?php echo esc_html( $searchform->reset_filters_text ); ?></a>
	<?php endif; ?>

	<?php if ($searchform->has_apply_filters_button): ?>
		<button type="submit" class="btn btn-primary btn-sm"><?php echo esc_html( $searchform->apply_filters_text ); ?></button>
	<?php endif; ?>

</div>