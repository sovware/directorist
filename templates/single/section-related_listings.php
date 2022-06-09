<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$query_args = $listing->get_related_listings_query_args();
$related = directorist()->listings;
$related->setup_data( ['query_args' => $query_args] );
$query = $related->get_query();
$columns = get_directorist_type_option( $listing->type, 'similar_listings_number_of_columns', 3 );

if ( !$query->have_posts() ) {
	directorist()->listings->reset_data();
	return;

}
?>

<div class="directorist-related <?php echo esc_attr( $class );?>" <?php $listing->section_id( $id ); ?>>

	<div class="directorist-related-listing-header">

		<h4><?php echo esc_html( $label );?></h4>

	</div>


	<div class="directorist-related-carousel" data-columns="<?php echo esc_attr( $columns ); ?>">

		<?php while ( $query->have_posts() ): ?>

			<?php $query->the_post(); ?>

			<?php $related->loop_template( 'grid' ); ?>

		<?php endwhile; ?>

		<?php wp_reset_postdata(); ?>

	</div>

</div>

<?php
directorist()->listings->reset_data();