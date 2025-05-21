<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */
?>
<div class='map-listing-card-single'>

	<?php 
		if( ! empty( $display_favorite_badge_map ) ) {
			$ls_data['listings']->get_favorite_badge(); 
		}
	?>
	
	<?php if ( ! empty( $display_image_map ) ) { ?>
		<div class='map-listing-card-single__img'>
			<?php if ( ! $disable_single_listing ) { ?>
				<a href='<?php echo esc_url( get_the_permalink() ); ?>'><figure>
				<?php
			}

			if ( ! empty( $ls_data['listing_prv_img'] ) ) { ?>
				<img src='<?php echo esc_url( $ls_data['prv_image'] ); ?>' alt='<?php echo esc_attr( get_the_title() ); ?>'>
				<?php
			}

			if ( ! empty( $ls_data['listing_img'][0] ) && empty( $ls_data['listing_prv_img'] ) ) { ?>
				<img src='<?php echo esc_url( $ls_data['gallery_img'] ); ?>' alt='<?php echo esc_attr( get_the_title() ); ?>'>
				<?php
			}

			if ( empty( $ls_data['listing_img'][0] ) && empty( $ls_data['listing_prv_img'] ) ) {?>
				<img src='<?php echo esc_url( $ls_data['default_image'] ); ?>' alt='<?php echo esc_attr( get_the_title() ); ?>'>
				<?php
			}

			if ( ! $disable_single_listing ) { ?>
				</figure></a>
				<?php
			}
			?>
		</div>
	<?php } ?>

	<?php 
		if( ! empty( $display_user_avatar_map ) ) { ?>
		<div class='map-listing-card-single__author'>
			<?php
				$ls_data['listings']->get_user_avatar();
			?>
		</div>
	<?php } ?>

	<div class='map-listing-card-single__content'>

		<?php if ( ! empty( $display_title_map ) ) { ?>
			<?php if ( ! $disable_single_listing ) { ?>
				<h3 class='map-listing-card-single__content__title'>
					<a href='<?php echo esc_url( get_the_permalink() ); ?>'><?php the_title(); ?></a>
				</h3>
				<?php
			}
			else { ?>
				<h3 class='map-listing-card-single__content__title'><?php the_title();?></h3>
				<?php
			}
			?>
		<?php } ?>

		<?php if ( ! empty( $display_review_map ) || ! empty( $display_price_map )  ) { ?>
			<div class="map-listing-card-single__content__meta">
				<?php 
					if( ! empty( $display_review_map ) ) {
						$ls_data['listings']->get_listing_review();
					}

					if( ! empty( $display_price_map ) ) {
						$ls_data['listings']->get_price();
					}
				?>
			</div>
		<?php } ?>

		<?php if ( ! empty( $ls_data['address'] ) || ! empty( $ls_data['phone'] )  ) { ?>
			<div class="map-listing-card-single__content__info">
				<?php
					if ( ! empty( $ls_data['address'] ) ) {
						if ( ! empty( $display_address_map ) ) { ?>
							<div class='directorist-info-item map-listing-card-single__content__address'>
								<?php directorist_icon( 'fas fa-map-marker-alt' ); ?>
								<div class="map-listing-card-single__content__location">
									<?php echo esc_html( $ls_data['address'] ); ?>
								</div>
							</div>
							<?php
						}

					}

					if ( ! empty( $ls_data['phone'] ) && ! empty( $display_phone_map ) ) { ?>
						<div class='directorist-info-item map-listing-card-single__content__phone'>
							<?php directorist_icon( 'fas fa-phone-alt' ); ?>
							<a href='./' class='map-info-link'><?php echo esc_html( $ls_data['phone'] ); ?></a>
						</div>
						<?php
					}
				?>
			</div>
		<?php } ?>
	</div>
</div>