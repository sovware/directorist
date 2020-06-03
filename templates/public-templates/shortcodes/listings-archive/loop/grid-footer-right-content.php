<?php
if ($listings->display_view_count) { ?>
	<ul class="atbd_content_right">
		<li class="atbd_count"><span class="<?php echo atbdp_icon_type() ?>-eye"></span><?php echo ( ! empty($listings->loop['post_view']) ) ? $listings->loop['post_view'] : 0; ?></li>
	</ul>
	<?php
}