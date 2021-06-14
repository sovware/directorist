const $ = jQuery;

// License Authentication
// ----------------------------------------------------------
// atbdp_get_license_authentication
let is_sending = false;
$('#atbdp-directorist-license-login-form').on('submit', function (e) {
    e.preventDefault();
    if (is_sending) {
        return;
    }

    const form = $(this);
    const submit_button = form.find('button[type="submit"]');

    const form_data = {
        action: 'atbdp_authenticate_the_customer',
        username: form.find('input[name="username"]').val(),
        password: form.find('input[name="password"]').val(),
    };

    $('.atbdp-form-feedback').html('');

    is_sending = true;
    jQuery.ajax({
        type: 'post',
        url: atbdp_admin_data.ajaxurl,
        data: form_data,
        beforeSend() {
            submit_button.prepend(
                '<span class="atbdp-loading"><span class="fas fa-spinner fa-spin"></span></span>'
            );
            submit_button.attr('disabled', true);
        },
        success(response) {
            console.log({response});

            if ( response.has_previous_subscriptions ) {
                location.reload();
                return;
            }

            is_sending = false;
            submit_button.attr('disabled', false);
            submit_button.find('.atbdp-loading').remove();

            if ( response?.status?.log ) {
                for ( const feedback in response.status.log ) {
                    const alert_type = response.status.log[feedback].type;
                    
                    let alert = `<div class="atbdp-form-alert"`;
                    const alert_message = response.status.log[feedback].message;
                    alert = `<div class="atbdp-form-alert atbdp-form-alert-${alert_type}">${alert_message}<div>`;

                    $('.atbdp-form-feedback').append(alert);
                }
            }

            if (response?.status?.success) {

                location.reload();
                return;

                form.attr('id', 'atbdp-product-download-form');
                form.find('.atbdp-form-page').remove();

                const form_response_page = form.find('.atbdp-form-response-page');
                form_response_page.removeClass('atbdp-d-none');

                // Append Response
                form_response_page.append('<div class="atbdp-form-feedback"></div>');

                const themes =
                    response.license_data && response.license_data.themes
                        ? response.license_data.themes
                        : [];
                const plugins =
                    response.license_data && response.license_data.plugins
                        ? response.license_data.plugins
                        : [];

                const total_theme = themes.length;
                const total_plugin = plugins.length;

                // console.log( { plugins, themes } );

                if (!plugins.length && !themes.length) {
                    var title =
                        '<h3 class="h3 form-header-title">There is no product in your purchase, redirecting...</h3>';
                    form_response_page.find('.atbdp-form-feedback').append(title);
                    location.reload();

                    return;
                }

                var title = '<h3 class="h3 form-header-title">Activate your products</h3>';
                form_response_page.find('.atbdp-form-feedback').append(title);

                // Show Log - Themes
                if (total_theme) {
                    const theme_section =
                        '<div class="atbdp-checklist-section atbdp-themes-list-section"></div>';
                    form_response_page.find('.atbdp-form-feedback').append(theme_section);

                    const theme_title = `<h4 class="atbdp-theme-title">Themes <span class="atbdp-count">(${themes.length
                        })</span></h4>`;
                    const theme_check_lists =
                        '<ul class="atbdp-check-lists atbdp-themes-list"></ul>';

                    form_response_page
                        .find('.atbdp-themes-list-section')
                        .append(theme_title);
                    form_response_page
                        .find('.atbdp-themes-list-section')
                        .append(theme_check_lists);

                    var counter = 0;
                    for (const theme of themes) {
                        // console.log( theme );
                        var checkbox = `<input type="checkbox" class="atbdp-checkbox atbdp-theme-checkbox-item-${theme.item_id
                            }" value="${theme.item_id}" id="${theme.item_id}">`;
                        var label = `<label for="${theme.item_id}">${theme.title
                            }</label>`;
                        var list_action = `<span class="atbdp-list-action">${checkbox}</span> `;
                        var li = `<li class="atbdp-check-list-item atbdp-theme-checklist-item check-list-item-${theme.item_id
                            }">${list_action}${label}</li>`;
                        form_response_page.find('.atbdp-themes-list').append(li);
                        counter++;
                    }
                }

                // Show Log - Extensions
                if (total_plugin) {
                    const plugin_section =
                        '<div class="atbdp-checklist-section atbdp-extensions-list-section"></div>';
                    form_response_page.find('.atbdp-form-feedback').append(plugin_section);

                    const plugin_title = `<h4 class="atbdp-extension-title">Extensions <span class="atbdp-count">(${plugins.length
                        })</span></h4>`;
                    const plugin_check_lists =
                        '<ul class="atbdp-check-lists atbdp-extensions-list"></ul>';

                    form_response_page
                        .find('.atbdp-extensions-list-section')
                        .append(plugin_title);
                    form_response_page
                        .find('.atbdp-extensions-list-section')
                        .append(plugin_check_lists);

                    var counter = 0;
                    for (const extension of plugins) {
                        // console.log( extension );
                        var checkbox = `<input type="checkbox" class="atbdp-checkbox atbdp-plugin-checkbox-item-${extension.item_id
                            }" value="${extension.item_id}" id="${extension.item_id}">`;
                        var list_action = `<span class="atbdp-list-action">${checkbox}</span> `;
                        var label = `<label for="${extension.item_id}">${extension.title
                            }</label>`;
                        var li = `<li class="atbdp-check-list-item atbdp-plugin-checklist-item check-list-item-${extension.item_id
                            }">${list_action}${label}</li>`;

                        form_response_page.find('.atbdp-extensions-list').append(li);
                        counter++;
                    }
                }

                const continue_button =
                    '<div class="account-connect__form-btn"><button type="button" class="account-connect__btn atbdp-download-products-btn">Continue <span class="la la-arrow-right"></span></button></div>';
                const skip_button =
                    '<a href="#" class="atbdp-link atbdp-link-secondery reload">Skip</a>';

                form_response_page.append(continue_button);
                form_response_page.append(skip_button);

                $('.atbdp-download-products-btn').on('click', function (e) {
                    $(this).prop('disabled', true);

                    let skiped_themes = 0;
                    $(
                        '.atbdp-theme-checklist-item .atbdp-list-action .atbdp-checkbox'
                    ).each(function (i, e) {
                        const is_checked = $(e).is(':checked');

                        if (!is_checked) {
                            const id = $(e).attr('id');
                            const list_item = $(`.check-list-item-${id}`);
                            list_item.remove();

                            skiped_themes++;
                        }
                    });

                    let skiped_plugins = 0;
                    $(
                        '.atbdp-plugin-checklist-item .atbdp-list-action .atbdp-checkbox'
                    ).each(function (i, e) {
                        const is_checked = $(e).is(':checked');

                        if (!is_checked) {
                            const id = $(e).attr('id');
                            const list_item = $(`.check-list-item-${id}`);
                            list_item.remove();

                            skiped_plugins++;
                        }
                    });

                    const new_theme_count = total_theme - skiped_themes;
                    const new_plugin_count = total_plugin - skiped_plugins;

                    $('.atbdp-theme-title')
                        .find('.atbdp-count')
                        .html(`(${new_theme_count})`);
                    $('.atbdp-extension-title')
                        .find('.atbdp-count')
                        .html(`(${new_plugin_count})`);

                    $('.atbdp-check-list-item .atbdp-list-action .atbdp-checkbox').css(
                        'display',
                        'none'
                    );
                    $('.atbdp-check-list-item .atbdp-list-action').prepend(
                        '<span class="atbdp-icon atbdp-text-danger"><span class="fas fa-times"></span></span> '
                    );

                    const files_download_states = {
                        succeeded_plugin_downloads: [],
                        failed_plugin_downloads: [],
                        succeeded_theme_downloads: [],
                        failed_theme_downloads: [],
                    };

                    // Download Files
                    var download_files = function (file_list, counter, callback) {
                        if (counter > file_list.length - 1) {
                            if (callback) {
                                callback();
                            }

                            return;
                        }
                        const next_index = counter + 1;
                        const file_item = file_list[counter];
                        const { file } = file_item;
                        const file_type = file_item.type;

                        const list_item = $(`.check-list-item-${file.item_id}`);
                        const icon_elm = list_item.find(
                            '.atbdp-list-action .atbdp-icon'
                        );
                        const list_checkbox = $(
                            `.atbdp-${file_type}-checkbox-item-${file.item_id}`
                        );
                        const is_checked = list_checkbox.is(':checked');

                        if (!is_checked) {
                            download_files(file_list, next_index, callback);
                            return;
                        }

                        const form_data = {
                            action: 'atbdp_download_file',
                            download_item: file,
                            type: file_type,
                        };
                        jQuery.ajax({
                            type: 'post',
                            url: atbdp_admin_data.ajaxurl,
                            data: form_data,
                            beforeSend() {
                                icon_elm.removeClass('atbdp-text-danger');
                                icon_elm.html(
                                    '<span class="fas fa-circle-notch fa-spin"></span>'
                                );
                            },
                            success(response) {
                                console.log('success', counter, response);

                                if (response.status.success) {
                                    icon_elm.addClass('atbdp-text-success');
                                    icon_elm.html(
                                        '<span class="fas fa-check"></span>'
                                    );

                                    if (file_type == 'plugin') {
                                        files_download_states.succeeded_plugin_downloads.push(
                                            file
                                        );
                                    }

                                    if (file_type == 'theme') {
                                        files_download_states.succeeded_theme_downloads.push(
                                            file
                                        );
                                    }
                                } else {
                                    const msg = `<span class="atbdp-list-feedback atbdp-text-danger">${response.status.message
                                        }</span>`;
                                    list_item.append(msg);
                                    icon_elm.addClass('atbdp-text-danger');
                                    icon_elm.html(
                                        '<span class="fas fa-times"></span>'
                                    );

                                    if (file_type == 'plugin') {
                                        files_download_states.failed_plugin_downloads.push(
                                            file
                                        );
                                    }

                                    if (file_type == 'theme') {
                                        files_download_states.failed_theme_downloads.push(
                                            file
                                        );
                                    }
                                }

                                download_files(file_list, next_index, callback);
                            },
                            error(error) {
                                console.log(error);

                                icon_elm.addClass('atbdp-text-danger');
                                icon_elm.html(
                                    '<span class="fas fa-times"></span>'
                                );
                            },
                        });
                    };

                    // Remove Unnecessary Sections
                    if (!new_theme_count) {
                        $('.atbdp-themes-list-section').remove();
                    }

                    if (!new_plugin_count) {
                        $('.atbdp-extensions-list-section').remove();
                    }

                    if (new_theme_count || new_plugin_count) {
                        const form_header_title = 'Activating your products';
                        form_response_page
                            .find('.atbdp-form-feedback .form-header-title')
                            .html(form_header_title);
                    }

                    const downloading_files = [];

                    // Download Themes
                    if (new_theme_count) {
                        for (const theme of themes) {
                            downloading_files.push({ file: theme, type: 'theme' });
                        }
                    }

                    // Download Plugins
                    if (new_plugin_count) {
                        for (const plugin of plugins) {
                            downloading_files.push({
                                file: plugin,
                                type: 'plugin',
                            });
                        }
                    }

                    const self = this;
                    const after_download_callback = function () {
                        // Check invalid themes
                        let all_thmes_are_invalid = false;
                        const failed_download_themes_count =
                            files_download_states.failed_theme_downloads.length;
                        if (
                            new_theme_count &&
                            failed_download_themes_count === new_theme_count
                        ) {
                            all_thmes_are_invalid = true;
                        }

                        // Check invalid plugin
                        let all_plugins_are_invalid = false;
                        const failed_download_plugins_count =
                            files_download_states.failed_plugin_downloads.length;
                        if (
                            new_plugin_count &&
                            failed_download_plugins_count === new_plugin_count
                        ) {
                            all_plugins_are_invalid = true;
                        }

                        let all_products_are_invalid = false;
                        if (all_thmes_are_invalid && all_plugins_are_invalid) {
                            all_products_are_invalid = true;
                        }

                        $(form_response_page)
                            .find( '.account-connect__form-btn .account-connect__btn' )
                            .remove();

                        const finish_btn_label = all_products_are_invalid ? 'Close' : 'Finish';
                        const finish_btn = `<button type="button" class="account-connect__btn reload">${finish_btn_label}</button>`;
                        
                        $(form_response_page)
                            .find('.account-connect__form-btn')
                            .append(finish_btn);
                    };

                    if (downloading_files.length) {
                        download_files(downloading_files, 0, after_download_callback);
                    }
                });
            }
        },

        error(error) {
            console.log(error);
            is_sending = false;
            submit_button.attr('disabled', false);
            submit_button.find('.atbdp-loading').remove();
        },
    });
});

