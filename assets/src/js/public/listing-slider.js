// All Listing Slider
(function ($) {
    window.addEventListener('DOMContentLoaded', () => {
        /* Check Slider Data */
        let checkData = function (data, value) {
            return typeof data === 'undefined' ? value : data;
        };


        /* Swiper Slider Listing */
        let swiperCarouselListing = document.querySelectorAll('.directorist-swiper-listing');

        swiperCarouselListing.forEach(function (el, i) {

            let navBtnPrev = document.querySelectorAll('.directorist-swiper-listing .directorist-swiper__nav--prev-listing');
            let navBtnNext = document.querySelectorAll('.directorist-swiper-listing .directorist-swiper__nav--next-listing');
            let swiperPagination = document.querySelectorAll('.directorist-swiper-listing .directorist-swiper__pagination--listing');

            navBtnPrev.forEach((el, i) => {
                el.classList.add(`directorist-swiper__nav--prev-listing-${i}`);
            });
            navBtnNext.forEach((el, i) => {
                el.classList.add(`directorist-swiper__nav--next-listing-${i}`);
            });
            swiperPagination.forEach((el, i) => {
                el.classList.add(`directorist-swiper__pagination--listing-${i}`);
            });

            el.classList.add(`directorist-swiper-listing-${i}`);
            
            let swiper = new Swiper(`.directorist-swiper-listing-${i}`, {
                slidesPerView: checkData(parseInt(el.dataset.swItems), 4),
                spaceBetween: checkData(parseInt(el.dataset.swMargin), 30),
                loop: checkData(el.dataset.swLoop, true),
                slidesPerGroup: checkData(parseInt(el.dataset.swPerslide), 1),
                speed: checkData(parseInt(el.dataset.swSpeed), 3000),
                autoplay: checkData(el.dataset.swAutoplay, {}),
                navigation: {
                    nextEl: `.directorist-swiper__nav--next-listing-${i}`,
                    prevEl: `.directorist-swiper__nav--prev-listing-${i}`,
                },
                pagination: {
                    el: `.directorist-swiper__pagination--listing-${i}`,
                    type: 'bullets',
                    clickable: true,
                },
                breakpoints: checkData(el.dataset.swResponsive ? JSON.parse(el.dataset.swResponsive) : undefined, {})
            });
        });

        /* Swiper Slider Related Listing */
        let swiperCarouselRelated = document.querySelectorAll('.directorist-swiper-related');

        swiperCarouselRelated.forEach(function (el, i) {

            let navBtnPrev = document.querySelectorAll('.directorist-swiper-related .directorist-swiper__nav--prev-related');
            let navBtnNext = document.querySelectorAll('.directorist-swiper-related .directorist-swiper__nav--next-related');
            let swiperPagination = document.querySelectorAll('.directorist-swiper-related .directorist-swiper__pagination--related');

            navBtnPrev.forEach((el, i) => {
                el.classList.add(`directorist-swiper__nav--prev-related-${i}`);
            });
            navBtnNext.forEach((el, i) => {
                el.classList.add(`directorist-swiper__nav--next-related-${i}`);
            });
            swiperPagination.forEach((el, i) => {
                el.classList.add(`directorist-swiper__pagination--related-${i}`);
            });

            el.classList.add(`directorist-swiper-related-${i}`);
            
            let swiper = new Swiper(`.directorist-swiper-related-${i}`, {
                slidesPerView: checkData(parseInt(el.dataset.swItems), 4),
                spaceBetween: checkData(parseInt(el.dataset.swMargin), 30),
                loop: checkData(el.dataset.swLoop, false),
                slidesPerGroup: checkData(parseInt(el.dataset.swPerslide), 1),
                speed: checkData(parseInt(el.dataset.swSpeed), 3000),
                autoplay: checkData(el.dataset.swAutoplay, {}),
                navigation: {
                    nextEl: `.directorist-swiper__nav--next-related-${i}`,
                    prevEl: `.directorist-swiper__nav--prev-related-${i}`,
                },
                pagination: {
                    el: `.directorist-swiper__pagination--related-${i}`,
                    type: 'bullets',
                    clickable: true,
                },
                breakpoints: checkData(el.dataset.swResponsive ? JSON.parse(el.dataset.swResponsive) : undefined, {})
            });
        });
        
        /* Swiper Slider All in One */
        // let swiperCarousel = document.querySelectorAll('.directorist-swiper');

        // swiperCarousel.forEach(function (el, i) {

        //     let navBtnPrev = document.querySelectorAll('.directorist-swiper__nav--prev');
        //     let navBtnNext = document.querySelectorAll('.directorist-swiper__nav--next');
        //     let swiperPagination = document.querySelectorAll('.directorist-swiper__pagination');

        //     navBtnPrev.forEach((el, i) => {
        //         el.classList.add(`directorist-swiper__nav--prev-${i}`);
        //     });
        //     navBtnNext.forEach((el, i) => {
        //         el.classList.add(`directorist-swiper__nav--next-${i}`);
        //     });
        //     swiperPagination.forEach((el, i) => {
        //         el.classList.add(`directorist-swiper__pagination-${i}`);
        //     });

        //     el.classList.add(`directorist-swiper-${i}`);
            
        //     let swiper = new Swiper(`.directorist-swiper-${i}`, {
        //         slidesPerView: checkData(parseInt(el.dataset.swItems), 4),
        //         spaceBetween: checkData(parseInt(el.dataset.swMargin), 30),
        //         loop: checkData(el.dataset.swLoop, true),
        //         slidesPerGroup: checkData(parseInt(el.dataset.swPerslide), 1),
        //         speed: checkData(parseInt(el.dataset.swSpeed), 3000),
        //         autoplay: checkData(el.dataset.swAutoplay, {}),
        //         observer: true,
        //         observeParents: true,
        //         navigation: {
        //             nextEl: `.directorist-swiper__nav--next-${i}`,
        //             prevEl: `.directorist-swiper__nav--prev-${i}`,
        //         },
        //         pagination: {
        //             el: `.directorist-swiper__pagination-${i}`,
        //             type: 'bullets',
        //             clickable: true,
        //         },
        //         breakpoints: checkData(el.dataset.swResponsive ? JSON.parse(el.dataset.swResponsive) : undefined, {})
        //     });
        // });

        /* Swiper Slider Listing */

        var swiperSingleListingThumb = new Swiper('.directorist-single-listing-slider-thumb', {
            slidesPerView: 6,
            spaceBetween: 10,
            loop: false,
            freeMode: true,
            navigation: {
                nextEl: `.directorist-swiper__nav--next-single-listing-thumb`,
                prevEl: `.directorist-swiper__nav--prev-single-listing-thumb`,
            },
            pagination: {
                el: `.directorist-swiper__pagination--single-listing-thumb`,
                type: 'bullets',
                clickable: true,
            },
            breakpoints: {
                0: {
                  slidesPerView: 1,
                  spaceBetween: 0,
                },
                480: {
                  slidesPerView: 2,
                },
                767: {
                  slidesPerView: 3,
                },
                1200: {
                  slidesPerView: 4,
                },
                1440: {
                  slidesPerView: 5,
                },
                1600: {
                  slidesPerView: 6,
                }
            }
        });
        
        var swiperSingleListing = new Swiper('.directorist-single-listing-slider', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            slidesPerGroup: 1,
            observer: true,
            observeParents: true,
            navigation: {
                nextEl: `.directorist-swiper__nav--next-single-listing`,
                prevEl: `.directorist-swiper__nav--prev-single-listing`,
            },
            pagination: {
                el: `.directorist-swiper__pagination--single-listing`,
                type: 'bullets',
                clickable: true,
            },
            thumbs: {
                swiper: swiperSingleListingThumb
            },
        });

        
    });
})(jQuery);