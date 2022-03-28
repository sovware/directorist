window.addEventListener('DOMContentLoaded', () => {
    (function ($) {
        /* Category/Location expand */
        $('.atbdp_child_category').hide();
        $('.atbdp-widget-categories .atbdp_parent_category >li >span').on('click', function () {
            $(this).siblings('.atbdp_child_category').slideToggle();
        });
        $('.atbdp_child_location').hide();
        $('.atbdp-widget-categories .atbdp_parent_location >li >span').on('click', function () {
            $(this).siblings('.atbdp_child_location').slideToggle();
        });

        //Advanced search form reset
        function adsFormReset(searchForm) {
            searchForm.querySelectorAll("input[type='text']").forEach(function (el) {
                el.value = "";
            });
            searchForm.querySelectorAll("input[type='date']").forEach(function (el) {
                el.value = "";
            });
            searchForm.querySelectorAll("input[type='time']").forEach(function (el) {
                el.value = "";
            });
            searchForm.querySelectorAll("input[type='url']").forEach(function (el) {
                el.value = "";
            });
            searchForm.querySelectorAll("input[type='number']").forEach(function (el) {
                el.value = "";
            });
            searchForm.querySelectorAll("input[type='hidden']:not(.listing_type)").forEach(function (el) {
                el.value = "";
            });
            searchForm.querySelectorAll("input[type='radio']").forEach(function (el) {
                el.checked = false;
            });
            searchForm.querySelectorAll("input[type='checkbox']").forEach(function (el) {
                el.checked = false;
            });
            searchForm.querySelectorAll("select").forEach(function (el) {
                el.selectedIndex = 0;
                $(el).val('').trigger('change');
            });

            const irisPicker = searchForm.querySelector("input.wp-picker-clear");
            if (irisPicker !== null) {
                irisPicker.click();
            }

            const rangeValue = searchForm.querySelector(".directorist-range-slider-current-value span");
            if (rangeValue !== null) {
                rangeValue.innerHTML = "0";
            }
        }

        //Search from reset fields
        function resetFields() {
            var inputArray = document.querySelectorAll('.search-area input');
            inputArray.forEach(function (input) {
                if (input.getAttribute("type") !== "hidden" || input.getAttribute("id") === "atbd_rs_value") {
                    input.value = "";
                }
            });

            var textAreaArray = document.querySelectorAll('.search-area textArea');
            textAreaArray.forEach(function (textArea) {
                textArea.innerHTML = "";
            });

            var range = document.querySelector(".atbdpr-range .ui-slider-horizontal .ui-slider-range");
            var rangePos = document.querySelector(".atbdpr-range .ui-slider-horizontal .ui-slider-handle");
            var rangeAmount = document.querySelector(".atbdpr_amount");
            if (range) {
                range.setAttribute("style", "width: 0;");
            }
            if (rangePos) {
                rangePos.setAttribute("style", "left: 0;");
            }
            if (rangeAmount) {
                rangeAmount.innerText = "0 Mile";
            }

            var checkBoxes = document.querySelectorAll('.directorist-advanced-filter input[type="checkbox"]');
            checkBoxes.forEach(function (el, ind) {
                el.checked = false;
            })

            var radios = document.querySelectorAll('.directorist-advanced-filter input[type="radio"]');
            radios.forEach(function (el, ind) {
                el.checked = false;
            })

            $('.search-area select').prop('selectedIndex', 0);
            $(".bdas-location-search, .bdas-category-search").val('').trigger('change');
        }
        $("body").on("click", ".atbd_widget .directorist-advanced-filter #atbdp_reset", function (e) {
            e.preventDefault();
            resetFields();
        });

        /* Single Listing widget Form */
        if ($(".atbd_widget .search-area .directorist-btn-reset-js") !== null) {
            $("body").on("click", ".atbd_widget .search-area .directorist-btn-reset-js", function (e) {
                e.preventDefault();
                if (this.closest('.search-area')) {
                    const searchForm = this.closest('.search-area').querySelector('.directorist-advanced-filter__form');
                    if (searchForm) {
                        adsFormReset(searchForm);
                    }
                }
                directorist_callingSlider(0);
            });
        }
    })(jQuery);
});