<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

get_header( 'directorist' );
?>

<div class="directorist-single <?php Helper::directorist_container(); ?>">
	<?php Helper::get_template( 'single-contents' ); ?>
</div>

<?php
get_footer( 'directorist' );