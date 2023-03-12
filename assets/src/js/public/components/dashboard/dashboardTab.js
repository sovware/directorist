;
(function ($) {
    window.addEventListener('DOMContentLoaded', () => {
        // User Dashboard Tab
        $(function () {
            var hash = window.location.hash;
            var newHash = hash.replace('#active_', '');
            var selectedTab = document.querySelectorAll('.directorist-tab__nav__link');
            selectedTab.forEach(elm=>{
                let elmAttr = elm.getAttribute('target');
                if(elmAttr == newHash){
                    elm.click();
                }
            })

            var activeSubTab = document.querySelector('.directorist-tab__nav__items .atbdp_tab_nav--has-child .atbd-dashboard-nav .directorist-tab__nav__link.directorist-tab__nav__active');
            
            activeSubTab.parentElement.parentElement.style.display="block";

        });

        // store the currently selected tab in the hash value
        $("ul.directorist-tab__nav__items > li a.directorist-tab__nav__link").on("click", function (e) {
            var id = $(e.target).attr("target").substr();
            window.location.hash = "#active_" + id;
            e.stopPropagation();
        });
    });
})(jQuery);