// Reload Button
$('body').on('click', '.reload', function (e) {
    e.preventDefault();
    console.log('reloading...');
    location.reload();
});

// Extension Update Button
$('.ext-update-btn').on('click', function (e) {
    e.preventDefault();

    $(this).prop('disabled', true);

    const plugin_key = $(this).data('key');
    const button_default_html = $(this).html();

    const form_data = {
        action: 'atbdp_update_plugins',
    };

    if (plugin_key) {
        form_data.plugin_key = plugin_key;
    }

    console.log( { plugin_key } );

    const self = this;

    jQuery.ajax({
        type: 'post',
        url: atbdp_admin_data.ajaxurl,
        data: form_data,
        beforeSend() {
            const icon = '<i class="fas fa-circle-notch fa-spin"></i> Updating';
            $(self).html(icon);
        },
        success(response) {
            console.log( { response } );

            if (response.status.success) {
                $(self).html('Updated');

                location.reload();
            } else {
                $(self).html(button_default_html);
                alert(response.status.massage);
            }
        },
        error(error) {
            console.log(error);
            $(self).html(button_default_html);
            $(this).prop('disabled', false);
        },
    });
});

// Install Button
$('.file-install-btn').on('click', function (e) {
    e.preventDefault();

    if ($(this).hasClass('in-progress')) {
        console.log('Wait...');
        return;
    }

    const data_key = $(this).data('key');
    const data_type = $(this).data('type');
    const form_data = {
        action: 'atbdp_install_file_from_subscriptions',
        item_key: data_key,
        type: data_type,
    };
    const btn_default_html = $(this).html();

    ext_is_installing = true;
    const self = this;
    $(this).prop('disabled', true);
    $(this).addClass('in-progress');

    jQuery.ajax({
        type: 'post',
        url: atbdp_admin_data.ajaxurl,
        data: form_data,
        beforeSend() {
            $(self).html('Installing');
            const icon = '<i class="fas fa-circle-notch fa-spin"></i> ';

            $(self).prepend(icon);
        },
        success(response) {
            console.log(response);

            if (response.status && !response.status.success && response.status.message) {
                alert(response.status.message);
            }

            if (response.status && response.status.success) {
                $(self).html('Installed');
                location.reload();
            } else {
                $(self).html('Failed');
            }
        },
        error(error) {
            console.log(error);
            $(this).prop('disabled', false);
            $(this).removeClass('in-progress');

            $(self).html(btn_default_html);
        },
    });
});


