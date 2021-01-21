<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$loc_list = get_the_term_list( $listing->id, ATBDP_LOCATION, '', ', ');

if ( empty( $loc_list ) ) {
	return;
}
?>
<div class="atbd-listing-location">
	<ul class="directory_cats">
		<li><span class="<?php atbdp_icon_type(true);?>-map-marker"></span></li>
		<li><p class="directory_tag"><span><?php echo $loc_list;?></span></p></li>
	</ul>
</div>