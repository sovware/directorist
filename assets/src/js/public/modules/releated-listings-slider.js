jQuery(document).ready(function ($) {
    var rtl = !data.is_rtl ? false : true;
    $('.directorist-related-carousel').slick({
        dots: false,
        arrows: false,
        infinite: true,
        speed: 300,
        slidesToShow: data.rel_listing_column,
        slidesToScroll: 1,
        autoplay: false,
        rtl: rtl,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: data.rel_listing_column,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: false
                }
            },
            {
                breakpoint: 767,
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
});