// Plugin Active Button
$('.plugin-active-btn').on('click', function (e) {
    e.preventDefault();

    if ($(this).hasClass('in-progress')) {
        console.log('Wait...');
        return;
    }

    const data_key = $(this).data('key');
    const form_data = {
        action: 'atbdp_activate_plugin',
        item_key: data_key,
    };
    const btn_default_html = $(this).html();

    const self = this;
    $(this).prop('disabled', true);
    $(this).addClass('in-progress');

    jQuery.ajax({
        type: 'post',
        url: atbdp_admin_data.ajaxurl,
        data: form_data,
        beforeSend() {
            $(self).html('Activating');
            const icon = '<i class="fas fa-circle-notch fa-spin"></i> ';

            $(self).prepend(icon);
        },
        success(response) {
            console.log(response);

            // return;

            if (response.status && !response.status.success && response.status.message) {
                alert(response.status.message);
            }

            if (response.status && response.status.success) {
                $(self).html('Activated');
            } else {
                $(self).html('Failed');
            }

            location.reload();
        },
        error(error) {
            console.log(error);
            $(this).prop('disabled', false);
            $(this).removeClass('in-progress');

            $(self).html(btn_default_html);
        },
    });
});

// Purchase refresh btn
$('.purchase-refresh-btn').on('click', function (e) {
    e.preventDefault();

    const purchase_refresh_btn_wrapper = $(this).parent();
    const auth_section = $('.et-auth-section');

    $(purchase_refresh_btn_wrapper).animate(
        {
            width: 0,
        },
        500
    );

    $(auth_section).animate(
        {
            width: 330,
        },
        500
    );
});

