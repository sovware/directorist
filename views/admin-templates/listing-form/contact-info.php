 <?php
if (!empty($args['listing_contact_info'])) {
    extract($args['listing_contact_info']);
}
// grab social information
$social_info = !empty($social) ? $social : array();
$default_latitude = get_directorist_option('default_latitude', '40.7127753');
$default_longitude = get_directorist_option('default_longitude', '-74.0059728');
$map_zoom_level = get_directorist_option('map_zoom_level', 4);
$disable_price = get_directorist_option('disable_list_price');
$disable_contact_info = get_directorist_option('disable_contact_info');
$disable_contact_owner = get_directorist_option('disable_contact_owner',1);
$currency = get_directorist_option('g_currency', 'USD');
$display_address_field = get_directorist_option('display_address_field', 1);
$address_placeholder   = get_directorist_option('address_placeholder',__('Listing address eg. New York, USA', 'directorist'));
$display_phone_field = get_directorist_option('display_phone_field', 1);
$display_phone2_field = get_directorist_option('display_phone_field2', 1);
$display_fax = get_directorist_option('display_fax', 1);
$phone_placeholder = get_directorist_option('phone_placeholder', __('Phone Number', 'directorist'));
$phone_placeholder2 = get_directorist_option('phone_placeholder2', __('Phone Number 2', 'directorist'));
$fax_placeholder = get_directorist_option('fax_placeholder', __('Fax', 'directorist'));
$display_email_field = get_directorist_option('display_email_field', 1);
$email_placeholder = get_directorist_option('email_placeholder', __('Enter Email', 'directorist'));
$display_website_field = get_directorist_option('display_website_field', 1);
$website_placeholder = get_directorist_option('website_placeholder', __('Listing Website eg. http://example.com', 'directorist'));
$display_zip_field = get_directorist_option('display_zip_field', 1);
$zip_placeholder = get_directorist_option('zip_placeholder', __('Enter Zip/Post Code', 'directorist'));
$display_social_info_field = get_directorist_option('display_social_info_field', 1);
$display_map_field = get_directorist_option('display_map_field', 1);
$select_listing_map = get_directorist_option('select_listing_map', 'google');
$display_contact_hide = get_directorist_option('display_contact_hide', 1);
$contact_hide_label = get_directorist_option('contact_hide_label', __('Check it to hide Contact Information for this listing', 'directorist'));
$t = '';//later need to configure the marker info window
//$t = !empty( $t ) ? esc_html($t) : __('No Title ', 'directorist');
$tg = !empty( $tagline ) ? esc_html($tagline) : '';
$ad = !empty( $address ) ? esc_html($address) : '';
$image = (!empty($attachment_id[0])) ? "<img src='". esc_url(wp_get_attachment_image_url($attachment_id[0], 'thumbnail'))."'>": '';
$info_content = "<div class='map_info_window'> <h3>{$t}</h3>";
$info_content .= "<p> {$tg}</p>";
$info_content .= $image ; // add the image if available
$info_content .= "<p> {$ad}</p></div>";
?>
<div id="directorist" class="directorist atbd_wrapper">
    <!-- MAP or ADDRESS related information starts here -->
    <?php if(!$disable_contact_owner) { ?>
        <div class="form-check">
            <input type="checkbox" name="hide_contact_owner" class="form-check-input" id="hide_contact_owner"
                   value="1" <?php if (!empty($hide_contact_owner)) {
                checked($hide_contact_owner);
            } ?> >
            <label class="form-check-label"
                   for="hide_contact_owner"><?php _e('Check it to hide listing contact owner form', 'directorist'); ?></label>

        </div>
    <?php } ?>
    <?php if (!empty( $display_phone_field || $display_phone2_field|| $display_fax || $display_map_field || $display_address_field || $display_email_field || $display_website_field || $display_zip_field)) { ?>
        <!-- MAP or ADDRESS related information starts here -->
        <?php if(!empty($display_contact_hide)) { ?>
        <div class="form-check">
            <input type="checkbox" name="hide_contact_info" class="form-check-input" id="hide_contact_info"
                   value="1" <?php if (!empty($hide_contact_info)) {
                checked($hide_contact_info);
            } ?> >
            <label class="form-check-label"
                   for="hide_contact_info"><?php echo !empty($contact_hide_label) ? $contact_hide_label : __('Check it to hide Contact Information for this listing', 'directorist'); ?></label>

        </div>
        <?php } ?>

         <?php if (!empty($display_map_field) || !empty($display_address_field)) { ?>


    <!--Google map will be generated here using js-->
        <div class="form-group">
            <?php if(!empty($display_address_field)) { ?>
                <div class="g_address_wrap">
                    <label for="address"><?php
                        $address_label = get_directorist_option('address_label', __('Google Address', 'directorist'));
                        esc_html_e($address_label.':', 'directorist'); ?></label>
                    <input type="text" name="address" id="address" autocomplete="off" value="<?php echo !empty($address) ? esc_attr($address) : ''; ?>"
                        class="form-control directory_field"
                        placeholder="<?php echo esc_attr($address_placeholder); ?>"/>
                    <div id="result">
                        <ul></ul>
                    </div>
                </div>
            <?php } ?>
            <?php if(!empty($display_map_field)) { ?>
            <div class="map_wrapper">
                <?php if('google' == $select_listing_map) {?>
                <div id="floating-panel">
                    <button class="btn btn-danger"
                            id="delete_marker"> <?php _e('Delete Marker', 'directorist'); ?></button>
                </div>
                <?php } ?>
                <div id="osm">
                    <div id="gmap"></div>
                </div>
                <?php if('google' == $select_listing_map) {?>
                <small class="map_drag_info"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('You can drag pinpoint to place the correct address manually.', 'directorist'); ?></small>
                 <?php } ?>
                <div class="map-coordinate form-group">
                    <div class="cor-wrap map_cor">
                        <input type="checkbox" name="manual_coordinate" value="1"
                            id="manual_coordinate" <?php echo (!empty($manual_coordinate)) ? 'checked' : ''; ?> >
                        <label for="manual_coordinate"> <?php _e('Or Enter Coordinates (latitude and longitude) Manually.', 'directorist'); ?> </label>
                    </div>
                </div>
            </div>
            <div id="hide_if_no_manual_cor">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-5 col-sm-12 v_middle">
                            <div class="form-group">
                                <label for="manual_lat"> <?php _e('Latitude', 'directorist'); ?>  </label>
                                <input type="text" name="manual_lat" id="manual_lat"
                                    value="<?php echo (!empty($manual_lat)) ? $manual_lat : $default_latitude ?>"
                                    class="form-control directory_field"
                                    placeholder="<?php esc_attr_e('Enter Latitude eg. 24.89904', 'directorist'); ?>"/>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-12 v_middle">
                            <div class="form-group">
                                <label for="manual_lng"> <?php _e('Longitude', 'directorist'); ?> </label>
                                <input type="text" name="manual_lng" id="manual_lng"
                                    value="<?php echo (!empty($manual_lng)) ? $manual_lng : $default_longitude ?>"
                                    class="form-control directory_field"
                                    placeholder="<?php esc_attr_e('Enter Longitude eg. 91.87198', 'directorist'); ?>"/>
                            </div>
                        </div>
                    </div>

                    <div class="lat_btn_wrap">
                        <button class="btn btn-primary btn-sm"
                                id="generate_admin_map"><?php _e('Generate on Map', 'directorist'); ?></button>
                    </div>

                </div>
            </div> <!--ends #hide_if_no_manual_cor -->
            <div class="atbd_map_hide form-group">
                <input type="checkbox" name="hide_map" value="1"
                    id="hide_map" <?php echo (!empty($hide_map)) ? 'checked' : ''; ?> >
                <label for="hide_map"> <?php _e('Hide Map', 'directorist'); ?> </label>
            </div>
                 <?php } ?>
        </div>

<?php
}

        /**
        * It fires after the google map preview area
        * @param string $type Page type.
        * @param array $listing_contact_info Information of the current listing
        * @since 1.1.1
        **/
        do_action('atbdp_edit_after_googlemap_preview', 'add_listing_page_backend', $args['listing_contact_info'], get_the_ID());
        ?>
    <!--    zip code-->
        <?php if(!empty($display_zip_field) ) {?>
            <div class="form-group" id="atbdp__zip">
                <label for="atbdp_zip"><?php $zip_label = get_directorist_option('zip_label', __('Zip/Post Code', 'directorist'));
                    esc_html_e($zip_label.':', 'directorist'); ?></label>

                <input type="text" id="atbdp_zip" name="zip"
                       value="<?php echo !empty($zip) ? esc_attr($zip) : ''; ?>" class="form-control directory_field"
                       placeholder="<?php echo esc_attr($zip_placeholder); ?>"/>
            </div>
        <?php } ?>
        <!--phone-->
        <?php if(!empty($display_phone_field) ) { ?>
        <div class="form-group" id="atbdp_phone__number">
            <label for="atbdp_phone_number"><?php $phone_label = get_directorist_option('phone_label', __('Phone Number', 'directorist'));
                esc_html_e($phone_label.':', 'directorist'); ?></label>
            <input type="tel" name="phone" id="atbdp_phone_number"
                   value="<?php echo !empty($phone) ? esc_attr($phone) : ''; ?>" class="form-control directory_field"
                   placeholder="<?php echo esc_attr($phone_placeholder); ?>"/>
        </div>
        <?php } ?>
        <!--phone2-->
        <?php if(!empty($display_phone2_field) ) {
            ?>
        <div class="form-group" id="atbdp_phone__number2">
            <label for="atbdp_phone_number2"><?php $phone_label2 = get_directorist_option('phone_label2', __('Phone Number Two', 'directorist'));
                esc_html_e($phone_label2.':', 'directorist'); ?></label>
            <input type="tel" name="phone2" id="atbdp_phone_number2"
                   value="<?php echo !empty($phone2) ? esc_attr($phone2) : ''; ?>" class="form-control directory_field"
                   placeholder="<?php echo esc_attr($phone_placeholder2); ?>"/>
        </div>
        <?php } ?>
        <!--fax-->
        <?php if(!empty($display_fax) ) { ?>
        <div class="form-group" id="atbdp__fax">
            <label for="atbdp_fax"><?php $fax_label = get_directorist_option('fax_label', __('Fax', 'directorist'));
                esc_html_e($fax_label.':', 'directorist'); ?></label>
            <input type="tel" name="fax" id="atbdp_fax"
                   value="<?php echo !empty($fax) ? esc_attr($fax) : ''; ?>" class="form-control directory_field"
                   placeholder="<?php echo esc_attr($fax_placeholder); ?>"/>
        </div>
        <?php } ?>
        <?php if(!empty($display_email_field)) { ?>
        <div class="form-group" id="atbdp__email">
            <label for="atbdp_email"><?php $email_label = get_directorist_option('email_label', __('Email', 'directorist'));
                esc_html_e($email_label.':', 'directorist'); ?></label>
            <input type="email" name="email" id="atbdp_email" value="<?php echo !empty($email) ? esc_attr($email) : ''; ?>"
                   class="form-control directory_field"
                   placeholder="<?php echo esc_attr($email_placeholder); ?>"/>
        </div>
        <?php } ?>
        <?php if(!empty($display_website_field) ) {?>
        <div class="form-group" id="atbdp__website">
            <label for="atbdp_website"><?php  $website_label = get_directorist_option('website_label', __('Website', 'directorist'));
                esc_html_e($website_label.':', 'directorist'); ?></label>

            <input type="text" id="atbdp_website" name="website"
                   value="<?php echo !empty($website) ? esc_attr($website) : ''; ?>" class="form-control directory_field"
                   placeholder="<?php echo esc_attr($website_placeholder); ?>"/>
        </div>
        <?php } ?>
        <?php } ?>

        <!--Social Information-->

            <?php
            /**
             * It fires before social information fields
             * @param string $type Page type.
             * @param array $listing_contact_info Information of the current listing
             * @since 1.1.1
             **/
            do_action('atbdp_edit_before_social_info_fields', 'add_listing_page_backend', $args['listing_contact_info']);
            if(!empty($display_social_info_field)) {
                ATBDP()->load_template('admin-templates/listing-form/social', array('social_info' => $social_info));
            }
            /**
    * It fires after social information fields
    * @param string $type Page type.
    * @param array $listing_contact_info Information of the current listing
    * @since 1.1.1
    **/
    do_action('atbdp_edit_after_social_info_fields', 'add_listing_page_backend', $args['listing_contact_info']);
    ?>
    <div class="atbd_backend_business_hours">
        <?php
        if (class_exists('BD_Business_Hour') && get_directorist_option('enable_business_hour') ==1){
        ?>
        <div class="atbd_backend_business_hour">
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="atbd_backend_area_title"><?php _e('Enter Information about Business/Opening Hours', 'directorist'); ?></h6>
                    </div>
                </div>
        <?php do_action('atbdp_edit_after_contact_info_fields', 'add_listing_page_backend', $args['listing_contact_info']); ?>
        </div>
            <?php
        }
        ?>
    </div> <!--ends .row-->
   <?php

