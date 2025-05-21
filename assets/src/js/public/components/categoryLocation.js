window.addEventListener('load', () => {
    const $ = jQuery;

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

    // Taxonomy Ajax
    $(document).on('click', '.directorist-categories .directorist-pagination a', function(e) {
        taxonomyPagination(e, $(this), '.directorist-categories')
    });
    
    $(document).on('click', '.directorist-location .directorist-pagination a', function(e) {
        taxonomyPagination(e, $(this), '.directorist-location')
    });
    
    function taxonomyPagination(event, clickedElement, containerSelector) {
        event.preventDefault();

        const pageNumber = clickedElement?.attr('data-page') || 1;
        const container = clickedElement.closest(containerSelector);
        const containerAttributes = container ? $(container).data('attrs') : {};
        
        $.ajax({
            url: directorist.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'directorist_taxonomy_pagination',
                nonce: directorist.directorist_nonce,
                page: parseInt(pageNumber),
                attrs: containerAttributes
            },
            beforeSend: function() {
                $(containerSelector).addClass('atbdp-form-fade');
            },
            success: function(response) {
                if (!response?.success) {
                    console.error('Failed to load taxonomy content');
                    return;
                }

                const tempContainer = document.createElement('div');
                tempContainer.innerHTML = response.data.content;
                // Handle both category and location wrappers
                const taxonomyWrapper = document.querySelector('.taxonomy-category-wrapper');
                const locationWrapper = document.querySelector('.taxonomy-location-wrapper');
                const updatedCategoryContent = tempContainer.querySelector('.taxonomy-category-wrapper')?.innerHTML;
                const updatedLocationContent = tempContainer.querySelector('.taxonomy-location-wrapper')?.innerHTML;

                if (taxonomyWrapper && updatedCategoryContent) {
                    taxonomyWrapper.innerHTML = updatedCategoryContent;
                }

                if (locationWrapper && updatedLocationContent) {
                    locationWrapper.innerHTML = updatedLocationContent;
                }

                if (!taxonomyWrapper && !locationWrapper) {
                    console.error('Required elements not found in response');
                    return;
                }
            },
            complete: function() {
                $(containerSelector).removeClass('atbdp-form-fade');
            }
        });
    }
});