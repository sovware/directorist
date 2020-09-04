<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */

if (!$listings->disable_single_listing) { ?>
	<a href="<?php echo esc_url( $listings->loop['permalink'] ); ?>" <?php echo $listings->loop_link_attr(); ?>><?php atbdp_thumbnail_card(); ?></a>
	<?php
}
else {
	atbdp_thumbnail_card();
}