<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="atbd_dropdown">
	<a class="atbd_dropdown-toggle" href="#" id="sortByDropdownMenuLink"><?php echo esc_html( $listings->sort_by_text ); ?><span class="atbd_drop-caret"></span></a>
	<div class="atbd_dropdown-menu atbd_dropdown-menu--lg" aria-labelledby="sortByDropdownMenuLink">
		<form id="atbdp_sort" method="post" action="">
		<?php foreach ($listings->get_sort_by_link_list() as $key => $value) {
			global $wp;
			$current_url =  home_url( $wp->request ) . '/';
			$pattern = '/page\\/[0-9]+\\//i';
			$actual_link = preg_replace($pattern, '', $current_url);
			?>
			<a class="atbdp_sorting_item atbd_dropdown-item<?php echo esc_attr($value['active_class']);?>" data="<?php echo esc_attr($value['link']);?>"><?php echo esc_html($value['label']);?></a>
		<?php } ?>
		</form>
	</div>
</div>