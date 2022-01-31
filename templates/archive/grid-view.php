<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;

$query = $listings->get_query();
?>

<div class="directorist-archive-grid-view">

	<?php do_action( 'directorist_before_grid_listings_loop' ); ?>

	<div class="<?php Helper::directorist_container_fluid(); ?>">

		<?php if ( $query->have_posts() ): ?>

			<div class="<?php Helper::directorist_row(); ?>">

				<?php while ( $query->have_posts() ): ?>

					<?php $query->the_post(); ?>

					<div class="<?php Helper::directorist_column( $listings->columns() ); ?> directorist-all-listing-col">
						<?php $listings->loop_template(); ?>
					</div>

				<?php endwhile; ?>

				<?php wp_reset_postdata(); ?>

			</div>

			<?php do_action( 'directorist_before_listings_pagination' ); ?>

			<?php
			if ( $listings->display_pagination() ) {
				$listings->pagination_template();
			}
			?>

			<?php do_action('directorist_after_grid_listings_loop'); ?>

		<?php else: ?>

			<div class="directorist-archive-notfound"><?php esc_html_e( 'No listings found.', 'directorist' ); ?></div>

		<?php endif; ?>

	</div>

</div>