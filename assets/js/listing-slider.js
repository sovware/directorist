/******/ (function() { // webpackBootstrap
/*!************************************************!*\
  !*** ./assets/src/js/public/listing-slider.js ***!
  \************************************************/
function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t.return || t.return(); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
/***
    All Listing Slider
***/
(function ($) {
  // All Listing Slider
  function allListingSlider() {
    /* Check Slider Data */
    var checkData = function checkData(data, value) {
      return typeof data === "undefined" ? value : data;
    };

    /* Swiper Slider Listing */
    var swiperCarouselListing = document.querySelectorAll(".directorist-swiper-listing");
    swiperCarouselListing.forEach(function (el, i) {
      var navBtnPrev = document.querySelectorAll(".directorist-swiper-listing .directorist-swiper__nav--prev-listing");
      var navBtnNext = document.querySelectorAll(".directorist-swiper-listing .directorist-swiper__nav--next-listing");
      var swiperPagination = document.querySelectorAll(".directorist-swiper-listing .directorist-swiper__pagination--listing");
      navBtnPrev.forEach(function (el, i) {
        el.classList.add("directorist-swiper__nav--prev-listing-".concat(i));
      });
      navBtnNext.forEach(function (el, i) {
        el.classList.add("directorist-swiper__nav--next-listing-".concat(i));
      });
      swiperPagination.forEach(function (el, i) {
        el.classList.add("directorist-swiper__pagination--listing-".concat(i));
      });
      el.classList.add("directorist-swiper-listing-".concat(i));
      var swiperConfig = {
        slidesPerView: checkData(parseInt(el.dataset.swItems), 4),
        spaceBetween: checkData(parseInt(el.dataset.swMargin), 30),
        loop: checkData(el.dataset.swLoop, true),
        slidesPerGroup: checkData(parseInt(el.dataset.swPerslide), 1),
        speed: checkData(parseInt(el.dataset.swSpeed), 300),
        navigation: {
          nextEl: ".directorist-swiper__nav--next-listing-".concat(i),
          prevEl: ".directorist-swiper__nav--prev-listing-".concat(i)
        },
        pagination: {
          el: ".directorist-swiper__pagination--listing-".concat(i),
          type: "bullets",
          clickable: true
        },
        breakpoints: checkData(el.dataset.swResponsive ? JSON.parse(el.dataset.swResponsive) : undefined, {})
      };

      // Conditionally add autoplay property
      var enableAutoplay = checkData(el.dataset.swAutoplay, "false");

      // Conditionally add autoplay property
      if (enableAutoplay === "true") {
        swiperConfig.autoplay = {
          delay: checkData(parseInt(el.dataset.swSpeed), 500),
          disableOnInteraction: false
        };
      }
      var swiper = new Swiper(".directorist-swiper-listing-".concat(i), swiperConfig);
    });

    /* Swiper Slider Related Listing */
    var swiperCarouselRelated = document.querySelectorAll(".directorist-swiper-related-listing");
    swiperCarouselRelated.forEach(function (el, i) {
      // Assign unique classes
      var navBtnPrev = document.querySelectorAll(".directorist-swiper-related-listing .directorist-swiper__nav--prev-related");
      var navBtnNext = document.querySelectorAll(".directorist-swiper-related-listing .directorist-swiper__nav--next-related");
      var swiperPagination = document.querySelectorAll(".directorist-swiper-related-listing .directorist-swiper__pagination--related");
      navBtnPrev.forEach(function (el, i) {
        return el.classList.add("directorist-swiper__nav--prev-related-".concat(i));
      });
      navBtnNext.forEach(function (el, i) {
        return el.classList.add("directorist-swiper__nav--next-related-".concat(i));
      });
      swiperPagination.forEach(function (el, i) {
        return el.classList.add("directorist-swiper__pagination--related-".concat(i));
      });
      el.classList.add("directorist-swiper-related-listing-".concat(i));

      // Count slides directly from the DOM
      var relatedWrapper = el.querySelector(".swiper-wrapper");
      var totalSlides = relatedWrapper ? relatedWrapper.children.length : 0;

      // Get Data Attribute
      var baseSlidesPerView = checkData(parseInt(el.dataset.swItems), 4);
      var responsiveBreakPoints = checkData(el.dataset.swResponsive ? JSON.parse(el.dataset.swResponsive) : undefined, {});
      var swiper = null; // Store swiper instance here
      var currentLoop = null; // Track last loop value

      // Init or Reinit Swiper
      function initSwiper(loopValue) {
        // Destroy previous if exists
        if (swiper) {
          swiper.destroy(true, true);
        }

        // Store loopValue to detect future changes
        currentLoop = loopValue;
        var config = {
          slidesPerView: baseSlidesPerView,
          spaceBetween: checkData(parseInt(el.dataset.swMargin), 30),
          loop: loopValue,
          slidesPerGroup: checkData(parseInt(el.dataset.swPerslide), 1),
          navigation: {
            nextEl: ".directorist-swiper__nav--next-related-".concat(i),
            prevEl: ".directorist-swiper__nav--prev-related-".concat(i)
          },
          pagination: {
            el: ".directorist-swiper__pagination--related-".concat(i),
            type: "bullets",
            clickable: true
          },
          breakpoints: responsiveBreakPoints
        };

        // Add autoplay if enabled
        if (checkData(el.dataset.swAutoplay, "false") === "true") {
          config.autoplay = {
            delay: checkData(parseInt(el.dataset.swSpeed), 500),
            disableOnInteraction: false,
            pauseOnMouseEnter: true
          };
        }
        swiper = new Swiper(".directorist-swiper-related-listing-".concat(i), config);
      }
      function getCurrentSlidesPerView() {
        var windowWidth = window.innerWidth;
        var slides = baseSlidesPerView;
        if (responsiveBreakPoints) {
          var breakPoints = Object.keys(responsiveBreakPoints).map(function (k) {
            return parseInt(k);
          }).sort(function (a, b) {
            return a - b;
          });
          var _iterator = _createForOfIteratorHelper(breakPoints),
            _step;
          try {
            for (_iterator.s(); !(_step = _iterator.n()).done;) {
              var point = _step.value;
              if (windowWidth >= point && responsiveBreakPoints[point].slidesPerView) {
                slides = responsiveBreakPoints[point].slidesPerView;
              }
            }
          } catch (err) {
            _iterator.e(err);
          } finally {
            _iterator.f();
          }
        }
        return slides;
      }
      function checkAndUpdateSwiper() {
        var currentSlidesPerView = getCurrentSlidesPerView();
        var loopShouldBeEnabled = checkData(el.dataset.swLoop, false) === "true" && totalSlides > currentSlidesPerView;
        if (loopShouldBeEnabled !== currentLoop) {
          initSwiper(loopShouldBeEnabled);
        }

        // Add class if only 1 bullet exists
        if (totalSlides === 1) {
          el.classList.add("slider-has-one-item");
        } else {
          el.classList.remove("slider-has-one-item");
        }

        // Add or remove "less items" class
        if (totalSlides <= currentSlidesPerView) {
          el.classList.add("slider-has-less-items");
        } else {
          el.classList.remove("slider-has-less-items");
        }
      }

      // Initial setup
      checkAndUpdateSwiper();

      // Recheck on window resize
      window.addEventListener("resize", function () {
        checkAndUpdateSwiper();
      });
    });

    /* Swiper Slider Single Listing */
    var singleListingSlider = document.querySelectorAll(".directorist-single-listing-slider-wrap");
    singleListingSlider.forEach(function (el, i) {
      // Get Data Attribute
      var dataWidth = el.getAttribute("data-width");
      var dataHeight = el.getAttribute("data-height");
      var dataRTL = el.getAttribute("data-rtl");
      var dataBackgroundColor = el.getAttribute("data-background-color");
      var dataBackgroundSize = el.getAttribute("data-background-size");
      var dataBackgroundBlur = el.getAttribute("data-blur-background");
      var dataShowThumbnails = el.getAttribute("data-show-thumbnails");
      var dataThumbnailsBackground = el.getAttribute("data-thumbnail-background-color");

      // Find Sliders
      var swiperCarouselSingleListingThumb = el.querySelector(".directorist-single-listing-slider-thumb");
      var swiperCarouselSingleListing = el.querySelector(".directorist-single-listing-slider");

      // Single Listing Thumb Init
      var swiperSingleListingThumb = new Swiper(swiperCarouselSingleListingThumb, {
        slidesPerView: 6,
        spaceBetween: 10,
        loop: false,
        freeMode: true,
        navigation: {
          nextEl: ".directorist-swiper__nav--next-single-listing-thumb",
          prevEl: ".directorist-swiper__nav--prev-single-listing-thumb"
        },
        pagination: {
          el: ".directorist-swiper__pagination--single-listing-thumb",
          type: "bullets",
          clickable: true
        },
        breakpoints: {
          0: {
            slidesPerView: 1,
            spaceBetween: 0
          },
          480: {
            slidesPerView: 2
          },
          767: {
            slidesPerView: 3
          },
          1200: {
            slidesPerView: 4
          },
          1440: {
            slidesPerView: 5
          },
          1600: {
            slidesPerView: 6
          }
        }
      });
      var singleSliderTotalSlides = swiperCarouselSingleListing.querySelectorAll(".swiper-slide:not(.swiper-slide-duplicate)");
      var singleSliderLoopEnable = singleSliderTotalSlides.length > 1;

      // Single Listing Slider Config
      var swiperSingleListingConfig = {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: singleSliderLoopEnable,
        slidesPerGroup: 1,
        observer: true,
        observeParents: true,
        navigation: {
          nextEl: ".directorist-swiper__nav--next-single-listing",
          prevEl: ".directorist-swiper__nav--prev-single-listing"
        },
        pagination: {
          el: ".directorist-swiper__pagination--single-listing",
          type: "bullets",
          clickable: true
        }
      };

      // Single Slider Thumb Config
      if (swiperCarouselSingleListingThumb) {
        swiperSingleListingConfig.thumbs = {
          swiper: swiperSingleListingThumb
        };
      }

      // Initialize Swiper
      var swiperSingleListing = new Swiper(swiperCarouselSingleListing, swiperSingleListingConfig);

      // Function to update blurred background
      var updateBlurredBackground = function updateBlurredBackground() {
        // Check if the blurred background element exists
        var blurredBackground = swiperCarouselSingleListing.querySelector(".blurred-background");

        // If it doesn't exist, create it
        if (!blurredBackground) {
          blurredBackground = document.createElement("div"); // Create a new div
          blurredBackground.classList.add("blurred-background"); // Add the class
          swiperCarouselSingleListing.appendChild(blurredBackground); // Append it to the section
        }

        // Get the active slide image
        var activeSlide = swiperCarouselSingleListing.querySelector(".swiper-slide-active img");
        if (activeSlide) {
          var activeImageSrc = activeSlide.src; // Get the source of the active image
          swiperCarouselSingleListing.style.backgroundColor = "transparent"; // Remove background color
          blurredBackground.style.backgroundImage = "url(".concat(activeImageSrc, ")"); // Set as background image
          blurredBackground.style.backgroundSize = "cover"; // Ensure it covers the div
          blurredBackground.style.filter = "blur(10px)"; // Apply blur
          blurredBackground.style.position = "absolute"; // Position it behind other content
          blurredBackground.style.top = "0";
          blurredBackground.style.left = "0";
          blurredBackground.style.right = "0";
          blurredBackground.style.bottom = "0";
          blurredBackground.style.transform = "scale(1.5)";
        }
      };

      // Attach the slideChangeTransitionEnd event listener
      if (dataBackgroundBlur === "1") {
        swiperSingleListing.on("slideChangeTransitionEnd", updateBlurredBackground); // Use slideChangeTransitionEnd here
      }

      // Loop Destroy on Single Slider Item
      var sliderItemsCount = swiperCarouselSingleListing.querySelectorAll(".directorist-swiper__pagination .swiper-pagination-bullet");
      var swiperListingThumb = swiperCarouselSingleListing.parentElement.querySelector(".directorist-single-listing-slider-thumb");
      if (sliderItemsCount.length <= 1) {
        swiperSingleListing.loopDestroy();
        swiperCarouselSingleListing.classList.add("slider-has-one-item");
        if (swiperListingThumb) {
          swiperListingThumb.style.display = "none";
        }
      }

      // Add Styles
      if (swiperCarouselSingleListing) {
        swiperCarouselSingleListing.dir = dataRTL !== "0" ? "rtl" : "ltr";
        swiperCarouselSingleListing.style.width = dataWidth ? dataWidth + "px" : "100%";
        swiperCarouselSingleListing.style.height = dataHeight ? dataHeight + "px" : "auto";
        swiperCarouselSingleListing.style.backgroundSize = dataBackgroundSize ? dataBackgroundSize : "";

        // Initial setup
        if (dataBackgroundSize === "contain") {
          swiperCarouselSingleListing.style.backgroundColor = dataBackgroundColor ? dataBackgroundColor : "transparent";

          // Call the update function for initial setup if blur is active
          if (dataBackgroundBlur === "1") {
            updateBlurredBackground(); // Set initial blurred background
          } else {
            // If blur is not active, remove the blurred background if it exists
            var blurredBackground = swiperCarouselSingleListing.querySelector(".blurred-background");
            if (blurredBackground) {
              swiperCarouselSingleListing.removeChild(blurredBackground);
            }
          }
        }
      }
      if (swiperCarouselSingleListingThumb) {
        // swiperCarouselSingleListingThumb.style.display = dataShowThumbnails == '0' ? 'none' : '';
        swiperCarouselSingleListingThumb.style.width = dataWidth ? dataWidth + "px" : "100%";
        swiperCarouselSingleListingThumb.style.backgroundColor = dataThumbnailsBackground ? dataThumbnailsBackground : "transparent";
      }
    });
  }

  // Slider Call on Page Load
  window.addEventListener("load", function () {
    if ($(".directorist-archive-items .directorist-swiper-listing")) {
      allListingSlider();
    }
    $("body").on("input keyup change", ".directorist-archive-contents form", function (e) {
      if (e.target.classList.contains("directorist-location-js")) {
        sliderObserver();
      }
    });
  });

  // Slider Call on Page instant search
  window.addEventListener("directorist-instant-search-reloaded", function () {
    if ($(".directorist-archive-items .directorist-swiper-listing")) {
      allListingSlider();
    }
  });

  // Mutation Observer on Range Slider
  function sliderObserver() {
    var rangeSliders = document.querySelectorAll(".directorist-custom-range-slider__value input");
    rangeSliders.forEach(function (rangeSlider) {
      if (rangeSlider) {
        var timeout;
        var observerCallback = function observerCallback(mutationList, observer) {
          var _iterator2 = _createForOfIteratorHelper(mutationList),
            _step2;
          try {
            for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
              var mutation = _step2.value;
              if (mutation.attributeName == "value") {
                clearTimeout(timeout);
                timeout = setTimeout(function () {
                  allListingSlider();
                }, 1000);
              }
            }
          } catch (err) {
            _iterator2.e(err);
          } finally {
            _iterator2.f();
          }
        };
        var observer = new MutationObserver(observerCallback);
        observer.observe(rangeSlider, {
          attributes: true,
          childList: true,
          subtree: true
        });
      }
    });
  }

  /* Slider Call on Elementor EditMode */
  $(window).on("elementor/frontend/init", function () {
    setTimeout(function () {
      if ($("body").hasClass("elementor-editor-active")) {
        allListingSlider();
      }
      if ($("body").hasClass("elementor-editor-active")) {
        allListingSlider();
      }
    }, 3000);
  });
  $("body").on("click", function (e) {
    if ($("body").hasClass("elementor-editor-active") && e.target.nodeName !== "A" && e.target.nodeName !== "BUTTON") {
      allListingSlider();
    }
  });
})(jQuery);
/******/ })()
;
//# sourceMappingURL=listing-slider.js.map