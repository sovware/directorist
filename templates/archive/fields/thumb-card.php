<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.9.3
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$display_config = directorist_get_listing_preview_image_display_config();
$image_size     = $display_config['mode'];
$style          = $display_config['container_style'];
$blur_background = 'blur' === $display_config['background_type'];

$front_wrap_html = "<div class='directorist-thumnail-card-front-wrap'>" . $listings->loop_get_the_thumbnail( 'directorist-thumnail-card-front-img' ) . "</div>";
$back_wrap_html = "<div class='directorist-thumnail-card-back-wrap'>" . $listings->loop_get_the_thumbnail( 'directorist-thumnail-card-back-img' ) . "</div>";
$card_content = ( 'contain' === $image_size && $blur_background ) ? $back_wrap_html . $front_wrap_html : $front_wrap_html;

$the_html = sprintf(
    '<div class="directorist-thumnail-card %1$s" style="%2$s">%3$s</div>',
    esc_attr( $display_config['container_class'] ),
    esc_attr( $style ),
    $card_content
);


$link_start = '<a href="' . esc_url( apply_filters( 'directorist_archive_single_listing_url', $listings->loop['permalink'], $listings->loop['id'], 'thumbnail' ) ) . '">';
$link_end   = '</a>';

echo wp_kses_post( $the_html );
