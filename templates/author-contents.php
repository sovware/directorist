<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-wrapper directorist-author-profile-content">

	<div class="<?php Helper::directorist_container_fluid(); ?>">
		<?php
		$author->header_template();
		$author->about_template();
		$author->author_listings_template();
		?>
	</div>
	
</div>