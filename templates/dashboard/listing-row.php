<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( $query->have_posts() ) {

	while ( $query->have_posts() ) {
		$query->the_post();
		?>

		<tr data-id="<?php the_ID(); ?>">

			<?php do_action( 'directorist_dashboard_listing_td_start', $dashboard ); ?>

			<td>
				<div class="directorist-listing-table-listing-info">

					<div class="directorist-listing-table-listing-info__img">
						<a href="<?php the_permalink(); ?>"><?php echo wp_kses_post( $dashboard->get_listing_thumbnail() ); ?></a>
					</div>

					<div class="directorist-listing-table-listing-info__content">

						<h4 class="directorist-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>

						<?php Helper::listing_price(); ?>

					</div>

				</div>
			</td>

			<?php do_action( 'directorist_dashboard_listing_td_2', $dashboard ); ?>

			<?php if ( directorist_is_multi_directory_enabled() ): ?>
				<td><span class="directorist-listing-plan"><?php echo esc_html( $dashboard->get_listing_type() ); ?></span></td>
			<?php endif; ?>

			<td><span class="directorist-ex-plan"><?php echo wp_kses_post( $dashboard->get_listing_expired_html() ); ?></span></td>

			<td><?php echo wp_kses_post( $dashboard->get_listing_status_html() ); ?></td>

			<?php do_action( 'directorist_dashboard_listing_td_6', $dashboard ); ?>

			<td>
				<div class="directorist-actions">

					<a href="<?php echo esc_url(ATBDP_Permalink::get_edit_listing_page_link(get_the_ID())); ?>" class="directorist-link-btn"><?php directorist_icon( 'las la-edit' ); ?><?php esc_html_e( 'Edit', 'directorist' ); ?></a>

					<div class="directorist-dropdown">

						<a href="#" class="directorist-btn-more"><?php directorist_icon( 'las la-ellipsis-h' ); ?><?php esc_html_e( 'More', 'directorist' ); ?></a>

						<div class="directorist-dropdown-menu directorist-dashboard-listing-actions">
							<div class="directorist-dropdown-menu__list">

								<?php
								$dropdown_items = $dashboard->get_action_dropdown_item();

								if( $dropdown_items ) {
									foreach( $dropdown_items as $item ) {
										?>
										<a class="directorist-dropdown-item <?php echo esc_attr( $item['class'] ); ?>" <?php echo wp_kses_post( $item['data_attr'] ); ?> href="<?php echo esc_url( $item['link'] ); ?>"><?php echo wp_kses_post( $item['icon'] ); ?><?php echo wp_kses_post( $item['label'] ); ?></a>
										<?php
									}
								}
								?>

							</div>
						</div>

					</div>

				</div>
			</td>

			<?php do_action( 'directorist_dashboard_listing_td_end', $dashboard ); ?>

		</tr>
		<?php
	}
	wp_reset_postdata();
}
else {
	?>
	<tr><td colspan="5"><?php esc_html_e( 'No items found', 'directorist' ); ?></td></tr>
	<?php
}