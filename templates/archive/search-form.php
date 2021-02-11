<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */
?>
<div class="directorist-archive-af directorist-advanced-filter">
	<form action="<?php atbdp_search_result_page_link(); ?>" class="directorist-advanced-filter__form">

		<div class="directorist-advanced-filter__basic">
			<?php foreach ( $searchform->form_data[0]['fields'] as $field ): ?>
				<div class="directorist-advanced-filter__basic--element"><?php $searchform->field_template( $field ); ?></div>
			<?php endforeach; ?>
		</div>

		<div class="directorist-advanced-filter__advanced">
			<?php foreach ( $searchform->form_data[1]['fields'] as $field ): ?>
				<div class="directorist-form-group directorist-advanced-filter__advanced--element directorist-advanced-filter__advanced--<?php echo esc_attr( $field['widget_name'] )?>"><?php $searchform->field_template( $field ); ?></div>
			<?php endforeach; ?>
		</div>

		<?php $searchform->buttons_template(); ?>
		
	</form>
</div>