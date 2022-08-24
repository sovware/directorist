<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$related = $listing->get_related_listings();
$columns = get_directorist_type_option( $listing->type, 'similar_listings_number_of_columns', 3 );

if ( !$related->have_posts() ) {
	return;
}
?>

<div class="directorist-related <?php echo esc_attr( $class );?>" <?php $listing->section_id( $id ); ?>>

	<div class="directorist-related-listing-header">

		<h4><?php echo esc_html( $label );?></h4>

	</div>


	<div class="directorist-related-carousel" data-columns="<?php echo esc_attr( $columns ); ?>">

		<?php foreach ( $related->post_ids() as $listing_id ): ?>

			<?php $related->loop_template( 'grid', $listing_id ); ?>

		<?php endforeach; ?>

	</div>

</div>