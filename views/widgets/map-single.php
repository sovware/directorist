<?php
echo $args['before_widget'];
echo '<div class="atbd_widget_title">';
echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
echo '</div>';

$display_map_info               = apply_filters('atbdp_listing_map_info_window', get_directorist_option('display_map_info', 1));
$atbdp_legacy_template          = get_directorist_option( 'atbdp_legacy_template' );
$data = array(
  'listing'               => $this,
  'default_latitude'      => get_directorist_option('default_latitude', '40.7127753'),
  'default_longitude'     => get_directorist_option('default_longitude', '-74.0059728'),
  'manual_lat'            => $manual_lat,
  'manual_lng'            => $manual_lng,
  'listing_location_text' => apply_filters('atbdp_single_listing_map_section_text', get_directorist_option('listing_location_text', __('Location', 'directorist'))),
  'select_listing_map'    => get_directorist_option('select_listing_map', 'google'),
  'info_content'          => $info_content,
  'display_map_info'      => $display_map_info,
  'map_zoom_level'        => get_directorist_option('map_zoom_level', 16),
  'cat_icon'              => $cat_icon,
);

if ('openstreet' == $select_listing_map) {
    if( ! empty( $atbdp_legacy_template ) ) {
        wp_localize_script('atbdp-single-listing-osm', 'localized_data', $data);
        wp_enqueue_script('atbdp-single-listing-osm');
    } else {
        wp_localize_script('directorist-single-listing-openstreet-map-custom-script', 'localized_data', $data);
        wp_enqueue_script('directorist-single-listing-openstreet-map-custom-script');
    }
}

if ('google' == $select_listing_map) {
    if( ! empty( $atbdp_legacy_template ) ) {
        wp_localize_script('atbdp-single-listing-gmap', 'localized_data', $data);
        wp_enqueue_script('atbdp-single-listing-gmap');
    }
} ?>
 
<?php if( ! empty( $atbdp_legacy_template ) ) { ?> 
    <div id="gmap" class="atbd_google_map"></div>
<?php } else { ?>
    <div id="gmap" class="directorist-google-map"></div>
<?php } ?>

<?php
echo $args['after_widget'];