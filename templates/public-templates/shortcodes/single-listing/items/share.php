<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<div class="atbd_action atbd_share atbd_tooltip" aria-label="<?php esc_html_e('Share', 'directorist'); ?>">
	<span class="<?php echo atbdp_icon_type(); ?>-share"></span>
	<div class="atbd_directory_social_wrap">
		<ul>
			<?php foreach ( $listing->social_share_data() as $social ): ?>
				<li><a href="<?php echo esc_url( $social['link'] );?>"><span class="<?php echo esc_attr( $social['icon'] );?>"></span><?php echo esc_html( $social['title'] );?></a></li>
			<?php endforeach; ?>							
		</ul>
	</div>
</div>