;
(function ($) {
    window.addEventListener('DOMContentLoaded', () => {
        // User Dashboard Tab
        $(function () {
            var hash = window.location.hash;

            // Split the URL into its components
            var urlParts = hash.split(/[?|&]/);

            if(urlParts.length > 1) {
                // Get Hash Link
                var hashLink = urlParts[0];

                // Get the search parameters
                var searchParams = urlParts[1];

                window.location.hash = hashLink;

                var updatedHash = window.location.hash;

                var newHash = updatedHash.replace('#active_', '');
            } else {
                var newHash = hash.replace('#active_', '');
            }

            var selectedTab = document.querySelectorAll('.directorist-tab__nav__link');
            selectedTab.forEach(elm=>{
                let elmAttr = elm.getAttribute('target');
                if(elmAttr == newHash){
                    elm.click();
                }
            })

            if(searchParams) {
                // Reconstruct the URL with the updated search parameters
                var newUrl = window.location.pathname + window.location.hash + "?" + searchParams;
                window.history.replaceState(null, null, newUrl);
            }

        });
    });

    window.addEventListener("load", () => {
        // Restore URL Parameter on Click
        $("ul.directorist-tab__nav__items > li a.directorist-tab__nav__link").on("click", function (e) {
            var id = $(e.target).attr("target").substr();
            window.location.hash = "#active_" + id;
            var newHash = window.location.hash;
            var newUrl = window.location.pathname + newHash;
            window.history.replaceState(null, null, newUrl);
            e.stopPropagation();
        });

        var activeSubTab = document.querySelector('.directorist-tab__nav__items .atbdp_tab_nav--has-child .atbd-dashboard-nav .directorist-tab__nav__link.directorist-tab__nav__active');

        if(activeSubTab) {
            activeSubTab.parentElement.parentElement.style.display="block";
        }
    });
})(jQuery);