<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$enabled = get_directorist_type_option( $listing->type, 'enable_similar_listings', 1 );
$title   = get_directorist_type_option( $listing->type, 'similar_listings_title' );
$logic   = get_directorist_type_option( $listing->type, 'similar_listings_logics', 'OR' );
$number  = get_directorist_type_option( $listing->type, 'similar_listings_number_of_listings_to_show', 2 );

$relationship = ( $logic == 'AND' ) ? 'AND' : 'OR';

if (empty($enabled)) {
	return;
}

$query = $listing->related_listings_query( $number, $relationship );
$related_listings = new Directorist_Listings(array(), 'related', $query, ['cache' => false]);

$listing->load_related_listings_script();
?>

<div class="containess-fluid">
	<div class="atbdp-related-listing-header">
		<h4><?php echo esc_html( $title ); ?></h4>
	</div>
	<div class="atbd_margin_fix">
		<div class="related__carousel">
			<?php $related_listings->setup_loop(); ?>
		</div>
	</div>
</div>