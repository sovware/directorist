<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<div class="atbd_data_info atbd_listing_meta">
	<?php
	foreach ( $info as $item ):
		$listing->field_template( $item );
	endforeach;
	?>
</div>