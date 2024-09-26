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

    if ($pageContainer?.length) {
        let $fieldsContainer = null;

        $pageContainer.on('change', '.directorist-category-select, .directorist-search-category select', function (event) {
            const $this          = $(this);
            const $form          = $this.parents('form');
            const $advancedForm = $('.directorist-search-form');
            const category       = $this.val();
            const directory      = $pageContainer.find('[name="directory_type"]').val(); // Sidebar has multiple forms that's why it's safe to use page container
            const formData       = new FormData();
            let   atts           = $form.data('atts');
            const hasCustomField = $this.find('option[value="'+category+'"]').data('custom-field');

            if (!hasCustomField) {
                return;
            }

            formData.append('action', 'directorist_category_custom_field_search');
            formData.append('nonce', directorist.directorist_nonce);
            formData.append('directory', directory);
            formData.append('cat_id', category);

            if (!atts) {
                atts = $pageContainer.data('atts');
            }

            formData.append('atts', JSON.stringify(atts));
            $form.addClass('atbdp-form-fade');
            $advancedForm.addClass('atbdp-form-fade');

            $.ajax({
                method     : 'POST',
                processData: false,
                contentType: false,
                url        : directorist.ajax_url,
                data       : formData,
                success: function success(response) {
                    if (response) {
                        $fieldsContainer = $pageContainer.find(response['container']);

                        $fieldsContainer.html(response['search_form']);

                        // $form.find('.directorist-category-select option').data('custom-field', 1);
                        // $this.find('option').data('custom-field', 1);
                        $this.val(category);

                        [
                            'directorist-search-form-nav-tab-reloaded',
                            'directorist-reload-select2-fields',
                            'directorist-reload-map-api-field',
                            'triggerSlice'
                        ].forEach(function(event) {
                            event = new CustomEvent(event);
                            document.body.dispatchEvent(event);
                            window.dispatchEvent(event);
                        });
                    }

                    onSuccessCallback();

                    $form.removeClass('atbdp-form-fade');
                    $advancedForm.removeClass('atbdp-form-fade');
                },
                error: function error(_error) {
                    //console.log(_error);
                }
            });
        });
    }
}
