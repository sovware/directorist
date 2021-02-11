<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="atbd_data_info atbd_listing_meta">

	<?php
	foreach ( $info as $item ) {
		$listing->field_template( $item );
	}
	?>

</div>