// et-close-auth-btn
$('.et-close-auth-btn').on('click', function (e) {
    e.preventDefault();

    const auth_section = $('.et-auth-section');
    const purchase_refresh_btn_wrapper = $('.purchase-refresh-btn-wrapper');

    $(purchase_refresh_btn_wrapper).animate(
        {
            width: 182,
        },
        500
    );

    $(auth_section).animate(
        {
            width: 0,
        },
        500
    );
});

// purchase-refresh-form
$('#purchase-refresh-form').on('submit', function (e) {
    e.preventDefault();
    // console.log( 'purchase-refresh-form' );

    const submit_btn = $(this).find('button[type="submit"]');
    const btn_default_html = submit_btn.html();
    const close_btn = $(this).find('.et-close-auth-btn');
    const form_feedback = $(this).find('.atbdp-form-feedback');

    $(submit_btn).prop('disabled', true);
    $(close_btn).addClass('atbdp-d-none');

    const password = $(this)
        .find('input[name="password"]')
        .val();

    const form_data = {
        action: 'atbdp_refresh_purchase_status',
        password,
    };

    form_feedback.html('');

    jQuery.ajax({
        type: 'post',
        url: atbdp_admin_data.ajaxurl,
        data: form_data,
        beforeSend() {
            $(submit_btn).html('<i class="fas fa-circle-notch fa-spin"></i>');
        },
        success(response) {
            console.log(response);

            if (response.status.message) {
                var feedback_type = response.status.success ? 'success' : 'danger';
                var message = `<span class="atbdp-text-${feedback_type}">${response.status.message
                    }</span>`;
                    form_feedback.html(message);
                
                
            }

            if (!response.status.success) {
                $(submit_btn).html(btn_default_html);
                $(submit_btn).prop('disabled', false);
                $(close_btn).removeClass('atbdp-d-none');

                if (response.status.reload) {
                    location.reload();
                }
            } else {
                location.reload();
            }
        },
        error(error) {
            console.log(error);

            $(submit_btn).prop('disabled', false);
            $(submit_btn).html(btn_default_html);

            $(close_btn).removeClass('atbdp-d-none');
        },
    });
});

