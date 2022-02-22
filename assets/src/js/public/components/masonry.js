/* All listings Masonry layout */
(function($){
    function authorsMasonry(selector) {
        let authorsCard = $(selector);
        $(authorsCard).each(function(id, elm){
            let authorsCardRow = $(elm).find('.directorist-row');
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