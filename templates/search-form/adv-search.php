<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-adv-filter directorist-advanced-filter">

	<?php foreach ( $searchform->form_data[1]['fields'] as $field ): ?>
		<div class="directorist-form-group directorist-advanced-filter__advanced--element direcorist-search-field-<?php echo esc_attr( $field['widget_name'] )?>"><?php $searchform->field_template( $field ); ?></div>
	<?php endforeach; ?>

	<?php $searchform->buttons_template(); ?>

</div>