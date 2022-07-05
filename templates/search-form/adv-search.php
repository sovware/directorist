<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-adv-filter directorist-advanced-filter">

	<?php foreach ( $searchform->form_data[1]['fields'] as $field ): ?>

		<?php if (  ! in_array( $field['field_key'], $searchform->assign_to_category()['custom_field_key'] ) ) { ?>

			<div class="directorist-form-group directorist-advanced-filter__advanced--element direcorist-search-field-<?php echo esc_attr( $field['widget_name'] )?>"><?php $searchform->field_template( $field ); ?></div>

		<?php } ?>

	<?php endforeach; ?>

	<?php $searchform->buttons_template(); ?>

</div>