<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="atbdp_tab_nav_wrapper">
	<ul class="atbdp_tab_nav--content atbd-dashboard-nav">
		<?php foreach ($dashboard_tabs as $key => $value): ?>
			<li class="atbdp_tab_nav--content-link"><a href="" class="atbd_tn_link" target="<?php echo esc_attr($key);?>"><?php echo wp_kses_post( $value['title'] ); ?></a>
			</li>
			<?php
			if (!empty($value['after_nav_hook'])) {
				do_action($value['after_nav_hook']);
			}
			?>
		<?php endforeach; ?>
		<li class="atbdp_tab_nav--content-link atbdp_tab_nav--has-child">
			<a href="" class="atbdp_all_booking_nav-link atbd-dash-nav-dropdown">
				<span class="directorist_menuItem-text"><span class="directorist_menuItem-icon"><i class="la la-search"></i></span>Menu Item</span>
				<i class="la la-angle-down"></i>
			</a>
			<ul class="atbd-dashboard-nav">
				<li>
					<a href="#" target="tab1" class="atbd_tn_link"></i>Submenu 1</a>
				</li>
				<li>
					<a href="#" target="tab2" class="atbd_tn_link">Submenu 2</a>
				</li>
				<li>
					<a href="#" target="tab3" class="atbd_tn_link">Submenu 3</a>
				</li>
			</ul>
		</li>
	</ul>
</div>
