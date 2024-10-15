window.addEventListener('load', () => {

    // Add focus class to the parent field of .directorist-location-js
    function addFocusClass(location) {
        // Get the parent field of .directorist-location-js
        let parentField = location.closest('.directorist-search-field');

        // Add the 'input-is-focused' class if not already present
        if (parentField && !parentField.hasClass('input-is-focused')) {
            parentField.addClass('input-is-focused');
        }
    }

    (function ($) {
        /* get current location */
        setTimeout(() => {
            if (directorist.i18n_text.select_listing_map === 'google') {
                /* Event Delegation in Vanilla JS */
                function eventDelegation(event, selector, program) {
                    document.body.addEventListener(event, function (e) {
                        document.querySelectorAll(selector).forEach(elem => {
                            if (e.target === elem) {
                                program(e);
                            }
                        })
                    });
                } 
                (function () {
                    eventDelegation('click', '.directorist-filter-location-icon > i, .directorist-filter-location-icon > span', function (e) {
                        let locationInput = e.target.closest('.directorist-search-field').querySelector('.location-name');
                        let get_lat = e.target.closest('.directorist-search-field').querySelector("#cityLat");
                        let get_lng = e.target.closest('.directorist-search-field').querySelector("#cityLng");

                        function getLocation() {
                            if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(showPosition, showError);
                            } else {
                                locationInput.value = 'Geolocation is not supported by this browser.';
                            }
                        }
                        getLocation();

                        function showPosition(position) {
                            lat = position.coords.latitude;
                            lon = position.coords.longitude;
                            displayCurrentLocation(lat, lon);
                            get_lat.value = lat;
                            get_lng.value = lon;
                        }

                        function showError(error) {
                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    locationInput.value = 'User denied the request for Geolocation.';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    locationInput.value = 'Location information is unavailable.';
                                    break;
                                case error.TIMEOUT:
                                    locationInput.value = 'The request to get user location timed out.';
                                    break;
                                case error.UNKNOWN_ERROR:
                                    locationInput.value = 'An unknown error occurred.';
                                    break;
                            }
                        }

                        function displayLocation(latitude, longitude) {
                            let geocoder;
                            geocoder = new google.maps.Geocoder();
                            let latlng = new google.maps.LatLng(latitude, longitude);
                            geocoder.geocode({
                                latLng: latlng,
                                componentRestrictions: {
                                    country: 'GB'
                                }
                            },
                            function (results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    if (results[0]) {
                                        let add = results[0].formatted_address;
                                        let value = add.split(',');

                                        count = value.length;
                                        country = value[count - 1];
                                        state = value[count - 2];
                                        city = value[count - 3];
                                        locationInput.value = city;
                                    } else {
                                        locationInput.value = 'address not found';
                                    }
                                } else {
                                    locationInput.value = `Geocoder failed due to: ${status}`;
                                }
                            });
                        }

                        function displayCurrentLocation(latitude, longitude) {
                            let geocoder;
                            geocoder = new google.maps.Geocoder();
                            let latlng = new google.maps.LatLng(latitude, longitude);
                            geocoder.geocode({
                                latLng: latlng
                            },
                            function (results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    if (results[0]) {
                                        let add = results[0].formatted_address;
                                        let value = add.split(',');

                                        count = value.length;
                                        country = value[count - 1];
                                        state = value[count - 2];
                                        city = value[count - 3];
                                        locationInput.value = value;
                                        $('.directorist-location-js, .atbdp-search-address').attr("data-value", city);
                                    } else {
                                        locationInput.value = 'address not found';
                                    }
                                } else {
                                    locationInput.value = `Geocoder failed due to: ${status}`;
                                }
                            });
                        }

                        let parentField = e.target.closest('.directorist-search-field');

                        if (parentField && !parentField.classList.contains('input-is-focused')) {
                            parentField.classList.add('input-is-focused');
                        }
                    })
                })();
            } else if (directorist.i18n_text.select_listing_map === 'openstreet') {
                function displayLocation(position, event) {
                    let lat = position.coords.latitude;
                    let lng = position.coords.longitude;
                    let locIcon = event.target;

                    $.ajax({
                        url: `https://nominatim.openstreetmap.org/reverse?format=json&lon=${lng}&lat=${lat}`,
                        type: 'GET',
                        data: {},
                        success(data) {
                            $('.directorist-location-js, .atbdp-search-address').val(data.display_name);
                            $('.directorist-location-js, .atbdp-search-address').attr("data-value", data.display_name);
                            $('#cityLat').val(lat);
                            $('#cityLng').val(lng);
                            addFocusClass($('.directorist-location-js'));
                        },
                        error(err) {
                            $('.directorist-location-js').val('Location not found');
                            addFocusClass($('.directorist-location-js'));
                        }
                    });
                }
                $('body').on("click", ".directorist-filter-location-icon", function (e) {
                    navigator.geolocation.getCurrentPosition((position) => displayLocation(position, e));
                    
                    // let parentField = e.target.closest('.directorist-search-field');

                    // if (parentField && !parentField.classList.contains('input-is-focused')) {
                    //     parentField.classList.add('input-is-focused');
                    // }
                });
            }

        }, 1000);
    })(jQuery);
});