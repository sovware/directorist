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
    include $theme_template_file_path;
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


// atbdp_listings_loop
function atbdp_listings_loop( $loop_file, $atts = [] ) {
  $model = new All_Listins_Model( $atts );
  $template_data = $model->listings_loop_data();
  
  atbdp_shortcode_template( "listings-archive/loop/$loop_file", $template_data, true, $model);
}

// atbdp_listings_map
function atbdp_listings_map( $atts ) {
  $model = new All_Listins_Model( $atts );
  $template_data = ( array ) $model;
  $select_listing_map = $model->select_listing_map;

  if ( 'google' != $select_listing_map) {
    atbdp_shortcode_template( "listings-archive/loop/openstreet-map", $template_data, true, $model);
    return;
  }

  if ( 'google' == $select_listing_map ) {
      atbdp_shortcode_template( "listings-archive/loop/google-map", $template_data, true, $model);
  }
}

// atbdp_search_result_page_link
function atbdp_search_result_page_link()
{
  echo ATBDP_Permalink::get_search_result_page_link();
}
