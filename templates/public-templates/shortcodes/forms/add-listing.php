<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div id="directorist" class="directorist atbd_wrapper atbd_add_listing_wrapper">
    <div class="<?php echo apply_filters('atbdp_add_listing_container_fluid', $container_fluid) ?>">
        <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post" id="add-listing-form">
            <fieldset>
                <?php
                do_action('atbdb_before_add_listing_from_frontend');//for dev purpose
                ?>
                <div class="atbdp-form-fields">
                    <?php
                    /**
                     * @since 7.0
                     * @hooked Directorist_Template_Hooks::add_listing_title - 10
                     */
                    do_action( 'directorist_add_listing_title' );

                    /*
                     * if fires after
                     * @since 4.0.4
                     */
                    do_action('atbdp_listing_form_after_add_listing_title', $listing_info)
                    ?>
                    <!--add nonce field security -->
                    <?php ATBDP()->listing->add_listing->show_nonce_field(); ?>
                    <input type="hidden" name="add_listing_form" value="1">
                    <input type="hidden" name="listing_id" value="<?php echo !empty($p_id) ? esc_attr($p_id) : ''; ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            /**
                             * It fires before the listing title
                             * @param string $type Page type.
                             * @since 1.1.1
                             **/
                            do_action('atbdp_edit_before_title_fields', 'add_listing_page_frontend');
                            ?>

                            <div class="atbdb_content_module">
                                <?php
                                /**
                                 * @since 7.0
                                 * @hooked Directorist_Template_Hooks::add_listing_general - 10
                                 * @hooked Directorist_Template_Hooks::add_listing_contact - 15
                                 * @hooked Directorist_Template_Hooks::add_listing_map - 20
                                 * @hooked Directorist_Template_Hooks::add_listing_image - 25
                                 * @hooked Directorist_Template_Hooks::add_listing_submit - 30
                                 */
                                do_action( 'directorist_add_listing_contents' );
                                ?>
                            </div>
                        </div>
                    </div>
            </fieldset>
        </form>
    </div> <!--ends container-fluid-->
</div>

