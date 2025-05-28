/******/ (function() { // webpackBootstrap
/*!**********************************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/single-listing/openstreet-map-widget.js ***!
  \**********************************************************************************/
/* Widget OSMap */

(function ($) {
  // Single Listing Map Initialize
  function initSingleMap() {
    // Localized Data
    if ($('#gmap-widget').length) {
      var map_container = localized_data_widget.map_container_id ? localized_data_widget.map_container_id : 'gmap';
      var loc_default_latitude = parseFloat(localized_data_widget.default_latitude);
      var loc_default_longitude = parseFloat(localized_data_widget.default_longitude);
      var loc_manual_lat = parseFloat(localized_data_widget.manual_lat);
      var loc_manual_lng = parseFloat(localized_data_widget.manual_lng);
      var loc_map_zoom_level = parseInt(localized_data_widget.map_zoom_level);
      var _localized_data_widge = localized_data_widget,
        display_map_info = _localized_data_widge.display_map_info;
      var _localized_data_widge2 = localized_data_widget,
        cat_icon = _localized_data_widge2.cat_icon;
      var _localized_data_widge3 = localized_data_widget,
        info_content = _localized_data_widge3.info_content;
      loc_manual_lat = isNaN(loc_manual_lat) ? loc_default_latitude : loc_manual_lat;
      loc_manual_lng = isNaN(loc_manual_lng) ? loc_default_longitude : loc_manual_lng;
      $manual_lat = $('#manual_lat');
      $manual_lng = $('#manual_lng');
      saved_lat_lng = {
        lat: loc_manual_lat,
        lng: loc_manual_lng
      };
      function mapLeaflet(lat, lon) {
        var fontAwesomeIcon = L.divIcon({
          html: "<div class=\"atbd_map_shape\"><span class=\"\">".concat(cat_icon, "</span></div>"),
          iconSize: [20, 20],
          className: 'myDivIcon'
        });
        var mymap = L.map(map_container).setView([lat, lon], loc_map_zoom_level);
        if (display_map_info) {
          L.marker([lat, lon], {
            icon: fontAwesomeIcon
          }).addTo(mymap).bindPopup(info_content);
        } else {
          L.marker([lat, lon], {
            icon: fontAwesomeIcon
          }).addTo(mymap);
        }
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mymap);
      }
      mapLeaflet(loc_manual_lat, loc_manual_lng);
    }
  }
  jQuery(document).ready(function () {
    initSingleMap();
  });

  // Single Listing Map on Elementor EditMode 
  $(window).on('elementor/frontend/init', function () {
    setTimeout(function () {
      if ($('body').hasClass('elementor-editor-active')) {
        initSingleMap();
      }
    }, 3000);
  });
  $('body').on('click', function (e) {
    if ($('body').hasClass('elementor-editor-active') && e.target.nodeName !== 'A' && e.target.nodeName !== 'BUTTON') {
      initSingleMap();
    }
  });
})(jQuery);
/******/ })()
;
//# sourceMappingURL=single-listing-openstreet-map-widget.js.map