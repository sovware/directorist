<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */
?>

<div class="marker" data-latitude="<?php echo esc_attr( $ls_data['manual_lat'] ); ?>" data-longitude="<?php echo esc_attr($ls_data['manual_lng']); ?>" data-icon="<?php echo esc_attr($ls_data['cat_icon']); ?>">

	<?php if ( ! $map_is_disabled ) { ?>

		<div class="map-info-wrapper" style="display:none;">
			
			<?php 
				if( ! empty( $display_favorite_badge_map ) ) {
					$ls_data['listings']->get_favorite_badge(); 
				}
			?>

			<?php if ( ! empty( $display_image_map ) ) { ?>
				<div class="map-info-img">
					<?php
					if ( ! $disable_single_listing ) {
						echo "<a href='" . esc_url(get_the_permalink()) . "'>";
					}

					if ( ! empty( $ls_data['listing_prv_img'] ) ) {
						echo '<img src="' . esc_url( $ls_data['prv_image'] ) . '" alt="' . esc_attr( get_the_title() ) . '" width="280">';
					}

					if ( ! empty( $ls_data['listing_img'][0] ) && empty( $ls_data['listing_prv_img'] ) ) {
						echo '<img src="' . esc_url( $ls_data['gallery_img'] ) . '" alt="' . esc_attr( get_the_title() ) . '" width="280">';
					}

					if ( empty( $ls_data['listing_img'][0] ) && empty( $ls_data['listing_prv_img'] ) ) {
						echo '<img src="' . esc_url( $ls_data['default_img'] ) . '" alt="' . esc_attr( get_the_title() ) . '" width="280">';
					}

					if ( ! $disable_single_listing ) {
						echo '</a>';
					}
					?>
				</div>

				<?php 
				if( ! empty( $display_user_avatar_map ) ) {
					$ls_data['listings']->get_user_avatar();
				}

				if( ! empty( $display_review_map ) ) {
					$ls_data['listings']->get_listing_review();
				}

				if( ! empty( $display_price_map ) ) {
					$ls_data['listings']->get_price();
				}

			}

			if ( ! empty( $display_title_map ) || ! empty( $display_address_map ) || ! empty( $display_direction_map ) ) { ?>

				<div class="map-info-details">

					<?php if ( ! empty( $display_title_map ) ) { ?>
						<div class="atbdp-listings-title-block">
							<h3 class="atbdp-no-margin">
								<?php if ( ! $disable_single_listing ){ ?>
									<a href="<?php the_permalink();?>"><?php the_title();?></a>
									<?php
								}
								else {
									the_title();
								}
								?>
							</h3>
						</div>
						<?php
					}

					if ( ! empty( $ls_data['address'] ) ) { ?>
						<?php if ( ! empty( $display_address_map ) ) { ?>
							<div class="map_addr"><?php directorist_icon( 'las la-map-marker' ); ?> <a href="" class="map-info-link"><?php echo esc_html( $ls_data['address'] ); ?></a></div>
							<?php
						}

						if ( ! empty( $display_direction_map ) ) { ?>
							<div class="map_get_dir">
								<a href='http://www.google.com/maps?daddr=<?php echo esc_attr( $ls_data['manual_lat'] ); ?>,<?php echo esc_attr( $ls_data['manual_lng'] ); ?>' target='_blank'><?php esc_html_e( 'Get Directions', 'directorist' ); ?></a>
								<?php directorist_icon( 'las la-arrow-right' ); ?>
							</div>
							<?php
						}
					}

					if ( ! empty( $ls_data['phone'] ) && ! empty( $display_phone_map ) ) { ?>
						<div class="map_phone"><?php directorist_icon( 'las la-map-marker' ); ?> <a href="" class="map-info-link"><?php echo esc_html( $ls_data['phone'] ); ?></a></div>
						<?php
					}
					
					do_action( 'atbdp_after_listing_content', $ls_data['post_id'], 'map' );?>
				</div>
				<?php
			}
			?>

			<span class="iw-close-btn"><?php directorist_icon( 'las la-times' ); ?></span>
		</div>
		<?php
	}
	?>
</div>