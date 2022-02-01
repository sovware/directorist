<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;

$published_date = $listings->posted_date_is_days_ago() ? sprintf( esc_html__('Posted %s ago', 'directorist'), human_time_diff(get_the_time('U'), current_time('timestamp') ) ) : get_the_date();
?>

<div class="directorist-listing-card-posted-on">
	<?php $listings->print_icon(); ?>
	<?php echo esc_html( $published_date );?>
</div>