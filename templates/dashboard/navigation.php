<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="atbd_user_dashboard_nav atbd_tab_nav">

	<div class="atbdp_tab_nav_wrapper">

		<ul class="atbdp_tab_nav--content atbd-dashboard-nav">

			<?php foreach ( $dashboard->dashboard_tabs() as $key => $value ): ?>

				<li class="atbdp_tab_nav--content-link">
					<a href="" class="atbdp_all_booking_nav-link atbd-dash-nav-dropdown atbd_tn_link" target="<?php echo esc_attr( $key );?>"><i class="<?php echo esc_attr( $value['icon'] ); ?>"></i><?php echo wp_kses_post( $value['title'] ); ?></a>
				</li> 

				<?php do_action( 'directorist_dashboard_navigation', $key, $dashboard ); ?>

			<?php endforeach; ?>

			<?php do_action( 'directorist_after_dashboard_navigation', $dashboard ); ?>

		</ul>

	</div>

	<?php $dashboard->nav_buttons_template(); ?>

</div>