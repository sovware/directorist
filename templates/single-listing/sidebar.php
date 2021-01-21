<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */

if ( is_active_sidebar( 'right-sidebar-listing' ) ) { ?>
	<div class="directorist col-lg-4 col-md-12">
		<div class="directorist atbd_sidebar">
			<?php dynamic_sidebar(apply_filters('atbdp_single_listing_register_sidebar', 'right-sidebar-listing')); ?>
		</div>
	</div>
	<?php
}