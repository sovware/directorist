<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="atbd_tab_inner tabContentActive" id="dashboard_my_listings" data-paged="1" data-search="">
	<div id="directorist-dashboard-preloader" style="display:none">
		<div></div><div></div><div></div><div></div>
	</div>
	<div class="directorist_userDashboard-area">
		<div class="directorist_userDashboard-tab">
			<div class="atbd_tab_nav">
				<ul class="directorist-dashboard-listing-nav-js">
					<li class="directorist_tab_nav--content-link">
						<a href="#" data-tab="all" class="tabItemActive"><?php esc_html_e( 'All Listings', 'directorist' ); ?></a>
					</li>
					<li class="directorist_tab_nav--content-link">
						<a href="#" data-tab="publish"><?php esc_html_e( 'Published', 'directorist' ); ?></a>
					</li>
					<li class="directorist_tab_nav--content-link">
						<a href="#" data-tab="pending"><?php esc_html_e( 'Pending', 'directorist' ); ?></a>
					</li>
					<li class="directorist_tab_nav--content-link">
						<a href="#" data-tab="expired"><?php esc_html_e( 'Expired', 'directorist' ); ?></a>
					</li>
				</ul>
				<div class="directorist_userDashboard-search">
					<div class="directorist_userDashboard-search__icon">
						<i class="la la-search"></i>
					</div>
					<form id="directorist-dashboard-listing-searchform">
						<input type="text" placeholder="<?php _e( 'Search listings', 'directorist' ); ?>" name="searchtext">
					</form>
				</div>
			</div>

			<div class="directorist_userDashboard-tabcontent">
				<div class="directorist_listing-table directorist_table-responsive">
					<table class="directorist_table">
						<thead>
							<tr>
								<th class="directorist_table-listing"><?php esc_html_e( 'Listings', 'directorist' ); ?></th>
								<?php do_action( 'directorist_dashboard_listing_th_2', $dashboard ); ?>
								<th class="directorist_table-plan"><?php esc_html_e( 'Type', 'directorist' ); ?></th>
								<th class="directorist_table-ex-date"><?php esc_html_e( 'Expiration Date', 'directorist' ); ?></th>
								<th class="directorist_table-status"><?php esc_html_e( 'Status', 'directorist' ); ?></th>
								<?php do_action( 'directorist_dashboard_listing_th_6', $dashboard ); ?>
								<th class="directorist_table-actions"></th>
							</tr>
						</thead>

						<tbody class="directorist-dashboard-listings-tbody">
							<?php $dashboard->listing_row_template(); ?>
						</tbody>
					</table>

					<?php do_action( 'directorist_dashboard_after_loop' ); ?>

					<div class="directorist-dashboard-pagination">
						<div class="navigation pagination">
							<div class="nav-links">
								<?php echo $dashboard->listing_pagination(); ?>
							</div>
						</div>
					</div>

				</div>
			</div>

		</div>
	</div>
</div>