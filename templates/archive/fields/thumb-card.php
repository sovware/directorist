<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$is_blur           = get_directorist_option('prv_background_type', 'blur');
$is_blur           = ('blur' === $is_blur ? true : false);
$container_size_by = get_directorist_option('prv_container_size_by', 'px');
$by_ratio          = ( 'px' === $container_size_by ) ? false : true;
$image_size        = get_directorist_option('way_to_show_preview', 'cover');
$ratio_width       = get_directorist_option('crop_width', 360);
$ratio_height      = get_directorist_option('crop_height', 300);
$blur_background   = $is_blur;
$background_color  = get_directorist_option('prv_background_color', '#fff');

// Style
$style_component = [];

if ( $by_ratio ) {
	$padding_top_value = (int) $ratio_height / (int) $ratio_width * 100;
	$style_component[ 'padding-top' ] = "{$padding_top_value}%";
} else {
	$height_value = (int) $ratio_height;
	$style_component[ 'height' ] = "{$height_value}px";
}
if ( $image_size !== 'full' && ! $blur_background ) {
	$style_component[ 'background-color' ] = $background_color;
}
if ( $image_size === 'full' ) {
	unset( $style_component[ 'height' ] );
}

$style = '';
foreach ( $style_component as $style_prop => $style_value ) {
	$style .= "{$style_prop}: {$style_value};";
}

$front_wrap_html = "<div class='directorist-thumnail-card-front-wrap'>".$listings->loop_get_the_thumbnail('directorist-thumnail-card-front-img')."</div>";
$back_wrap_html = "<div class='directorist-thumnail-card-back-wrap'>".$listings->loop_get_the_thumbnail('directorist-thumnail-card-back-img')."</div>";


$blur_bg = ( $blur_background ) ? $back_wrap_html : '';

// Card Contain 
$card_contain_wrap = "<div class='directorist-thumnail-card directorist-card-contain' style='$style'>";
$image_contain_html = $card_contain_wrap . $blur_bg . $front_wrap_html . "</div>";

// Card Cover
$card_cover_wrap = "<div class='directorist-thumnail-card directorist-card-cover' style='$style'>";
$image_cover_html = $card_cover_wrap . $front_wrap_html . "</div>";

// Card Full
$card_full_wrap = "<div class='directorist-thumnail-card directorist-card-full' style='$style'>";
$image_full_html = $card_full_wrap . $front_wrap_html . "</div>";

$the_html = $image_cover_html;
switch ($image_size) {
	case 'cover':
	$the_html = $image_cover_html;
	break;
	case 'contain':
	$the_html = $image_contain_html;
	break;
	case 'full':
	$the_html = $image_full_html;
	break;
}


$link_start = '<a href="'.esc_url( $listings->loop['permalink'] ).'">';
$link_end   = '</a>';

if (!$listings->disable_single_listing) {
	echo $link_start;
}

echo $the_html;

if (!$listings->disable_single_listing) {
	echo $link_end;
}