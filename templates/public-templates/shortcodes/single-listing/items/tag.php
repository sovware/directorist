<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$tags = get_the_terms( $listing->id, ATBDP_TAGS );
?>
<ul>
	<?php
	foreach ($tags as $tag) {
		$link = ATBDP_Permalink::atbdp_get_tag_page($tag);
		$name = $tag->name;
		?>
		<li><a href="<?php echo esc_url($link); ?>"><span class="<?php echo apply_filters('atbdp_single_listing_tags_icon', atbdp_icon_type().'-tag'); ?>"></span> <?php echo esc_html($name); ?></a></li>
		<?php
	}
	?>
</ul>