<?php
/**
 * @template_description1
 *
 * @template_description2
 *
 * @author  wpWax
 * @since   6.6
 * @version 6.6
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-archive-grid-view">

	<div class="<?php Helper::directorist_container(); ?>">

		<?php if ( $listings->have_posts() ): ?>

			<div class="<?php Helper::directorist_row(); ?>">

				<?php foreach ( $listings->post_ids() as $listing_id ): ?>

					<div class="<?php Helper::directorist_column( $listings->columns ); ?>">
						<?php $listings->loop_template( 'grid', $listing_id ); ?>
					</div>
					
				<?php endforeach; ?>

			</div>

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