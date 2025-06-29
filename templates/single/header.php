<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 8.0
 */

 use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<section class="directorist-single-listing-header">
	<?php
	$args = [
		'listing' => $listing,
		'display_title' => $display_title,
		'display_tagline' => $display_tagline,
	];
	foreach ( $listing->header_data as $data ) {
		if ( empty( $data['placeholderKey'] ) ) {
			continue;
		}
		$template = str_replace( "-placeholder", "", $data['placeholderKey'] );
		Helper::get_template( 'single/header-parts/'. $template, $args );
	} ?>
</section>