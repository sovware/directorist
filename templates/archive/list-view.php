<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-archive-items directorist-archive-list-view">
	<div class="<?php Helper::directorist_container_fluid(); ?>">

		<?php do_action( 'directorist_before_list_listings_loop' ); ?>

			<?php if ( $listings->have_posts() ): ?>

				<?php foreach ( $listings->post_ids() as $listing_id ): ?>

					<?php $listings->loop_template( 'list', $listing_id ); ?>

				<?php endforeach; ?>

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