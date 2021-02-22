<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$related = $listing->get_related_listings();

if ( !$related->have_posts() ) {
	return;
}

$listing->load_related_listings_script();
?>

<div class="directorist-related <?php echo esc_attr( $class );?>" <?php $listing->section_id( $id ); ?>>

	<div class="directorist-related-listing-header">

		<h4><?php echo esc_html( $label );?></h4>
		
	</div>


	<div class="directorist-related-carousel">

		<?php foreach ( $related->post_ids() as $listing_id ): ?>

			<?php $related->loop_template( 'grid', $listing_id ); ?>

		<?php endforeach; ?>

	</div>

</div>