// Logout
$('.subscriptions-logout-btn').on('click', function (e) {
    e.preventDefault();

    const hard_logout = $(this).data('hard-logout');

    const form_data = {
        action: 'atbdp_close_subscriptions_sassion',
        hard_logout,
    };

    const self = this;

    jQuery.ajax({
        type: 'post',
        url: atbdp_admin_data.ajaxurl,
        data: form_data,
        beforeSend() {
            $(self).html('<i class="fas fa-circle-notch fa-spin"></i> Logging out');
        },
        success(response) {
            console.log( response );
            location.reload();
        },
        error(error) {
            console.log(error);
            $(this).prop('disabled', false);
            $(this).removeClass('in-progress');

            $(self).html(btn_default_html);
        },
    });

    // atbdp_close_subscriptions_sassion
});

// Form Actions
// Bulk Actions - My extensions form
var is_bulk_processing = false;
$('#atbdp-my-extensions-form').on('submit', function (e) {
    e.preventDefault();

    if ( is_bulk_processing ) { return; }

    const task = $(this)
        .find('select[name="bulk-actions"]')
        .val();
    const plugins_items = [];

    $(this)
        .find('.extension-name-checkbox')
        .each(function (i, e) {
            const is_checked = $(e).is(':checked');
            const id = $(e).attr('id');

            if (is_checked) {
                plugins_items.push(id);
            }
        });

    if (!task.length || !plugins_items.length) {
        return;
    }

    const self = this;
    is_bulk_processing = true;
    form_data = {
        action: 'atbdp_plugins_bulk_action',
        task,
        plugin_items: plugins_items,
    };

    jQuery.ajax({
        type: 'post',
        url: atbdp_admin_data.ajaxurl,
        data: form_data,
        beforeSend() {
            $(self)
                .find('button[type="submit"]')
                .prepend(
                    '<span class="atbdp-icon"><span class="fas fa-circle-notch fa-spin"></span></span> '
                );
        },
        success(response) {
            console.log( response );
            $(self)
                .find('button[type="submit"] .atbdp-icon')
                .remove();
            location.reload();
        },
        error(error) {
            console.log(error);
            uninstalling = false;
        },
    });

    // console.log( task, plugins_items );
});

