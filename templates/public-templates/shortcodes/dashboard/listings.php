<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="atbd_tab_inner tabContentActive" id="my_listings">
	<div class="directorist_userDashboard-area">
		<div class="directorist_userDashboard-tab">
			<div class="atbd_tab_nav">
				<ul>
					<li class="directorist_tab_nav--content-link">
						<a href="#" target="all_llistings" class="atbd_tn_link tabItemActive"><?php esc_html_e( 'All Listings', 'directorist' ); ?></a>
					</li>
					<li class="directorist_tab_nav--content-link">
						<a href="#" target="published" class="atbd_tn_link"><?php esc_html_e( 'Published', 'directorist' ); ?></a>
					</li>
					<li class="directorist_tab_nav--content-link">
						<a href="#" target="pending" class="atbd_tn_link"><?php esc_html_e( 'Pending', 'directorist' ); ?></a>
					</li>
					<li class="directorist_tab_nav--content-link">
						<a href="#" target="unpaid" class="atbd_tn_link"><?php esc_html_e( 'UnPaid', 'directorist' ); ?></a>
					</li>
					<li class="directorist_tab_nav--content-link">
						<a href="#" target="expired" class="atbd_tn_link"><?php esc_html_e( 'Expired', 'directorist' ); ?></a>
					</li>
				</ul>
				<div class="directorist_userDashboard-search">
					<div class="directorist_userDashboard-search__icon">
						<i class="la la-search"></i>
					</div>
					<input type="text" placeholder="Search listings">
				</div>
			</div>

			<div class="directorist_userDashboard-tabcontent">
				<div class="atbd_tab_inner tabContentActive" id="all_llistings">
					<div class="directorist_listing-table directorist_table-responsive">
						<table class="directorist_table">

							<thead>
								<tr>
									<th class="directorist_table-listing"><?php esc_html_e( 'Listings', 'directorist' ); ?></th>
									<th class="directorist_table-plan"><?php esc_html_e( 'Type', 'directorist' ); ?></th>
									<th class="directorist_table-ex-date"><?php esc_html_e( 'Expiration Date', 'directorist' ); ?></th>
									<th class="directorist_table-status"><?php esc_html_e( 'Status', 'directorist' ); ?></th>
									<th class="directorist_table-actions"></th>
								</tr>
							</thead>

							<tbody class="directorist-dashboard-listings-tbody">
								<?php $dashboard->listing_row_template(); ?>
							</tbody>

						</table>

						<div class="directorist-dashboard-pagination">
							<div class="navigation pagination">
								<div class="nav-links">
									<?php $dashboard->listing_pagination(); ?>
								</div>	
							</div>
						</div>

					</div>
				</div>
			</div>

		</div>
	</div>
</div>