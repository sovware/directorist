<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.7
 */
?>
<div id="directorist" class="directorist atbd_wrapper atbd_add_listing_wrapper">
	<div class="<?php echo apply_filters('atbdp_add_listing_container_fluid', 'container-fluid'); ?>">
		<form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post" id="add-listing-form">
			<fieldset>
				<?php do_action('atbdb_before_add_listing_from_frontend');?>

				<div class="atbdp-form-fields">
					<input type="hidden" name="add_listing_form" value="1">
					<input type="hidden" name="listing_id" value="<?php echo !empty($p_id) ? esc_attr($p_id) : ''; ?>">
					<?php
					ATBDP()->listing->add_listing->show_nonce_field();

					if ( !empty( $is_edit_mode ) || !empty( $single_directory )) {
						$listing_form->add_listing_type_template();
					}

					foreach ( $form_data as $section ) {
						$listing_form->add_listing_section_template( $section );
					}

					$listing_form->add_listing_submit_template();
					?>
				</div>
			</fieldset>
		</form>
	</div>
</div>