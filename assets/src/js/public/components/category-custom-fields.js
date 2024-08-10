// Search Category Change
export default function initSearchCategoryCustomFields($, onSuccessCallback) {
    const $searchPageContainer = $('.directorist-search-contents');
    const $archivePageContainer = $('.directorist-archive-contents');
    let $pageContainer;
    if ($searchPageContainer.length) {
        $pageContainer = $searchPageContainer;
    } else if ($archivePageContainer.length) {
        $pageContainer = $archivePageContainer;
    }

    if ($pageContainer.length) {
        let searchFormCache = {};
        let $fieldsContainer = null;

        $pageContainer.on('change', '.directorist-category-select, .directorist-search-category select', function (event) {
            const $this          = $(this);
            const $container     = $this.parents('form');
            const cat_id         = $this.val();
            const directory_type = $container.find('.listing_type').val();
            // const $search_form_box = $container.find('.directorist-search-form-wrap');
            const form_data      = new FormData();
            let   atts           = $container.data('atts');
            const hasCustomField = $this.find('option[value="'+cat_id+'"]').data('custom-field');

            if (!hasCustomField && !$fieldsContainer) {
                return;
            }

            if (!hasCustomField && $fieldsContainer && $fieldsContainer.length) {
                $fieldsContainer.html(searchFormCache[0]);
                return;
            }

            if (hasCustomField && searchFormCache[cat_id]) {
                $fieldsContainer.html(searchFormCache[cat_id]);
                return;
            }

            form_data.append('action', 'directorist_category_custom_field_search');
            form_data.append('nonce', directorist.directorist_nonce);
            form_data.append('listing_type', directory_type);
            form_data.append('cat_id', cat_id);

            if (!atts) {
                atts = $pageContainer.data('atts');
            }

            form_data.append('atts', JSON.stringify(atts));
            $container.addClass('atbdp-form-fade');

            $.ajax({
                method     : 'POST',
                processData: false,
                contentType: false,
                url        : directorist.ajax_url,
                data       : form_data,
                success: function success(response) {
                    if (response) {
                        $fieldsContainer        = $pageContainer.find(response['container']);
                        searchFormCache[0]      = $fieldsContainer.html();
                        searchFormCache[cat_id] = response['search_form'];

                        $fieldsContainer.html(searchFormCache[cat_id]);

                        // $container.find('.directorist-category-select option').data('custom-field', 1);
                        // $this.find('option').data('custom-field', 1);
                        $this.val(cat_id);

                        [
                            new CustomEvent('directorist-search-form-nav-tab-reloaded'),
                            new CustomEvent('directorist-reload-select2-fields'),
                            new CustomEvent('directorist-reload-map-api-field'),
                            new CustomEvent('triggerSlice')
                        ].forEach(function (event) {
                            document.body.dispatchEvent(event);
                            window.dispatchEvent(event);
                        });
                    }

                    $container.removeClass('atbdp-form-fade');
                    onSuccessCallback();
                },
                error: function error(_error) {
                    //console.log(_error);
                }
            });
        });
    }
}
