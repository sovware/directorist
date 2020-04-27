<?php
if (!defined('ABSPATH')) {
  exit;
}

// atbdp_get_shortcode_template
function atbdp_shortcode_template( $template_file = '', $template_data = null, $extract = true, $template ) {
  if ( $extract && is_array( $template_data ) ) {
    extract( $template_data );
  }

  $theme_template_file =  ATBDP_SHORTCODE_TEMPLATES_THEME_DIR . "$template_file.php";
  $default_template_file = ATBDP_SHORTCODE_TEMPLATES_DEFAULT_DIR . "$template_file.php";

  $theme_template_file_path = get_theme_file_path($theme_template_file);

  if (file_exists($theme_template_file_path)) {
    include $theme_template_file;
    return;
  }


  if (file_exists($default_template_file)) {
    include($default_template_file);
    return;
  }
}

// atbdp_listings_header
function atbdp_listings_header( $atts = [] )
{
  ob_start();

  if ( ! empty( $atts['header'] ) && 'yes' !== $atts['header'] ) {
    return '';
  }

  $model = new All_Listins_Model( $atts );
  $template_data = ( array ) $model;
  
  atbdp_shortcode_template( 'listings-archive/listings-header', $template_data, true, $model);
  $header = ob_get_clean();
  
  /**
   * @since 6.3.5
   * 
   * @filter key: atbdp_listing_header_html
   */
  
  extract( $template_data );
  $filter_args = compact( 'display_header','header_container_fluid','search_more_filters_fields', 'listing_filters_button', 'header_title','listing_filters_icon','display_viewas_dropdown','display_sortby_dropdown', 'filters', 'view_as_text', 'view_as_items', 'sort_by_text', 'sort_by_items', 'listing_location_address', 'filters_button' );
  echo apply_filters('atbdp_listing_header_html', $header, $filter_args);
}

// atbdp_search_result_page_link
function atbdp_search_result_page_link()
{
  echo ATBDP_Permalink::get_search_result_page_link();
}

// atbdp_listings_loop_data
function atbdp_listings_loop_data( $template_data = null )
{
  $data = [
    'listing_id' => get_the_ID(),
    'cats' =>  get_the_terms(get_the_ID(), ATBDP_CATEGORY),
    'locs' => get_the_terms(get_the_ID(), ATBDP_LOCATION),
    'featured' => get_post_meta(get_the_ID(), '_featured', true),
    'price' => get_post_meta(get_the_ID(), '_price', true),
    'price_range' => get_post_meta(get_the_ID(), '_price_range', true),
    'atbd_listing_pricing' => get_post_meta(get_the_ID(), '_atbd_listing_pricing', true),
    'listing_img' => get_post_meta(get_the_ID(), '_listing_img', true),
    'listing_prv_img' => get_post_meta(get_the_ID(), '_listing_prv_img', true),
    'excerpt' => get_post_meta(get_the_ID(), '_excerpt', true),
    'tagline' => get_post_meta(get_the_ID(), '_tagline', true),
    'address' => get_post_meta(get_the_ID(), '_address', true),
    'email' => get_post_meta(get_the_ID(), '_email', true),
    'web' => get_post_meta(get_the_ID(), '_website', true),
    'phone_number' => get_post_meta(get_the_Id(), '_phone', true),
    'category' => get_post_meta(get_the_Id(), '_admin_category_select', true),
    'post_view' => get_post_meta(get_the_Id(), '_atbdp_post_views_count', true),
    'hide_contact_info' => get_post_meta(get_the_ID(), '_hide_contact_info', true),
    'disable_contact_info' => get_directorist_option('disable_contact_info', 0),
    'display_title' => get_directorist_option('display_title', 1),
    'display_review' => get_directorist_option('enable_review', 1),
    'display_price' => get_directorist_option('display_price', 1),
    'display_email' => get_directorist_option('display_email', 0),
    'display_web_link' => get_directorist_option('display_web_link', 0),
    'display_category' => get_directorist_option('display_category', 1),
    'display_view_count' => get_directorist_option('display_view_count', 1),
    'display_mark_as_fav' => get_directorist_option('display_mark_as_fav', 1),
    'display_publish_date' => get_directorist_option('display_publish_date', 1),
    'display_contact_info' => get_directorist_option('display_contact_info', 1),
    'display_feature_badge_cart' => get_directorist_option('display_feature_badge_cart', 1),
    'display_popular_badge_cart' => get_directorist_option('display_popular_badge_cart', 1),
    'popular_badge_text' => get_directorist_option('popular_badge_text', 'Popular'),
    'feature_badge_text' => get_directorist_option('feature_badge_text', 'Featured'),
    'enable_tagline' => get_directorist_option('enable_tagline'),
    'enable_excerpt' => get_directorist_option('enable_excerpt'),
    'address_location' => get_directorist_option('address_location', 'location'),
    'bdbh' => get_post_meta(get_the_ID(), '_bdbh', true),
    'enable247hour' => get_post_meta(get_the_ID(), '_enable247hour', true),
    'disable_bz_hour_listing' => get_post_meta(get_the_ID(), '_disable_bz_hour_listing', true),
    'business_hours' => !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(),
    'author_id' => get_the_author_meta('ID'),
    'display_author_image' => get_directorist_option('display_author_image', 1),
    'u_pro_pic' => get_user_meta($author_id, 'pro_pic', true),
    'u_pro_pic' => !empty($u_pro_pic) ? wp_get_attachment_image_src($u_pro_pic, 'thumbnail') : '',
    'avatar_img' => get_avatar( $author_id, apply_filters('atbdp_avatar_size', 32) ),
    'display_tagline_field' => get_directorist_option('display_tagline_field', 0),
    'display_pricing_field' => get_directorist_option('display_pricing_field', 1),
    'display_excerpt_field' => get_directorist_option('display_excerpt_field', 0),
    'display_address_field' => get_directorist_option('display_address_field', 1),
    'display_phone_field' => get_directorist_option('display_phone_field', 1),
    'display_image' => !empty( $template_data && $template_data->display_image ) ? $template_data->display_image : '',
    'prv_image' => '',
    'default_image' => get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg'),
    'thumbnail_link_attr' => trim(" " . apply_filters('grid_view_thumbnail_link_add_attr', '')),
    'title_link_attr' => trim(" " . apply_filters('grid_view_title_link_add_attr', '')),
  ];

  $data['listing_preview_img_class'] = ('no' == $data['listing_preview_img'] || (empty($data['prv_image']) && empty($data['default_image']) && empty($data['gallery_img'])) ) ? ' listing_preview_img_none' : '';
  $data['listing_preview_img'] = empty(get_directorist_option('display_preview_image', 1)) || 'no' == $data['display_image'] ? 'no' : 'yes';
  
  if ( ! empty( $data['listing_preview_img'] ) ) {
    $data['prv_image_full'] = atbdp_get_image_source($data['listing_preview_img'], 'full');
  }
  
  if ( ! empty( $data['listing_img'][0] ) ) {
    $data['gallery_img_full'] = atbdp_get_image_source( $data['listing_img'][0], 'full' );
  }

  $data['business_hours'] = !empty($data['bdbh']) ? atbdp_sanitize_array($data['bdbh']) : array();
  
  return (object) $data;
}
