<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="directorist" class="directorist atbd_wrapper atbd_add_listing_wrapper">
	<div class="container-fluid">
		<div class="row directorist-listing-type-wrapper">

			<?php foreach ( $listing_form->get_listing_types() as $id => $value ): ?>

				<div class="col-3">
					<div class="directorist-each-listing-type">

						<a href="<?php echo esc_url( add_query_arg('directory_type', $value['term']->slug ) ); ?>">
							<i class="<?php echo esc_html( $value['data']['icon'] );?>"></i>
							<span><?php echo esc_html( $value['name'] );?></span>
						</a>

					</div>
				</div>
				
			<?php endforeach; ?>

		</div>
	</div>
</div>