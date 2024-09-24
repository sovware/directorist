<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 8.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$id         = $listing->id;
$author_id  = $listing->author_id;
$u_pro_pic  = get_user_meta($author_id, 'pro_pic', true);
$u_pro_pic  = !empty($u_pro_pic) ? wp_get_attachment_image_src($u_pro_pic, 'thumbnail') : '';
$author_img = !empty($u_pro_pic) ? $u_pro_pic[0] : '';
$avatar_img = get_avatar($author_id, 32);
?>

<section class="directorist-card directorist-card-author-info <?php echo esc_attr( $class );?>" <?php $listing->section_id( $id ); ?>>

	<header class="directorist-card__header">

		<h3 class="directorist-card__header__title">
			<?php if ( ! empty( $icon ) ) : ?>
				<span class="directorist-card__header-icon"><?php directorist_icon( $icon ); ?></span>
			<?php endif; ?>
			<span class="directorist-card__header-text"><?php echo esc_html( $label ); ?></span>
		</h3>

	</header>

	<div class="directorist-card__body">

		<div class="directorist-single-author-info">

			<div class="directorist-single-author-avatar">

				<figure class="directorist-single-author-avatar-inner">
					<?php if ( $author_img ): ?>
						<img src="<?php echo esc_url( $author_img ); ?>" alt="<?php esc_attr_e( 'Avatar', 'directorist' );?>">
					<?php else: ?>
						<?php echo wp_kses_post( $avatar_img ); ?>
					<?php endif; ?>
				</figure>

				<div class="directorist-single-author-name">
					<h4><?php echo esc_html( $listing->author_info( 'name' ) ); ?></h4>
					<span class="directorist-single-author-membership"><?php printf( esc_html__( 'Member since %s ago', 'directorist' ), esc_html( $listing->author_info( 'member_since' ) ) ); ?></span>
				</div>

			</div>

			<div class="directorist-single-author-contact-info">

				<ul>
					<?php if ( $address = $listing->author_info( 'address' ) ): ?>
						<li>
							<?php directorist_icon( 'fas fa-map-marker-alt' ); ?>
							<span class="directorist-single-author-contact-info-text"><?php echo esc_html( $address ); ?></span>
						</li>
					<?php endif; ?>


					<?php if ( $phone = $listing->author_info( 'phone' ) ): ?>
						<li>
							<?php directorist_icon( 'fas fa-phone-alt' ); ?>
							<span class="directorist-single-author-contact-info-text">
								<a href="tel:<?php Helper::formatted_tel( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
							</span>
						</li>
					<?php endif; ?>

					<?php if ( $listing->author_display_email( $section_data ) ): ?>
						<li>
							<?php directorist_icon( 'fas fa-envelope-open' ); ?>
							<span class="directorist-single-author-contact-info-text"><?php echo esc_html( $listing->author_info( 'email' ) ); ?></span>
						</li>
					<?php endif; ?>

					<?php if ( $website = $listing->author_info( 'website' ) ): ?>
						<li>
							<?php directorist_icon( 'fas fa-globe-americas' ); ?>
							<a href="<?php echo esc_url( $website ); ?>" class="directorist-single-author-contact-info-text"><?php echo esc_url( $website ); ?></a>
						</li>
					<?php endif; ?>
				</ul>

			</div>

			<?php if ( $listing->author_has_socials() ): ?>

				<ul class="directorist-author-social">

					<?php if ( $facebook = $listing->author_info( 'facebook' ) ): ?>
						<li class="directorist-author-social-item directorist-author-social__item"><a target="_blank" href="<?php echo esc_url( $facebook ); ?>"><?php directorist_icon( 'lab la-facebook' ); ?></a></li>
					<?php endif; ?>

					<?php if ( $twitter = $listing->author_info( 'twitter' ) ): ?>
						<li class="directorist-author-social-item directorist-author-social__item"><a target="_blank" href="<?php echo esc_url( $twitter ); ?>"><?php directorist_icon( 'lab la-twitter' ); ?></a></li>
					<?php endif; ?>

					<?php if ( $linkedin = $listing->author_info( 'linkedin' ) ): ?>
						<li class="directorist-author-social-item directorist-author-social__item"><a target="_blank" href="<?php echo esc_url( $linkedin ); ?>"><?php directorist_icon( 'lab la-linkedin' ); ?></a></li>
					<?php endif; ?>

					<?php if ( $youtube = $listing->author_info( 'youtube' ) ): ?>
						<li class="directorist-author-social-item directorist-author-social__item"><a target="_blank" href="<?php echo esc_url( $youtube ); ?>"><?php directorist_icon( 'lab la-youtube' ); ?></a></li>
					<?php endif; ?>

				</ul>

			<?php endif; ?>

			<a class="directorist-btn directorist-btn-light directorist-btn-md diretorist-view-profile-btn" href="<?php echo esc_url( ATBDP_Permalink::get_user_profile_page_link( $author_id ) ); ?>"><?php esc_html_e( 'View Profile', 'directorist' ); ?></a>

		</div>
	</div>

</section>