<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="<?php Helper::directorist_row(); ?>">

	<div class="<?php Helper::directorist_column( 8 ); ?>">

		<div class="atbd_author_module">

			<div class="atbd_area_title">
				<h4><span class="<?php atbdp_icon_type( true ); ?>-user"></span><?php esc_html_e( 'About', 'directorist' ); ?></h4>
			</div>

			<div class="atbdb_content_module_contents">
				<div><?php echo $bio ? wp_kses_post( $bio ) : esc_html__( 'Nothing to show!', 'directorist' );?></div>
			</div>

		</div>

	</div>

	<div class="<?php Helper::directorist_column( 4 ); ?>">

		<div class="widget atbd_widget">

			<div class="atbd_widget_title">
				<h4><?php esc_html_e( 'Contact Info', 'directorist' ); ?></h4>
			</div>

			<div class="atbdp atbd_author_info_widget">

				<ul>
					<?php if ( $address ): ?>
						<li>
							<span class="<?php atbdp_icon_type(true); ?>-map-marker"></span>
							<span class="atbd_info"><?php echo esc_html( $address ); ?></span>
						</li>
					<?php endif; ?>

					<?php if ( $phone ): ?>
						<li>
							<span class="<?php atbdp_icon_type(true); ?>-phone"></span>
							<span class="atbd_info"><a href="tel:<?php ATBDP_Helper::sanitize_tel_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a></span>
						</li>
					<?php endif; ?>

					<?php if ( $email_endabled && $email ): ?>
						<li>
							<span class="<?php atbdp_icon_type( true ); ?>-envelope"></span>
							<span class="atbd_info"><?php echo esc_html( $email ); ?></span>
						</li>
					<?php endif; ?>

					<?php if ( $website ): ?>
						<li>
							<span class="<?php atbdp_icon_type( true ); ?>-globe"></span>
							<span class="atbd_info"><a target="_blank" href="<?php echo esc_url( $website ); ?>"><?php echo esc_html( $website) ; ?></a></span>
						</li>
					<?php endif; ?>
				</ul>

				<?php if ( $facebook || $twitter || $linkedIn || $youtube ): ?>

					<ul class="atbd_social_wrap">

						<?php if ( $facebook ): ?>
							<li><a target="_blank" href="<?php echo esc_url( $facebook ); ?>"><span class="<?php atbdp_icon_type( true ); ?>-facebook"></span></a></li>
						<?php endif; ?>

						<?php if ( $twitter ): ?>
							<li><a target="_blank" href="<?php echo esc_url( $twitter ); ?>"><span class="<?php atbdp_icon_type( true ); ?>-twitter"></span></a></li>
						<?php endif; ?>

						<?php if ( $linkedin ): ?>
							<li><a target="_blank" href="<?php echo esc_url( $linkedin ); ?>"><span class="<?php atbdp_icon_type( true ); ?>-linkedin"></span></a></li>
						<?php endif; ?>

						<?php if ( $youtube ): ?>
							<li><a target="_blank" href="<?php echo esc_url( $youtube ); ?>"><span class="<?php atbdp_icon_type( true ); ?>-youtube"></span></a></li>
						<?php endif; ?>
					</ul>

				<?php endif; ?>

			</div>

		</div>

	</div>
	
</div>