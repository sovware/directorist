<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-single directorist-listing-single-quickinfo">
	
	<div class="directorist-listing-single__info">

		<?php
		foreach ( $info as $item ) {
			$listing->field_template( $item );
		}
		?>
		
	</div>

</div>