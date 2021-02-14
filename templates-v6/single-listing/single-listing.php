<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

use \Directorist\Directorist_Single_Listing;
$listing = Directorist_Single_Listing::instance();
?>

<div class="directorist-single-wrapper">
	<?php
	$listing->header_template();

	foreach ( $listing->content_data as $section ) {
		$listing->section_template( $section );
	}
	?>
</div>