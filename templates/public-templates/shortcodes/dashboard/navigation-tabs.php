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
	</ul>
</div>