// Bulk Actions - My extensions form
var is_bulk_processing = false;
$('#atbdp-my-subscribed-extensions-form').on('submit', function (e) {
    e.preventDefault();

    if ( is_bulk_processing) { return; }

    const self = this;
    const task = $(this)
        .find('select[name="bulk-actions"]')
        .val();

    const plugins_items = [];
    const tergeted_items_elm = '.extension-name-checkbox';
    
    $(self)
        .find( tergeted_items_elm )
        .each(function (i, e) {
            const is_checked = $(e).is(':checked');
            const key = $(e).attr('name');

            if (is_checked) {
                plugins_items.push( key );
            }
        });

    if ( ! task.length || ! plugins_items.length ) {
        return;
    }

    // Before Install
    $(this)
        .find('.file-install-btn')
        .prop('disabled', true)
        .addClass('in-progress');

    const loading_icon = '<span class="atbdp-icon"><span class="fas fa-circle-notch fa-spin"></span></span> ';
    
    $(this)
        .find('button[type="submit"]')
        .prop('disabled', true)
        .prepend(loading_icon);

    is_bulk_processing = true;
    const after_bulk_process = function () {
        is_bulk_processing = false;

        $(self)
            .find('button[type="submit"]')
            .find('.atbdp-icon')
            .remove();
        $(self)
            .find('button[type="submit"]')
            .prop('disabled', false);

        location.reload();
    };

    plugins_bulk_actions( 'install', plugins_items, after_bulk_process );
});

