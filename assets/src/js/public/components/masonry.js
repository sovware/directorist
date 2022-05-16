/* All listings Masonry layout */
window.addEventListener('DOMContentLoaded', () => {
    (function ($) {
        function authorsMasonry(selector) {
            let authorsCard = $(selector);
            $(authorsCard).each(function (id, elm) {
                let authorsCardRow = $(elm).find('.directorist-masonry');
                let authorMasonryInit = $(authorsCardRow).imagesLoaded(function () {
                    $(authorMasonryInit).masonry({
                        percentPosition: true,
                        horizontalOrder: true
                    });
                })
            })
        }
        authorsMasonry('.directorist-archive-grid-view');
    })(jQuery);
});