<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-favourite-items-wrap">

	<div class="directorist-favourirte-items">

		<?php if ( $dashboard->fav_listing_items() ): ?>

			<div class="directorist-dashboard-items-list">
				<?php foreach ( $dashboard->fav_listing_items() as $item ): ?>

					<div class="directorist-dashboard-items-list__single directorist_favourite_<?php echo esc_attr( $item['obj']->ID ); ?>">

						<div class="directorist-dashboard-items-list__single__info">

							<div class="directorist-listing-img">
								<a href="<?php echo esc_url( $item['permalink'] );?>">
									<img src="<?php echo esc_url( $item['img_src'] );?>" alt="<?php echo esc_attr( $item['title'] );?>">
								</a>
							</div>

							<div class="directorist-listing-content">

								<h4 class="directorist-listing-title"><a href="<?php echo esc_url( $item['permalink'] );?>"><?php echo esc_html( $item['title'] );?></a></h4>

								<a class="directorist-listing-category" href="<?php echo esc_url( $item['category_link'] );?>"><?php echo esc_html( $item['category_name'] );?></a>

							</div>

						</div>

						<div class="directorist-dashboard-items-list__single__action">
							<a href="#" id="directorist-fav_<?php echo esc_attr( $item['obj']->ID ); ?>" class="directorist-btn directorist-btn-sm directorist-btn-danger directorist-favourite-remove-btn" data-listing_id="<?php echo esc_attr( $item['obj']->ID ); ?>">
								<?php directorist_icon( 'las la-trash' ); ?>
								<span class="directorist-favourite-remove-text"><?php esc_html_e( 'Remove', 'directorist' ); ?></span>
							</a>
						</div>

					</div>

				<?php endforeach; ?>
			</div>

		<?php else: ?>

			<div class="directorist-notfound"><?php esc_html_e( 'Nothing found!', 'directorist' ); ?></div>

		<?php endif; ?>

	</div>

</div>