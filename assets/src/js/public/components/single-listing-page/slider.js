document.addEventListener('DOMContentLoaded', () => {
    let $ = jQuery;
    // Plasma Slider Initialization
    if($('.plasmaSlider').length !==0){
        var single_listing_slider = new PlasmaSlider({
            containerID: "directorist-single-listing-slider",
        });
        single_listing_slider.init();
    }

    /* Related listings slider */
    var rtl = (directorist.rtl === 'true');
    var relLis = $('.directorist-related-carousel');
    console.log(relLis);
    if (relLis.length) {
        const relLisData = relLis.data('attr');
        console.log(directorist)
        const prevArrow = relLisData.prevArrow;
        const nextArrow = relLisData.nextArrow;
        const relLisCol = relLisData.columns;
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