// Bulk Actions - Required extensions form
var is_bulk_processing = false;
$('#atbdp-required-extensions-form').on('submit', function (e) {
    e.preventDefault();

    if ( is_bulk_processing) { return; }

    const self = this;
    const task = $(this)
        .find('select[name="bulk-actions"]')
        .val();

    const plugins_items = [];
    const tergeted_items_elm = ( 'install' === task ) ? '.extension-install-checkbox' : '.extension-activate-checkbox';
    
    $(self)
        .find( tergeted_items_elm )
        .each(function (i, e) {
            const is_checked = $(e).is(':checked');
            const key = $(e).attr('value');

            if (is_checked) {
                plugins_items.push( key );
            }
        });

    if ( ! task.length || ! plugins_items.length ) {
        return;
    }

    // Before Install
    $(this)
        .find('.file-install-btn')
        .prop('disabled', true)
        .addClass('in-progress');

    $(this)
        .find('.plugin-active-btn')
        .prop('disabled', true)
        .addClass('in-progress');


    const loading_icon = '<span class="atbdp-icon"><span class="fas fa-circle-notch fa-spin"></span></span> ';
    $(this)
        .find('button[type="submit"]')
        .prop('disabled', true)
        .prepend(loading_icon);

    is_bulk_processing = true;
    const after_bulk_process = function () {
        is_bulk_processing = false;

        $(self)
            .find('button[type="submit"]')
            .find('.atbdp-icon')
            .remove();

        $(self)
            .find('button[type="submit"]')
            .prop('disabled', false);

        location.reload();
    };

    const available_task_list = [ 'install', 'activate' ];

    if ( ( available_task_list.includes( task ) ) ) {
        plugins_bulk_actions( task, plugins_items, after_bulk_process);
    }
});

// plugins_bulk__actions
function plugins_bulk_actions( task, plugins_items, after_plugins_install ) {

    const action = {
        install: 'atbdp_install_file_from_subscriptions',
        activate: 'atbdp_activate_plugin',
    };

    const btnLabelOnProgress = {
        install: 'Installing',
        activate: 'Activating',
    };

    const btnLabelOnSuccess = {
        install: 'Installed',
        activate: 'Activated',
    };

    const processStartBtn = {
        install: '.file-install-btn',
        activate: '.plugin-active-btn',
    };

    var bulk_task = function (plugins, counter, callback) {

        if ( counter > plugins.length - 1 ) {
            if (callback) { callback(); }
            return;
        }

        const current_item       = plugins[counter];
        const action_wrapper_key = ( 'install' === task ) ? plugins[counter] : plugins[counter].replace( /\/.+$/g, '' );
        const action_wrapper     = $(`.ext-action-${action_wrapper_key}`);
        const action_btn         = action_wrapper.find( processStartBtn[ task ] );
        const next_index         = counter + 1;
        const form_action        = ( action[ task ] ) ? action[ task ] : '';
        
        form_data = {
            action: form_action,
            item_key: current_item,
            type: 'plugin',
        };

        jQuery.ajax({
            type: 'post',
            url: atbdp_admin_data.ajaxurl,
            data: form_data,
            beforeSend() {
                action_btn.html(
                    `<span class="atbdp-icon">
                        <span class="fas fa-circle-notch fa-spin"></span>
                    </span> ${btnLabelOnProgress[ task ]}`
                );
            },

            success(response) {
                console.log( { response } );
                if (response.status.success) {
                    action_btn.html( btnLabelOnSuccess[ task ] );
                } else {
                    action_btn.html('Failed');
                }

                bulk_task(plugins, next_index, callback);
            },

            error(error) {
                console.log(error);
            },
        });
    };

    bulk_task(plugins_items, 0, after_plugins_install);
}

// Ext Actions | Uninstall
var uninstalling = false;
$('.ext-action-uninstall').on('click', function (e) {
    e.preventDefault();
    if (uninstalling) {
        return;
    }

    const data_target = $(this).data('target');

    const form_data = {
        action: 'atbdp_plugins_bulk_action',
        task: 'uninstall',
        plugin_items: [data_target],
    };

    const self = this;
    uninstalling = true;

    jQuery.ajax({
        type: 'post',
        url: atbdp_admin_data.ajaxurl,
        data: form_data,
        beforeSend() {
            $(self).prepend(
                '<span class="atbdp-icon"><span class="fas fa-circle-notch fa-spin"></span></span> '
            );
        },
        success(response) {
            console.log( response );
            $(self)
                .closest('.ext-action')
                .find('.ext-action-drop')
                .removeClass('active');
            location.reload();
        },
        error(error) {
            console.log(error);
            uninstalling = false;
        },
    });
});

