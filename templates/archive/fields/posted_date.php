<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$published_date = ( 'days_ago' === $data['date_type'] ) ? sprintf(__('Posted %s ago', 'directorist'), human_time_diff(get_the_time('U'), current_time('timestamp'))) : get_the_date();
?>

<div class="directorist-listing-card-posted-on"><?php directorist_icon( $icon );?><?php echo esc_html( $published_date );?></div>