<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="atbd_listing_data_list">
	<ul>
		<?php
		/**
		 * @since 4.7.6
		 */
		do_action('atbdp_listings_before_location');
		?>

		<?php if ($listings->display_contact_info): ?>
			<?php if (!empty($listings->loop['address']) && 'contact' == $listings->address_location && $listings->display_address_field): ?>
				<li>
					<p><span class="<?php atbdp_icon_type(true); ?>-map-marker"></span><?php echo esc_html($listings->loop['address']); ?></p>
				</li>
			<?php elseif (!empty($listings->loop['locs']) && 'location' == $listings->address_location): ?>
				<li>
					<p><span class="<?php atbdp_icon_type(true); ?>-map-marker"></span><?php echo $listings->loop_get_address_from_locaton(); ?></span></p>
				</li>
			<?php endif; ?>

			<?php
			/**
			* @since 4.7.6
			*/
			do_action('atbdp_listings_before_phone');
			?>
			

			<?php if (!empty($listings->loop['phone_number']) && $listings->display_phone_field): ?>
			<li>
				<p><span class="<?php atbdp_icon_type(true); ?>-phone"></span><a href="tel:<?php ATBDP_Helper::sanitize_tel_attr( $listings->loop['phone_number'] ); ?>"><?php ATBDP_Helper::sanitize_html( $listings->loop['phone_number'] ); ?></a></p>
			</li>
			<?php endif; ?>

		<?php endif; ?>
		<?php
		/**
		 * @since 4.7.6
		 */
		do_action('atbdp_listings_before_post_date');
		?>

		<?php if ($listings->display_publish_date): ?>
			<li>
				<p><span class="<?php atbdp_icon_type(true); ?>-clock-o"></span><?php echo esc_html( $listings->loop_get_published_date() );?></p>
			</li>
		<?php endif; ?>
		<?php
		/**
		 * @since 4.7.6
		 */
		do_action('atbdp_listings_after_post_date');
		?>

		<?php if (!empty($listings->loop['email'] && $listings->display_email)): ?>
			<li><p><span class="<?php echo atbdp_icon_type();?>-envelope"></span><a target="_top" href="mailto:<?php echo $listings->loop['email'];?>"><?php echo $listings->loop['email'];?></a></p></li>
		<?php endif; ?>

		<?php if (!empty($listings->loop['web'] && $listings->display_web_link)): ?>
			<li>
				<p><span class="<?php atbdp_icon_type(true); ?>-globe"></span><a target="_blank" href="<?php echo esc_url($listings->loop['web']); ?>"><?php echo esc_html($listings->loop['web']); ?></a>
				</p>
			</li>
		<?php endif; ?>

	    <?php
	    /**
	     * @since 6.6
	     */
	    do_action( 'directorist_loop_data_list_end', $listings );
	    ?>

	</ul>
</div>