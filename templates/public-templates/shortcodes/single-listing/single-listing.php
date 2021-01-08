<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="directorist-single-wrapper">
	<?php
	$listing->header_template();

	foreach ( $listing->content_data as $section ) {
		$listing->section_template( $section );
	}
	?>
</div>