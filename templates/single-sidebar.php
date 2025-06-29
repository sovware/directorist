<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !is_active_sidebar( 'right-sidebar-listing' ) ) {
	return;
}
?>

<div class="<?php Helper::directorist_column('lg-4'); ?>">

	<div class="directorist-sidebar">
		<?php dynamic_sidebar( 'right-sidebar-listing' ); ?>
	</div>

</div>