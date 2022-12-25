<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.5.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-adv-filter directorist-advanced-filter">

	<div class="directorist-search-form-cat-fields" data-inner-class="directorist-form-group directorist-advanced-filter__advanced--element direcorist-search-field-WIDGETNAME"></div>

	<?php foreach ( $searchform->form_data[1]['fields'] as $field ): ?>

		<div class="directorist-form-group directorist-advanced-filter__advanced--element direcorist-search-field-<?php echo esc_attr( $field['widget_name'] )?>"><?php $searchform->field_template( $field ); ?></div>

	<?php endforeach; ?>

	<?php $searchform->buttons_template(); ?>

</div>