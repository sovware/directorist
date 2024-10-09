/***
    All Listing Slider
***/
(function ($) {
    // All Listing Slider
    function allListingSlider() {
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

            let swiperConfig = {
                slidesPerView: checkData(parseInt(el.dataset.swItems), 4),
                spaceBetween: checkData(parseInt(el.dataset.swMargin), 30),
                loop: checkData(el.dataset.swLoop, true),
                slidesPerGroup: checkData(parseInt(el.dataset.swPerslide), 1),
                speed: checkData(parseInt(el.dataset.swSpeed), 300),
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
            };
            
            // Conditionally add autoplay property
            const enableAutoplay = checkData(el.dataset.swAutoplay, 'false');
            
            // Conditionally add autoplay property
            if (enableAutoplay === "true") {
                swiperConfig.autoplay = {
                    delay: checkData(parseInt(el.dataset.swSpeed), 500),
                    disableOnInteraction: false,
                };
            } 
            
            let swiper = new Swiper(`.directorist-swiper-listing-${i}`, swiperConfig);
        });

        /* Swiper Slider Related Listing */
        let swiperCarouselRelated = document.querySelectorAll('.directorist-swiper-related-listing');

        swiperCarouselRelated.forEach(function (el, i) {
            let navBtnPrev = document.querySelectorAll('.directorist-swiper-related-listing .directorist-swiper__nav--prev-related');
            let navBtnNext = document.querySelectorAll('.directorist-swiper-related-listing .directorist-swiper__nav--next-related');
            let swiperPagination = document.querySelectorAll('.directorist-swiper-related-listing .directorist-swiper__pagination--related');

            navBtnPrev.forEach((el, i) => {
                el.classList.add(`directorist-swiper__nav--prev-related-${i}`);
            });
            navBtnNext.forEach((el, i) => {
                el.classList.add(`directorist-swiper__nav--next-related-${i}`);
            });
            swiperPagination.forEach((el, i) => {
                el.classList.add(`directorist-swiper__pagination--related-${i}`);
            });

            el.classList.add(`directorist-swiper-related-listing-${i}`);
            
            let swiperRelatedConfig = {
                slidesPerView: checkData(parseInt(el.dataset.swItems), 4),
                spaceBetween: checkData(parseInt(el.dataset.swMargin), 30),
                loop: checkData(el.dataset.swLoop, false),
                slidesPerGroup: checkData(parseInt(el.dataset.swPerslide), 1),
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
            };

            const enableRelatedAutoplay = checkData(el.dataset.swAutoplay, 'false');
            
            // Conditionally add autoplay property
            if (enableRelatedAutoplay === "true") {
                swiperRelatedConfig.autoplay = {
                    delay: checkData(parseInt(el.dataset.swSpeed), 500),
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                };
            } 
            
            let swiper = new Swiper(`.directorist-swiper-related-listing-${i}`, swiperRelatedConfig);            


            // Destroy Swiper Slider When Slider Image Are Less Than Minimum Required Image
            function destroySwiperSlider() {
                var windowScreen = screen.width;                  

                var breakpoints = JSON.parse(el.dataset.swResponsive);

                var breakpointKeys = Object.keys(breakpoints);
                
                var legalBreakpointKeys = breakpointKeys.filter(breakpointKey => breakpointKey <= windowScreen);
                
                var currentBreakpointKey = legalBreakpointKeys.reduce((prev, acc) => {
                    return Math.abs(acc - windowScreen) < Math.abs(prev - windowScreen) ? acc : prev;
                });
                
                var breakpointValues = Object.entries(breakpoints); 
                var currentBreakpoint = breakpointValues.filter(([key]) => key == currentBreakpointKey); 

                var sliderItemsCount = document.querySelectorAll(`.directorist-swiper-related-listing-${i} .directorist-swiper__pagination--related-${i} .swiper-pagination-bullet`);

                if(sliderItemsCount.length == '1') {
                    swiper.loopDestroy();
                    swiper.update();
                    var relatedListingSlider = document.querySelector('.directorist-swiper-related-listing');
                    relatedListingSlider.classList.add('slider-has-one-item');
                }

                currentBreakpoint[0].forEach((elm, ind) => {  
                    var relatedListingSlider = document.querySelector('.directorist-swiper-related-listing');               
                    if (swiper.loopedSlides < elm.slidesPerView) {
                        swiper.loopDestroy();
                        swiper.update();
                        relatedListingSlider.classList.add('slider-has-less-items');
                    } else {
                        if(relatedListingSlider && relatedListingSlider.classList.contains('slider-has-less-items')) {
                            relatedListingSlider.classList.remove('slider-has-less-items');
                        }
                    } 
                });

            }

            window.addEventListener('resize', function () {
                destroySwiperSlider();
            });
            
            destroySwiperSlider();
        });


        /* Swiper Slider Single Listing */
        let singleListingSlider = document.querySelectorAll('.directorist-single-listing-slider-wrap');
        
        singleListingSlider.forEach(function (el, i) {
            // Get Data Attribute
            let dataWidth = el.getAttribute('data-width');
            let dataHeight = el.getAttribute('data-height');
            let dataRTL = el.getAttribute('data-rtl');
            let dataBackgroundColor = el.getAttribute('data-background-color');
            let dataBackgroundSize = el.getAttribute('data-background-size');
            let dataBackgroundBlur = el.getAttribute('data-blur-background');
            let dataShowThumbnails = el.getAttribute('data-show-thumbnails');
            let dataThumbnailsBackground = el.getAttribute('data-thumbnail-background-color');
            
            // Find Sliders
            let swiperCarouselSingleListingThumb = el.querySelector('.directorist-single-listing-slider-thumb');
            let swiperCarouselSingleListing = el.querySelector('.directorist-single-listing-slider');

            // Single Listing Thumb Init
            let swiperSingleListingThumb = new Swiper(swiperCarouselSingleListingThumb, {
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
            
            // Single Listing Slider Config
            let swiperSingleListingConfig =  {
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
            };

            // Single Slider Thumb Config
            if (swiperCarouselSingleListingThumb) {
                swiperSingleListingConfig.thumbs = {
                    swiper: swiperSingleListingThumb
                };
            }
            
            // Initialize Swiper
            let swiperSingleListing = new Swiper(swiperCarouselSingleListing, swiperSingleListingConfig);

             // Function to update blurred background
             const updateBlurredBackground = () => {
                // Check if the blurred background element exists
                let blurredBackground = swiperCarouselSingleListing.querySelector('.blurred-background');

                // If it doesn't exist, create it
                if (!blurredBackground) {
                    blurredBackground = document.createElement('div'); // Create a new div
                    blurredBackground.classList.add('blurred-background'); // Add the class
                    swiperCarouselSingleListing.appendChild(blurredBackground); // Append it to the section
                }

                // Get the active slide image
                const activeSlide = swiperCarouselSingleListing.querySelector('.swiper-slide-active img');
                if (activeSlide) {
                    const activeImageSrc = activeSlide.src; // Get the source of the active image
                    swiperCarouselSingleListing.style.backgroundColor = 'transparent'; // Remove background color
                    blurredBackground.style.backgroundImage = `url(${activeImageSrc})`; // Set as background image
                    blurredBackground.style.backgroundSize = 'cover'; // Ensure it covers the div
                    blurredBackground.style.filter = 'blur(10px)'; // Apply blur
                    blurredBackground.style.position = 'absolute'; // Position it behind other content
                    blurredBackground.style.top = '0';
                    blurredBackground.style.left = '0';
                    blurredBackground.style.right = '0';
                    blurredBackground.style.bottom = '0';
                    blurredBackground.style.transform= 'scale(1.5)';
                }
            };

            // Attach the slideChangeTransitionEnd event listener
            if (dataBackgroundBlur === '1') {
                swiperSingleListing.on('slideChangeTransitionEnd', updateBlurredBackground); // Use slideChangeTransitionEnd here
            }

            // Loop Destroy on Single Slider Item
            let sliderItemsCount = swiperCarouselSingleListing.querySelectorAll('.directorist-swiper__pagination .swiper-pagination-bullet');

            if(sliderItemsCount.length <= '1') {
                swiperSingleListing.loopDestroy();
                swiperCarouselSingleListing.classList.add('slider-has-one-item');
                swiperCarouselSingleListing.parentElement.querySelector('.directorist-single-listing-slider-thumb').style.display = 'none';
            }

            // Add Styles
            if (swiperCarouselSingleListing) {
                swiperCarouselSingleListing.dir = dataRTL !== '0' ? 'rtl' : 'ltr';
                swiperCarouselSingleListing.style.width = dataWidth ? dataWidth + 'px' : '100%';
                swiperCarouselSingleListing.style.height = dataHeight ? dataHeight + 'px' : 'auto';
                swiperCarouselSingleListing.style.backgroundSize = dataBackgroundSize ? dataBackgroundSize : '';

                // Initial setup
                if (dataBackgroundSize === "contain") {
                    swiperCarouselSingleListing.style.backgroundColor = dataBackgroundColor ? dataBackgroundColor : 'transparent';

                    // Call the update function for initial setup if blur is active
                    if (dataBackgroundBlur === '1') {
                        updateBlurredBackground(); // Set initial blurred background
                    } else {
                        // If blur is not active, remove the blurred background if it exists
                        const blurredBackground = swiperCarouselSingleListing.querySelector('.blurred-background');
                        if (blurredBackground) {
                            swiperCarouselSingleListing.removeChild(blurredBackground);
                        }
                    }
                }
            }

            if(swiperCarouselSingleListingThumb) {
                // swiperCarouselSingleListingThumb.style.display = dataShowThumbnails == '0' ? 'none' : '';
                swiperCarouselSingleListingThumb.style.width = dataWidth ? dataWidth + 'px' : '100%';
                swiperCarouselSingleListingThumb.style.backgroundColor = dataThumbnailsBackground ? dataThumbnailsBackground : 'transparent';
            }

        });
    }

    // Slider Call on Page Load
    window.addEventListener('load', () => {

        allListingSlider();

        $('body').on('click', '.directorist-viewas__item, .directorist-instant-search .directorist-search-field__btn--clear, .directorist-instant-search .directorist-btn-reset-js', function(e) {
            setTimeout(() => {
                if($('.directorist-archive-items .directorist-swiper-listing')) {
                    allListingSlider();
                }
            }, 1000)
        });


        $('body').on('input keyup change', '.directorist-archive-contents form', function(e) {
            if(e.target.classList.contains('directorist-location-js')) {
                sliderObserver();
            }
            
            setTimeout(() => {
                if($('.directorist-archive-items .directorist-swiper-listing')) {
                    allListingSlider();
                }
            }, 1000)

        });
        
    });

    // Mutation Observer on Range Slider
    function sliderObserver() {
        let rangeSliders = document.querySelectorAll('.directorist-custom-range-slider__value input');

        rangeSliders.forEach((rangeSlider) => {

            if (rangeSlider) {
                let timeout;
                const observerCallback = (mutationList, observer) => {
                    for (const mutation of mutationList) {
                        if (mutation.attributeName == 'value') {
                            clearTimeout(timeout);
                            timeout = setTimeout(() => {
                                allListingSlider();
                            }, 1000);
                        }
                    }
                };
        
                const observer = new MutationObserver(observerCallback);
                observer.observe(rangeSlider, { attributes: true, childList: true, subtree: true });
            }
        })
    }

    /* Slider Call on Elementor EditMode */
    $(window).on('elementor/frontend/init', function () {
        setTimeout(function() {
            if ($('body').hasClass('elementor-editor-active')) {
                allListingSlider();
            }
            if ($('body').hasClass('elementor-editor-active')) {
                allListingSlider();
            }
        }, 3000);

    });

    $('body').on('click', function (e) {
        if ($('body').hasClass('elementor-editor-active')  && (e.target.nodeName !== 'A' && e.target.nodeName !== 'BUTTON')) {
            allListingSlider();
        }
    });

})(jQuery);