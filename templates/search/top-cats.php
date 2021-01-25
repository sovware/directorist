<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.6
 */
?>
<div class="directory_home_category_area atbdp_search-category">

	<ul class="categories atbdp_listing_top_category">
		<?php 
		 $counter = 0;
		foreach ($top_categories as $cat) { 
			apply_filters('atbdp_popular_category_loop', $counter++, $cat);
			?>
			<li>
				<a href="<?php echo ATBDP_Permalink::atbdp_get_category_page($cat); ?>" <?php do_action('search_home_popular_category', $counter); ?> >
					<span class="<?php echo esc_attr($searchform->category_icon_class($cat)); ?>" aria-hidden="true"></span>
					<p><?php echo esc_html( $cat->name ); ?></p>
				</a>
			</li>
		<?php } ?>
	</ul>
	
	<?php do_action('atbdp_search_after_popular_categories');?>
</div>