<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div <?php $listings->wrapper_class(); $listings->data_atts(); ?>>

	<?php
	$listings->directory_type_nav_template();
    $listings->basic_search_form_template();
    $listings->advance_search_form_template();
	$listings->header_bar_template();
	$listings->archive_view_template();
	?>

</div>