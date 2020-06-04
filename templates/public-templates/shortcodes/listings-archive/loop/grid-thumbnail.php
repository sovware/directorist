<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>

<div class="atbd_listing_image">
	
	<?php
	$listings->loop_thumb_card_template();

	if ($listings->display_author_image) { ?>
		<div class="atbd_author"><?php $listings->loop_author_template(); ?></div>
		<?php
	}
	?>
</div>

<?php
/**
 * @since 5.0
 * @hooked Directorist_Template_Hooks::business_hours_badge - 10
 */
?>
<span class="atbd_upper_badge bh_only"><?php echo apply_filters('atbdp_upper_badges', '');?></span>

<?php
/**
 * @since 5.0
 * @hooked Directorist_Template_Hooks::featured_badge - 10
 * @hooked Directorist_Template_Hooks::popular_badge - 15
 * @hooked Directorist_Template_Hooks::new_listing_badge - 20
 */
?>
<span class="atbd_lower_badge"><?php echo apply_filters('atbdp_grid_lower_badges', '');?></span>

<?php
/**
 * @since 7.0
 * @hooked Directorist_Template_Hooks::mark_as_favourite_button - 10
 */
do_action('atbdp_listing_thumbnail_area');