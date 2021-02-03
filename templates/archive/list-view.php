<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-archive-list-view">

	<div class="<?php Helper::directorist_container_fluid(); ?>">
		<div class="<?php Helper::directorist_row(); ?>">
			<div class="<?php Helper::directorist_column( 12 ); ?>">
				<?php if ( $listings->have_posts() ): ?>

				<?php foreach ( $listings->post_ids() as $listing_id ): ?>

					<?php $listings->loop_template( 'list', $listing_id ); ?>
					
				<?php endforeach; ?>

				<?php
				if ( $listings->show_pagination ) {
				$listings->pagination();
				}
				?>

				<?php else: ?>

				<div class="directorist-archive-notfound"><?php esc_html_e( 'No listings found.', 'directorist' ); ?></div>

				<?php endif; ?>
			</div>
		</div>
		
	</div>
	
</div>