(function ($) {
    'use strict';

    const page = $('.directorist-te-page');

    if (!page.length) {
        return;
    }

    const state = {
        type: 'all',
        status: 'all',
        query: '',
    };

    const rows = () => $('.directorist-te-row');
    const visibleRows = () => rows().filter(':not(.is-hidden)');
    const selectableChecks = () =>
        $('#atbdp-my-extensions-form .extension-name-checkbox');
    const checkedSelectableChecks = () => selectableChecks().filter(':checked');
    const visibleSelectableChecks = () =>
        selectableChecks().filter(function () {
            return !$(this).closest('.directorist-te-row').hasClass('is-hidden');
        });
    const updateBanner = () => $('.directorist-te-update-banner');
    const updatePill = () =>
        $('.directorist-te-segmented button[data-filter-status="update"] span');

    const ajaxConfig = () => window.directorist_admin || {};
    const ajaxUrl = () => ajaxConfig().ajaxurl || window.ajaxurl;
    const nonce = () => ajaxConfig().nonce || '';
    const directoristNonce = () => ajaxConfig().directorist_nonce || nonce();

    function statusTokens(row) {
        return String($(row).data('product-status') || '')
            .split(/\s+/)
            .filter(Boolean);
    }

    function rowHasStatus(row, status) {
        const tokens = statusTokens(row);

        if (status === 'all') {
            return true;
        }

        if (status === 'installed') {
            return (
                tokens.includes('installed') ||
                tokens.includes('active') ||
                tokens.includes('update')
            );
        }

        if (status === 'not-installed') {
            return (
                tokens.includes('not-installed') ||
                tokens.includes('marketplace')
            );
        }

        return tokens.includes(status);
    }

    function setButtonLoading(button, label) {
        const $button = $(button);

        if (!$button.data('default-html')) {
            $button.data('default-html', $button.html());
        }

        $button.prop('disabled', true).html(
            `<i class="fas fa-circle-notch fa-spin" aria-hidden="true"></i> ${label}`
        );
    }

    function resetButton(button) {
        const $button = $(button);
        const defaultHtml = $button.data('default-html');

        $button.prop('disabled', false);

        if (defaultHtml) {
            $button.html(defaultHtml);
        }
    }

    function setCheckbox(checkbox, checked) {
        const $checkbox = $(checkbox);
        const $row = $checkbox.closest('.directorist-te-row');
        const $label = $checkbox.closest('.directorist-te-checkbox');

        $checkbox.prop('checked', checked);
        $row.toggleClass('selected', checked);
        $label.toggleClass('is-checked', checked);
    }

    function syncMasterCheckbox() {
        const master = $('#select-all-installed');

        if (!master.length) {
            return;
        }

        const visible = visibleSelectableChecks();
        const checked = visible.filter(':checked');
        const isAll = visible.length > 0 && checked.length === visible.length;
        const isSome = checked.length > 0 && checked.length < visible.length;

        master.prop('checked', isAll);
        master.prop('indeterminate', isSome);
        master
            .closest('.directorist-te-checkbox')
            .toggleClass('is-checked', isAll || isSome);
    }

    function updateCountsAndEmptyState() {
        const shown = visibleRows().length;
        const label = shown === 1 ? '1 add-on' : `${shown} add-ons`;

        $('.directorist-te-count').text(label);
        $('.directorist-te-empty--filter').toggleClass('is-visible', shown === 0);
        $('.directorist-te-upsell').toggle(
            state.type === 'all' && state.status === 'all' && !state.query.trim()
        );
    }

    function scopedUpdateCount() {
        return rows()
            .filter(function () {
                const typeMatches =
                    state.type === 'all' ||
                    $(this).data('product-type') === state.type;

                return typeMatches && statusTokens(this).includes('update');
            })
            .length;
    }

    function updateUpdateBanner() {
        const banner = updateBanner();

        if (!banner.length) {
            return;
        }

        const updates = scopedUpdateCount();
        const hasSelection = checkedSelectableChecks().length > 0;
        const shouldShow =
            updates > 0 &&
            !hasSelection &&
            ['all', 'installed', 'update'].includes(state.status);

        banner.prop('hidden', !shouldShow);
        updatePill().text(updates).toggle(updates > 0);
    }

    function updateBulkBar() {
        const checked = checkedSelectableChecks();
        const count = checked.length;
        const bulkbar = $('.directorist-te-bulkbar');

        bulkbar.prop('hidden', count === 0).toggleClass('show', count > 0);
        $('.directorist-te-selected-count').text(`${count} selected`);

        syncMasterCheckbox();
        updateUpdateBanner();
    }

    function updateFilters() {
        const query = state.query.trim().toLowerCase();

        rows().each(function () {
            const row = $(this);
            const typeMatches =
                state.type === 'all' || row.data('product-type') === state.type;
            const statusMatches = rowHasStatus(this, state.status);
            const textMatches =
                !query ||
                String(row.data('search-text') || '').includes(query);

            row.toggleClass(
                'is-hidden',
                !(typeMatches && statusMatches && textMatches)
            );
        });

        updateCountsAndEmptyState();
        updateBulkBar();
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formMessage(form, key, fallback) {
        return form.attr(`data-${key}`) || fallback;
    }

    function connectFeedback(form) {
        return form.find('.directorist-te-feedback').first();
    }

    function renderConnectFeedback(form, type, message) {
        connectFeedback(form)
            .attr('role', 'alert')
            .html(
                `<div class="atbdp-form-alert atbdp-form-alert-${type}">${escapeHtml(
                    message
                )}</div>`
            );
    }

    function clearConnectFeedback(form) {
        connectFeedback(form).attr('role', 'status').empty();
        form.find('input[name="username"], input[name="password"]').removeAttr(
            'aria-invalid'
        );
    }

    function setConnectBusy(form, busy) {
        const submitButton = form.find('button[type="submit"]').first();
        const fields = form.find('input[name="username"], input[name="password"]');

        if (!submitButton.data('default-html')) {
            submitButton.data('default-html', submitButton.html());
        }

        form.toggleClass('is-submitting', busy).attr('aria-busy', busy);
        fields.prop('disabled', busy);
        submitButton.prop('disabled', busy);

        if (busy) {
            submitButton.html(
                formMessage(form, 'connecting-label', 'Connecting...')
            );
            return;
        }

        submitButton.html(submitButton.data('default-html'));
    }

    function isAuthRequest(settings) {
        const data = settings?.data || '';

        if (typeof data === 'string') {
            try {
                return (
                    new URLSearchParams(data).get('action') ===
                    'atbdp_authenticate_the_customer'
                );
            } catch (error) {
                return data.includes('action=atbdp_authenticate_the_customer');
            }
        }

        return data?.action === 'atbdp_authenticate_the_customer';
    }

    function resetCatalogFilters() {
        state.type = 'all';
        state.status = 'all';
        state.query = '';

        $('.directorist-te-tab').removeClass('is-active');
        $('.directorist-te-tab[data-filter-type="all"]').addClass('is-active');
        $('.directorist-te-segmented button').removeClass('is-active');
        $('.directorist-te-segmented button[data-filter-status="all"]').addClass(
            'is-active'
        );
        $('.directorist-te-search-input').val('');
        updateFilters();
        $('.directorist-te-search-input').first().trigger('focus');
    }

    function runPluginBulkTask(task, pluginItems, button) {
        if (!pluginItems.length) {
            return;
        }

        setButtonLoading(button, 'Working');

        $.ajax({
            type: 'post',
            url: ajaxUrl(),
            data: {
                action: 'atbdp_plugins_bulk_action',
                task,
                plugin_items: pluginItems,
                directorist_nonce: directoristNonce(),
            },
            success(response) {
                if (response?.status && response.status.success === false) {
                    alert(response.status.message || 'Action failed.');
                    resetButton(button);
                    return;
                }

                window.location.reload();
            },
            error() {
                alert('Action failed. Please reload the page and try again.');
                resetButton(button);
            },
        });
    }

    function runSelectedPluginUpdates(pluginItems, button) {
        const queue = pluginItems.slice();

        if (!queue.length) {
            return;
        }

        setButtonLoading(button, 'Updating');

        const next = () => {
            const pluginKey = queue.shift();

            if (!pluginKey) {
                window.location.reload();
                return;
            }

            $.ajax({
                type: 'post',
                url: ajaxUrl(),
                data: {
                    action: 'atbdp_update_plugins',
                    plugin_key: pluginKey,
                    nonce: nonce(),
                },
                success(response) {
                    if (
                        response?.status &&
                        response.status.success === false
                    ) {
                        alert(response.status.message || 'Update failed.');
                        resetButton(button);
                        return;
                    }

                    next();
                },
                error() {
                    alert('Update failed. Please reload the page and try again.');
                    resetButton(button);
                },
            });
        };

        next();
    }

    $('.directorist-te-tab').on('click', function () {
        $('.directorist-te-tab').removeClass('is-active');
        $(this).addClass('is-active');
        state.type = $(this).data('filter-type') || 'all';
        updateFilters();
    });

    $('.directorist-te-segmented button').on('click', function () {
        $('.directorist-te-segmented button').removeClass('is-active');
        $(this).addClass('is-active');
        state.status = $(this).data('filter-status') || 'all';
        updateFilters();
    });

    $('.directorist-te-search-input').on('input', function () {
        state.query = $(this).val();
        updateFilters();
    });

    $('.directorist-te-empty-reset').on('click', function () {
        resetCatalogFilters();
    });

    $('#atbdp-directorist-license-login-form.directorist-te-connect-form').on(
        'submit',
        function (event) {
            const form = $(this);
            const username = form.find('input[name="username"]').first();
            const password = form.find('input[name="password"]').first();

            clearConnectFeedback(form);

            if (!username.val().trim()) {
                event.preventDefault();
                event.stopImmediatePropagation();
                username.attr('aria-invalid', 'true').trigger('focus');
                renderConnectFeedback(
                    form,
                    'danger',
                    formMessage(
                        form,
                        'username-required',
                        'Enter your Directorist account username or email.'
                    )
                );
                return;
            }

            if (!password.val()) {
                event.preventDefault();
                event.stopImmediatePropagation();
                password.attr('aria-invalid', 'true').trigger('focus');
                renderConnectFeedback(
                    form,
                    'danger',
                    formMessage(
                        form,
                        'password-required',
                        'Enter your Directorist account password.'
                    )
                );
                return;
            }

            setConnectBusy(form, true);
        }
    );

    $(
        '#atbdp-directorist-license-login-form.directorist-te-connect-form input[name="username"], #atbdp-directorist-license-login-form.directorist-te-connect-form input[name="password"]'
    ).on('input', function () {
        const form = $(this).closest('.directorist-te-connect-form');

        $(this).removeAttr('aria-invalid');

        if (!form.find('[aria-invalid="true"]').length) {
            connectFeedback(form).attr('role', 'status').empty();
        }
    });

    $(document).on('ajaxSuccess', function (_event, _xhr, settings, response) {
        if (!isAuthRequest(settings)) {
            return;
        }

        const form = $(
            '#atbdp-directorist-license-login-form.directorist-te-connect-form'
        );

        if (!form.length || response?.has_previous_subscriptions) {
            return;
        }

        if (response?.status?.success) {
            return;
        }

        if (!connectFeedback(form).text().trim()) {
            renderConnectFeedback(
                form,
                'danger',
                formMessage(
                    form,
                    'unexpected-error',
                    'Could not connect. Please check your details and try again.'
                )
            );
        }

        setConnectBusy(form, false);
    });

    $(document).on('ajaxError', function (_event, _xhr, settings) {
        if (!isAuthRequest(settings)) {
            return;
        }

        const form = $(
            '#atbdp-directorist-license-login-form.directorist-te-connect-form'
        );

        if (!form.length) {
            return;
        }

        renderConnectFeedback(
            form,
            'danger',
            formMessage(
                form,
                'network-error',
                'Could not reach Directorist.com. Please try again.'
            )
        );
        setConnectBusy(form, false);
    });

    $('.directorist-te-password-toggle').on('click', function (event) {
        event.preventDefault();

        const button = $(this);
        const input = button
            .closest('.directorist-te-password-control')
            .find('input[name="password"]')
            .first();

        if (!input.length) {
            return;
        }

        const isHidden = input.attr('type') === 'password';
        const nextLabel = isHidden
            ? button.data('hide-label')
            : button.data('show-label');

        input.attr('type', isHidden ? 'text' : 'password').trigger('focus');
        button.attr({
            'aria-label': nextLabel,
            'aria-pressed': isHidden ? 'true' : 'false',
        });
        button.find('i').toggleClass('la-eye', !isHidden);
        button.find('i').toggleClass('la-eye-slash', isHidden);
    });

    $('#atbdp-my-extensions-form').on(
        'change',
        '.extension-name-checkbox',
        function () {
            setCheckbox(this, this.checked);
            updateBulkBar();
        }
    );

    $('#select-all-installed').on('change', function () {
        const checked = this.checked;

        visibleSelectableChecks().each(function () {
            setCheckbox(this, checked);
        });

        updateBulkBar();
    });

    $('.directorist-te-bulk-clear').on('click', function () {
        selectableChecks().each(function () {
            setCheckbox(this, false);
        });

        updateBulkBar();
    });

    $(document).on('keydown', function (event) {
        if (event.key !== 'Escape' || !checkedSelectableChecks().length) {
            return;
        }

        selectableChecks().each(function () {
            setCheckbox(this, false);
        });

        $('.ext-action-drop').removeClass('active');
        updateBulkBar();
    });

    $('.directorist-te-bulk-action').on('click', function (event) {
        event.preventDefault();

        const task = $(this).data('task');
        const pluginItems = checkedSelectableChecks()
            .map(function () {
                return this.id;
            })
            .get();

        if (!task || !pluginItems.length) {
            return;
        }

        if (
            task === 'uninstall' &&
            !window.confirm(
                'Delete removes the selected plugin files from this site. Continue?'
            )
        ) {
            return;
        }

        if (task === 'update') {
            runSelectedPluginUpdates(pluginItems, this);
            return;
        }

        runPluginBulkTask(task, pluginItems, this);
    });

    $('.directorist-te-single-plugin-task').on('click', function (event) {
        event.preventDefault();

        const task = $(this).data('task');
        const target = $(this).data('target');

        if (!task || !target) {
            return;
        }

        runPluginBulkTask(task, [target], this);
    });

    $('.directorist-te-update-all').on('click', function (event) {
        event.preventDefault();

        const button = this;
        const updateExtensions = $(button).data('update-extensions') === 1;
        const updateThemes = $(button).data('update-themes') === 1;
        const queue = [];

        if (updateExtensions) {
            queue.push('atbdp_update_plugins');
        }

        if (updateThemes) {
            queue.push('atbdp_update_theme');
        }

        if (!queue.length) {
            return;
        }

        setButtonLoading(button, 'Updating');

        const next = () => {
            const action = queue.shift();

            if (!action) {
                window.location.reload();
                return;
            }

            $.ajax({
                type: 'post',
                url: ajaxUrl(),
                data: {
                    action,
                    nonce: nonce(),
                },
                success(response) {
                    if (
                        response?.status &&
                        response.status.success === false
                    ) {
                        alert(response.status.message || 'Update failed.');
                        resetButton(button);
                        return;
                    }

                    next();
                },
                error() {
                    alert('Update failed. Please reload the page and try again.');
                    resetButton(button);
                },
            });
        };

        next();
    });

    $('.directorist-te-menu-toggle').on('click', function (event) {
        event.preventDefault();
        event.stopPropagation();

        const wasOpen = $(this).hasClass('active');

        $('.ext-action-drop').removeClass('active');

        if (!wasOpen) {
            $(this).addClass('active');
        }
    });

    $('.directorist-te-menu__items').on('click', function (event) {
        event.stopPropagation();
    });

    $('.directorist-te-menu-link').on('click', function () {
        $('.ext-action-drop').removeClass('active');
    });

    $(document).on('click', function (event) {
        if ($(event.target).closest('.directorist-te-menu').length) {
            return;
        }

        $('.ext-action-drop').removeClass('active');
    });

    document.addEventListener(
        'click',
        function (event) {
            const menuToggle = event.target.closest(
                '.directorist-te-menu-toggle'
            );

            if (menuToggle) {
                event.preventDefault();
                event.stopPropagation();

                const wasOpen = menuToggle.classList.contains('active');

                document
                    .querySelectorAll('.ext-action-drop.active')
                    .forEach((toggle) => toggle.classList.remove('active'));

                if (!wasOpen) {
                    menuToggle.classList.add('active');
                }

                return;
            }

            const themeButton = event.target.closest('.theme-activate-btn');

            if (themeButton && !themeButton.dataset.directoristConfirmed) {
                if (
                    !window.confirm(
                        'Activating this theme changes the live site theme. Continue?'
                    )
                ) {
                    event.preventDefault();
                    event.stopPropagation();
                    event.stopImmediatePropagation();
                    return;
                }

                themeButton.dataset.directoristConfirmed = '1';
                window.setTimeout(() => {
                    delete themeButton.dataset.directoristConfirmed;
                }, 1000);
            }

            const uninstallLink = event.target.closest('.ext-action-uninstall');

            if (!uninstallLink) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();

            const target = uninstallLink.dataset.target;

            if (!target) {
                return;
            }

            if (
                !window.confirm(
                    'Delete removes this plugin from the server. Continue?'
                )
            ) {
                return;
            }

            runPluginBulkTask('uninstall', [target], uninstallLink);
        },
        true
    );

    selectableChecks().each(function () {
        setCheckbox(this, this.checked);
    });

    updateFilters();
})(jQuery);
