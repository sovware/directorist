<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-add-listing-types directorist-w-100">
	<div class="<?php Helper::directorist_container_fluid(); ?>">
		<div class="<?php Helper::directorist_row(); ?> directorist-justify-content-center ">

			<?php foreach ( $listing_form->get_listing_types() as $id => $value ): ?>

				<div class="<?php Helper::directorist_column(['lg-3', 'md-4', 'sm-6']); ?>">
					<div class="directorist-add-listing-types__single">

						<a href="<?php echo esc_url( add_query_arg('directory_type', $value['term']->slug ) ); ?>" class="directorist-add-listing-types__single__link">
							<i class="<?php echo esc_html( $value['data']['icon'] );?>"></i>
							<span><?php echo esc_html( $value['name'] );?></span>
						</a>

					</div>
				</div>

			<?php endforeach; ?>

		</div>
	</div>
</div>