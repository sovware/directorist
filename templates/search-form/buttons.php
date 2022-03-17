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

	<?php if ( $search_form->display_reset_filter_button() ): ?>
		<a href="#" class="directorist-btn directorist-btn-sm directorist-btn-outline-dark directorist-btn-reset-js"><?php echo esc_html( $search_form->reset_filters_button_label() ); ?></a>
	<?php endif; ?>

	<?php if ( $search_form->display_apply_filter_button() ): ?>
		<button type="submit" class="directorist-btn directorist-btn-sm directorist-btn-dark"><?php echo esc_html( $search_form->apply_filters_button_label() ); ?></button>
	<?php endif; ?>

</div>