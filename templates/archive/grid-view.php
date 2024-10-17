<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-archive-items directorist-archive-grid-view">
	<div class="<?php Helper::directorist_container_fluid(); ?>">

		<?php do_action( 'directorist_before_grid_listings_loop' ); ?>

		<?php if ( $listings->have_posts() ): ?>

			<div class="<?php echo $listings->has_masonry() ? 'directorist-masonry' : ''; ?> <?php Helper::directorist_row(); ?>">

				<?php foreach ( $listings->post_ids() as $listing_id ): ?>

					<div class="<?php Helper::directorist_column( $listings->columns ); ?> directorist-all-listing-col"><!-- directorist-all-listing-col -->
						<?php $listings->loop_template( 'grid', $listing_id ); ?>
					</div>

				<?php endforeach; ?>

			</div>

			<?php
			if ( $listings->show_pagination ) {

				do_action( 'directorist_before_listings_pagination' );

				$listings->pagination();

				do_action( 'directorist_after_listings_pagination' );
			}
			?>

			<?php do_action('directorist_after_grid_listings_loop'); ?>

		<?php else: ?>

			<div class="directorist-archive-notfound"><?php esc_html_e( 'No listings found.', 'directorist' ); ?></div>

		<?php endif; ?>
	</div>
</div>