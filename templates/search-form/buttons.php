<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.5.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$search_form = directorist()->search_form;
?>

<div class="directorist-advanced-filter__action directorist-flex directorist-justify-content-end">

	<?php if ( $search_form->has_reset_filters_button ): ?>
		<a href="#" class="directorist-btn directorist-btn-sm directorist-btn-outline-dark directorist-btn-reset-js"><?php echo esc_html( $search_form->reset_filters_text ); ?></a>
	<?php endif; ?>

	<?php if ( $search_form->has_apply_filters_button ): ?>
		<button type="submit" class="directorist-btn directorist-btn-sm directorist-btn-dark"><?php echo esc_html( $search_form->apply_filters_text ); ?></button>
	<?php endif; ?>

</div>