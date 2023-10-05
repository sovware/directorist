<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.5
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-add-listing-types directorist-w-100">
	<div class="<?php Helper::directorist_container_fluid(); ?>">
		<div class="<?php Helper::directorist_row(); ?> directorist-justify-content-center">

			<?php foreach ( $listing_form->get_listing_types() as $id => $value ): ?>

				<div class="<?php Helper::directorist_column(['lg-3', 'md-4', 'sm-6']); ?>">
						<a href="<?php echo esc_url( add_query_arg('directory_type', $value['term']->slug ) ); ?>" class="directorist-add-listing-types__single__link">
							<?php 
								if( ! empty( $value['data']['icon'] ) ) {
									directorist_icon( $value['data']['icon'] ); 
								} else {
									directorist_icon( 'las la-home' ); 
								}
							?>
							<span><?php echo esc_html( $value['name'] );?></span>
						</a>
				</div>

			<?php endforeach; ?>

		</div>
	</div>
</div>