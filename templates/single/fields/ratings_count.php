<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !Helper::is_review_enabled() ) {
	return;
}
?>

<div class="directorist-info-item directorist-rating-meta directorist-info-item-rating"><?php echo $listing->get_rating_count();?><i class="<?php atbdp_icon_type( true ); ?>-star"></i></div>