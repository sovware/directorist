<?php
/**
 * The template for displaying all listings page
 *
 * This template can be overridden by copying it to yourtheme/directorist/archive.php
 *
 * @author  wpWax
 * @since   6.6
 * @version 6.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-archive-contents">

	<?php
	$listings->listing_type_template();
	$listings->header_template();
	$listings->archive_view_template();
	?>

</div>