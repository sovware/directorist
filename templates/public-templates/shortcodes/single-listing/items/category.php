<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$cat_list = get_the_term_list( $listing->id, ATBDP_CATEGORY, '', ', ');

if ( empty( $cat_list ) ) {
	return;
}
?>
<div class="atbd_listing_category">
	<ul class="directory_cats">
		<li><span class="<?php atbdp_icon_type(true);?>-tags"></span></li>
		<li><p class="directory_tag"><span><?php echo $cat_list;?></span></p></li>
	</ul>
</div>