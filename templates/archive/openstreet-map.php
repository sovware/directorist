<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;

$query = $listings->get_query();
?>

<div id="map" style="width: 100%; height: <?php echo esc_attr( $listings->map_height() );?>px;"></div>

<?php while ( $query->have_posts() ): ?>

	<?php $query->the_post(); ?>

	<div class='atbdp-body atbdp-map embed-responsive embed-responsive-16by9 atbdp-margin-bottom'>

		<?php if ( $listings->display_map_image() ) { ?>
			
			<div class='media-left'>

				<?php if ( ! $listings->disable_single_listing() ) { ?>
					<a href="<?php the_permalink(); ?>">
					<?php
				}

				echo $listings->loop_get_the_thumbnail();

				if ( ! $listings->disable_single_listing() ) { ?>
					</a>
					<?php
				}
				?>
				
			</div>
			<?php
		}
		?>

		<div class='media-body'>

			<?php if ( $listings->display_map_title() ) { ?>

				<div class="atbdp-listings-title-block">

					<h3 class="atbdp-no-margin">

						<?php if ( ! $listings->disable_single_listing() ): ?>

							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

						<?php else: ?>

							<?php the_title(); ?>

						<?php endif; ?>

					</h3>



				</div>

				<?php
			}

			if ( ! empty( $ls_data['address'] ) ) {
				if ( $listings->display_map_address() ) { ?>
					<div class='osm-iw-location'>
						<span class='<?php echo atbdp_icon_type(); ?>-map-marker'></span>
						<a href='./' class='map-info-link'><?php echo $ls_data['address'] ?></a>
					</div>
					<?php
				}

				if ( $listings->display_map_direction() ) { ?>
					<div class='osm-iw-get-location'>
						<a href='http://www.google.com/maps?daddr=<?php echo $ls_data['manual_lat'] . ',' . $ls_data['manual_lng']; ?>' target='_blank'><?php esc_html_e( 'Get Direction', 'directorist' );?></a>
						<span class='<?php echo atbdp_icon_type(); ?>-arrow-right'></span>
					</div>
					<?php
				}
			}
			?>
		</div>
	</div>
		

<?php endwhile; ?>

<?php
wp_reset_postdata();