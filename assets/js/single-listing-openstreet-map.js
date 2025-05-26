/******/ (function() { // webpackBootstrap
/*!***************************************************************************!*\
  !*** ./assets/src/js/global/map-scripts/single-listing/openstreet-map.js ***!
  \***************************************************************************/
/* Single listing OSMap */

(function ($) {
  // Single Listing Map Initialize
  function initSingleMap() {
    // Localized Data
    if ($('.directorist-single-map').length) {
      document.querySelectorAll('.directorist-single-map').forEach(function (mapElm) {
        var mapData = JSON.parse(mapElm.getAttribute('data-map'));
        var loc_default_latitude = parseFloat(mapData.default_latitude);
        var loc_default_longitude = parseFloat(mapData.default_longitude);
        var loc_manual_lat = parseFloat(mapData.manual_lat);
        var loc_manual_lng = parseFloat(mapData.manual_lng);
        var loc_map_zoom_level = parseInt(mapData.map_zoom_level);
        var display_map_info = mapData.display_map_info;
        var cat_icon = mapData.cat_icon;
        var info_content = mapData.info_content;
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
            html: "<div class=\"atbd_map_shape\">".concat(cat_icon, "</div>"),
            iconSize: [20, 20],
            className: 'myDivIcon'
          });
          var mymap = L.map(mapElm, {
            scrollWheelZoom: false
          }).setView([lat, lon], loc_map_zoom_level);
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
      });
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
//# sourceMappingURL=single-listing-openstreet-map.js.map