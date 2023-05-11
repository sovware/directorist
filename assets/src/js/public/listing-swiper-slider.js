// All Listing Slider
(function ($) {
    window.addEventListener('DOMContentLoaded', () => {
        /* Check Slider Data */
        let checkData = function (data, value) {
            return typeof data === 'undefined' ? value : data;
        };

        /* Swiper Slider */
        let swiperCarousel = document.querySelectorAll('.directorist-swiper');

        swiperCarousel.forEach(function (el, i) {

            let navBtnPrev = document.querySelectorAll('.directorist-swiper__nav--prev');
            let navBtnNext = document.querySelectorAll('.directorist-swiper__nav--next');
            let swiperPagination = document.querySelectorAll('.directorist-swiper__pagination');

            navBtnPrev.forEach((el, i) => {
                el.classList.add(`directorist-swiper__nav--prev-${i}`);
            });
            navBtnNext.forEach((el, i) => {
                el.classList.add(`directorist-swiper__nav--next-${i}`);
            });
            swiperPagination.forEach((el, i) => {
                el.classList.add(`directorist-swiper__pagination-${i}`);
            });

            el.classList.add(`directorist-swiper-${i}`);
            
            let swiper = new Swiper(`.directorist-swiper-${i}`, {
                slidesPerView: checkData(parseInt(el.dataset.swItems), 4),
                spaceBetween: checkData(parseInt(el.dataset.swMargin), 30),
                loop: checkData(el.dataset.swLoop, true),
                slidesPerGroup: checkData(parseInt(el.dataset.swPerslide), 1),
                speed: checkData(parseInt(el.dataset.swSpeed), 3000),
                autoplay: checkData(el.dataset.swAutoplay, {}),
                observer: true,
                observeParents: true,
                navigation: {
                    nextEl: `.directorist-swiper__nav--next-${i}`,
                    prevEl: `.directorist-swiper__nav--prev-${i}`,
                },
                pagination: {
                    el: `.directorist-swiper__pagination-${i}`,
                    type: 'bullets',
                    clickable: true,
                },
                breakpoints: checkData(el.dataset.swResponsive ? JSON.parse(el.dataset.swResponsive) : undefined, {})
            });
        });
    });
})(jQuery);