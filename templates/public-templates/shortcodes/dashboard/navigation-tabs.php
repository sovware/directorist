<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="atbdp_tab_nav_wrapper">
	<ul class="atbdp_tab_nav--content atbd-dashboard-nav">
		<?php foreach ($dashboard_items as $key => $value): ?>
			<li class="atbdp_tab_nav--content-link"><a href="" class="atbd_tn_link" target="<?php echo esc_attr($key);?>"><?php echo wp_kses_post( $value['title'] ); ?></a>
			</li>
			<?php
			if (!empty($value['after_nav_hook'])) {
				do_action($value['after_nav_hook']);
			}
			?>
		<?php endforeach; ?>
		<li class="atbdp_tab_nav--content-link atbdp-tab-nav-last">
			<a href="#" class="atbdp-tab-nav-link"><span class="fa fa-ellipsis-h"></span></a>
		</li>
	</ul>
</div>