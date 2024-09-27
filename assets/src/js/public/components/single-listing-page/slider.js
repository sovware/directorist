document.addEventListener('DOMContentLoaded', () => {
    let $ = jQuery;
    // Plasma Slider Initialization
    if($('.plasmaSlider').length !== 0){
        var single_listing_slider = new PlasmaSlider({
            containerID: "directorist-single-listing-slider",
        });
        single_listing_slider.init();
    }

    /* Related listings slider */
    var rtl = (directorist.rtl === 'true');
    const relLis = $('.directorist-related-carousel');
    if (relLis.length !== 0) {
        const relLisData = relLis.data('attr');
        const prevArrow = typeof relLisData !== 'undefined' ? relLisData.prevArrow : '';
        const nextArrow = typeof relLisData !== 'undefined' ? relLisData.nextArrow: '';
        const relLisCol = typeof relLisData !== 'undefined' ? relLisData.columns : 3;
        $('.directorist-related-carousel').slick({
            dots: false,
            arrows: true,
            prevArrow: prevArrow,
            nextArrow: nextArrow,
            infinite: true,
            speed: 300,
            slidesToShow: relLisCol,
            slidesToScroll: 1,
            autoplay: false,
            rtl: rtl,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: relLisCol,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }
})