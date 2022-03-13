<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$search_form = directorist()->search_form;
?>

<div class="directorist-search-adv-filter directorist-advanced-filter">

	<?php foreach ( $search_form->form_data[1]['fields'] as $field ): ?>
		<div class="directorist-form-group directorist-advanced-filter__advanced--element direcorist-search-field-<?php echo esc_attr( $field['widget_name'] )?>"><?php $search_form->field_template( $field ); ?></div>
	<?php endforeach; ?>

	<?php $search_form->buttons_template(); ?>

</div>