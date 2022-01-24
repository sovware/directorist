<?php
/**
 * @author  wpWax
 * @since   7.1.0
 * @version 7.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings  = directorist()->listings;
$query     = $listings->get_query();
$large_num = 999999999;

$links = paginate_links(array(
	'base'      => str_replace( $large_num, '%#%', esc_url( get_pagenum_link( $large_num ) ) ),
	'format'    => '?paged=%#%',
	'current'   => max( 1, get_query_var('paged') ),
	'total'     => $query->max_num_pages,
	'prev_text' => apply_filters('directorist_pagination_prev_text', '<span class="fa fa-chevron-left"></span>'),
	'next_text' => apply_filters('directorist_pagination_next_text', '<span class="fa fa-chevron-right atbdp_right_nav"></span>'),
));

if ( $links ) {
	echo '<div class="directorist-pagination">'.$links.'</div>';
}