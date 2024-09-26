window.addEventListener('load', () => {
    /* Make sure the codes in this file runs only once, even if enqueued twice */
    if ( typeof window.directorist_catloc_executed === 'undefined' ) {
        window.directorist_catloc_executed = true;
    } else {
        return;
    }

    /* Category card grid three width/height adjustment */
    const categoryCard = document.querySelectorAll('.directorist-categories__single--style-three');
    if(categoryCard){
        categoryCard.forEach(elm =>{
            const categoryCardWidth = elm.offsetWidth;
            elm.style.setProperty('--directorist-category-box-width', `${categoryCardWidth}px`);
        })
    }

    /* Taxonomy list dropdown */
    function categoryDropdown(selector, parent){
        var categoryListToggle = document.querySelectorAll(selector);
        categoryListToggle.forEach(function(item) {
            item.addEventListener('click', function(e) {
                const categoryName = item.querySelector('.directorist-taxonomy-list__name');
                if(e.target !== categoryName){
                    e.preventDefault();
                    this.classList.toggle('directorist-taxonomy-list__toggle--open');
                }
            });
        });
    }
    categoryDropdown('.directorist-taxonomy-list-one .directorist-taxonomy-list__toggle', '.directorist-taxonomy-list-one .directorist-taxonomy-list');
    categoryDropdown('.directorist-taxonomy-list-one .directorist-taxonomy-list__sub-item-toggle', '.directorist-taxonomy-list-one .directorist-taxonomy-list');
});