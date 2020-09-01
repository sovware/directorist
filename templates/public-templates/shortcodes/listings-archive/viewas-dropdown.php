<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="atbd_dropdown">
	<a class="atbd_dropdown-toggle" href="#" id="viewAsDropdownMenuLink"><?php echo esc_html( $listings->view_as_text ); ?><span class="atbd_drop-caret"></span></a>
	<div class="atbd_dropdown-menu" aria-labelledby="viewAsDropdownMenuLink">
		<?php foreach ($listings->get_view_as_link_list() as $key => $value) {?>
			<a class="atbd_dropdown-item<?php echo esc_attr($value['active_class']);?>" href="<?php echo esc_attr($value['link']);?>"><?php echo esc_html($value['label']);?></a>
		<?php } ?>
	</div>
</div>