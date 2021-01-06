<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */

if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();
		?>
		<tr>
			<td>
				<div class="directorist_listing-info">
					<div class="directorist_listing-info__img">
						<a href="<?php the_permalink(); ?>"><?php echo $dashboard->get_listing_thumbnail(); ?></a>
					</div>
					<div class="directorist_listing-info__content">
						<h4 class="directorist_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
						<span class="directorist_price">$275.20</span>
					</div>
				</div>
			</td>

			<td><span class="directorist_listing-plan"><?php echo $dashboard->get_listing_type(); ?></span></td>

			<td><span class="directorist_ex-plan"> <?php echo $dashboard->get_listing_expired_html(); ?></span></td>

			<td><?php echo $dashboard->get_listing_status_html(); ?></td>

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
}

wp_reset_postdata();