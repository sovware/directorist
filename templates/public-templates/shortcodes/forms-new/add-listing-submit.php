<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>

<div class="directorist-contact-fields atbdp_info_module">

	<?php
    /*
     * @since 4.1.0
     */
    do_action('atbdp_before_terms_and_conditions_font');

    if ( $display_guest_listings && !atbdp_logged_in_user() ) {
    	?>
    	<div class="atbd_content_module" id="atbdp_front_media_wrap">
    		<div class="atbdb_content_module_contents atbdp_video_field">
    			<div class="form-group">
    				<label for="guest_user"><?php echo wp_kses_post( $guest_email_label_html ); ?></label>
    				<input type="text" id="guest_user_email" name="guest_user_email" class="form-control directory_field" placeholder="<?php echo esc_attr($guest_email_placeholder); ?>"/>
    			</div>
    		</div>
    	</div>
    	<?php
    }

    if ($display_privacy) { ?>
    	<div class="atbd_privacy_policy_area">
    		<?php if ($privacy_is_required) { ?>
    			<span class="atbdp_make_str_red"> *</span>
    			<?php
    		}
    		?>
    		<input id="privacy_policy" type="checkbox" name="privacy_policy"<?php checked( $privacy_checked ); ?>>
    		<label for="privacy_policy"><?php echo esc_html($privacy_label); ?> <a style="color: red" target="_blank" href="<?php echo esc_url($privacy_link) ?>"><?php echo esc_html($privacy_link_text); ?></a></label>
    	</div>
    	<?php
    }

    if ($display_terms) { ?>
    	<div class="atbd_term_and_condition_area">
    		<?php if ($terms_is_required) { ?>
    			<span class="atbdp_make_str_red"> *</span>
    			<?php
    		}
    		?>
    		<input id="listing_t" type="checkbox" name="t_c_check"<?php checked( $terms_checked ); ?>>
    		<label for="listing_t"><?php echo esc_html($terms_label); ?> <a style="color: red" target="_blank" href="<?php echo esc_url($terms_link) ?>"><?php echo esc_html($terms_link_text); ?></a>
    		</label>
    	</div>
    	<?php
    }

    /**
     * It fires before rendering submit listing button on the front end.
     */
    do_action('atbdp_before_submit_listing_frontend', $p_id);
    ?>

    <div id="listing_notifier"></div>
    
    <div class="btn_wrap list_submit">
    	<button type="submit" class="btn btn-primary btn-lg listing_submit_btn"><?php echo !empty($p_id) ? esc_html__('Preview Changes', 'directorist') : $submit_label; ?></button>
    </div>

    <div class="clearfix"></div>
</div>