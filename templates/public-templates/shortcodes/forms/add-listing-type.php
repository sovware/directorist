<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<div id="directorist" class="directorist atbd_wrapper atbd_add_listing_wrapper">
	<div class="<?php echo apply_filters('atbdp_add_listing_container_fluid', 'container-fluid'); ?>">
		<div class="row">
			<?php foreach ( $listing_types as $id => $value ): ?>
				<div class="col-3">
					<div class="directorist-each-listing-type">
						<a href="<?php echo esc_url( add_query_arg('listing_type', $id) ); ?>">
							<span class="directorist-listing-type-icon">
								<i class="<?php echo esc_html( $value['data']['icon'] );?>"></i>
							</span>
							<?php echo esc_html( $value['name'] );?>
						</a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>