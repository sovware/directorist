<?php
if ( $listings->display_category || $listings->display_view_count || $listings->display_author_image ) { ?>
	<div class="atbd_listing_bottom_content">
		
		<?php if ( $listings->display_category ): ?>
			<div class="atbd_content_left">
				<?php $listings->loop_cats_template();?> 
			</div>
		<?php endif;

		if ( $listings->display_view_count || $listings->display_author_image ) { ?>
			<ul class="atbd_content_right">

				<?php if ( $listings->display_view_count ) { ?>
					<li class="atbd_count"><?php $listings->loop_view_count_template(); ?></li>
					<?php
				}

				if ( $listings->display_author_image ) { ?>
					<li class="atbd_author"><?php $listings->loop_author_template(); ?></li>
					<?php
				}?>
			</ul>
			<?php
		}?>
	</div>
	<?php
}