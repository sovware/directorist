<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.2
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-archive-contents <?php echo esc_attr( $listings->instant_searching_class() ); ?>">

	<?php
	$listings->directory_type_nav_template();
	$listings->header_bar_template();
	$listings->archive_view_template();
	?>

</div>