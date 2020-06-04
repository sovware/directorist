<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */

if ( $listings->display_category || $listings->display_view_count ) { ?>
	<div class="atbd_listing_bottom_content">
		
		<?php if ( $listings->display_category ): ?>
			<div class="atbd_content_left">
				<?php $listings->loop_cats_template();?> 
			</div>
		<?php endif; ?>

		<?php $listings->loop_grid_footer_right_template();?> 
	</div>
	<?php
}