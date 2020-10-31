<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<div class="atbd_listing_action_area">
	<?php
	foreach ( $actions as $action ):
		$listing->render_item( $action );
	endforeach;
	?>
</div>