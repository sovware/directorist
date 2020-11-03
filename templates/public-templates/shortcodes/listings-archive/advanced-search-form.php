<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.7
 */
?>
<div class="ads-advanced">
	<form action="<?php atbdp_search_result_page_link(); ?>" class="atbd_ads-form">
		<div class="atbd_seach_fields_wrapper">
			<div class="atbdp-search-form atbdp-basic-search-fields">
				<?php foreach ( $searchform->form_data[0]['fields'] as $field ){ ?>
					<div class="atbdp-basic-search-fields-each"><?php $searchform->field_template( $field ); ?></div>
				<?php } ?>
			</div>
		</div>

		<div class="atbdp-adv-search-fields">
			<?php foreach ( $searchform->form_data[1]['fields'] as $field ){ ?>
				<div class="form-group atbdp-adv-search-fields-each"><?php $searchform->field_template( $field ); ?></div>
			<?php } ?>
		</div>

		<?php $searchform->buttons_template(); ?>
		<?php e_var_dump($searchform->form_data);?>
	</form>
</div>