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
						<a href="#" target="all_llistings" class="atbd_tn_link tabItemActive">All Listings</a>
					</li>
					<li class="directorist_tab_nav--content-link">
						<a href="#" target="published" class="atbd_tn_link">Published</a>
					</li>
					<li class="directorist_tab_nav--content-link">
						<a href="#" target="pending" class="atbd_tn_link">Pending</a>
					</li>
					<li class="directorist_tab_nav--content-link">
						<a href="#" target="unpaid" class="atbd_tn_link">UnPaid</a>
					</li>
					<li class="directorist_tab_nav--content-link">
						<a href="#" target="expired" class="atbd_tn_link">Expired</a>
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
									<th class="directorist_table-listing">Listings</th>
									<th class="directorist_table-plan">Plan</th>
									<th class="directorist_table-ex-date">Expiration Date</th>
									<th class="directorist_table-status">Status</th>
									<th class="directorist_table-actions"></th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($listing_items)) {
									foreach ($listing_items as $item) { ?>
										<tr>
											<td>
												<div class="directorist_listing-info">
													<div class="directorist_listing-info__img">
														<img src="http://directoristfbuilder.local/wp-content/uploads/2020/12/4.jpg" alt="">
													</div>
													<div class="directorist_listing-info__content">
														<h4 class="directorist_title"><?php echo esc_html($item['title']); ?></h4>
														<span class="directorist_price">$275.20</span>
													</div>
												</div>
											</td>
											<td>
												<span class="directorist_listing-plan">Super Plan</span>
											</td>
											<td>
												<span class="directorist_ex-plan"> <?php echo $item['exp_html']; ?></span>
											</td>
											<td>
												<span class="directorist_badge dashboard-badge success"><?php echo esc_html($item['status_label']); ?></span>
											</td>
											<td>
												<div class="directorist_actions">
													<a href="#" class="directorist_link-btn"><i class="la la-edit"></i>Edit</a>
													<div class="directorist_dropdown">
														<a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
														<div class="directorist_dropdown-menu">
															<div class="directorist_dropdown-menu__list">
																<a class="directorist_dropdown-item" href="#"><i class=""></i>Change Plan</a>
																<a class="directorist_dropdown-item" href="#"><i class="la la-adversal"></i>Promote Listing</a>
																<a class="directorist_dropdown-item" href="#"><i class="la la-amazon-pay"></i>Pay Now</a>
															</div>
															<div class="directorist_dropdown-menu__list">
																<div class="directorist_custom-checkbox">
																	<input type="checkbox" id="m-navigation" name="mark-navigation">
																	<label for="m-navigation">
																		Mark as Negotiation
																	</label>
																</div>
																<div class="directorist_custom-checkbox">
																	<input type="checkbox" id="m-sold" name="mark-sold">
																	<label for="m-sold">
																		Mark as Sold
																	</label>
																</div>
															</div>
															<div class="directorist_dropdown-menu__list">
																<a class="directorist_dropdown-item" href="#"><i class="la la-trash"></i>Delete Listing</a>
															</div>
														</div>
													</div>
												</div>
											</td>
										</tr>
										<?php
									}
								} else { ?>
									<tr><td><?php esc_html_e("Looks like you have not created any listing yet!", 'directorist'); ?></td></tr>
									<?php
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>