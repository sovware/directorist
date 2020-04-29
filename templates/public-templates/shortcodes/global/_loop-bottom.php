<?php
$catViewCount = '';
// atbdp_get_shortcode_template( 'global/loop-bottom', compact( 'display_category', 'display_view_count', 'cats', 'post_view' ) );
?>
<?php if (!empty($display_category) || !empty($display_view_count)): ?>
	<div class="atbd_listing_bottom_content">
	<?php if (!empty($display_category)): ?>
		<?php if (!empty($cats)): ?>
		    <div class="atbd_content_left">
		        <div class="atbd_listing_category">
		        	<a href="<?php echo esc_url(ATBDP_Permalink::atbdp_get_category_page($cats[0])); ?>"><span class="<?php echo esc_attr(atbdp_icon_type()); ?>-tags"></span><?php echo $cats[0]->name; ?></a>
		            <div class="atbd_cat_popup"><span>+1</span>
		                <div class="atbd_cat_popup_wrapper"><span> <span><a href="http://localhost/wpwax/drestaurant/single-category/outdoor-activities/">Outdoor Activities<span>,</span></a>
		                    </span>
		                    </span>
		                </div>
		            </div>
		        </div>
		    </div>
		<?php else: ?>
		    <div class="atbd_content_left">
		        <div class="atbd_listing_category"><a href="http://localhost/wpwax/drestaurant/single-category/hotels/"><span class="la la-tags"></span>Hotels</a>
		            <div class="atbd_cat_popup"><span>+1</span>
		                <div class="atbd_cat_popup_wrapper"><span> <span><a href="http://localhost/wpwax/drestaurant/single-category/outdoor-activities/">Outdoor Activities<span>,</span></a>
		                    </span>
		                    </span>
		                </div>
		            </div>
		        </div>
		    </div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if (!empty($display_view_count)): ?>
	    <ul class="atbd_content_right">
	        <li class="atbd_count"><span class="<?php echo esc_attr(atbdp_icon_type()); ?>-eye"></span><?php echo !empty($post_view) ? $post_view : 0; ?></li>
	    </ul>
	<?php endif; ?>
	</div>
<?php endif; ?>





<?php
$catViewCount = '';
if (!empty($display_category) || !empty($display_view_count)) {
	$catViewCount .= '<div class="atbd_listing_bottom_content">';
	if (!empty($display_category)) {
		if (!empty($cats)) {
			$totalTerm = count($cats);
			$catViewCount .= '<div class="atbd_content_left">';
			$catViewCount .= '<div class="atbd_listing_category">';
			$catViewCount .= '<a href="' . ATBDP_Permalink::atbdp_get_category_page($cats[0]) . '">';
			$catViewCount .= '<span class="' . atbdp_icon_type() . '-tags"></span>';
			$catViewCount .= $cats[0]->name;
			$catViewCount .= '</a>';
			if ($totalTerm > 1) {
				$totalTerm = $totalTerm - 1;
				$catViewCount .= '<div class="atbd_cat_popup">';
				$catViewCount .= '<span>+' . $totalTerm . '</span>';
				$catViewCount .= '<div class="atbd_cat_popup_wrapper">';
				$output = array();
				foreach (array_slice($cats, 1) as $cat) {
					$link = ATBDP_Permalink::atbdp_get_category_page($cat);
					$space = str_repeat(' ', 1);
					$output[] = "{$space}<span><a href='{$link}'>{$cat->name}<span>,</span></a></span>";
				}
				$catViewCount .= '<span>' . join($output) . '</span>';
				$catViewCount .= '</div>';
				$catViewCount .= '</div>';
			}
			$catViewCount .= '</div>';
			$catViewCount .= '</div>';
		} else {
			$catViewCount .= '<div class="atbd_content_left">';
			$catViewCount .= '<div class="atbd_listing_category">';
			$catViewCount .= '<a href="">';
			$catViewCount .= '<span class="' . atbdp_icon_type() . '-tags"></span>';
			$catViewCount .= __('Uncategorized', 'directorist');
			$catViewCount .= '</a>';
			$catViewCount .= '</div>';
			$catViewCount .= '</div>';
		}
	}
	if (!empty($display_view_count)) {
		$fotter_right = '<ul class="atbd_content_right">';
		$fotter_right .= '<li class="atbd_count">';
		$fotter_right .= '<span class="' . atbdp_icon_type() . '-eye"></span>';
		$fotter_right .= !empty($post_view) ? $post_view : 0;
		$fotter_right .= '</li>';
		$fotter_right .= '</ul>';
		$catViewCount .= apply_filters('atbdp_grid_footer_right_html', $fotter_right);
	}

  $catViewCount .= '</div>'; //end ./atbd_listing_bottom_content

}
echo apply_filters('atbdp_listings_grid_cat_view_count', $catViewCount);

/**
 * @param mixed $footer_html
 * @since
 * @package Directorist
 */
//apply_filters('atbdp_listings_footer_content')