// Bulk checkbox toggle
$('#select-all-installed')
    .on('change', function (e) {
        const is_checked = $(this).is(':checked');
        if (is_checked) {
            $('#atbdp-my-extensions-form')
                .find('.extension-name-checkbox')
                .prop('checked', true);
        } else {
            $('#atbdp-my-extensions-form')
                .find('.extension-name-checkbox')
                .prop('checked', false);
        }
    });

$('#select-all-subscription')
    .on('change', function (e) {
        const is_checked = $(this).is(':checked');

        if (is_checked) {
            $('#atbdp-my-subscribed-extensions-form')
                .find('.extension-name-checkbox')
                .prop('checked', true);
        } else {
            $('#atbdp-my-subscribed-extensions-form')
                .find('.extension-name-checkbox')
                .prop('checked', false);
        }
    });

$('#select-all-required-extensions')
    .on('change', function (e) {
        const is_checked = $(this).is(':checked');

        if (is_checked) {
            $('#atbdp-required-extensions-form')
                .find('.extension-name-checkbox')
                .prop('checked', true);
        } else {
            $('#atbdp-required-extensions-form')
                .find('.extension-name-checkbox')
                .prop('checked', false);
        }
    });

//
$('.ext-action-drop').each(function (i, e) {
    $(e).on('click', function (elm) {
        elm.preventDefault();

        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $('.ext-action-drop').removeClass('active');
            $(this).addClass('active');
        }
    });
});

// Theme Activation
let theme_is_activating = false;
$('.theme-activate-btn').on('click', function (e) {
    e.preventDefault();

    if (theme_is_activating) {
        return;
    }

    const data_target = $(this).data('target');
    if (!data_target) {
        return;
    }
    if (!data_target.length) {
        return;
    }

    const form_data = {
        action: 'atbdp_activate_theme',
        theme_stylesheet: data_target,
    };

    const self = this;
    theme_is_activating = true;

    $.ajax({
        type: 'post',
        url: atbdp_admin_data.ajaxurl,
        data: form_data,
        beforeSend() {
            $(self).prepend(
                '<span class="atbdp-icon"><span class="fas fa-circle-notch fa-spin"></span></span> '
            );
        },
        success(response) {
            console.log({ response });
            $(self)
                .find('.atbdp-icon')
                .remove();

            if (response.status && response.status.success) {
                location.reload();
            }
        },
        error(error) {
            console.log({ error });
            theme_is_activating = false;
            $(self)
                .find('.atbdp-icon')
                .remove();
        },
    });
});

// Theme Update
$('.theme-update-btn').on('click', function (e) {
    e.preventDefault();

    $(this).prop('disabled', true);
    if ($(this).hasClass('in-progress')) {
        return;
    }

    const theme_stylesheet = $(this).data('target');
    const button_default_html = $(this).html();
    const form_data = { action: 'atbdp_update_theme' };

    if (theme_stylesheet) {
        form_data.theme_stylesheet = theme_stylesheet;
    }

    const self = this;
    $(this).addClass('in-progress');

    $.ajax({
        type: 'post',
        url: atbdp_admin_data.ajaxurl,
        data: form_data,
        beforeSend() {
            $(self).html(
                '<span class="atbdp-icon"><span class="fas fa-circle-notch fa-spin"></span></span> Updating'
            );
        },
        success(response) {
            console.log({ response });

            if (response.status && response.status.success) {
                $(self).html('Updated');
                location.reload();
            } else {
                $(self).removeClass('in-progress');
                $(self).html(button_default_html);
                $(self).prop('disabled', false);

                alert(response.status.message);
            }
        },
        error(error) {
            console.log({ error });
            $(self).removeClass('in-progress');
            $(self).html(button_default_html);
            $(self).prop('disabled', false);
        },
    });
});