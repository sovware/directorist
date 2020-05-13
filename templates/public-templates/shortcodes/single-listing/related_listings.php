<?
/**
  * @package Directorist
  * @since 5.10.0
  */
do_action('atbdp_before_related_listing_start', $post);
if ($related_listings->have_posts()) {
    $templete = apply_filters('atbdp_related_listing_template', 'default');
    related_listing_slider($related_listings, $pagenation = null, $is_disable_price, $templete);
} 

do_action('atbdp_after_single_listing', $post, $listing_info);
?>