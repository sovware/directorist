<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<?php do_action( 'directorist_before_author_about_section' ); ?>

<div class="<?php Helper::directorist_row(); ?> directorist-mb-40">

	<div class="<?php Helper::directorist_column( 'md-8' ); ?>">
		<div class="directorist-card directorist-author-about">

			<div class="directorist-card__header">
				<h4 class="directorist-card__header--title"><span class="<?php atbdp_icon_type( true ); ?>-user"></span><?php esc_html_e( 'About', 'directorist' ); ?></h4>
			</div>

			<div class="directorist-card__body">
				<div class="directorist-author-about__content">
					<p><?php echo $bio ? wp_kses_post( $bio ) : esc_html__( 'Nothing to show!', 'directorist' );?></p>
				</div>
			</div>

		</div>
	</div>

	<div class="<?php Helper::directorist_column( 'md-4' ); ?>">
		<div class="directorist-card directorist-widget directorist-author-contact">

			<div class="directorist-card__header">
				<h4 class="directorist-card__header--title directorist-widget-title"><?php esc_html_e( 'Contact Info', 'directorist' ); ?></h4>
			</div>

			<div class="directorist-card__body">
				<div class="directorist-author-info-widget">

					<ul class="directorist-author-info-list">

						<?php if ( $address ): ?>
							<li class="directorist-author-info-list__item">
								<span class="<?php atbdp_icon_type(true); ?>-map-marker"></span>
								<span class="directorist-info"><?php echo esc_html( $address ); ?></span>
							</li>
						<?php endif; ?>

						<?php if ( $phone ): ?>
							<li class="directorist-author-info-list__item">
								<span class="<?php atbdp_icon_type(true); ?>-phone"></span>
								<span class="directorist-info"><a href="tel:<?php ATBDP_Helper::sanitize_tel_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a></span>
							</li>
						<?php endif; ?>

						<?php if ( $email_endabled && $email ): ?>
							<li class="directorist-author-info-list__item">
								<span class="<?php atbdp_icon_type( true ); ?>-envelope"></span>
								<span class="directorist-info"><?php echo esc_html( $email ); ?></span>
							</li>
						<?php endif; ?>

						<?php if ( $website ): ?>
							<li class="directorist-author-info-list__item">
								<span class="<?php atbdp_icon_type( true ); ?>-globe"></span>
								<span class="directorist-info"><a target="_blank" href="<?php echo esc_url( $website ); ?>"><?php echo esc_html( $website) ; ?></a></span>
							</li>
						<?php endif; ?>

					</ul>

					<?php if ( $facebook || $twitter || $linkedin || $youtube ): ?>

						<ul class="directorist-author-social">

							<?php if ( $facebook ): ?>
								<li class="directorist-author-social-item"><a target="_blank" href="<?php echo esc_url( $facebook ); ?>"><span class="<?php atbdp_icon_type( true ); ?>-facebook"></span></a></li>
							<?php endif; ?>

							<?php if ( $twitter ): ?>
								<li class="directorist-author-social-item"><a target="_blank" href="<?php echo esc_url( $twitter ); ?>"><span class="<?php atbdp_icon_type( true ); ?>-twitter"></span></a></li>
							<?php endif; ?>

							<?php if ( $linkedin ): ?>
								<li class="directorist-author-social-item"><a target="_blank" href="<?php echo esc_url( $linkedin ); ?>"><span class="<?php atbdp_icon_type( true ); ?>-linkedin"></span></a></li>
							<?php endif; ?>

							<?php if ( $youtube ): ?>
								<li class="directorist-author-social-item"><a target="_blank" href="<?php echo esc_url( $youtube ); ?>"><span class="<?php atbdp_icon_type( true ); ?>-youtube"></span></a></li>
							<?php endif; ?>

						</ul>

					<?php endif; ?>
				</div>
			</div>

		</div>
	</div>

</div>

<?php do_action( 'directorist_author_listing_after_about_section' ); ?>