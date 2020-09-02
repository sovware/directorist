<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="form-group">
	<div class="bottom-inputs">
		<?php if ( $searchform->has_website_field ) { ?>
			<div>
				<input type="text" name="website" placeholder="<?php echo esc_attr($searchform->website_label); ?>" value="<?php echo isset($_GET['website']) ? esc_attr($_GET['website']) : ''; ?>" class="form-control">
			</div>
			<?php
		}

		if ( $searchform->has_email_field ) { ?>
			<div>
				<input type="text" name="email" placeholder="<?php echo esc_attr($searchform->email_label); ?>" value="<?php echo isset($_GET['email']) ? esc_attr($_GET['email']) : ''; ?>" class="form-control">
			</div>
			<?php
		}

		if ( $searchform->has_phone_field ) { ?>
			<div>
				<input type="text" name="phone" placeholder="<?php _e('Phone Number', 'directorist'); ?>" value="<?php echo isset($_GET['phone']) ? esc_attr($_GET['phone']) : ''; ?>" class="form-control">
			</div>
			<?php
		}

		if ( $searchform->has_fax_field ) { ?>
			<div>
				<input type="text" name="fax" placeholder="<?php echo esc_attr($searchform->fax_label); ?>" value="<?php echo isset($_GET['fax']) ? esc_attr($_GET['fax']) : ''; ?>" class="form-control">
			</div>
			<?php
		}

		if ( $searchform->has_address_field ) { ?>
			<div class="atbdp_map_address_field">
				<input type="text" name="address" id="address" value="<?php echo isset($_GET['address']) ? esc_attr($_GET['address']) : ''; ?>" placeholder="<?php echo esc_attr($searchform->address_label); ?>" class="form-control location-name">
				<div class="address_result" style="display: none"><ul></ul></div>
				<input type="hidden" id="cityLat" name="cityLat"/>
				<input type="hidden" id="cityLng" name="cityLng"/>
			</div>
			<?php
		}
		
		if ( $searchform->has_zip_code_field ) { ?>
			<div>
				<input type="text" name="zip_code" placeholder="<?php echo esc_attr($searchform->zip_label); ?>" value="<?php echo isset($_GET['zip_code']) ? esc_attr($_GET['zip_code']) : ''; ?>" class="form-control">
			</div>
		<?php } ?>
	</div>
</div>