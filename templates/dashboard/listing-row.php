<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
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
						<a href="<?php the_permalink(); ?>"><?php echo $dashboard->get_listing_thumbnail(); ?></a>
					</div>

					<div class="directorist-listing-table-listing-info__content">

						<h4 class="directorist-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>

						<?php Helper::listing_price(); ?>

					</div>

				</div>
			</td>

			<?php do_action( 'directorist_dashboard_listing_td_2', $dashboard ); ?>

			<?php if ( Helper::multi_directory_enabled() ): ?>
				<td><span class="directorist-listing-plan"><?php echo $dashboard->get_listing_type(); ?></span></td>
			<?php endif; ?>

			<td><span class="directorist-ex-plan"><?php echo $dashboard->get_listing_expired_html(); ?></span></td>

			<td><?php echo $dashboard->get_listing_status_html(); ?></td>

			<?php do_action( 'directorist_dashboard_listing_td_6', $dashboard ); ?>

			<td>
				<div class="directorist-actions">

					<a href="<?php echo esc_url(ATBDP_Permalink::get_edit_listing_page_link(get_the_ID())); ?>" class="directorist-link-btn"><i class="la la-edit"></i><?php esc_html_e( 'Edit', 'directorist' ); ?></a>

					<div class="directorist-dropdown directorist-dropdown-js directorist-dropdown-right">

						<a href="#" class="directorist-btn-more directorist-dropdown__toggle directorist-dropdown__toggle-js" type="button"><i class="<?php atbdp_icon_type( true );?>-ellipsis-h"></i><?php esc_html_e( 'More', 'directorist' ); ?></a>

						<div class="directorist-dropdown__links directorist-dropdown__links-js directorist-dashboard-listing-actions-js">
							
								<?php
								$dropdown_items = $dashboard->get_action_dropdown_item();

								if( $dropdown_items ) {
									foreach( $dropdown_items as $item ) {
										?>
										<a class="directorist-dropdown__links--single <?php echo $item['class']; ?>" <?php echo $item['data_attr']; ?> href="<?php echo $item['link']; ?>"><?php echo $item['icon']; ?><?php echo $item['label']; ?></a>
										<?php
									}
								}
								?>

						</div>

					</div>

				</div>
			</td>

			<?php do_action( 'directorist_dashboard_listing_td_end', $dashboard ); ?>

		</tr>
		<?php
	}
}
else {
	?>
	<tr><td colspan="5"><?php esc_html_e( 'No items found', 'directorist' ); ?></td></tr>
	<?php
}

wp_reset_postdata();