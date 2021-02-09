<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$id = $listing->id;

$author_id           = get_post_field('post_author', $id);
$u_pro_pic           = get_user_meta($author_id, 'pro_pic', true);
$u_pro_pic           = !empty($u_pro_pic) ? wp_get_attachment_image_src($u_pro_pic, 'thumbnail') : '';
$author_img          = !empty($u_pro_pic) ? $u_pro_pic[0] : '';
$avatar_img          = get_avatar($author_id, 32);

$user_registered     = get_the_author_meta('user_registered', $author_id);
$member_since        = human_time_diff(strtotime($user_registered), current_time('timestamp'));
$author_name         = get_the_author_meta('display_name', $author_id);
$address             = get_user_meta($author_id, 'address', true);
$phone               = get_user_meta($author_id, 'atbdp_phone', true);
$email_display_type  = get_directorist_option('display_author_email', 'public');
$email               = get_the_author_meta('user_email', $author_id);
$website             = get_the_author_meta('user_url', $author_id);
$facebook            = get_user_meta($author_id, 'atbdp_facebook', true);
$twitter             = get_user_meta($author_id, 'atbdp_twitter', true);
$linkedIn            = get_user_meta($author_id, 'atbdp_linkedin', true);
$youtube             = get_user_meta($author_id, 'atbdp_youtube', true);
?>

<div class="atbd_content_module <?php echo esc_attr( $class );?>" <?php $listing->section_id( $id ); ?>>

	<div class="atbd_content_module_title_area">
		<h4><?php directorist_icon( $icon );?><?php echo esc_html( $label );?></h4>
	</div>

	<div class="atbdb_content_module_contents">

		<div class="directorist-single-author-info">

			<div class="atbd_avatar_wrapper">

				<div class="atbd_review_avatar">
					<?php if ( $author_img ): ?>
						<img src="<?php echo esc_url( $author_img ); ?>" alt="<?php esc_attr_e('Avatar Image', 'directorist');?>">
					<?php else: ?>
						<?php echo $avatar_img; ?>
					<?php endif; ?>
				</div>

				<div class="atbd_name_time">
					<h4><?php echo esc_html( $author_name ); ?></h4>
					<span class="review_time"><?php printf( esc_html__('Member since %s ago', 'directorist'), $member_since ); ?></span>
				</div>

			</div>

			<div class="atbd_widget_contact_info">
				<ul>

					<?php if ( $address ): ?>
						<li>
							<span class="<?php atbdp_icon_type( true );?>-map-marker"></span>
							<span class="atbd_info"><?php echo esc_html( $address ); ?></span>
						</li>
					<?php endif; ?>


					<?php if ( $phone ): ?>
						<li>
							<span class="<?php atbdp_icon_type(true);?>-phone"></span>
							<span class="atbd_info"><a href="tel:<?php Helper::formatted_tel( $phone ); ?>"><?php echo esc_html( $phone ); ?></a></span>
						</li>
					<?php endif; ?>

					<?php if ( $email && $email_display_type == 'public' ): ?>
						<li>
							<span class="<?php atbdp_icon_type(true);?>-envelope"></span>
							<span class="atbd_info"><?php echo esc_html( $email ); ?></span>
						</li>
					<?php elseif ( $email && $email_display_type == 'logged_in' && atbdp_logged_in_user() ): ?>
						<li>
							<span class="<?php atbdp_icon_type(true);?>-envelope"></span>
							<span class="atbd_info"><?php echo esc_html($email); ?></span>
						</li>
					<?php endif; ?>

					<?php if (!empty($website)): ?>
						<li>
							<span class="<?php atbdp_icon_type(true);?>-globe"></span>
							<a href="<?php echo esc_url($website); ?>" class="atbd_info" <?php echo is_directoria_active() ? 'style="text-transform: none;"' : ''; ?>><?php echo esc_url($website); ?></a>
						</li>
					<?php endif; ?>

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

</div>