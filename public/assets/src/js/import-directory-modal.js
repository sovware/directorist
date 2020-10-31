const axios = require('axios').default;

var $ = jQuery;

// cptm-import-directory-form
let term_id = 0;
$('.cptm-import-directory-form').on('submit', function (e) {
    e.preventDefault();

    let form_feedback = $(this).find('.cptm-form-group-feedback');
    let modal_content = $('.cptm-import-directory-modal').find('.cptm-modal-content');
    let modal_alert = $('.cptm-import-directory-modal-alert');

    let form_data = new FormData();
    form_data.append('action', 'save_imported_post_type_data');

    if (Number.isInteger(term_id) && term_id > 0) {
        form_data.append('term_id', term_id);
    }

    let form_fields = $(this).find('.cptm-form-field');
    let general_fields = ['text', 'number'];

    $(this).find('button[type=submit] .cptm-loading-icon').removeClass('cptm-d-none');

    for (let field of form_fields) {
        if (!field.name.length) { continue; }

        // General fields
        if (general_fields.includes(field.type)) {
            form_data.append(field.name, $(field).val());
        }

        // Media fields
        if ('file' === field.type) {
            form_data.append(field.name, field.files[0]);
        }
    }

    const self = this;
    form_feedback.html('');

    axios.post(ajax_data.ajax_url, form_data)
        .then(response => {
            // console.log( { response } );
            $(self).find('button[type=submit] .cptm-loading-icon').addClass('cptm-d-none');

            // Store term ID if exist
            if (response.data.term_id && Number.isInteger(response.data.term_id) && response.data.term_id > 0) {
                term_id = response.data.term_id;
                // console.log( 'Term ID has been updated' );
            }

            // Show status log
            if (response.data && response.data.status.status_log) {
                let status_log = response.data.status.status_log;
                for (let status in status_log) {
                    let alert = '<div class="cptm-form-alert cptm-' + status_log[status].type + '">' + status_log[status].message + '</div>';
                    form_feedback.append(alert);
                }
            }

            // Reload the page if success
            if (response.data && response.data.status && response.data.status.success) {
                // console.log( 'reloading...' );

                modal_content.addClass('cptm-d-none');
                modal_alert.removeClass('cptm-d-none');

                $(self).trigger("reset");
                location.reload();
            }
        })
        .catch(error => {
            console.log({ error });
            $(self).find('button[type=submit] .cptm-loading-icon').addClass('cptm-d-none');
        });
});