?>
</div>
<?php
if ('openstreet' == $select_listing_map) {
    wp_enqueue_script( 'openstreet_layer' );
    wp_enqueue_style('leaflet-css');
}
?>
<script>

    // Bias the auto complete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.

    jQuery(document).ready(function ($) {

        <?php if (!empty($display_map_field) && !empty($display_address_field) ) {
            if('google' == $select_listing_map) {
        ?>

        // initialize all vars here to avoid hoisting related misunderstanding.
        var placeSearch, map, autocomplete, address_input, markers, info_window, $manual_lat, $manual_lng, saved_lat_lng, info_content;
        $manual_lat = $('#manual_lat');
        $manual_lng = $('#manual_lng');
        saved_lat_lng = {
            lat:<?php echo (!empty($manual_lat)) ? floatval($manual_lat) : $default_latitude ?>,
            lng: <?php echo (!empty($manual_lng)) ? floatval($manual_lng) : $default_longitude ?> }; // default is London city
        info_content = "<?php echo $info_content; ?>";
        markers = [];// initialize the array to keep track all the marker
        /*@todo; make the max width size customizable*/
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

        /**
         * It deletes all the map markers
         * */
        function deleteMarker() {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers = [];
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
            deleteMarker();
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
                // at first delete the old marker if there is any and then add new marker
                deleteMarker();
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
            geocoder.geocode({'address': address}, function (results, status) {
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
            deleteMarker();// delete all markers

        });

        <?php }elseif('openstreet' == $select_listing_map) { ?>
        function mapLeaflet (lat, lon)	 {
            const fontAwesomeIcon = L.icon({
                iconUrl: "<?php echo DIRECTORIST_ASSETS . 'images/map-icon.png'; ?>",
                iconSize: [20, 25],
            });
            var mymap = L.map('gmap').setView([lat, lon], <?php echo !empty($map_zoom_level) ? intval($map_zoom_level) : 4; ?>);

            L.marker([lat, lon], {icon: fontAwesomeIcon, draggable: true}).addTo(mymap).on("drag", function(e) {
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

        $('#address').on('keyup', function(event) {
            event.preventDefault();
            if(event.keyCode !== 40 && event.keyCode !== 38){
                var search = $('#address').val();
                $('#result').css({'display':'block'});
                if(search === ""){
                    $('#result').css({'display':'none'});
                }
                var res = "";
                $.ajax({
                    url: `https://nominatim.openstreetmap.org/?q=%27+${search}+%27&format=json`,
                    type: 'POST',
                    data: {},
                    success: function (data) {
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

        mapLeaflet (lat, lon);

        $('body').on('click', '#result ul li a', function(event) {
            document.getElementById('osm').innerHTML = "<div id='gmap'></div>";
            event.preventDefault();
            let text = $(this).text(),
                lat = $(this).data('lat'),
                lon = $(this).data('lon');

            $('#manual_lat').val(lat);
            $('#manual_lng').val(lon);

            $('#address').val(text);
            $('#result').css({'display':'none'});

            mapLeaflet (lat, lon);
        });

        $('body').on('click', '#generate_admin_map', function (event) {
            event.preventDefault();
            document.getElementById('osm').innerHTML = "<div id='gmap'></div>";
            mapLeaflet ($('#manual_lat').val(), $('#manual_lng').val());

        });


        $('form').submit(function () {
            if (($(document.activeElement).attr('type') == 'submit') || $('#post-preview'))
                return true;
            else return false;
        });

        // Popup controller by keyboard
        var index = 0;
        $('#address').on('keyup', function(event) {
            var length = $('#directorist.atbd_wrapper #result ul li a').length;
            if(event.keyCode === 40) {
                index++;
                if( index > length) {
                    index = 0;
                }
            } else if(event.keyCode === 38) {
                index--;
                if(index < 0) {
                    index = length
                };
            }

            if($('#directorist.atbd_wrapper #result ul li a').length > 0){

                $('#directorist.atbd_wrapper #result ul li a').removeClass('active')
                $($('#directorist.atbd_wrapper #result ul li a')[index]).addClass('active');

                if(event.keyCode === 13){
                    $($('#directorist.atbd_wrapper #result ul li a')[index]).click();
                    index = 0;
                    event.preventDefault();
                    return false;
                }
            };
        });

        // Popup controller by keyboard



    <?php
         } // select map
        } // disable map
        ?>

    }); // ends jquery ready function.

</script>
 <style>
     #OL_Icon_34{
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
     #OL_Icon_34:hover .mapHover{
         display: block;
     }

     #directorist.atbd_wrapper a {
        display: block;
        background: #fff;
        padding: 8px 10px;
    }

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