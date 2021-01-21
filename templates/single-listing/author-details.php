<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */

/**
 * @since 5.10.0
 */
do_action('atbdp_before_listing_author_shorcode');
?>

<div class="atbd_content_module author_info_module">

	<div class="atbd_content_module_title_area">
		<div class="atbd_area_title">
			<h4><span class="la la-user atbd_area_icon"></span> <?php echo esc_html($section_title); ?></h4>
		</div>
	</div>

	<div class="atbdb_content_module_contents">
		
		<div class="atbd_avatar_wrapper">
			<div class="atbd_review_avatar">
				<?php if (!empty($u_pro_pic)) { ?>
					<img src="<?php echo esc_url($u_pro_pic[0]); ?>" alt="<?php esc_attr_e('Avatar Image', 'directorist');?>">
					<?php
				}
				else {
					echo $avatar_img;
				}
				?>
			</div>
			<div class="atbd_name_time">
				<h4><?php echo esc_html($author_name); ?></h4>
				<span class="review_time"><?php printf(esc_html__('Member since %s ago', 'directorist'), $member_since); ?></span>
			</div>
		</div>

		<div class="atbd_widget_contact_info">
			<ul>
				<?php
				if (!empty($address)) { ?>
					<li>
						<span class="<?php atbdp_icon_type(true);?>-map-marker"></span>
						<span class="atbd_info"><?php echo esc_html($address); ?></span>
					</li>
					<?php
				}

				if (isset($phone) && !is_empty_v($phone)) { ?>
					<li>
						<span class="<?php atbdp_icon_type(true);?>-phone"></span>
						<span class="atbd_info"><a href="tel:<?php echo esc_html(stripslashes($phone)); ?>"><?php echo esc_html(stripslashes($phone)); ?></a></span>
					</li>
					<?php
				}

				if ('public' === $email_show) {
					if (!empty($email)) {
						?>
						<li>
							<span class="<?php atbdp_icon_type(true);?>-envelope"></span>
							<span class="atbd_info"><?php echo esc_html($email); ?></span>
						</li>
						<?php
					}
				}

				elseif ('logged_in' === $email_show) {
					if (atbdp_logged_in_user()) {
						if (!empty($email)) {
							?>
							<li>
								<span class="<?php atbdp_icon_type(true);?>-envelope"></span>
								<span class="atbd_info"><?php echo esc_html($email); ?></span>
							</li>
							<?php
						}
					}
				}

				if (!empty($website)) { ?>
					<li>
						<span class="<?php atbdp_icon_type(true);?>-globe"></span>
						<a href="<?php echo esc_url($website); ?>" class="atbd_info" <?php echo is_directoria_active() ? 'style="text-transform: none;"' : ''; ?>><?php echo esc_url($website); ?></a>
					</li>
					<?php
				}
				?>

			</ul>
		</div>

		<?php if (!empty($facebook || $twitter || $linkedIn || $youtube )) { ?>
			<div class="atbd_social_wrap">
				<?php
				if ($facebook) {
					printf('<p><a target="_blank" href="%s"><span class="'.atbdp_icon_type().'-facebook"></span></a></p>', $facebook);
				}
				if ($twitter) {
					printf('<p><a target="_blank" href="%s"><span class="'.atbdp_icon_type().'-twitter"></span></a></p>', $twitter);
				}
				if ($linkedIn) {
					printf('<p><a target="_blank" href="%s"><span class="'.atbdp_icon_type().'-linkedin"></span></a></p>', $linkedIn);
				}
				if ($youtube) {
					printf('<p><a target="_blank" href="%s"><span class="'.atbdp_icon_type().'-youtube"></span></a></p>', $youtube);
				}
				?>
			</div>
			<?php
		}
		?>

		<a href="<?php echo ATBDP_Permalink::get_user_profile_page_link($author_id); ?>" class="<?php echo atbdp_directorist_button_classes(); ?>"><?php esc_html_e('View Profile', 'directorist'); ?></a>
	</div>
</div>