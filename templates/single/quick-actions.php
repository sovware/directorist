<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-single-listing-action directorist-single-listing-action-quick directorist-flex directorist-align-center">

	<?php
	foreach ( $actions as $action ):
		$listing->field_template( $action );
	endforeach;
	?>
	
</div>