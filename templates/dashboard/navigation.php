<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$counter = 1;
?>

<div class="directorist-user-dashboard__nav directorist-tab__nav">
	<span class="directorist-dashboard__nav__close"><?php directorist_icon( 'las la-times' ); ?></span>
	<div class="directorist-tab__nav__wrapper">

		<ul class="directorist-tab__nav__items">

			<?php foreach ( $dashboard->dashboard_tabs() as $key => $value ): ?>

				<li class="directorist-tab__nav__item">
					<a href="#" class="directorist-booking-nav-link directorist-tab__nav__link <?php echo ( $counter == 1 ) ? 'directorist-tab__nav__active' : ''; ?>" target="<?php echo esc_attr( $key ); ?>">
						<span class="directorist_menuItem-text">
							<span class="directorist_menuItem-icon">
								<?php directorist_icon( $value['icon'] ); ?>
							</span>
							<?php echo wp_kses_post( $value['title'] ); ?>
						</span>
					</a>
				</li>

				<?php do_action( 'directorist_dashboard_navigation', $key, $dashboard ); ?>
				<?php $counter++; ?>

			<?php endforeach; ?>

			<?php do_action( 'directorist_after_dashboard_navigation', $dashboard ); ?>

		</ul>

	</div>

	<?php $dashboard->nav_buttons_template(); ?>

</div>