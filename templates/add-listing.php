<?php
if (!empty($args['listing_info'])) { extract($args['listing_info']); }
//@TODO: I will have to add a text area to get the content for the info window of the map later
$t = get_the_title();
$t = !empty($t)? $t : __('No Title', ATBDP_TEXTDOMAIN);
$tg = !empty($tagline)? esc_html($tagline) : '';
$ad = !empty($address)? esc_html($address) : '';
$image = (!empty($attachment_id[0])) ? "<img src='". esc_url(wp_get_attachment_image_url($attachment_id[0], 'thumbnail'))."'>": '';
$info_content = "<div class='map_info_window'> <h3>{$t}</h3>";
$info_content .= "<p> {$tg}</p>";
$info_content .= $image ; // add the image if available
$info_content .= "<p> {$ad}</p></div>";
$post_ID = $post->ID;
// grab social information
$social_info = !empty( $social ) ? $social : array();
$map_zoom_level = get_directorist_option('map_zoom_level', 16);
$disable_map = get_directorist_option('disable_map');
$disable_price = get_directorist_option('disable_list_price');
$disable_contact_info = get_directorist_option('disable_contact_info');
$currency = get_directorist_option('g_currency', 'USD');
?>
<div id="directorist" class="directorist atbd_wrapper directory_wrapper">
        <?php
        /**
         * It fires before the listing tagline
         * @param string $type Page type.
         * @param array $args Current listing details.
         * @since 1.1.1
         **/
        do_action('atbdp_edit_before_tagline_fields', 'add_listing_page_backend', $args['listing_info']);
        ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <!--@todo; add toggle for the moto and excerpt later. -->
                    <div class="form-group">
                        <label for="atbdp_tagline"><?php esc_html_e('Tag-line/Motto', ATBDP_TEXTDOMAIN) ?></label>
                        <input type="text" id="atbdp_tagline" name="tagline" value="<?= !empty($tagline) ? esc_attr($tagline): ''; ?>" class="form-control directory_field" placeholder="<?= __('Your Organization\'s motto or tag-line', ATBDP_TEXTDOMAIN); ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="atbdp_excerpt"><?php esc_html_e('Short Description/Excerpt(eg. Text shown on Image Hover in grid layout):', ATBDP_TEXTDOMAIN) ?></label>
                        <textarea name="excerpt" id="atbdp_excerpt"  class="form-control directory_field" cols="30" rows="5" placeholder="<?= __('Short Description or Excerpt', ATBDP_TEXTDOMAIN); ?>"><?= !empty($excerpt) ?esc_textarea( stripslashes($excerpt)): ''; ?></textarea>
                    </div>
                    <?php if (!$disable_price){ ?>
                        <div class="form-group">
                            <!--@todo; Add currency Name near price-->
                            <label for="price"><?php
                                /*Translator: % is the name of the currency such eg. USD etc.*/
                                printf(esc_html__('Price [%s] ( Optional---Leave it blank to hide it)', ATBDP_TEXTDOMAIN), $currency); ?></label>
                            <input type="text" id="price" name="price" value="<?= !empty($price) ? esc_attr($price): ''; ?>" class="form-control directory_field" placeholder="<?= __('Price of this listing. Eg. 100', ATBDP_TEXTDOMAIN); ?>"/>
                        </div>
                    <?php } ?>


                    <!--***********************************************************************
                    Run the custom field loop to show all published custom fields asign to form
                    **************************************************************************-->
                    <?php
                    // custom fields information
                    //// get all the custom field that has posted by admin ane return the field
                    $custom_fields  = new WP_Query( array(
                        'post_type'      => ATBDP_CUSTOM_FIELD_POST_TYPE,
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'meta_key'       => 'associate',
                        'meta_value'     => 'form'
                    ) );
                    $fields = $custom_fields->posts;

                    foreach ($fields as $post){
                        setup_postdata($post);
                        $post_id = $post->ID;
                        $cf_required = get_post_meta($post_id, 'required', true);
                        if ($cf_required){
                            $required = '';
                        }
                        $post_meta = get_post_meta( $post_id );

                        ?>

                        <div class="form-group">
                            <label for=""><?php the_title(); ?><?php if($cf_required){echo '<span style="color: red"> *</span>'; }?></label>
                            <?php
                            if( isset( $post_meta[ $post->ID ] ) ) {
                                $value = $post_meta[0];
                            }

                            $cf_meta_default_val = $post_meta['default_value'];
                            if( isset( $post_id ) ) {
                                $cf_meta_default_val = $post_id[0];
                            }
                            $cf_meta_val = $post_meta['type'][0];
                            $cf_rows = $post_meta['rows'][0];
                            $cf_placeholder = $post_meta['placeholder'][0];
                            $value =  get_post_meta($post_ID, $post_id, true); ///store the value for the db

                            switch ($cf_meta_val){
                                case 'text' :
                                    echo '<div>';
                                    printf('<p style="font-style: italic">%s</p>', $post_meta['instructions'][0]);
                                    printf( '<input type="text" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>',$post_id,$cf_placeholder, esc_attr( $value ) );
                                    echo '</div>';
                                    break;
                                case 'textarea' :
                                    printf('<p style="font-style: italic">%s</p>', $post_meta['instructions'][0]);

                                    printf( '<textarea  class="form-control directory_field" name="custom_field[%d]" class="textarea" rows="%d" placeholder="%s">%s</textarea>', $post->ID, (int) $cf_rows,esc_attr( $cf_placeholder ), esc_textarea( $value ) );
                                    break;
                                case 'radio':
                                    $choices = get_post_meta($post_id, 'choices', true);
                                    $choices = explode( "\n", $choices );
                                    printf('<p style="font-style: italic">%s</p>', $value);
                                    echo '<ul class="atbdp-radio-list radio vertical">';
                                    foreach( $choices as $choice ) {
                                        if( strpos( $choice, ':' ) !== false ) {
                                            $_choice = explode( ':', $choice );
                                            $_choice = array_map( 'trim', $_choice );

                                            $_value  = $_choice[0];
                                            $_label  = $_choice[1];
                                        } else {
                                            $_value  = trim( $choice );
                                            $_label  = $_value;
                                        }

                                        $_checked = '';
                                        if( trim( $value ) == $_value ) $_checked = ' checked="checked"';

                                        printf( '<li><label><input type="radio" name="custom_field[%d]" value="%s"%s>%s</label></li>', $post->ID, $_value, $_checked, $_label );
                                    }
                                    echo '</ul>';
                                    break;

                                case 'select' :
                                    $choices = get_post_meta($post_id, 'choices', true);
                                    $choices = explode( "\n", $choices );
                                    printf('<p style="font-style: italic">%s</p>', get_post_meta($post_id, 'instructions', true));
                                    printf( '<select name="custom_field[%d]" class="form-control directory_field">', $post->ID );
                                    if( ! empty( $field_meta['allow_null'][0] ) ) {
                                        printf( '<option value="">%s</option>', '- '.__( 'Select an Option', 'advanced-classifieds-and-directory-pro' ).' -' );
                                    }
                                    foreach( $choices as $choice ) {
                                        if( strpos( $choice, ':' ) !== false ) {
                                            $_choice = explode( ':', $choice );
                                            $_choice = array_map( 'trim', $_choice );

                                            $_value  = $_choice[0];
                                            $_label  = $_choice[1];
                                        } else {
                                            $_value  = trim( $choice );
                                            $_label  = $_value;
                                        }

                                        $_selected = '';
                                        if( trim( $value ) == $_value ) $_selected = ' selected="selected"';

                                        printf( '<option value="%s"%s>%s</option>', $_value, $_selected, $_label );
                                    }
                                    echo '</select>';
                                    break;

                                case 'checkbox' :
                                    $choices = get_post_meta($post_id, 'choices', true);
                                    $choices = explode( "\n", $choices );

                                    $values = explode( "\n", $value );
                                    $values = array_map( 'trim', $values );
                                    printf('<p style="font-style: italic">%s</p>', $post_meta['instructions'][0]);
                                    echo '<ul class="atbdp-checkbox-list checkbox vertical">';

                                    foreach( $choices as $choice ) {
                                        if( strpos( $choice, ':' ) !== false ) {
                                            $_choice = explode( ':', $choice );
                                            $_choice = array_map( 'trim', $_choice );

                                            $_value  = $_choice[0];
                                            $_label  = $_choice[1];
                                        } else {
                                            $_value  = trim( $choice );
                                            $_label  = $_value;
                                        }

                                        $_checked = '';
                                        if( in_array( $_value, $values ) ) $_checked = ' checked="checked"';

                                        printf( '<li><label><input type="hidden" name="custom_field[%s][]" value="" /><input type="checkbox" name="custom_field[%d][]" value="%s"%s>%s</label></li>', $post->ID, $post->ID, $_value, $_checked, $_label );
                                    }
                                    echo '</ul>';
                                    break;
                                case 'url'  :
                                    echo '<div>';
                                    printf('<p style="font-style: italic">%s</p>', $post_meta['instructions'][0]);
                                    printf( '<input type="text" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $post->ID, esc_attr( $cf_placeholder ), esc_url( $value ) );
                                    echo '</div>';
                                    break;

                                case 'date'  :
                                    echo '<div>';
                                    printf('<p style="font-style: italic">%s</p>', $post_meta['instructions'][0]);
                                    printf( '<input type="date" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $post->ID, esc_attr( $cf_placeholder ), esc_attr( $value ) );
                                    echo '</div>';
                                    break;

                                    case 'email'  :
                                    echo '<div>';
                                    printf('<p style="font-style: italic">%s</p>', $post_meta['instructions'][0]);
                                    printf( '<input type="email" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $post->ID, esc_attr( $cf_placeholder ), esc_attr( $value ) );
                                    echo '</div>';
                                    break;

                                case 'color'  :
                                    echo '<div>';
                                    ?>
                                <script>
                                    jQuery(document).ready(function($){
                                        $('.my-color-field2').wpColorPicker();
                                    });
                                </script>
                            <?php
                                    printf('<p style="font-style: italic">%s</p>', $post_meta['instructions'][0]);
                                    printf( '<input type="color" name="custom_field[%d]" class="my-color-field2" value="%s" />', $post->ID, $value );
                                    echo '</div>';
                                    break;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    wp_reset_postdata();
                    ?>
                    <!--***********************************************************************
                   Run the custom field loop to show all published custom fields assign to Category
                   **************************************************************************-->
                    <div id="category_container">
                        <!--@ Options for select the category.-->

                        <div class="form-group">
                            <label for="atbdp_select_cat"><?php esc_html_e('Select Category', ATBDP_TEXTDOMAIN) ?></label>
                            <?php
                            $current_val = esc_attr(get_post_meta($post_ID, '_admin_category_select', true) );
                            $categories = get_terms(ATBDP_CATEGORY, array('hide_empty' => 0));
                            echo '<select class="form-control directory_field" id="cat-type" name="admin_category_select">';
                            echo '<option>'.__( "--Select a Category--", ATBDP_TEXTDOMAIN ).'</option>';
                            foreach ($categories as $key => $cat_title){
                                $term_id = $cat_title->term_id;
                                printf( '<option value="%s" %s>%s</option>', $term_id, selected( $term_id, $current_val), $cat_title->name );
                            }
                            echo '</select>';
                                $term_id_selected = $current_val;
                            ?>
                            <input type="hidden" id="value_selected" value="<?php echo $term_id_selected?>">
                        </div>
                    </div>

                    <div  id="atbdp-custom-fields-list" data-post_id="<?php echo $post_ID; ?>">
                        <?php
                        $selected_category = !empty($selected_category) ? $selected_category : '';
                        do_action( 'wp_ajax_atbdp_custom_fields_listings', $post_ID, $selected_category ); ?>
                    </div>
                    <?php
                    if ($term_id_selected){
                        ?>
                        <div  id="atbdp-custom-fields-list-selected" data-post_id="<?php echo $post_ID; ?>">
                            <?php
                            $selected_category = !empty($selected_category) ? $selected_category : '';
                            do_action( 'wp_ajax_atbdp_custom_fields_listings_selected', $post_ID, $selected_category ); ?>
                        </div>
                    <?php
                    }
                    ?>


                </div>
            </div>
        </div>


        <?php
            /**
             * It fires after the google map preview area
             * @param string $type Page type.
             * @param array $listing_info Information of the current listing
             * @since 1.1.1
             **/
            do_action('atbdp_edit_after_googlemap_preview', 'add_listing_page_backend', $args['listing_info'], get_the_ID());
            ?>
</div>
<script>

    // Bias the auto complete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.

    jQuery(document).ready(function ($) {

        // Load custom fields of the selected category in the custom post type "atbdp_listings"
        $( '#cat-type' ).on( 'change', function() {
            $( '#atbdp-custom-fields-list' ).html( '<div class="spinner"></div>' );

            var data = {
                'action'  : 'atbdp_custom_fields_listings',
                'post_id' : $( '#atbdp-custom-fields-list' ).data('post_id'),
                'term_id' : $(this).val()
            };

            $.post( ajaxurl, data, function(response) {
                $( '#atbdp-custom-fields-list' ).html( response );
            });
            $('#atbdp-custom-fields-list-selected').hide();

        });
        var selected_cat = $('#value_selected').val();
        if(!selected_cat){

        }else{
            $(window).on("load", function () {
                $('#atbdp-custom-fields-list-selected').html('<div class="spinner"></div>');
                var data = {
                    'action': 'atbdp_custom_fields_listings_selected',
                    'post_id': $('#atbdp-custom-fields-list-selected').data('post_id'),
                    'term_id': selected_cat
                };
                $.post(ajaxurl, data, function (response) {
                    $('#atbdp-custom-fields-list-selected').html(response);
                });
            });
        }

        <?php if (!$disable_map) { ?>

        // initialize all vars here to avoid hoisting related misunderstanding.
        var placeSearch, map, autocomplete, address_input, markers, info_window, $manual_lat, $manual_lng, saved_lat_lng, info_content;
         $manual_lat = $('#manual_lat');
         $manual_lng = $('#manual_lng');
         saved_lat_lng = {lat:<?= (!empty($manual_lat)) ? floatval($manual_lat) : '51.5073509' ?>, lng: <?= (!empty($manual_lng)) ? floatval($manual_lng) : '-0.12775829999998223' ?> }; // default is London city
        info_content = "<?= $info_content; ?>";
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
                navigator.geolocation.getCurrentPosition(function(position) {
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
                {types: ['geocode']});

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

            marker.addListener('click', function() {
                info_window.open(map, marker);
            });

            // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
            markers.push(marker);
        }

        initAutocomplete(); // start google map place auto complete API call


        function initMap() {
            /* Create new map instance*/
                map = new google.maps.Map(document.getElementById('gmap'), {
                zoom: <?php echo !empty($map_zoom_level) ? intval($map_zoom_level) : 16; ?>,
                center: saved_lat_lng
            });
             var marker = new google.maps.Marker({
                 map: map,
                 position:  saved_lat_lng,
                 draggable:true,
                 title: '<?php _e('You can drag the marker to your desired place to place a marker', ATBDP_TEXTDOMAIN); ?>'
             });
            marker.addListener('click', function() {
                info_window.open(map, marker);
            });
             // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
            markers.push(marker);

            // create a Geocode instance
            var geocoder = new google.maps.Geocoder();

            document.getElementById('generate_admin_map').addEventListener('click', function(e) {
                e.preventDefault();
                geocodeAddress(geocoder, map);
            });


            // This event listener calls addMarker() when the map is clicked.
            google.maps.event.addListener(map, 'click', function(event) {
                // at first delete the old marker if there is any and then add new marker
                deleteMarker();
                // set the value of input field to save them to the database
                $manual_lat.val(event.latLng.lat());
                $manual_lng.val(event.latLng.lng());
                // add the marker to the given map.
                addMarker(event.latLng, map);
            });

            // This event listener update the lat long field of the form so that we can add the lat long to the database when the MARKER is drag.
            google.maps.event.addListener(marker, 'dragend', function(event) {
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
            geocoder.geocode({'address': address}, function(results, status) {
                if (status === 'OK') {
                    // set the value of input field to save them to the database
                    $manual_lat.val(results[0].geometry.location.lat());
                    $manual_lng.val(results[0].geometry.location.lng());
                    resultsMap.setCenter(results[0].geometry.location);
                     var marker = new google.maps.Marker({
                        map: resultsMap,
                        position: results[0].geometry.location
                    });

                    marker.addListener('click', function() {
                        info_window.open(map, marker);
                    });

                    // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
                    markers.push(marker);
                } else {
                    alert( '<?php _e('Geocode was not successful for the following reason: ', ATBDP_TEXTDOMAIN); ?>' + status);
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
                 draggable:true,
                 title: '<?php _e('You can drag the marker to your desired place to place a marker', ATBDP_TEXTDOMAIN); ?>',
                map: map
            });
            marker.addListener('click', function() {
                info_window.open(map, marker);
            });
            // add the marker to the markers array to keep track of it, so that we can show/hide/delete them all later.
            markers.push(marker);
        }

        // Delete Marker
        $('#delete_marker').on('click', function (e) {
            e.preventDefault();
            deleteMarker();// delete all markers

        });
        /**
         * It deletes all the map markers
         * */
        function deleteMarker() {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers = [];
        }
        <?php } ?>


    }); // ends jquery ready function.







</script>