<?php
/**
 * @author  wpWax
 * @since   7.1.0
 * @version 7.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;


$pagination = '';
$paged = 1;
$largeNumber = 999999999;

$total = ( isset( $listings->query_results->total_pages ) ) ? $listings->query_results->total_pages : $listings->query_results->max_num_pages;
$paged = ( isset( $listings->query_results->current_page ) ) ? $listings->query_results->current_page : $paged;

$links = paginate_links(array(
	'base'      => str_replace( $largeNumber, '%#%', esc_url( get_pagenum_link( $largeNumber ) ) ),
	'format'    => '?paged=%#%',
	'current'   => max( 1, $paged ),
	'total'     => $total,
	'prev_text' => apply_filters('directorist_pagination_prev_text', '<span class="fa fa-chevron-left"></span>'),
	'next_text' => apply_filters('directorist_pagination_next_text', '<span class="fa fa-chevron-right atbdp_right_nav"></span>'),
));

if ( $links ) {
	$pagination = '<div class="directorist-pagination">'.$links.'</div>';
}

echo apply_filters('directorist_pagination', $pagination, $links, $listings->query_results, $paged );