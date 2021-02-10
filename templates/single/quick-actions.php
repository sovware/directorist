<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="atbd_listing_action_area">
	<?php
	foreach ( $actions as $action ):
		$listing->field_template( $action );
	endforeach;
	?>
</div>