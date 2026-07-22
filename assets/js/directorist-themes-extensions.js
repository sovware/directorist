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
        $('#atbdp-my-extensions-form .directorist-te-select-checkbox');
    const checkedSelectableChecks = () => selectableChecks().filter(':checked');
    const visibleSelectableChecks = () =>
        selectableChecks().filter(function () {
            return !$(this).closest('.directorist-te-row').hasClass('is-hidden');
        });
    const updateBanner = () => $('.directorist-te-update-banner');
    const updatePill = () =>
        $(
            '.directorist-te-segmented button[data-filter-status="update"] .directorist-te-update-count'
        );

    const ajaxConfig = () => window.directorist_admin || {};
    const ajaxUrl = () => ajaxConfig().ajaxurl || window.ajaxurl;
    const nonce = () => ajaxConfig().nonce || '';
    const directoristNonce = () => ajaxConfig().directorist_nonce || nonce();

    function normalizeSearchText(value) {
        return String(value || '')
            .toLowerCase()
            .replace(/[-_]+/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();
    }

    function rowDataText(row, key) {
        return normalizeSearchText($(row).attr(`data-${key}`) || '');
    }

    function escapeRegExp(value) {
        return String(value).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function searchHighlightTargets(row) {
        return $(row).find(
            '.directorist-te-row__title h2, .directorist-te-row__content > p'
        );
    }

    function originalHighlightText(element) {
        const $element = $(element);
        const originalText = $element.data('directorist-te-original-text');

        if (typeof originalText !== 'undefined') {
            return originalText;
        }

        const text = $element.text();
        $element.data('directorist-te-original-text', text);

        return text;
    }

    function renderHighlightedText(text, query, exactTokenOnly) {
        const rawQuery = String(query || '').trim();

        if (!rawQuery) {
            return escapeHtml(text);
        }

        const pattern = exactTokenOnly
            ? new RegExp(
                  `(^|[^A-Za-z0-9])(${escapeRegExp(
                      rawQuery
                  )})(?=[^A-Za-z0-9]|$)`,
                  'gi'
              )
            : new RegExp(escapeRegExp(rawQuery), 'gi');
        let html = '';
        let lastIndex = 0;
        let match;

        while ((match = pattern.exec(text)) !== null) {
            const matchText = exactTokenOnly ? match[2] : match[0];
            const matchIndex = exactTokenOnly
                ? match.index + match[1].length
                : match.index;

            html += escapeHtml(text.slice(lastIndex, matchIndex));
            html += `<mark class="directorist-te-search-highlight">${escapeHtml(
                matchText
            )}</mark>`;
            lastIndex = matchIndex + matchText.length;

            if (pattern.lastIndex === match.index) {
                pattern.lastIndex += 1;
            }
        }

        html += escapeHtml(text.slice(lastIndex));

        return html;
    }

    function updateSearchHighlights(query, exactTokenOnly) {
        const normalizedQuery = normalizeSearchText(query);

        rows().each(function () {
            const rowIsVisible = !$(this).hasClass('is-hidden');

            searchHighlightTargets(this).each(function () {
                const text = originalHighlightText(this);
                const normalizedText = normalizeSearchText(text);
                const hasMatch = exactTokenOnly
                    ? normalizedText.split(/\s+/).includes(normalizedQuery)
                    : normalizedText.includes(normalizedQuery);

                if (!normalizedQuery || !rowIsVisible || !hasMatch) {
                    $(this).text(text);
                    return;
                }

                $(this).html(renderHighlightedText(text, query, exactTokenOnly));
            });
        });
    }

    function collectBadgeSearchTerms() {
        const terms = new Set();

        rows().each(function () {
            const rawTerms = $(this).attr('data-badge-search-terms') || '[]';

            try {
                const parsedTerms = JSON.parse(rawTerms);

                if (Array.isArray(parsedTerms)) {
                    parsedTerms.forEach((term) => {
                        const normalizedTerm = normalizeSearchText(term);

                        if (normalizedTerm) {
                            terms.add(normalizedTerm);
                        }
                    });
                }
            } catch (error) {
                rowDataText(this, 'badge-search-text')
                    .split(/\s+/)
                    .filter(Boolean)
                    .forEach((term) => terms.add(term));
            }
        });

        return terms;
    }

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

        if ($checkbox.prop('disabled')) {
            return;
        }

        $checkbox.prop('checked', checked);
        $row.toggleClass('selected', checked);
        $label.toggleClass('is-checked', checked);
    }

    function checkboxActions(checkbox) {
        return String($(checkbox).attr('data-bulk-actions') || '')
            .split(/\s+/)
            .filter(Boolean);
    }

    function selectedBulkItems(task) {
        return checkedSelectableChecks()
            .map(function () {
                const actions = checkboxActions(this);

                if (task && !actions.includes(task)) {
                    return null;
                }

                return {
                    item: $(this).attr('data-bulk-item') || this.value || this.id,
                    type: $(this).attr('data-bulk-type') || 'plugin',
                    actions,
                };
            })
            .get()
            .filter(Boolean);
    }

    function selectedActionCounts() {
        const actionCounts = {};

        checkedSelectableChecks().each(function () {
            checkboxActions(this).forEach((action) => {
                actionCounts[action] = (actionCounts[action] || 0) + 1;
            });
        });

        return actionCounts;
    }

    function itemCountText(count) {
        return count === 1 ? '1 selected item' : `${count} selected items`;
    }

    function bulkActionLabel(button) {
        const $button = $(button);
        let label = $button.data('directoristTeBulkLabel');

        if (label) {
            return label;
        }

        label =
            $button.find('.directorist-te-bulk-action__label').text().trim() ||
            $button.text().trim();
        $button.data('directoristTeBulkLabel', label);

        return label;
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

        master.prop('checked', isAll).prop('disabled', visible.length === 0);
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
        const actionCounts = selectedActionCounts();
        let visibleActions = 0;

        bulkbar.prop('hidden', count === 0).toggleClass('show', count > 0);
        $('.directorist-te-selected-count').text(
            count === 1 ? '1 selected' : `${count} selected`
        );

        $('.directorist-te-bulk-action').each(function () {
            const task = String($(this).data('task') || '');
            const actionCount = actionCounts[task] || 0;
            const skippedCount = Math.max(count - actionCount, 0);
            const isVisible = count > 0 && actionCount > 0;
            const label = bulkActionLabel(this);
            const hint =
                `${label} will run on ${itemCountText(actionCount)}.` +
                (skippedCount
                    ? ` ${itemCountText(skippedCount)} will be skipped.`
                    : '');

            $(this)
                .prop('hidden', !isVisible)
                .prop('disabled', !isVisible)
                .attr('data-eligible-count', actionCount)
                .attr('title', isVisible ? hint : '')
                .attr('aria-label', isVisible ? hint : label);

            $(this)
                .find('.directorist-te-bulk-action__count')
                .text(actionCount)
                .prop('hidden', !isVisible);

            if (isVisible) {
                visibleActions++;
            }
        });

        $('.directorist-te-bulkbar__empty').prop(
            'hidden',
            count === 0 || visibleActions > 0
        );
        $('.directorist-te-bulkbar__notice').prop(
            'hidden',
            count === 0 || visibleActions === 0
        );

        syncMasterCheckbox();
        updateUpdateBanner();
    }

    function updateFilters() {
        const query = normalizeSearchText(state.query);
        const isExactBadgeQuery = query && collectBadgeSearchTerms().has(query);

        rows().each(function () {
            const row = $(this);
            const typeMatches =
                state.type === 'all' || row.data('product-type') === state.type;
            const statusMatches = rowHasStatus(this, state.status);
            const searchText = rowDataText(this, 'search-text');
            const badgeSearchText = rowDataText(this, 'badge-search-text');
            const textMatches =
                !query ||
                (isExactBadgeQuery
                    ? badgeSearchText.includes(query)
                    : searchText.includes(query) ||
                      badgeSearchText.includes(query));

            row.toggleClass(
                'is-hidden',
                !(typeMatches && statusMatches && textMatches)
            );
        });

        updateSearchHighlights(state.query, isExactBadgeQuery);
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

    function isRemoteConnectionMessage(message) {
        return /curl|operation timed out|timed out|could not resolve|ssl|connection|network/i.test(
            String(message || '')
        );
    }

    function authLogMessages(response) {
        const log = response?.status?.log;

        if (!log || typeof log !== 'object') {
            return [];
        }

        return Object.keys(log)
            .map((key) => log[key]?.message || '')
            .filter(Boolean);
    }

    function authHasRemoteConnectionError(response) {
        return authLogMessages(response).some(isRemoteConnectionMessage);
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

    function refreshPanelFocusable(panel) {
        return panel.find('a, button, input, select, textarea');
    }

    function setRefreshPanelHidden(panel, hidden) {
        panel.prop('hidden', hidden).attr('aria-hidden', hidden ? 'true' : 'false');

        if (hidden) {
            refreshPanelFocusable(panel).attr('tabindex', '-1');
            return;
        }

        refreshPanelFocusable(panel).removeAttr('tabindex');
    }

    function setRefreshTriggerHidden(wrapper, hidden) {
        wrapper
            .prop('hidden', hidden)
            .attr('aria-hidden', hidden ? 'true' : 'false');

        if (hidden) {
            wrapper.find('a, button').attr('tabindex', '-1');
            return;
        }

        wrapper.find('a, button').removeAttr('tabindex');
    }

    function setupAccountMenu() {
        const menu = $('.directorist-te-account-menu');
        const toggle = menu.find('.directorist-te-avatar').first();
        const dropdown = menu.find('.directorist-te-account-dropdown').first();
        let closeTimer = null;

        if (!menu.length || !toggle.length || !dropdown.length) {
            return;
        }

        function refreshPanelIsOpen() {
            const panel = dropdown.find('.directorist-te-refresh-panel').first();

            return panel.length && !panel.prop('hidden');
        }

        function setAccountMenuOpen(open) {
            window.clearTimeout(closeTimer);
            dropdown.prop('hidden', !open).attr('aria-hidden', open ? 'false' : 'true');
            toggle.attr('aria-expanded', open ? 'true' : 'false');

            if (!open) {
                const panel = dropdown.find('.directorist-te-refresh-panel').first();
                const wrapper = dropdown.find('.purchase-refresh-btn-wrapper').first();

                if (panel.length && wrapper.length) {
                    panel.find('input[name="password"]').val('');
                    setRefreshPanelHidden(panel, true);
                    setRefreshTriggerHidden(wrapper, false);
                }
            }
        }

        function scheduleClose() {
            window.clearTimeout(closeTimer);

            if (refreshPanelIsOpen()) {
                return;
            }

            closeTimer = window.setTimeout(function () {
                if (menu.find(':focus').length) {
                    return;
                }

                setAccountMenuOpen(false);
            }, 120);
        }

        setAccountMenuOpen(false);

        menu.on('focusout.directoristTeAccount', scheduleClose);

        toggle.on('click.directoristTeAccount', function (event) {
            event.preventDefault();
            setAccountMenuOpen(dropdown.prop('hidden'));
        });

        $(document).on('click.directoristTeAccount', function (event) {
            if ($(event.target).closest(menu).length) {
                return;
            }

            setAccountMenuOpen(false);
        });

        $(document).on('keydown.directoristTeAccount', function (event) {
            if ('Escape' !== event.key || dropdown.prop('hidden')) {
                return;
            }

            setAccountMenuOpen(false);
            toggle.trigger('focus');
        });
    }

    function setupRefreshPurchasePanel() {
        const panel = $('.directorist-te-refresh-panel');
        const triggerWrapper = $('.purchase-refresh-btn-wrapper');

        if (!panel.length || !triggerWrapper.length) {
            return;
        }

        setRefreshPanelHidden(panel, true);
        setRefreshTriggerHidden(triggerWrapper, false);

        $('.purchase-refresh-btn')
            .off('click')
            .on('click.directoristTeRefresh', function (event) {
                event.preventDefault();

                const wrapper = $(this).parent();

                setRefreshPanelHidden(panel, false);
                setRefreshTriggerHidden(wrapper, true);

                if (panel.closest('.directorist-te-account-dropdown').length) {
                    panel.find('input[name="password"]').trigger('focus');
                    return;
                }

                const targetWidth = Math.min(
                    panel.find('.directorist-te-refresh-form').outerWidth() || 330,
                    panel.parent().width() || 330
                );

                wrapper.stop(true, true).animate({ width: 0 }, 250);
                panel
                    .stop(true, true)
                    .css({ width: 0 })
                    .animate({ width: targetWidth }, 250, function () {
                        panel.css('width', '');
                        panel.find('input[name="password"]').trigger('focus');
                    });
            });

        $('.et-close-auth-btn')
            .off('click')
            .on('click.directoristTeRefresh', function (event) {
                event.preventDefault();

                const wrapper = $('.purchase-refresh-btn-wrapper');
                const trigger = wrapper.find('.purchase-refresh-btn').first();
                const targetWidth = trigger.outerWidth() || 160;

                if (panel.closest('.directorist-te-account-dropdown').length) {
                    panel.find('input[name="password"]').val('');
                    setRefreshPanelHidden(panel, true);
                    setRefreshTriggerHidden(wrapper, false);
                    trigger.trigger('focus');
                    return;
                }

                panel
                    .stop(true, true)
                    .animate({ width: 0 }, 250, function () {
                        panel.find('input[name="password"]').val('');
                        panel.css('width', '');
                        setRefreshPanelHidden(panel, true);
                    });

                setRefreshTriggerHidden(wrapper, false);
                wrapper
                    .stop(true, true)
                    .css({ width: 0 })
                    .animate({ width: targetWidth }, 250, function () {
                        wrapper.css('width', '');
                        trigger.trigger('focus');
                    });
            });
    }

    function clearProductSelection() {
        selectableChecks().each(function () {
            setCheckbox(this, false);
        });

        updateBulkBar();
    }

    function closeProductMenus() {
        $('.ext-action-drop').removeClass('active');
    }

    function setupViewSwitcher() {
        const switchers = $('[data-directorist-te-view-target]');
        const views = $('[data-directorist-te-view]');

        if (!switchers.length || !views.length) {
            return;
        }

        function setActiveView(target) {
            const targetView = views.filter(function () {
                return $(this).attr('data-directorist-te-view') === target;
            });

            if (!targetView.length) {
                return;
            }

            views.each(function () {
                const isActive =
                    $(this).attr('data-directorist-te-view') === target;

                $(this)
                    .prop('hidden', !isActive)
                    .attr('aria-hidden', isActive ? 'false' : 'true')
                    .toggleClass('is-active', isActive);
            });

            switchers.each(function () {
                const isActive =
                    $(this).attr('data-directorist-te-view-target') === target;

                $(this).toggleClass('active', isActive);

                if (isActive) {
                    $(this).attr('aria-current', 'page');
                    return;
                }

                $(this).removeAttr('aria-current');
            });

            closeProductMenus();

            if (target === 'dashboard') {
                clearProductSelection();
            }

            if (target === 'addons') {
                updateFilters();
            }
        }

        switchers.on('click.directoristTeView', function (event) {
            event.preventDefault();
            setActiveView($(this).attr('data-directorist-te-view-target'));
        });
    }

    function setupDashboardPreview() {
        const dashboard = $('.directorist-te-dashboard');
        const nudge = dashboard.find('.directorist-te-dashboard-nudge');
        const steps = dashboard.find('.directorist-te-dashboard-step');
        const ring = dashboard.find('.directorist-te-dashboard-ring circle').last();
        const ringLabel = dashboard.find('.directorist-te-dashboard-ring span');
        const radius = 19;
        const circumference = 2 * Math.PI * radius;

        if (!dashboard.length) {
            return;
        }

        function paintProgress() {
            if (!steps.length || !ring.length || !ringLabel.length) {
                return;
            }

            const done = steps.filter('.is-done').length;
            const percent = Math.round((done / steps.length) * 100);

            ring
                .attr('stroke-dasharray', circumference.toFixed(1))
                .attr(
                    'stroke-dashoffset',
                    (circumference * (1 - percent / 100)).toFixed(1)
                );
            ringLabel.text(`${percent}%`);
        }

        paintProgress();

        dashboard
            .find('.directorist-te-dashboard-nudge__dismiss')
            .on('click.directoristTeDashboard', function () {
                nudge.prop('hidden', true).attr('aria-hidden', 'true');
            });

        steps.on('click.directoristTeDashboard', function () {
            if ($(this).hasClass('is-done')) {
                return;
            }

            $(this).addClass('is-done');
            paintProgress();
        });
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

    function runSelectedInstalls(items, button) {
        const queue = items.slice();

        if (!queue.length) {
            return;
        }

        setButtonLoading(button, 'Installing');

        const next = () => {
            const item = queue.shift();

            if (!item) {
                window.location.reload();
                return;
            }

            $.ajax({
                type: 'post',
                url: ajaxUrl(),
                data: {
                    action: 'atbdp_install_file_from_subscriptions',
                    item_key: item.item,
                    type: item.type,
                    nonce: nonce(),
                },
                success(response) {
                    if (
                        response?.status &&
                        response.status.success === false
                    ) {
                        alert(response.status.message || 'Install failed.');
                        resetButton(button);
                        return;
                    }

                    next();
                },
                error() {
                    alert('Install failed. Please reload the page and try again.');
                    resetButton(button);
                },
            });
        };

        next();
    }

    function runSelectedUpdates(items, button) {
        const queue = items.slice();

        if (!queue.length) {
            return;
        }

        setButtonLoading(button, 'Updating');

        const next = () => {
            const item = queue.shift();

            if (!item) {
                window.location.reload();
                return;
            }

            const data =
                item.type === 'theme'
                    ? {
                          action: 'atbdp_update_theme',
                          theme_stylesheet: item.item,
                          nonce: nonce(),
                      }
                    : {
                          action: 'atbdp_update_plugins',
                          plugin_key: item.item,
                          nonce: nonce(),
                      };

            $.ajax({
                type: 'post',
                url: ajaxUrl(),
                data,
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
                        'Enter your Directorist account username or email address.'
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

        if (
            authHasRemoteConnectionError(response) ||
            isRemoteConnectionMessage(connectFeedback(form).text())
        ) {
            renderConnectFeedback(
                form,
                'danger',
                formMessage(
                    form,
                    'network-error',
                    'Could not reach Directorist.com. Please try again.'
                )
            );
        } else {
            renderConnectFeedback(
                form,
                'danger',
                formMessage(
                    form,
                    'invalid-credentials',
                    'The username, email address, or password is incorrect. Please check your details and try again.'
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
        '.directorist-te-select-checkbox',
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
        const selectedItems = selectedBulkItems(task);
        const selectedCount = checkedSelectableChecks().length;
        const skippedCount = Math.max(selectedCount - selectedItems.length, 0);

        if (!task || !selectedItems.length) {
            return;
        }

        if (
            task === 'uninstall' &&
            !window.confirm(
                `Delete removes ${itemCountText(
                    selectedItems.length
                )} from this site.` +
                    (skippedCount
                        ? ` ${itemCountText(skippedCount)} will be skipped.`
                        : '') +
                    ' Continue?'
            )
        ) {
            return;
        }

        if (task === 'install') {
            runSelectedInstalls(selectedItems, this);
            return;
        }

        if (task === 'update') {
            runSelectedUpdates(selectedItems, this);
            return;
        }

        const pluginItems = selectedItems
            .filter((item) => item.type === 'plugin')
            .map((item) => item.item);

        if (pluginItems.length !== selectedItems.length) {
            alert('This bulk action is only available for plugins.');
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

    setupAccountMenu();
    setupRefreshPurchasePanel();
    setupViewSwitcher();
    setupDashboardPreview();
    updateFilters();
})(jQuery);
