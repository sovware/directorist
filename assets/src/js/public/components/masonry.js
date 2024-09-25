// DOM Mutation observer
function initObserver() {
    const targetNode = document.querySelector('.directorist-archive-contents');
    const observer = new MutationObserver( initMasonry );
    if(targetNode){
        observer.observe( targetNode, { childList: true } );
    }
}

// All listings Masonry layout
function initMasonry() {
    var $ = jQuery;
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
}

window.addEventListener('load', initObserver);
window.addEventListener('load', initMasonry);