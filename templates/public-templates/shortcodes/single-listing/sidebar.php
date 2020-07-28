<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */

if ( is_active_sidebar( 'right-sidebar-listing' ) ) { ?>
	<div class="directorist col-lg-4 col-md-12">
		<div class="directorist atbd_sidebar">
			<?php dynamic_sidebar(apply_filters('atbdp_single_listing_register_sidebar', 'right-sidebar-listing')); ?>
		</div>
	</div>
	<?php
}