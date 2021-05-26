function directorist_init_radius_slider( selector, args ) {
    var selector = ( typeof selector === 'string' ) ? selector : '#atbdp-range-slider';
    let options = {};

    if ( typeof args !== 'undefined' && typeof args === 'object' ) {
        options = Object.assign( options, args );
    }

    if ( typeof directorist_radius_slider_config !== 'undefined' ) {
        options = Object.assign( options, directorist_radius_slider_config );
    }

    new swbdRangeSlider( selector, options );
}