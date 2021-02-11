<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( empty( $listing->get_tags() ) ) {
	return;
}
?>

<ul>
	<?php foreach ( $listing->get_tags() as $tag ): ?>
		<li><a href="<?php echo esc_url( ATBDP_Permalink::atbdp_get_tag_page( $tag ) ); ?>"><span class="<?php echo apply_filters('atbdp_single_listing_tags_icon', atbdp_icon_type().'-tag'); ?>"></span> <?php echo esc_html( $tag->name ); ?></a></li>
	<?php endforeach; ?>
</ul>