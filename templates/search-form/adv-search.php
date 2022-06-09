<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$search_form = directorist()->search_form;
?>

<div class="directorist-search-adv-filter directorist-advanced-filter">

	<?php foreach ( $search_form->form_data[1]['fields'] as $field ): ?>

		<?php if (  ! in_array( $field['field_key'], $search_form->assign_to_category()['custom_field_key'] ) ) { ?>

			<div class="directorist-form-group directorist-advanced-filter__advanced--element direcorist-search-field-<?php echo esc_attr( $field['widget_name'] )?>"><?php $search_form->field_template( $field ); ?></div>

		<?php } ?>

	<?php endforeach; ?>

	<?php $search_form->buttons_template(); ?>

</div>