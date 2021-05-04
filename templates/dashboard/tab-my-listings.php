<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-dashboard-mylistings" id="directorist-dashboard-mylistings-js" data-paged="1" data-search="">

	<div id="directorist-dashboard-preloader">
		<div></div><div></div><div></div><div></div>
	</div>

	<div class="directorist-user-dashboard-area">

		<div class="directorist-user-dashboard-tab">

			<div class="directorist-user-dashboard-tab__nav">

				<ul class="directorist-dashboard-listing-nav-js">

					<li class="directorist-tab-nav--content-link">
						<a href="#" data-tab="all" class="directorist-tab__nav__active"><?php esc_html_e( 'All Listings', 'directorist' ); ?></a>
					</li>
					
					<li class="directorist-tab-nav--content-link">
						<a href="#" data-tab="publish"><?php esc_html_e( 'Published', 'directorist' ); ?></a>
					</li>

					<li class="directorist-tab-nav--content-link">
						<a href="#" data-tab="pending"><?php esc_html_e( 'Pending', 'directorist' ); ?></a>
					</li>

					<li class="directorist-tab-nav--content-link">
						<a href="#" data-tab="expired"><?php esc_html_e( 'Expired', 'directorist' ); ?></a>
					</li>

				</ul>

				<div class="directorist-user-dashboard-search">

					<div class="directorist-user-dashboard-search__icon">
						<i class="la la-search"></i>
					</div>

					<form id="directorist-dashboard-listing-searchform">
						<input type="text" placeholder="<?php _e( 'Search listings', 'directorist' ); ?>" name="searchtext">
					</form>

				</div>

			</div>

			<div class="directorist-user-dashboard-tabcontent">
				<div class="directorist-listing-table directorist-table-responsive">

					<table class="directorist-table">

						<thead>
							<tr>
								<?php do_action( 'directorist_dashboard_listing_th_start', $dashboard ); ?>

								<th class="directorist-table-listing"><?php esc_html_e( 'Listings', 'directorist' ); ?></th>

								<?php do_action( 'directorist_dashboard_listing_th_2', $dashboard ); ?>

								<?php if ( Helper::multi_directory_enabled() ): ?>
									<th class="directorist-table-listing-type"><?php esc_html_e( 'Type', 'directorist' ); ?></th>
								<?php endif; ?>


								<th class="directorist-table-ex-date"><?php esc_html_e( 'Expiration Date', 'directorist' ); ?></th>

								<th class="directorist-table-status"><?php esc_html_e( 'Status', 'directorist' ); ?></th>

								<?php do_action( 'directorist_dashboard_listing_th_6', $dashboard ); ?>

								<th class="directorist-table-actions"></th>

								<?php do_action( 'directorist_dashboard_listing_th_end', $dashboard ); ?>
							</tr>
						</thead>

						<tbody class="directorist-dashboard-listings-tbody">
							<?php $dashboard->listing_row_template(); ?>
						</tbody>

					</table>
					
					<?php do_action( 'directorist_dashboard_after_loop' ); ?>
					
					<div class="directorist-dashboard-pagination">
						<?php echo $dashboard->listing_pagination(); ?>
					</div>

				</div>
			</div>

		</div>
	</div>

</div>