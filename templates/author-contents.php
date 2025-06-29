<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-wrapper directorist-author-profile-content directorist-w-100">

	<div class="<?php Helper::directorist_container(); ?>">
		<?php
		$author->header_template();
		$author->about_template();
		$author->author_listings_template();
		?>
	</div>
	
</div>