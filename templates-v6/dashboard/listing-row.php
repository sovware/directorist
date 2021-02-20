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
		<tr data-id="<?php the_ID(); ?>">
			<td>
				<div class="directorist_listing-info">
					<div class="directorist_listing-info__img">
						<a href="<?php the_permalink(); ?>"><?php echo $dashboard->get_listing_thumbnail(); ?></a>
					</div>
					<div class="directorist_listing-info__content">
						<h4 class="directorist_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
						<?php if ( $dashboard->get_listing_price_html() ): ?>
							<span class="directorist_price"><?php echo $dashboard->get_listing_price_html(); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</td>

			<?php do_action( 'directorist_dashboard_listing_td_2', $dashboard ); ?>

			<td><span class="directorist_listing-plan"><?php echo $dashboard->get_listing_type(); ?></span></td>

			<td><span class="directorist_ex-plan"><?php echo $dashboard->get_listing_expired_html(); ?></span></td>

			<td><?php echo $dashboard->get_listing_status_html(); ?></td>

			<?php do_action( 'directorist_dashboard_listing_td_6', $dashboard ); ?>

			<td>
				<div class="directorist_actions">
					<a href="<?php echo esc_url(ATBDP_Permalink::get_edit_listing_page_link(get_the_ID())); ?>" class="directorist_link-btn"><i class="la la-edit"></i><?php esc_html_e('Edit', 'directorist'); ?></a>
					<div class="directorist_dropdown">
						<a href="#" class="directorist_btn-more" type="button"><i class="la la-ellipsis-h"></i></a>
						<div class="directorist_dropdown-menu directorist-dashboard-listing-actions">
							<div class="directorist_dropdown-menu__list">
							<?php
							$dropdown_items = $dashboard->get_action_dropdown_item();
							if( $dropdown_items ) {
								foreach( $dropdown_items as $item ) {
							?>
									<a class="directorist_dropdown-item <?php echo $item['class']; ?>" <?php echo $item['data_attr']; ?> href="<?php echo $item['link']; ?>"><?php echo $item['icon']; ?><?php echo $item['label']; ?></a>
							<?php }
							} ?>
							</div>
						</div>
					</div>
				</div>
			</td>
			
		</tr>
		<?php
	}
}
else {
	?>
	<tr><td colspan="5"><?php esc_html_e('No items found', 'directorist'); ?></td></tr>
	<?php
}

wp_reset_postdata();