<?php
if ('openstreet' == $select_listing_map) {
    wp_register_script('openstreet_layer', ATBDP_PUBLIC_ASSETS . 'js/openstreetlayers.js', array('jquery'), ATBDP_VERSION, true);
    wp_enqueue_script('openstreet_layer');
    wp_enqueue_style('leaflet-css', ATBDP_PUBLIC_ASSETS . 'css/leaflet.css');
}
?>
<script>

    jQuery(document).ready(function ($) {
        <?php if(is_fee_manager_active() ) { ?>
//        $('#fm_plans_container').on('click', function(){
//            $('.atbdp-form-fields').fadeIn(1000);
//            $('#fm_plans_container').fadeOut(300)
//        });
        <?php } ?>
        // Bias the auto complete object to the user's geographical location,
        // as supplied by the browser's 'navigator.geolocation' object.
        <?php if (empty($display_map_for) && !empty($display_map_field)) {
        if('google' == $select_listing_map) {
        ?>
        // initialize all vars here to avoid hoisting related misunderstanding.
        var placeSearch, map, autocomplete, address_input, markers, info_window, $manual_lat, $manual_lng,
            saved_lat_lng, info_content;
        $manual_lat = $('#manual_lat');
        $manual_lng = $('#manual_lng');
        saved_lat_lng = {
            lat:<?php echo (!empty($manual_lat)) ? floatval($manual_lat) : $default_latitude ?>,
            lng: <?php echo (!empty($manual_lng)) ? floatval($manual_lng) : $default_longitude ?> }; // default is London city
        info_content = "<?php echo $info_content ?>";
        markers = [];// initialize the array to keep track all the marker
        info_window = new google.maps.InfoWindow({
            content: info_content,
            maxWidth: 400
        });

        address_input = document.getElementById('address');
        address_input.addEventListener('focus', geolocate);

        // this function will work on sites that uses SSL, it applies to Chrome especially, other browsers may allow location sharing without securing.
        function geolocate() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var geolocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    var circle = new google.maps.Circle({
                        center: geolocation,
                        radius: position.coords.accuracy
                    });
                    autocomplete.setBounds(circle.getBounds());
                });
            }
        }


        function initAutocomplete() {
            // Create the autocomplete object, restricting the search to geographical
            // location types.
            autocomplete = new google.maps.places.Autocomplete(
                (address_input),
                {types: []});

            // When the user selects an address from the dropdown, populate the necessary input fields and draw a marker
            autocomplete.addListener('place_changed', fillInAddress);
        }

        function fillInAddress() {
            // Get the place details from the autocomplete object.
            var place = autocomplete.getPlace();

            // set the value of input field to save them to the database
            $manual_lat.val(place.geometry.location.lat());
            $manual_lng.val(place.geometry.location.lng());
            map.setCenter(place.geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: place.geometry.location
            });

            // marker.addListener('click', function () {
            //     info_window.open(map, marker);
            // });

            // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
            markers.push(marker);
        }

        initAutocomplete(); // start google map place auto complete API call


        function initMap() {
            /* Create new map instance*/
            map = new google.maps.Map(document.getElementById('gmap'), {
                zoom: <?php echo !empty($map_zoom_level) ? intval($map_zoom_level) : 4; ?>,
                center: saved_lat_lng
            });
            var marker = new google.maps.Marker({
                map: map,
                position: saved_lat_lng,
                draggable: true,
                title: '<?php _e('You can drag the marker to your desired place to place a marker', 'directorist'); ?>'
            });
            // marker.addListener('click', function () {
            //     info_window.open(map, marker);
            // });
            // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
            markers.push(marker);

            // create a Geocode instance
            var geocoder = new google.maps.Geocoder();

            document.getElementById('generate_admin_map').addEventListener('click', function (e) {
                e.preventDefault();
                geocodeAddress(geocoder, map);
            });


            // This event listener calls addMarker() when the map is clicked.
            google.maps.event.addListener(map, 'click', function (event) {
                deleteMarker(); // at first remove previous marker and then set new marker;
                // set the value of input field to save them to the database
                $manual_lat.val(event.latLng.lat());
                $manual_lng.val(event.latLng.lng());
                // add the marker to the given map.
                addMarker(event.latLng, map);
            });
            // This event listener update the lat long field of the form so that we can add the lat long to the database when the MARKER is drag.
            google.maps.event.addListener(marker, 'dragend', function (event) {
                // set the value of input field to save them to the database
                $manual_lat.val(event.latLng.lat());
                $manual_lng.val(event.latLng.lng());
            });
        }

        /*
         * Geocode and address using google map javascript api and then populate the input fields for storing lat and long
         * */

        function geocodeAddress(geocoder, resultsMap) {
            var address = address_input.value;
            var lat = document.getElementById('manual_lat').value;
            var lng = document.getElementById('manual_lng').value;
            var latLng = new google.maps.LatLng( lat, lng );
            var opt = { location: latLng, address: address };

            geocoder.geocode(opt, function (results, status) {
                if (status === 'OK') {
                    // set the value of input field to save them to the database
                    $manual_lat.val(results[0].geometry.location.lat());
                    $manual_lng.val(results[0].geometry.location.lng());
                    resultsMap.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        map: resultsMap,
                        position: results[0].geometry.location
                    });

                    // marker.addListener('click', function () {
                    //     info_window.open(map, marker);
                    // });
                    
                    deleteMarker();
                    // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
                    markers.push(marker);
                } else {
                    alert('<?php _e('Geocode was not successful for the following reason: ', 'directorist'); ?>' + status);
                }
            });
        }

        initMap();


        // adding features of creating marker manually on the map on add listing page.
        /*var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
         var labelIndex = 0;*/


        // Adds a marker to the map.
        function addMarker(location, map) {
            // Add the marker at the clicked location, and add the next-available label
            // from the array of alphabetical characters.
            var marker = new google.maps.Marker({
                position: location,
                /*label: labels[labelIndex++ % labels.length],*/
                draggable: true,
                title: '<?php _e('You can drag the marker to your desired place to place a marker', 'directorist'); ?>',
                map: map
            });
            // marker.addListener('click', function () {
            //     info_window.open(map, marker);
            // });
            // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
            markers.push(marker);
        }

        // Delete Marker
        $('#delete_marker').on('click', function (e) {
            e.preventDefault();
            deleteMarker();

        });

        function deleteMarker() {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers = [];
        }
        <?php } elseif('openstreet' == $select_listing_map) { ?>
        function mapLeaflet(lat, lon) {
            const fontAwesomeIcon = L.icon({
                iconUrl: "<?php echo ATBDP_PUBLIC_ASSETS . 'images/map-icon.png'; ?>",
                iconSize: [20, 25],
            });
            var mymap = L.map('gmap').setView([lat, lon], <?php echo !empty($map_zoom_level) ? intval($map_zoom_level) : 4; ?>);

            L.marker([lat, lon], {
                icon: fontAwesomeIcon,
                draggable: true
            }).addTo(mymap).addTo(mymap).on("drag", function (e) {
                var marker = e.target;
                var position = marker.getLatLng();
                $('#manual_lat').val(position.lat);
                $('#manual_lng').val(position.lng);
                $.ajax({
                    url: `https://nominatim.openstreetmap.org/reverse?format=json&lon=${position.lng}&lat=${position.lat}`,
                    type: 'POST',
                    data: {},
                    success: function (data) {
                        $('#address').val(data.display_name);
                    }
                });
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(mymap);
        }

        $('#address').on('keyup', function (event) {
            event.preventDefault();
            if (event.keyCode !== 40 && event.keyCode !== 38) {
                var search = $('#address').val();
                $('#result').css({'display': 'block'});
                if (search === "") {
                    $('#result').css({'display': 'none'});
                }
                var res = "";
                $.ajax({
                    url: `https://nominatim.openstreetmap.org/?q=%27+${search}+%27&format=json`,
                    type: 'POST',
                    data: {},
                    success: function (data) {
                        //console.log(data);
                        for (var i = 0; i < data.length; i++) {
                            res += `<li><a href="#" data-lat=${data[i].lat} data-lon=${data[i].lon}>${data[i].display_name}</a></li>`
                        }
                        $('#result ul').html(res);
                    }
                });
            }
        });

        let lat = <?php echo (!empty($manual_lat)) ? floatval($manual_lat) : $default_latitude ?>,
            lon = <?php echo (!empty($manual_lng)) ? floatval($manual_lng) : $default_longitude ?>;

        mapLeaflet(lat, lon);

        $('body').on('click', '#result ul li a', function (event) {
            document.getElementById('osm').innerHTML = "<div id='gmap'></div>";
            event.preventDefault();
            let text = $(this).text(),
                lat = $(this).data('lat'),
                lon = $(this).data('lon');

            $('#manual_lat').val(lat);
            $('#manual_lng').val(lon);

            $('#address').val(text);
            $('#result').css({'display': 'none'});

            mapLeaflet(lat, lon);
        });

        $('body').on('click', '#generate_admin_map', function (event) {
            event.preventDefault();
            document.getElementById('osm').innerHTML = "<div id='gmap'></div>";
            mapLeaflet($('#manual_lat').val(), $('#manual_lng').val());

        });

        // Popup controller by keyboard
        var index = 0;
        $('#address').on('keyup', function (event) {
            event.preventDefault();
            var length = $('#directorist.atbd_wrapper #result ul li a').length;
            if (event.keyCode === 40) {
                index++;
                if (index > length) {
                    index = 0;
                }
            } else if (event.keyCode === 38) {
                index--;
                if (index < 0) {
                    index = length
                }
                ;
            }

            if ($('#directorist.atbd_wrapper #result ul li a').length > 0) {

                $('#directorist.atbd_wrapper #result ul li a').removeClass('active')
                $($('#directorist.atbd_wrapper #result ul li a')[index]).addClass('active');

                if (event.keyCode === 13) {
                    $($('#directorist.atbd_wrapper #result ul li a')[index]).click();
                    event.preventDefault();
                    index = 0;
                    return false;
                }
            }
            ;

        });

        $('#post').on('submit', function (event) {
            event.preventDefault();
            return false;
        });
        // Popup controller by keyboard

        <?php
        // address
        } // select map
        }  //disable map
        ?>

    }); // ends jquery ready function.
</script>
<style>
    #OL_Icon_33 {
        position: relative;
    }

    .mapHover {
        position: absolute;
        background: #fff;
        padding: 5px;
        width: 150px;
        border-radius: 3px;
        border: 1px solid #ddd;
        display: none;
    }

    #OL_Icon_33:hover .mapHover {
        display: block;
    }

    /*#directorist.atbd_wrapper a {
        display: block;
        background: #fff;
        padding: 8px 10px;
    }*/

    #directorist.atbd_wrapper a:hover {
        background: #eeeeee50;
    }

    #directorist.atbd_wrapper a.active {
        background: #eeeeee70;
    }

    .g_address_wrap ul li {
        margin-bottom: 0px;
        border-bottom: 1px solid #eee;
        padding-bottom: 0px;
    }
</style>
