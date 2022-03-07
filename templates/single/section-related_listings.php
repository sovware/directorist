<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$query_args = $listing->get_related_listings_query_args();
$related = directorist()->listings;
$related->setup_data( ['query_args' => $query_args] );
$query = $related->get_query();

if ( !$query->have_posts() ) {
	directorist()->listings->reset_data();
	return;

}

$listing->load_related_listings_script();
?>

<div class="directorist-related <?php echo esc_attr( $class );?>" <?php $listing->section_id( $id ); ?>>

	<div class="directorist-related-listing-header">

		<h4><?php echo esc_html( $label );?></h4>

	</div>


	<div class="directorist-related-carousel">

		<?php while ( $query->have_posts() ): ?>

			<?php $query->the_post(); ?>

			<?php $related->loop_template( 'grid' ); ?>

		<?php endwhile; ?>

		<?php wp_reset_postdata(); ?>

	</div>

</div>

<?php
directorist()->listings->reset_data();