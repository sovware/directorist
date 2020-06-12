<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div id="directorist" class="atbd_wrapper atbdp atbdp-categories atbdp-text-list">
	<div class="row atbdp-no-margin">
		<?php
		foreach ($categories as $category) {
			$plus_icon = $category['has_child'] ? '<span class="expander">+</span>' : '';
			?>
			<div class="<?php echo esc_attr($list_col_class);?>">
				<div class="atbd_category_wrapper">
					<a href="<?php echo esc_url($category['permalink']);?>" class="atbd_parent_cat"><span><?php echo esc_html($category['name']);?></span><?php echo $category['list_count_html'];?></a><?php echo $plus_icon;?>
					<?php echo $category['subterm_html'];?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>