(function ($) {
  "use strict";

  const page = $(".directorist-te-page");

  if (!page.length) {
    return;
  }

  const validViews = ["dashboard", "addons"];
  const validTypes = ["all", "extension", "theme"];
  const state = {
    view: validViews.includes(page.attr("data-initial-view"))
      ? page.attr("data-initial-view")
      : "dashboard",
    type: validTypes.includes(page.attr("data-initial-type"))
      ? page.attr("data-initial-type")
      : "all",
    status: "all",
    query: "",
  };

  const rows = () => $(".directorist-te-row");
  const visibleRows = () => rows().filter(":not(.is-hidden)");
  const selectableChecks = () =>
    $("#atbdp-my-extensions-form .directorist-te-select-checkbox");
  const checkedSelectableChecks = () => selectableChecks().filter(":checked");
  const visibleSelectableChecks = () =>
    selectableChecks().filter(function () {
      return !$(this).closest(".directorist-te-row").hasClass("is-hidden");
    });
  const updateBanner = () =>
    $(".directorist-te-update-banner:not(.directorist-te-action-result)");
  const updatePill = () =>
    $(
      '.directorist-te-segmented button[data-filter-status="update"] .directorist-te-update-count',
    );
  const actionResultStorageKey = "directorist_te_action_result";
  let productQueueRunning = false;

  const ajaxConfig = () => window.directorist_admin || {};
  const ajaxUrl = () => ajaxConfig().ajaxurl || window.ajaxurl;
  const nonce = () => ajaxConfig().nonce || "";
  const directoristNonce = () => ajaxConfig().directorist_nonce || nonce();

  function syncWordPressSubmenuState() {
    if (!page.hasClass("directorist-te-page--connected")) {
      return;
    }

    $("#adminmenu .wp-submenu a").each(function () {
      let linkUrl;

      try {
        linkUrl = new URL(this.href, window.location.href);
      } catch (error) {
        return;
      }

      if (linkUrl.searchParams.get("page") !== "atbdp-extension") {
        return;
      }

      const linkTargetsAddons =
        linkUrl.searchParams.get("te_view") === "addons";
      const isActive =
        (state.view === "addons" && linkTargetsAddons) ||
        (state.view === "dashboard" && !linkTargetsAddons);
      const link = $(this);

      link.toggleClass("current", isActive);
      link.closest("li").toggleClass("current", isActive);

      if (isActive) {
        link.attr("aria-current", "page");
        return;
      }

      link.removeAttr("aria-current");
    });
  }

  function syncPageStateUrl() {
    if (
      !page.hasClass("directorist-te-page--connected") ||
      !window.history?.replaceState
    ) {
      return;
    }

    const url = new URL(window.location.href);

    if (state.view === "addons") {
      url.searchParams.set("te_view", "addons");
    } else {
      url.searchParams.delete("te_view");
    }

    if (state.type === "all") {
      url.searchParams.delete("te_type");
    } else {
      url.searchParams.set("te_type", state.type);
    }

    window.history.replaceState(
      {
        ...(window.history.state || {}),
        directoristThemesExtensions: {
          view: state.view,
          type: state.type,
        },
      },
      "",
      url.toString(),
    );

    syncWordPressSubmenuState();
  }

  function normalizeSearchText(value) {
    return String(value || "")
      .toLowerCase()
      .replace(/[-_]+/g, " ")
      .replace(/\s+/g, " ")
      .trim();
  }

  function rowDataText(row, key) {
    return normalizeSearchText($(row).attr(`data-${key}`) || "");
  }

  function escapeRegExp(value) {
    return String(value).replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
  }

  function searchHighlightTargets(row) {
    return $(row).find(
      ".directorist-te-row__title h2, .directorist-te-row__content > p",
    );
  }

  function originalHighlightText(element) {
    const $element = $(element);
    const originalText = $element.data("directorist-te-original-text");

    if (typeof originalText !== "undefined") {
      return originalText;
    }

    const text = $element.text();
    $element.data("directorist-te-original-text", text);

    return text;
  }

  function renderHighlightedText(text, query, exactTokenOnly) {
    const rawQuery = String(query || "").trim();

    if (!rawQuery) {
      return escapeHtml(text);
    }

    const pattern = exactTokenOnly
      ? new RegExp(
          `(^|[^A-Za-z0-9])(${escapeRegExp(rawQuery)})(?=[^A-Za-z0-9]|$)`,
          "gi",
        )
      : new RegExp(escapeRegExp(rawQuery), "gi");
    let html = "";
    let lastIndex = 0;
    let match;

    while ((match = pattern.exec(text)) !== null) {
      const matchText = exactTokenOnly ? match[2] : match[0];
      const matchIndex = exactTokenOnly
        ? match.index + match[1].length
        : match.index;

      html += escapeHtml(text.slice(lastIndex, matchIndex));
      html += `<mark class="directorist-te-search-highlight">${escapeHtml(
        matchText,
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
      const rowIsVisible = !$(this).hasClass("is-hidden");

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
      const rawTerms = $(this).attr("data-badge-search-terms") || "[]";

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
        rowDataText(this, "badge-search-text")
          .split(/\s+/)
          .filter(Boolean)
          .forEach((term) => terms.add(term));
      }
    });

    return terms;
  }

  function statusTokens(row) {
    return String($(row).data("product-status") || "")
      .split(/\s+/)
      .filter(Boolean);
  }

  function rowHasStatus(row, status) {
    const tokens = statusTokens(row);

    if (status === "all") {
      return true;
    }

    if (status === "installed") {
      return (
        tokens.includes("installed") ||
        tokens.includes("active") ||
        tokens.includes("update")
      );
    }

    if (status === "not-installed") {
      return tokens.includes("not-installed") || tokens.includes("marketplace");
    }

    return tokens.includes(status);
  }

  function setButtonLoading(button, label) {
    const $button = $(button);

    if (!$button.data("default-html")) {
      $button.data("default-html", $button.html());
    }

    $button
      .prop("disabled", true)
      .html(
        `<i class="fas fa-circle-notch fa-spin" aria-hidden="true"></i> ${label}`,
      );
  }

  function setCheckbox(checkbox, checked) {
    const $checkbox = $(checkbox);
    const $row = $checkbox.closest(".directorist-te-row");
    const $label = $checkbox.closest(".directorist-te-checkbox");

    if ($checkbox.prop("disabled")) {
      return;
    }

    $checkbox.prop("checked", checked);
    $row.toggleClass("selected", checked);
    $label.toggleClass("is-checked", checked);
  }

  function checkboxActions(checkbox) {
    return String($(checkbox).attr("data-bulk-actions") || "")
      .split(/\s+/)
      .filter(Boolean);
  }

  function bulkItemsForTask(checks, task) {
    return checks
      .map(function () {
        const actions = checkboxActions(this);

        if (task && !actions.includes(task)) {
          return null;
        }

        return {
          item: $(this).attr("data-bulk-item") || this.value || this.id,
          type: $(this).attr("data-bulk-type") || "plugin",
          label:
            $(this)
              .closest(".directorist-te-row")
              .find(".directorist-te-row__title h2")
              .first()
              .text()
              .trim() ||
            this.value ||
            this.id,
          row: $(this).closest(".directorist-te-row").get(0),
          actions,
        };
      })
      .get()
      .filter(Boolean);
  }

  function selectedBulkItems(task) {
    return bulkItemsForTask(checkedSelectableChecks(), task);
  }

  function availableBulkItems(task) {
    return bulkItemsForTask(selectableChecks(), task);
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
    return count === 1 ? "1 selected item" : `${count} selected items`;
  }

  function bulkActionLabel(button) {
    const $button = $(button);
    let label = $button.data("directoristTeBulkLabel");

    if (label) {
      return label;
    }

    label =
      $button.find(".directorist-te-bulk-action__label").text().trim() ||
      $button.text().trim();
    $button.data("directoristTeBulkLabel", label);

    return label;
  }

  function syncMasterCheckbox() {
    const master = $("#select-all-installed");

    if (!master.length) {
      return;
    }

    const visible = visibleSelectableChecks();
    const checked = visible.filter(":checked");
    const isAll = visible.length > 0 && checked.length === visible.length;
    const isSome = checked.length > 0 && checked.length < visible.length;

    master.prop("checked", isAll).prop("disabled", visible.length === 0);
    master.prop("indeterminate", isSome);
    master
      .closest(".directorist-te-checkbox")
      .toggleClass("is-checked", isAll || isSome);
  }

  function updateCountsAndEmptyState() {
    const shown = visibleRows().length;
    const label = shown === 1 ? "1 add-on" : `${shown} add-ons`;

    $(".directorist-te-count").text(label);
    $(".directorist-te-empty--filter").toggleClass("is-visible", shown === 0);
    $(".directorist-te-upsell").toggle(
      state.type === "all" && state.status === "all" && !state.query.trim(),
    );
  }

  function scopedStatusCount(status) {
    return rows().filter(function () {
      const typeMatches =
        state.type === "all" || $(this).data("product-type") === state.type;

      return typeMatches && rowHasStatus(this, status);
    }).length;
  }

  function updateStatusCounts() {
    $(".directorist-te-status-count").each(function () {
      const status = $(this).data("status-count");

      $(this).text(scopedStatusCount(status));
    });
  }

  function updateUpdateBanner() {
    const banner = updateBanner();

    if (!banner.length) {
      return;
    }

    const updates = scopedStatusCount("update");
    const hasSelection = checkedSelectableChecks().length > 0;
    const shouldShow =
      updates > 0 &&
      !hasSelection &&
      ["all", "installed", "update"].includes(state.status);

    banner.prop("hidden", !shouldShow);
    updatePill()
      .text(updates)
      .toggle(updates > 0);
  }

  function updateBulkBar() {
    const checked = checkedSelectableChecks();
    const count = checked.length;
    const bulkbar = $(".directorist-te-bulkbar");
    const actionCounts = selectedActionCounts();
    let visibleActions = 0;

    bulkbar.prop("hidden", count === 0).toggleClass("show", count > 0);
    $(".directorist-te-selected-count").text(
      count === 1 ? "1 selected" : `${count} selected`,
    );

    $(".directorist-te-bulk-action").each(function () {
      const task = String($(this).data("task") || "");
      const actionCount = actionCounts[task] || 0;
      const skippedCount = Math.max(count - actionCount, 0);
      const isVisible = count > 0 && actionCount > 0;
      const label = bulkActionLabel(this);
      const hint =
        `${label} will run on ${itemCountText(actionCount)}.` +
        (skippedCount
          ? ` ${itemCountText(skippedCount)} will be skipped.`
          : "");

      $(this)
        .prop("hidden", !isVisible)
        .prop("disabled", !isVisible)
        .attr("data-eligible-count", actionCount)
        .attr("title", isVisible ? hint : "")
        .attr("aria-label", isVisible ? hint : label);

      $(this)
        .find(".directorist-te-bulk-action__count")
        .text(actionCount)
        .prop("hidden", !isVisible);

      if (isVisible) {
        visibleActions++;
      }
    });

    $(".directorist-te-bulkbar__empty").prop(
      "hidden",
      count === 0 || visibleActions > 0,
    );
    $(".directorist-te-bulkbar__notice").prop(
      "hidden",
      count === 0 || visibleActions === 0,
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
        state.type === "all" || row.data("product-type") === state.type;
      const statusMatches = rowHasStatus(this, state.status);
      const searchText = rowDataText(this, "search-text");
      const badgeSearchText = rowDataText(this, "badge-search-text");
      const textMatches =
        !query ||
        (isExactBadgeQuery
          ? badgeSearchText.includes(query)
          : searchText.includes(query) || badgeSearchText.includes(query));

      row.toggleClass(
        "is-hidden",
        !(typeMatches && statusMatches && textMatches),
      );
    });

    updateSearchHighlights(state.query, isExactBadgeQuery);
    updateCountsAndEmptyState();
    updateStatusCounts();
    updateBulkBar();
  }

  function escapeHtml(value) {
    return String(value)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  function formMessage(form, key, fallback) {
    return form.attr(`data-${key}`) || fallback;
  }

  function isRemoteConnectionMessage(message) {
    return /curl|operation timed out|timed out|could not resolve|ssl|connection|network/i.test(
      String(message || ""),
    );
  }

  function authLogMessages(response) {
    const log = response?.status?.log;

    if (!log || typeof log !== "object") {
      return [];
    }

    return Object.keys(log)
      .map((key) => log[key]?.message || "")
      .filter(Boolean);
  }

  function authHasRemoteConnectionError(response) {
    return authLogMessages(response).some(isRemoteConnectionMessage);
  }

  function connectFeedback(form) {
    return form.find(".directorist-te-feedback").first();
  }

  function connectFields(form) {
    return form.find(
      'input[name="username"], input[name="password"], input[name="access_key"]',
    );
  }

  function connectAuthMethod(form) {
    return form.find('input[name="auth_method"]').val() === "access_key"
      ? "access_key"
      : "account";
  }

  function syncConnectAuthMethod(form, shouldFocus) {
    const method = connectAuthMethod(form);
    const busy = form.attr("aria-busy") === "true";

    form.find("[data-auth-method]").each(function () {
      const button = $(this);
      const isActive = button.attr("data-auth-method") === method;

      button
        .toggleClass("is-active", isActive)
        .attr("aria-selected", isActive ? "true" : "false");
    });

    form.find("[data-auth-panel]").each(function () {
      const panel = $(this);
      const isActive = panel.attr("data-auth-panel") === method;

      panel.prop("hidden", !isActive).attr("aria-hidden", !isActive);
      panel.find("input, button").prop("disabled", !isActive || busy);
    });

    if (shouldFocus) {
      form.find(`[data-auth-panel="${method}"] input`).first().trigger("focus");
    }
  }

  function renderConnectFeedback(form, type, message) {
    connectFeedback(form)
      .attr("role", "alert")
      .html(
        `<div class="atbdp-form-alert atbdp-form-alert-${type}">${escapeHtml(
          message,
        )}</div>`,
      );
  }

  function clearConnectFeedback(form) {
    connectFeedback(form).attr("role", "status").empty();
    connectFields(form).removeAttr("aria-invalid");
  }

  function setConnectBusy(form, busy) {
    const submitButton = form.find('button[type="submit"]').first();
    const fields = connectFields(form);
    const methodButtons = form.find("[data-auth-method]");

    if (!submitButton.data("default-html")) {
      submitButton.data("default-html", submitButton.html());
    }

    form.toggleClass("is-submitting", busy).attr("aria-busy", busy);
    fields.prop("disabled", busy);
    methodButtons.prop("disabled", busy);
    submitButton.prop("disabled", busy);

    if (busy) {
      submitButton.html(formMessage(form, "connecting-label", "Connecting..."));
      return;
    }

    submitButton.html(submitButton.data("default-html"));
    syncConnectAuthMethod(form, false);
  }

  function isAuthRequest(settings) {
    const data = settings?.data || "";

    if (typeof data === "string") {
      try {
        return (
          new URLSearchParams(data).get("action") ===
          "atbdp_authenticate_the_customer"
        );
      } catch (error) {
        return data.includes("action=atbdp_authenticate_the_customer");
      }
    }

    return data?.action === "atbdp_authenticate_the_customer";
  }

  function resetCatalogFilters() {
    state.type = "all";
    state.status = "all";
    state.query = "";

    $(".directorist-te-tab").removeClass("is-active");
    $('.directorist-te-tab[data-filter-type="all"]').addClass("is-active");
    $(".directorist-te-segmented button").removeClass("is-active");
    $('.directorist-te-segmented button[data-filter-status="all"]').addClass(
      "is-active",
    );
    $(".directorist-te-search-input").val("");
    updateFilters();
    $(".directorist-te-search-input").first().trigger("focus");
  }

  function refreshPanelFocusable(panel) {
    return panel.find("a, button, input, select, textarea");
  }

  function setRefreshPanelHidden(panel, hidden) {
    panel.prop("hidden", hidden).attr("aria-hidden", hidden ? "true" : "false");

    if (hidden) {
      refreshPanelFocusable(panel).attr("tabindex", "-1");
      return;
    }

    refreshPanelFocusable(panel).removeAttr("tabindex");
  }

  function setRefreshTriggerHidden(wrapper, hidden) {
    wrapper
      .prop("hidden", hidden)
      .attr("aria-hidden", hidden ? "true" : "false");

    if (hidden) {
      wrapper.find("a, button").attr("tabindex", "-1");
      return;
    }

    wrapper.find("a, button").removeAttr("tabindex");
  }

  function setupAccountMenu() {
    const menu = $(".directorist-te-account-menu");
    const toggle = menu.find(".directorist-te-avatar").first();
    const dropdown = menu.find(".directorist-te-account-dropdown").first();
    let closeTimer = null;

    if (!menu.length || !toggle.length || !dropdown.length) {
      return;
    }

    function refreshPanelIsOpen() {
      const panel = dropdown.find(".directorist-te-refresh-panel").first();

      return panel.length && !panel.prop("hidden");
    }

    function setAccountMenuOpen(open) {
      window.clearTimeout(closeTimer);

      if (open) {
        $('.directorist-te-notification-toggle[aria-expanded="true"]')
          .first()
          .trigger("click");
      }

      dropdown
        .prop("hidden", !open)
        .attr("aria-hidden", open ? "false" : "true");
      toggle.attr("aria-expanded", open ? "true" : "false");

      if (!open) {
        const panel = dropdown.find(".directorist-te-refresh-panel").first();
        const wrapper = dropdown.find(".purchase-refresh-btn-wrapper").first();

        if (panel.length && wrapper.length) {
          panel.find('input[name="password"]').val("");
          setRefreshPanelHidden(panel, true);
          setRefreshTriggerHidden(wrapper, false);
        }
      }
    }

    function scheduleClose() {
      window.clearTimeout(closeTimer);

      if (refreshPanelIsOpen() || menu.data("directoristTeRefreshResetting")) {
        return;
      }

      closeTimer = window.setTimeout(function () {
        if (menu.find(":focus").length) {
          return;
        }

        setAccountMenuOpen(false);
      }, 120);
    }

    setAccountMenuOpen(false);

    menu.on("directoristTeRefreshReset", function () {
      window.clearTimeout(closeTimer);
      menu.data("directoristTeRefreshResetting", true);
      setAccountMenuOpen(true);

      window.setTimeout(function () {
        menu.removeData("directoristTeRefreshResetting");
      }, 200);
    });

    menu.on("focusout.directoristTeAccount", scheduleClose);

    toggle.on("click.directoristTeAccount", function (event) {
      event.preventDefault();
      setAccountMenuOpen(dropdown.prop("hidden"));
    });

    $(document).on("click.directoristTeAccount", function (event) {
      if ($(event.target).closest(menu).length) {
        return;
      }

      setAccountMenuOpen(false);
    });

    $(document).on("keydown.directoristTeAccount", function (event) {
      if ("Escape" !== event.key || dropdown.prop("hidden")) {
        return;
      }

      setAccountMenuOpen(false);
      toggle.trigger("focus");
    });
  }

  function setupNotificationMenu() {
    const menu = $(".directorist-te-notification-menu").first();
    const toggle = menu.find(".directorist-te-notification-toggle").first();
    const dropdown = menu.find(".directorist-te-notification-dropdown").first();

    if (!menu.length || !toggle.length || !dropdown.length) {
      return;
    }

    function setNotificationMenuOpen(open) {
      if (open) {
        $('.directorist-te-avatar[aria-expanded="true"]')
          .first()
          .trigger("click");
      }

      dropdown
        .prop("hidden", !open)
        .attr("aria-hidden", open ? "false" : "true");
      toggle.attr("aria-expanded", open ? "true" : "false");
    }

    setNotificationMenuOpen(false);

    toggle.on("click.directoristTeNotifications", function (event) {
      event.preventDefault();
      event.stopPropagation();
      setNotificationMenuOpen(dropdown.prop("hidden"));
    });

    menu.on(
      "click.directoristTeNotifications",
      ".directorist-te-notification-item",
      function (event) {
        event.preventDefault();

        const type = $(this).attr("data-notification-type") || "all";
        const status = $(this).attr("data-notification-status") || "all";
        const typeTab = $(
          `.directorist-te-tab[data-filter-type="${type}"]`,
        ).first();
        const statusFilter = $(
          `.directorist-te-segmented button[data-filter-status="${status}"]`,
        ).first();
        const toolbar = $("#atbdp-themes-extensions-contents").first();

        setNotificationMenuOpen(false);
        $('[data-directorist-te-view-target="addons"]')
          .first()
          .trigger("click");
        $(".directorist-te-search-input").val("");
        state.query = "";

        if (typeTab.length) {
          typeTab.trigger("click");
        }

        if (statusFilter.length) {
          statusFilter.trigger("click");
        }

        const highlightedControls = typeTab.add(statusFilter);

        highlightedControls
          .removeClass("directorist-te-filter-focus")
          .addClass("directorist-te-filter-focus");

        window.setTimeout(function () {
          highlightedControls.removeClass("directorist-te-filter-focus");
        }, 1400);

        if (toolbar.length) {
          toolbar[0].scrollIntoView({
            block: "start",
            behavior: window.matchMedia("(prefers-reduced-motion: reduce)")
              .matches
              ? "auto"
              : "smooth",
          });
        }

        (statusFilter.length ? statusFilter : typeTab).trigger("focus");
      },
    );

    $(document).on("click.directoristTeNotifications", function (event) {
      if ($(event.target).closest(menu).length) {
        return;
      }

      setNotificationMenuOpen(false);
    });

    $(document).on("keydown.directoristTeNotifications", function (event) {
      if ("Escape" !== event.key || dropdown.prop("hidden")) {
        return;
      }

      setNotificationMenuOpen(false);
      toggle.trigger("focus");
    });
  }

  function setupRefreshPurchasePanel() {
    const panel = $(".directorist-te-refresh-panel");
    const triggerWrapper = $(".purchase-refresh-btn-wrapper");
    const isAccountDropdown = Boolean(
      panel.closest(".directorist-te-account-dropdown").length,
    );

    if (!panel.length || !triggerWrapper.length) {
      return;
    }

    if (isAccountDropdown) {
      panel.stop(true, true).removeAttr("style");
      triggerWrapper.stop(true, true).removeAttr("style");
    }

    setRefreshPanelHidden(panel, true);
    setRefreshTriggerHidden(triggerWrapper, false);

    $(".purchase-refresh-btn")
      .off("click")
      .on("click.directoristTeRefresh", function (event) {
        event.preventDefault();

        const wrapper = $(this).parent();

        if (isAccountDropdown) {
          event.stopImmediatePropagation();
          panel.stop(true, true).removeAttr("style");
          wrapper.stop(true, true).removeAttr("style");
        }

        setRefreshPanelHidden(panel, false);
        setRefreshTriggerHidden(wrapper, true);

        if (panel.closest(".directorist-te-account-dropdown").length) {
          panel.find('input[name="password"]').trigger("focus");
          return;
        }

        const targetWidth = Math.min(
          panel.find(".directorist-te-refresh-form").outerWidth() || 330,
          panel.parent().width() || 330,
        );

        wrapper.stop(true, true).animate({ width: 0 }, 250);
        panel
          .stop(true, true)
          .css({ width: 0 })
          .animate({ width: targetWidth }, 250, function () {
            panel.css("width", "");
            panel.find('input[name="password"]').trigger("focus");
          });
      });

    $(".et-close-auth-btn")
      .off("click")
      .on("click.directoristTeRefresh", function (event) {
        event.preventDefault();

        const wrapper = $(".purchase-refresh-btn-wrapper");
        const trigger = wrapper.find(".purchase-refresh-btn").first();
        const targetWidth = trigger.outerWidth() || 160;
        const accountMenu = panel.closest(".directorist-te-account-menu");

        if (accountMenu.length) {
          event.stopImmediatePropagation();
          panel.stop(true, true).removeAttr("style");
          wrapper.stop(true, true).removeAttr("style");
          accountMenu.trigger("directoristTeRefreshReset");
          panel.find('input[name="password"]').val("");
          panel.find(".directorist-te-feedback").empty();
          setRefreshTriggerHidden(wrapper, false);

          if (trigger.length) {
            trigger[0].focus({ preventScroll: true });
          }

          setRefreshPanelHidden(panel, true);
          return;
        }

        panel.stop(true, true).animate({ width: 0 }, 250, function () {
          panel.find('input[name="password"]').val("");
          panel.css("width", "");
          setRefreshPanelHidden(panel, true);
        });

        setRefreshTriggerHidden(wrapper, false);
        wrapper
          .stop(true, true)
          .css({ width: 0 })
          .animate({ width: targetWidth }, 250, function () {
            wrapper.css("width", "");
            trigger.trigger("focus");
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
    $(".ext-action-drop").removeClass("active");
  }

  function setupViewSwitcher() {
    const switchers = $("[data-directorist-te-view-target]");
    const views = $("[data-directorist-te-view]");

    if (!switchers.length || !views.length) {
      return;
    }

    function setActiveView(target, syncUrl) {
      const targetView = views.filter(function () {
        return $(this).attr("data-directorist-te-view") === target;
      });

      if (!targetView.length) {
        return;
      }

      state.view = target;

      views.each(function () {
        const isActive = $(this).attr("data-directorist-te-view") === target;

        $(this)
          .prop("hidden", !isActive)
          .attr("aria-hidden", isActive ? "false" : "true")
          .toggleClass("is-active", isActive);
      });

      switchers.each(function () {
        const isActive =
          $(this).attr("data-directorist-te-view-target") === target;

        $(this).toggleClass("active", isActive);

        if (isActive) {
          $(this).attr("aria-current", "page");
          return;
        }

        $(this).removeAttr("aria-current");
      });

      closeProductMenus();

      if (target === "dashboard") {
        clearProductSelection();
      }

      if (target === "addons") {
        updateFilters();
      }

      if (syncUrl !== false) {
        syncPageStateUrl();
      }

      syncWordPressSubmenuState();
      page.trigger("directoristTeViewChange", [target]);
    }

    switchers.on("click.directoristTeView", function (event) {
      event.preventDefault();
      setActiveView($(this).attr("data-directorist-te-view-target"));
    });

    setActiveView(state.view, false);
    syncPageStateUrl();
  }

  function setupDashboardActivity() {
    const drawer = $("[data-directorist-te-activity-drawer]").first();
    const panel = drawer.find(".directorist-te-activity-drawer__panel");
    const openButton = $("[data-activity-drawer-open]").first();
    const closeButtons = drawer.find("[data-activity-drawer-close]");
    const list = drawer.find("[data-activity-drawer-list]");
    const stateRegion = drawer.find("[data-activity-drawer-state]");
    const filters = drawer.find("[data-activity-filter]");
    const loadMoreButton = drawer.find("[data-activity-load-more]");
    let activityType = "all";
    let nextPage = 1;
    let hasMore = false;
    let isLoading = false;
    let loadedItems = [];
    let restoreFocus = null;
    const labels = {
      loading: drawer.attr("data-loading-label") || "Loading activity...",
      loadingMore:
        drawer.attr("data-loading-more-label") || "Loading more activity...",
      emptyTitle: drawer.attr("data-empty-title") || "No activity found",
      emptyMessage:
        drawer.attr("data-empty-message") ||
        "There is no Directorist activity in this category yet.",
      errorTitle: drawer.attr("data-error-title") || "Unable to load activity",
      errorMessage:
        drawer.attr("data-error-message") ||
        "Activity could not be loaded. Close the panel and try again.",
    };

    if (!drawer.length || !panel.length || !openButton.length) {
      return;
    }

    function activityFocusable() {
      return panel
        .find("a, button, input, select, textarea, [tabindex]")
        .filter(":visible")
        .filter(function () {
          return !this.disabled && $(this).attr("tabindex") !== "-1";
        });
    }

    function setDrawerOpen(open) {
      drawer.prop("hidden", !open).attr("aria-hidden", open ? "false" : "true");
      $("body").toggleClass("directorist-te-activity-drawer-open", open);

      if (open) {
        restoreFocus = document.activeElement;
        panel.find("[data-activity-drawer-close]").first().trigger("focus");

        if (!loadedItems.length && !isLoading) {
          loadActivity(true);
        }

        return;
      }

      if (restoreFocus && document.contains(restoreFocus)) {
        $(restoreFocus).trigger("focus");
      }
    }

    function activityItemHtml(item) {
      const subject = item.subject ? `<b>${escapeHtml(item.subject)}</b>` : "";
      const context = item.context
        ? `<span>${escapeHtml(item.context)}</span>`
        : "";
      const action =
        item.action_url && item.action_label
          ? `<a class="directorist-te-btn directorist-te-btn--soft" href="${escapeHtml(
              item.action_url,
            )}">${escapeHtml(item.action_label)}</a>`
          : "";

      return `
                <article class="directorist-te-activity-drawer__item">
                    <span class="directorist-te-dashboard-activity-icon directorist-te-dashboard-activity-icon--${escapeHtml(
                      item.tone || "blue",
                    )}">
                        <i class="${escapeHtml(
                          item.icon || "la la-history",
                        )}" aria-hidden="true"></i>
                    </span>
                    <div class="directorist-te-dashboard-activity-copy">
                        <strong>${escapeHtml(item.title || "")}</strong>
                        <span class="directorist-te-dashboard-activity-summary">${subject}${context}</span>
                        <small><i class="la la-clock" aria-hidden="true"></i>${escapeHtml(
                          item.time_label || "",
                        )}</small>
                    </div>
                    ${action}
                </article>
            `;
    }

    function renderActivity() {
      if (!loadedItems.length) {
        list.html(
          `<div class="directorist-te-activity-drawer__empty">
                        <i class="la la-check-circle" aria-hidden="true"></i>
                        <strong>${escapeHtml(labels.emptyTitle)}</strong>
                        <p>${escapeHtml(labels.emptyMessage)}</p>
                    </div>`,
        );
        return;
      }

      const groupedItems = [];
      const groupsByKey = {};

      loadedItems.forEach((item) => {
        const key = item.group || "earlier";

        if (!groupsByKey[key]) {
          groupsByKey[key] = {
            key,
            label: item.group_label || "Earlier",
            items: [],
          };
          groupedItems.push(groupsByKey[key]);
        }

        groupsByKey[key].items.push(item);
      });

      list.html(
        groupedItems
          .map(
            (group) => `
                            <section class="directorist-te-activity-drawer__group" data-activity-group="${escapeHtml(
                              group.key,
                            )}">
                                <h3>${escapeHtml(group.label)}</h3>
                                <div>${group.items
                                  .map(activityItemHtml)
                                  .join("")}</div>
                            </section>
                        `,
          )
          .join(""),
      );
    }

    function mergeItems(items) {
      const itemsById = new Map(
        loadedItems.map((item) => [String(item.id), item]),
      );

      items.forEach((item) => {
        if (item?.id) {
          itemsById.set(String(item.id), item);
        }
      });

      loadedItems = Array.from(itemsById.values());
    }

    function setLoading(loading) {
      isLoading = loading;
      drawer.attr("aria-busy", loading ? "true" : "false");
      filters.prop("disabled", loading);
      loadMoreButton.prop("disabled", loading);

      if (loading) {
        stateRegion.text(nextPage > 1 ? labels.loadingMore : labels.loading);
        return;
      }

      stateRegion.empty();
    }

    function loadActivity(reset) {
      if (isLoading) {
        return;
      }

      if (reset) {
        nextPage = 1;
        hasMore = false;
        loadedItems = [];
        list.empty();
        loadMoreButton.prop("hidden", true);
      }

      setLoading(true);

      $.ajax({
        type: "post",
        url: ajaxUrl(),
        dataType: "json",
        data: {
          action: "directorist_te_get_activity",
          nonce: nonce(),
          activity_page: nextPage,
          activity_type: activityType,
        },
      })
        .done(function (response) {
          if (!response?.success || !response.data) {
            stateRegion.text(response?.data?.message || labels.errorMessage);
            return;
          }

          mergeItems(
            Array.isArray(response.data.items) ? response.data.items : [],
          );
          hasMore = Boolean(response.data.has_more);
          nextPage = Number(response.data.next_page) || nextPage + 1;
          renderActivity();
          loadMoreButton.prop("hidden", !hasMore);
        })
        .fail(function () {
          stateRegion.text(labels.errorMessage);

          if (!loadedItems.length) {
            list.html(
              `<div class="directorist-te-activity-drawer__empty directorist-te-activity-drawer__empty--error">
                                <i class="la la-exclamation-circle" aria-hidden="true"></i>
                                <strong>${escapeHtml(labels.errorTitle)}</strong>
                                <p>${escapeHtml(labels.errorMessage)}</p>
                            </div>`,
            );
          }
        })
        .always(function () {
          setLoading(false);
        });
    }

    openButton.on("click.directoristTeActivity", function () {
      setDrawerOpen(true);
    });

    closeButtons.on("click.directoristTeActivity", function () {
      setDrawerOpen(false);
    });

    filters.on("click.directoristTeActivity", function () {
      activityType = $(this).attr("data-activity-filter") || "all";
      filters.removeClass("is-active").attr("aria-pressed", "false");
      $(this).addClass("is-active").attr("aria-pressed", "true");
      loadActivity(true);
    });

    loadMoreButton.on("click.directoristTeActivity", function () {
      if (hasMore) {
        loadActivity(false);
      }
    });

    page.on("directoristTeViewChange", function (event, target) {
      if (target !== "dashboard" && !drawer.prop("hidden")) {
        setDrawerOpen(false);
      }
    });

    $(document).on("keydown.directoristTeActivity", function (event) {
      if (drawer.prop("hidden")) {
        return;
      }

      if (event.key === "Escape") {
        event.preventDefault();
        setDrawerOpen(false);
        return;
      }

      if (event.key !== "Tab") {
        return;
      }

      const focusable = activityFocusable();

      if (!focusable.length) {
        event.preventDefault();
        return;
      }

      const first = focusable.first()[0];
      const last = focusable.last()[0];

      if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        $(last).trigger("focus");
      } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        $(first).trigger("focus");
      }
    });

    setDrawerOpen(false);
  }

  function setupRequiredExtensionsCompatibility() {
    const legacyHash = "#atbdp-required-extensions-form";

    function showRequiredExtensions() {
      if (window.location.hash !== legacyHash) {
        return;
      }

      const requiredFilter = $(
        '.directorist-te-segmented button[data-filter-status="required"]',
      ).first();
      const legacyTarget = $("#atbdp-required-extensions-form").first();
      const extensionTab = $(
        '.directorist-te-tab[data-filter-type="extension"]',
      ).first();
      const isConnected = page.hasClass("directorist-te-page--connected");

      if (isConnected) {
        $('[data-directorist-te-view-target="addons"]')
          .first()
          .trigger("click");
        $(".directorist-te-search-input").val("");
        state.query = "";
        extensionTab.trigger("click");
      }

      if (!requiredFilter.length) {
        const fallbackTarget = legacyTarget.length
          ? legacyTarget
          : $(".directorist-te-connect").first();

        if (!fallbackTarget.length) {
          return;
        }

        window.setTimeout(function () {
          fallbackTarget[0].scrollIntoView({
            block: "start",
            behavior: "auto",
          });

          fallbackTarget
            .find('input[name="username"]')
            .first()
            .trigger("focus");

          if (isConnected) {
            extensionTab.trigger("focus");
          }
        }, 0);

        return;
      }

      requiredFilter.trigger("click");

      window.setTimeout(function () {
        const scrollTarget = legacyTarget.length
          ? legacyTarget[0]
          : requiredFilter[0];

        scrollTarget.scrollIntoView({
          block: "start",
          behavior: "auto",
        });
        requiredFilter.trigger("focus");
      }, 100);
    }

    $(window).on(
      "hashchange.directoristTeRequiredExtensions",
      showRequiredExtensions,
    );
    showRequiredExtensions();
  }

  function setupDashboardRecommendations() {
    const section = $("[data-directorist-te-recommendations]").first();

    if (!section.length) {
      return;
    }

    const groups = section.find("[data-recommendation-group]");
    const previousButton = section.find("[data-recommendation-previous]");
    const nextButton = section.find("[data-recommendation-next]");
    const directorySelect = section.find(
      "[data-recommendation-directory-select]",
    );
    const heading = section.find("[data-recommendation-heading]");
    const description = section.find("[data-recommendation-description]");
    const liveRegion = section.find("[data-recommendation-live]");
    const headingTemplate =
      section.attr("data-heading-template") || "Recommended for %s";
    const directoryIds = groups
      .map(function () {
        return String($(this).attr("data-recommendation-group") || "");
      })
      .get()
      .filter(Boolean);
    const cardOffsets = {};
    const paintedDirectories = {};
    const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
    const rotationInterval = Math.max(
      4000,
      Number(section.attr("data-rotation-interval")) || 6000,
    );
    let selectedId =
      String(section.attr("data-default-directory") || "") || directoryIds[0];
    let autoplayTimer = 0;
    let autoplayPaused = reducedMotion.matches;

    function getGroup(directoryId) {
      return groups.filter(function () {
        return (
          String($(this).attr("data-recommendation-group")) ===
          String(directoryId)
        );
      });
    }

    function paintCards(group, directoryId, advance) {
      const cards = group.find("[data-recommendation-card]");
      const cardCount = cards.length;
      let offset = cardOffsets[directoryId] || 0;

      cards.prop("hidden", true);

      if (!cardCount) {
        return;
      }

      if (advance && paintedDirectories[directoryId] && cardCount > 3) {
        offset = (offset + 3) % cardCount;
        cardOffsets[directoryId] = offset;
      }

      paintedDirectories[directoryId] = true;

      for (let index = 0; index < Math.min(3, cardCount); index += 1) {
        cards.eq((offset + index) % cardCount).prop("hidden", false);
      }
    }

    function selectDirectory(directoryId, announce, advanceCards) {
      const group = getGroup(directoryId);

      if (!group.length) {
        return;
      }

      selectedId = String(directoryId);

      groups.prop("hidden", true);
      group.prop("hidden", false);
      paintCards(group, selectedId, advanceCards);

      const name =
        String(group.attr("data-directory-name") || "").trim() ||
        "your directory";
      const summary = String(group.attr("data-directory-description") || "");

      heading.text(headingTemplate.replace("%s", name));
      description.text(summary);
      directorySelect.val(selectedId);

      if (announce) {
        liveRegion.text(`${name} recommendations shown.`);
      }
    }

    function moveDirectory(step, announce) {
      if (directoryIds.length > 1) {
        const currentIndex = directoryIds.indexOf(selectedId);
        const nextIndex =
          (currentIndex + step + directoryIds.length) % directoryIds.length;

        selectDirectory(directoryIds[nextIndex], announce, true);
        return;
      }

      selectDirectory(selectedId, announce, true);
    }

    function stopAutoplay() {
      window.clearInterval(autoplayTimer);
      autoplayTimer = 0;
    }

    function canAutoplay() {
      return (
        directoryIds.length > 1 ||
        groups.filter(function () {
          return $(this).find("[data-recommendation-card]").length > 3;
        }).length > 0
      );
    }

    function isInteracting() {
      return (
        section.is(":hover") ||
        section.find(":focus").length > 0 ||
        document.hidden
      );
    }

    function startAutoplay() {
      stopAutoplay();

      if (!canAutoplay() || autoplayPaused || isInteracting()) {
        return;
      }

      autoplayTimer = window.setInterval(function () {
        moveDirectory(1, false);
      }, rotationInterval);
    }

    if (!directoryIds.includes(selectedId)) {
      selectedId = directoryIds[0];
    }

    const hasMultipleDirectories = directoryIds.length > 1;

    previousButton.prop("hidden", !hasMultipleDirectories);
    nextButton.prop("hidden", !hasMultipleDirectories);
    directorySelect.prop("hidden", !hasMultipleDirectories);
    selectDirectory(selectedId, false, false);
    startAutoplay();

    previousButton.on("click.directoristTeRecommendations", function () {
      moveDirectory(-1, true);
      startAutoplay();
    });

    nextButton.on("click.directoristTeRecommendations", function () {
      moveDirectory(1, true);
      startAutoplay();
    });

    directorySelect.on("change.directoristTeRecommendations", function () {
      selectDirectory(String($(this).val() || ""), true, true);
      startAutoplay();
    });

    section.on(
      "mouseenter.directoristTeRecommendations focusin.directoristTeRecommendations",
      stopAutoplay,
    );
    section.on(
      "mouseleave.directoristTeRecommendations focusout.directoristTeRecommendations",
      function () {
        window.setTimeout(startAutoplay, 0);
      },
    );

    $(document).on(
      "visibilitychange.directoristTeRecommendations",
      startAutoplay,
    );

    const handleMotionPreference = function (event) {
      autoplayPaused = event.matches;
      startAutoplay();
    };

    if (reducedMotion.addEventListener) {
      reducedMotion.addEventListener("change", handleMotionPreference);
    } else {
      reducedMotion.addListener(handleMotionPreference);
    }
  }

  function setupDashboardQuickActions() {
    const card = $("[data-directorist-te-quick-actions]").first();
    const directorySelect = card.find("[data-quick-actions-directory-select]");

    if (!card.length || !directorySelect.length) {
      return;
    }

    const storageKey = "directorist_te_quick_actions_directory";
    const liveRegion = card.find("[data-quick-actions-live]");
    const actionAttributes = {
      "add-listing": {
        url: "data-add-listing-url",
        ariaLabel: "data-add-listing-aria-label",
        description: "data-add-listing-description",
      },
      "manage-categories": {
        url: "data-manage-categories-url",
        ariaLabel: "data-manage-categories-aria-label",
      },
      "listing-layout": {
        url: "data-listing-layout-url",
        ariaLabel: "data-listing-layout-aria-label",
      },
      "submission-form": {
        url: "data-submission-form-url",
        ariaLabel: "data-submission-form-aria-label",
      },
    };

    function selectedOption() {
      return directorySelect.find("option:selected").first();
    }

    function updateActions(announce) {
      const option = selectedOption();

      if (!option.length) {
        return;
      }

      Object.entries(actionAttributes).forEach(([key, attributes]) => {
        const action = card.find(`[data-quick-action="${key}"]`);
        const url = option.attr(attributes.url);
        const ariaLabel = option.attr(attributes.ariaLabel);

        if (!action.length || !url) {
          return;
        }

        action.attr("href", url);

        if (ariaLabel) {
          action.attr("aria-label", ariaLabel);
        }

        if (attributes.description) {
          const description = option.attr(attributes.description);

          if (description) {
            action.find("em").text(description);
          }
        }
      });

      if (!announce || !liveRegion.length) {
        return;
      }

      const directoryName = String(
        option.attr("data-directory-name") || option.text(),
      )
        .replace(/\s+/g, " ")
        .trim();
      const messageTemplate =
        card.attr("data-directory-change-message") ||
        "Quick actions now use %s.";

      liveRegion.text(messageTemplate.replace("%s", directoryName));
    }

    try {
      const storedDirectory = window.sessionStorage.getItem(storageKey);
      const storedOption = directorySelect.find("option").filter(function () {
        return String(this.value) === storedDirectory;
      });

      if (storedOption.length) {
        directorySelect.val(storedDirectory);
      }
    } catch (error) {}

    updateActions(false);

    directorySelect.on("change.directoristTeQuickActions", function () {
      try {
        window.sessionStorage.setItem(storageKey, this.value);
      } catch (error) {}

      updateActions(true);
    });
  }

  function setupDashboardPreview() {
    const dashboard = $(".directorist-te-dashboard");
    const nudge = dashboard.find(".directorist-te-dashboard-nudge");
    const steps = dashboard.find(".directorist-te-dashboard-step");
    const ring = dashboard.find(".directorist-te-dashboard-ring circle").last();
    const ringLabel = dashboard.find(".directorist-te-dashboard-ring span");
    const dismissKey = String(nudge.attr("data-dismiss-key") || "");
    const radius = 19;
    const circumference = 2 * Math.PI * radius;

    if (!dashboard.length || !nudge.length) {
      return;
    }

    if (dismissKey) {
      try {
        if (window.localStorage.getItem(dismissKey) === "1") {
          nudge.prop("hidden", true).attr("aria-hidden", "true");
          return;
        }
      } catch (error) {}
    }

    function paintProgress() {
      if (!steps.length || !ring.length || !ringLabel.length) {
        return;
      }

      const done = steps.filter(".is-done").length;
      const percent = Math.round((done / steps.length) * 100);

      ring.removeAttr(
        "visibility stroke-linecap stroke-dasharray stroke-dashoffset",
      );

      if (percent === 0) {
        ring.attr("visibility", "hidden");
      } else if (percent < 100) {
        ring
          .attr("stroke-linecap", "round")
          .attr("stroke-dasharray", circumference.toFixed(1))
          .attr(
            "stroke-dashoffset",
            (circumference * (1 - percent / 100)).toFixed(1),
          );
      }

      ringLabel.text(`${percent}%`);
    }

    paintProgress();

    dashboard
      .find(".directorist-te-dashboard-nudge__dismiss")
      .on("click.directoristTeDashboard", function () {
        nudge.prop("hidden", true).attr("aria-hidden", "true");

        if (!dismissKey) {
          return;
        }

        try {
          window.localStorage.setItem(dismissKey, "1");
        } catch (error) {}
      });

    steps.on("click.directoristTeDashboard", function () {
      if ($(this).hasClass("is-done")) {
        return;
      }

      $(this).addClass("is-done");
      paintProgress();
    });
  }

  const productActionConfig = {
    install: {
      progress: "Installing",
      success: "Installed",
      past: "installed",
    },
    update: { progress: "Updating", success: "Updated", past: "updated" },
    activate: { progress: "Activating", success: "Active", past: "activated" },
    deactivate: {
      progress: "Deactivating",
      success: "Inactive",
      past: "deactivated",
    },
    uninstall: {
      progress: "Deleting",
      success: "Deleted",
      past: "deleted",
      verb: "delete",
    },
  };

  function actionFailureMessage(response, fallback) {
    if (!response || typeof response !== "object") {
      return fallback;
    }

    if (response?.success === false) {
      if (typeof response.data === "string") {
        return response.data || fallback;
      }

      return response?.data?.message || fallback;
    }

    if (response?.status?.success === false) {
      return response.status.message || fallback;
    }

    return response?.success === true || response?.status?.success === true
      ? ""
      : fallback;
  }

  function normalizeActionItems(action, items) {
    const seen = new Set();

    return items.filter((item) => {
      const type = item?.type;
      const key = String(item?.item || "").trim();
      const id = `${type}:${key}`;
      const supportsType =
        ["install", "update"].includes(action) || type === "plugin";

      if (
        !["plugin", "theme"].includes(type) ||
        !supportsType ||
        !key ||
        seen.has(id)
      ) {
        return false;
      }

      seen.add(id);
      return true;
    });
  }

  function actionRequestData(action, item) {
    if (action === "install") {
      return {
        action: "atbdp_install_file_from_subscriptions",
        item_key: item.item,
        type: item.type,
        nonce: nonce(),
      };
    }

    if (action === "update") {
      return item.type === "theme"
        ? {
            action: "atbdp_update_theme",
            theme_stylesheet: item.item,
            nonce: nonce(),
          }
        : {
            action: "atbdp_update_plugins",
            plugin_key: item.item,
            nonce: nonce(),
          };
    }

    return {
      action: "atbdp_plugins_bulk_action",
      task: action,
      plugin_items: [item.item],
      directorist_nonce: directoristNonce(),
    };
  }

  function productItemRow(item) {
    if (item?.row) {
      return $(item.row);
    }

    return rows()
      .filter(function () {
        const control = $(this).find("[data-bulk-item]").first();

        return (
          control.attr("data-bulk-item") === item.item &&
          (control.attr("data-bulk-type") || "plugin") === item.type
        );
      })
      .first();
  }

  function setProductItemState(item, stateName, label) {
    const row = productItemRow(item);

    if (!row.length) {
      return;
    }

    const statusCell = row.find(".directorist-te-row__status").first();
    let queueStatus = statusCell.find(".directorist-te-queue-status");
    const iconClasses = {
      waiting: "la la-clock",
      current: "la la-spinner la-spin",
      success: "la la-check-circle",
      failed: "la la-exclamation-circle",
      skipped: "la la-forward",
    };

    if (!queueStatus.length) {
      queueStatus = $("<span>", {
        class: "directorist-te-queue-status",
        role: "status",
        "aria-live": "polite",
      });
      statusCell.append(queueStatus);
    }

    statusCell.find(".directorist-te-status").prop("hidden", true);
    queueStatus
      .attr(
        "class",
        `directorist-te-queue-status directorist-te-queue-status--${stateName}`,
      )
      .empty()
      .append(
        $("<i>", {
          class: iconClasses[stateName] || iconClasses.waiting,
          "aria-hidden": "true",
        }),
        $("<span>").text(label),
      );
    row
      .attr("aria-busy", stateName === "current" ? "true" : "false")
      .removeClass(
        "is-queue-waiting is-queue-current is-queue-success is-queue-failed is-queue-skipped",
      )
      .addClass(`is-queue-${stateName}`);
  }

  function setProductQueueLock(isLocked) {
    productQueueRunning = isLocked;
    page
      .toggleClass("is-product-queue-running", isLocked)
      .attr("aria-busy", isLocked ? "true" : "false");
    selectableChecks().add("#select-all-installed").prop("disabled", isLocked);

    page
      .find(
        ".directorist-te-bulk-action, .directorist-te-update-all, .file-install-btn, .ext-update-btn, .theme-update-btn, .plugin-active-btn, .directorist-te-single-plugin-task, .ext-action-uninstall",
      )
      .attr("aria-disabled", isLocked ? "true" : null);
  }

  function actionResultDescription(result) {
    const parts = [];
    const action =
      productActionConfig[result.action] || productActionConfig.update;
    const actionVerb = action.verb || result.action;

    if (result.failedLabels.length) {
      const failedLabels = result.failedLabels.slice(0, 3).join(", ");
      const moreCount = Math.max(result.failedLabels.length - 3, 0);

      parts.push(
        `Could not ${actionVerb}: ${failedLabels}${moreCount ? ` and ${moreCount} more` : ""}.`,
      );
    }

    if (result.skipped) {
      parts.push(
        `${result.skipped} remaining ${result.skipped === 1 ? "item was" : "items were"} skipped after a network or server interruption.`,
      );
    }

    if (!parts.length) {
      parts.push(
        `WordPress product state was rechecked after each item was ${action.past}.`,
      );
    } else {
      parts.push("Review the current product states before trying again.");
    }

    return parts.join(" ");
  }

  function renderActionResult(result) {
    const total = Number(result?.total) || 0;
    const succeeded = Number(result?.succeeded) || 0;
    const failed = Number(result?.failed) || 0;
    const skipped = Number(result?.skipped) || 0;
    const actionKey = productActionConfig[result?.action]
      ? result.action
      : "update";
    const action = productActionConfig[actionKey];

    if (!total || succeeded + failed + skipped < 1) {
      return;
    }

    const normalizedResult = {
      action: actionKey,
      total,
      succeeded,
      failed,
      skipped,
      failedLabels: Array.isArray(result.failedLabels)
        ? result.failedLabels.map(String).filter(Boolean)
        : [],
    };
    const isSuccess = failed === 0 && skipped === 0;
    const title = isSuccess
      ? `${succeeded} ${succeeded === 1 ? "item" : "items"} ${action.past}`
      : `${succeeded} completed, ${failed} failed${skipped ? `, ${skipped} skipped` : ""}`;
    const resultBanner = $("<section>", {
      class: `directorist-te-update-banner directorist-te-action-result ${
        isSuccess
          ? "directorist-te-action-result--success"
          : "directorist-te-action-result--partial"
      }`,
      role: "status",
      "aria-live": "polite",
    });
    const icon = $("<div>", {
      class: "directorist-te-update-icon",
      "aria-hidden": "true",
    }).append(
      $("<i>", {
        class: isSuccess ? "la la-check-circle" : "la la-exclamation-circle",
      }),
    );
    const content = $("<div>").append(
      $("<h2>").text(title),
      $("<p>").text(actionResultDescription(normalizedResult)),
    );

    resultBanner.append(icon, content);

    const anchor = updateBanner().first();

    if (anchor.length) {
      anchor.before(resultBanner);
      return;
    }

    $(".directorist-te-toolbar").first().before(resultBanner);
  }

  function storeActionResult(result) {
    try {
      window.sessionStorage.setItem(
        actionResultStorageKey,
        JSON.stringify(result),
      );
      return true;
    } catch (error) {
      return false;
    }
  }

  function renderStoredActionResult() {
    let storedResult = "";

    try {
      storedResult =
        window.sessionStorage.getItem(actionResultStorageKey) || "";
      window.sessionStorage.removeItem(actionResultStorageKey);
    } catch (error) {
      return;
    }

    if (!storedResult) {
      return;
    }

    try {
      renderActionResult(JSON.parse(storedResult));
    } catch (error) {}
  }

  function setActionProgress(actionKey, button, current, total, item) {
    const action = productActionConfig[actionKey];
    const progressLabel = `${action.progress} ${current} of ${total}`;
    const rowActionSelectors = {
      install: ".file-install-btn",
      update: ".ext-update-btn, .theme-update-btn",
      activate: ".plugin-active-btn",
    };
    const rowAction = rowActionSelectors[actionKey]
      ? productItemRow(item).find(rowActionSelectors[actionKey]).first()
      : $();

    setButtonLoading(button, progressLabel);
    if (rowAction.length) {
      setButtonLoading(rowAction, progressLabel);
    }
    setProductItemState(item, "current", progressLabel);

    if (actionKey !== "update") {
      return;
    }

    const banner = updateBanner().first();

    if (!banner.length || banner.prop("hidden")) {
      return;
    }

    banner.attr({ role: "status", "aria-live": "polite" });
    banner.find("h2").text(progressLabel);
    banner
      .find("p")
      .text(
        `${item.label || item.item} is being ${action.progress.toLowerCase()}. Keep this page open.`,
      );
  }

  function runProductActionQueue(actionKey, items, button) {
    const action = productActionConfig[actionKey];
    const queue = normalizeActionItems(actionKey, items);

    if (!action || !queue.length || productQueueRunning) {
      return;
    }

    const total = queue.length;
    const result = {
      action: actionKey,
      total,
      succeeded: 0,
      failed: 0,
      skipped: 0,
      failedLabels: [],
    };
    let completed = 0;

    queue.forEach((item) => setProductItemState(item, "waiting", "Waiting"));
    setProductQueueLock(true);

    const finish = () => {
      const stored = storeActionResult(result);

      if (!stored) {
        renderActionResult(result);
        setButtonLoading(button, "Reloading");
        window.setTimeout(() => window.location.reload(), 1200);
        return;
      }

      window.location.reload();
    };

    const next = () => {
      const item = queue.shift();

      if (!item) {
        finish();
        return;
      }

      setActionProgress(actionKey, button, completed + 1, total, item);

      $.ajax({
        type: "post",
        url: ajaxUrl(),
        data: actionRequestData(actionKey, item),
        success(response) {
          const failureMessage = actionFailureMessage(
            response,
            `${action.progress} failed.`,
          );

          if (failureMessage) {
            result.failed += 1;
            result.failedLabels.push(item.label || item.item);
            setProductItemState(item, "failed", "Failed");
          } else {
            result.succeeded += 1;
            setProductItemState(item, "success", action.success);
          }

          completed += 1;
          next();
        },
        error() {
          result.failed += 1;
          result.failedLabels.push(item.label || item.item);
          setProductItemState(item, "failed", "Failed");
          result.skipped += queue.length;
          queue.forEach((queuedItem) =>
            setProductItemState(queuedItem, "skipped", "Skipped"),
          );
          queue.length = 0;
          finish();
        },
      });
    };

    next();
  }

  renderStoredActionResult();

  $(".directorist-te-tab").on("click", function () {
    $(".directorist-te-tab").removeClass("is-active");
    $(this).addClass("is-active");
    state.type = $(this).data("filter-type") || "all";
    updateFilters();
    syncPageStateUrl();
  });

  $(".directorist-te-segmented button").on("click", function () {
    $(".directorist-te-segmented button").removeClass("is-active");
    $(this).addClass("is-active");
    state.status = $(this).data("filter-status") || "all";
    updateFilters();
  });

  $(".directorist-te-search-input").on("input", function () {
    state.query = $(this).val();
    updateFilters();
  });

  $(".directorist-te-empty-reset").on("click", function () {
    resetCatalogFilters();
  });

  $("#atbdp-directorist-license-login-form.directorist-te-connect-form").on(
    "submit",
    function (event) {
      const form = $(this);
      const authMethod = connectAuthMethod(form);
      const username = form.find('input[name="username"]').first();
      const password = form.find('input[name="password"]').first();
      const accessKey = form.find('input[name="access_key"]').first();

      event.preventDefault();
      event.stopImmediatePropagation();

      if (form.attr("aria-busy") === "true") {
        return;
      }

      clearConnectFeedback(form);

      if (authMethod === "access_key" && !accessKey.val().trim()) {
        accessKey.attr("aria-invalid", "true").trigger("focus");
        renderConnectFeedback(
          form,
          "danger",
          formMessage(
            form,
            "access-key-required",
            "Enter your Directorist account access key.",
          ),
        );
        return;
      }

      if (authMethod === "account" && !username.val().trim()) {
        username.attr("aria-invalid", "true").trigger("focus");
        renderConnectFeedback(
          form,
          "danger",
          formMessage(
            form,
            "username-required",
            "Enter your Directorist account username or email address.",
          ),
        );
        return;
      }

      if (authMethod === "account" && !password.val()) {
        password.attr("aria-invalid", "true").trigger("focus");
        renderConnectFeedback(
          form,
          "danger",
          formMessage(
            form,
            "password-required",
            "Enter your Directorist account password.",
          ),
        );
        return;
      }

      setConnectBusy(form, true);

      $.ajax({
        type: "post",
        url: ajaxUrl(),
        directoristTeOwned: true,
        data: {
          action: "atbdp_authenticate_the_customer",
          auth_method: authMethod,
          access_key: authMethod === "access_key" ? accessKey.val().trim() : "",
          username: authMethod === "account" ? username.val().trim() : "",
          password: authMethod === "account" ? password.val() : "",
          nonce: nonce(),
        },
        success(response) {
          if (
            response?.has_previous_subscriptions ||
            response?.status?.success
          ) {
            window.location.reload();
            return;
          }

          if (authHasRemoteConnectionError(response)) {
            renderConnectFeedback(
              form,
              "danger",
              formMessage(
                form,
                "network-error",
                "Could not reach Directorist.com. Please try again.",
              ),
            );
          } else {
            renderConnectFeedback(
              form,
              "danger",
              formMessage(
                form,
                authMethod === "access_key"
                  ? "invalid-access-key"
                  : "invalid-credentials",
                authMethod === "access_key"
                  ? "The access key is invalid. Check the key in your Directorist account and try again."
                  : "The username, email address, or password is incorrect. Please check your details and try again.",
              ),
            );
          }

          setConnectBusy(form, false);
        },
        error() {
          renderConnectFeedback(
            form,
            "danger",
            formMessage(
              form,
              "network-error",
              "Could not reach Directorist.com. Please try again.",
            ),
          );
          setConnectBusy(form, false);
        },
      });
    },
  );

  $(
    '#atbdp-directorist-license-login-form.directorist-te-connect-form input[name="username"], #atbdp-directorist-license-login-form.directorist-te-connect-form input[name="password"], #atbdp-directorist-license-login-form.directorist-te-connect-form input[name="access_key"]',
  ).on("input", function () {
    const form = $(this).closest(".directorist-te-connect-form");

    $(this).removeAttr("aria-invalid");

    if (!form.find('[aria-invalid="true"]').length) {
      connectFeedback(form).attr("role", "status").empty();
    }
  });

  $(".directorist-te-auth-methods [data-auth-method]").on("click", function () {
    const button = $(this);
    const form = button.closest(".directorist-te-connect-form");

    if (!form.length || form.attr("aria-busy") === "true") {
      return;
    }

    form
      .find('input[name="auth_method"]')
      .val(
        button.attr("data-auth-method") === "access_key"
          ? "access_key"
          : "account",
      );
    clearConnectFeedback(form);
    syncConnectAuthMethod(form, true);
  });

  $(".directorist-te-auth-methods [data-auth-method]").on(
    "keydown",
    function (event) {
      if (!["ArrowLeft", "ArrowRight", "Home", "End"].includes(event.key)) {
        return;
      }

      event.preventDefault();

      const buttons = $(this)
        .closest(".directorist-te-auth-methods")
        .find("[data-auth-method]:not(:disabled)");
      const currentIndex = buttons.index(this);
      let nextIndex = currentIndex;

      if (event.key === "Home") {
        nextIndex = 0;
      } else if (event.key === "End") {
        nextIndex = buttons.length - 1;
      } else if (event.key === "ArrowRight") {
        nextIndex = (currentIndex + 1) % buttons.length;
      } else {
        nextIndex = (currentIndex - 1 + buttons.length) % buttons.length;
      }

      buttons.eq(nextIndex).trigger("click").trigger("focus");
    },
  );

  $("#atbdp-directorist-license-login-form.directorist-te-connect-form").each(
    function () {
      syncConnectAuthMethod($(this), false);
    },
  );

  $(document).on("ajaxSuccess", function (_event, _xhr, settings, response) {
    if (settings?.directoristTeOwned || !isAuthRequest(settings)) {
      return;
    }

    const form = $(
      "#atbdp-directorist-license-login-form.directorist-te-connect-form",
    );
    const authMethod = connectAuthMethod(form);

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
        "danger",
        formMessage(
          form,
          "network-error",
          "Could not reach Directorist.com. Please try again.",
        ),
      );
    } else {
      renderConnectFeedback(
        form,
        "danger",
        formMessage(
          form,
          authMethod === "access_key"
            ? "invalid-access-key"
            : "invalid-credentials",
          authMethod === "access_key"
            ? "The access key is invalid. Check the key in your Directorist account and try again."
            : "The username, email address, or password is incorrect. Please check your details and try again.",
        ),
      );
    }

    setConnectBusy(form, false);
  });

  $(document).on("ajaxError", function (_event, _xhr, settings) {
    if (settings?.directoristTeOwned || !isAuthRequest(settings)) {
      return;
    }

    const form = $(
      "#atbdp-directorist-license-login-form.directorist-te-connect-form",
    );

    if (!form.length) {
      return;
    }

    renderConnectFeedback(
      form,
      "danger",
      formMessage(
        form,
        "network-error",
        "Could not reach Directorist.com. Please try again.",
      ),
    );
    setConnectBusy(form, false);
  });

  $(".directorist-te-password-toggle").on("click", function (event) {
    event.preventDefault();

    const button = $(this);
    const input = button
      .closest(".directorist-te-password-control")
      .find("input")
      .first();

    if (!input.length) {
      return;
    }

    const isHidden = input.attr("type") === "password";
    const nextLabel = isHidden
      ? button.data("hide-label")
      : button.data("show-label");

    input.attr("type", isHidden ? "text" : "password").trigger("focus");
    button.attr({
      "aria-label": nextLabel,
      "aria-pressed": isHidden ? "true" : "false",
    });
    button.find("i").toggleClass("la-eye", !isHidden);
    button.find("i").toggleClass("la-eye-slash", isHidden);
  });

  $("#atbdp-my-extensions-form").on(
    "change",
    ".directorist-te-select-checkbox",
    function () {
      setCheckbox(this, this.checked);
      updateBulkBar();
    },
  );

  $("#select-all-installed").on("change", function () {
    const checked = this.checked;

    visibleSelectableChecks().each(function () {
      setCheckbox(this, checked);
    });

    updateBulkBar();
  });

  $(".directorist-te-bulk-clear").on("click", function () {
    selectableChecks().each(function () {
      setCheckbox(this, false);
    });

    updateBulkBar();
  });

  $(document).on("keydown", function (event) {
    if (event.key !== "Escape" || !checkedSelectableChecks().length) {
      return;
    }

    selectableChecks().each(function () {
      setCheckbox(this, false);
    });

    $(".ext-action-drop").removeClass("active");
    updateBulkBar();
  });

  $(".directorist-te-bulk-action").on("click", function (event) {
    event.preventDefault();

    const task = $(this).data("task");
    const selectedItems = selectedBulkItems(task);
    const selectedCount = checkedSelectableChecks().length;
    const skippedCount = Math.max(selectedCount - selectedItems.length, 0);

    if (!task || !selectedItems.length) {
      return;
    }

    if (
      task === "uninstall" &&
      !window.confirm(
        `Delete ${itemCountText(selectedItems.length)} from this site: ${selectedItems
          .slice(0, 3)
          .map((item) => item.label || item.item)
          .join(
            ", ",
          )}${selectedItems.length > 3 ? ` and ${selectedItems.length - 3} more` : ""}. Their plugin files will be removed.` +
          (skippedCount
            ? ` ${itemCountText(skippedCount)} will be skipped.`
            : "") +
          " Continue?",
      )
    ) {
      return;
    }

    if (productActionConfig[task]) {
      runProductActionQueue(task, selectedItems, this);
    }
  });

  $(".directorist-te-single-plugin-task").on("click", function (event) {
    event.preventDefault();

    const task = $(this).data("task");
    const target = $(this).data("target");

    if (!task || !target) {
      return;
    }

    const row = $(this).closest(".directorist-te-row");

    runProductActionQueue(
      task,
      [
        {
          item: target,
          type: "plugin",
          label: row
            .find(".directorist-te-row__title h2")
            .first()
            .text()
            .trim(),
          row: row.get(0),
        },
      ],
      this,
    );
  });

  $(".directorist-te-update-all").on("click", function (event) {
    event.preventDefault();

    const button = this;
    const updateExtensions = $(button).data("update-extensions") === 1;
    const updateThemes = $(button).data("update-themes") === 1;
    const items = availableBulkItems("update").filter(
      (item) =>
        (item.type === "plugin" && updateExtensions) ||
        (item.type === "theme" && updateThemes),
    );

    if (!items.length) {
      return;
    }

    runProductActionQueue("update", items, button);
  });

  function actionItemFromControl(control, type) {
    const element = $(control);
    const row = element.closest(".directorist-te-row");
    const item = element.data("key") || element.data("target");

    if (!item) {
      return null;
    }

    return {
      item: String(item),
      type,
      label: row.find(".directorist-te-row__title h2").first().text().trim(),
      row: row.get(0),
    };
  }

  $(".directorist-te-menu-toggle").on("click", function (event) {
    event.preventDefault();
    event.stopPropagation();

    const wasOpen = $(this).hasClass("active");

    $(".ext-action-drop").removeClass("active");

    if (!wasOpen) {
      $(this).addClass("active");
    }
  });

  $(".directorist-te-menu__items").on("click", function (event) {
    event.stopPropagation();
  });

  $(".directorist-te-menu-link").on("click", function () {
    $(".ext-action-drop").removeClass("active");
  });

  $(document).on("click", function (event) {
    if ($(event.target).closest(".directorist-te-menu").length) {
      return;
    }

    $(".ext-action-drop").removeClass("active");
  });

  document.addEventListener(
    "click",
    function (event) {
      const productControl = event.target.closest(
        ".file-install-btn, .ext-update-btn, .theme-update-btn, .plugin-active-btn, .ext-action-uninstall",
      );

      if (productControl) {
        const isTheme =
          productControl.classList.contains("theme-update-btn") ||
          productControl.dataset.type === "theme";
        const item = actionItemFromControl(
          productControl,
          isTheme ? "theme" : "plugin",
        );

        if (item) {
          event.preventDefault();
          event.stopPropagation();
          event.stopImmediatePropagation();

          let action = "install";

          if (
            productControl.classList.contains("ext-update-btn") ||
            productControl.classList.contains("theme-update-btn")
          ) {
            action = "update";
          } else if (productControl.classList.contains("plugin-active-btn")) {
            action = "activate";
          } else if (
            productControl.classList.contains("ext-action-uninstall")
          ) {
            action = "uninstall";
          }

          if (
            action === "uninstall" &&
            !window.confirm(
              `Delete ${item.label || "this plugin"} from this site? Its files will be removed.`,
            )
          ) {
            return;
          }

          runProductActionQueue(action, [item], productControl);
          return;
        }
      }

      const menuToggle = event.target.closest(".directorist-te-menu-toggle");

      if (menuToggle) {
        event.preventDefault();
        event.stopPropagation();

        const wasOpen = menuToggle.classList.contains("active");

        document
          .querySelectorAll(".ext-action-drop.active")
          .forEach((toggle) => toggle.classList.remove("active"));

        if (!wasOpen) {
          menuToggle.classList.add("active");
        }

        return;
      }

      const themeButton = event.target.closest(".theme-activate-btn");

      if (themeButton && !themeButton.dataset.directoristConfirmed) {
        if (
          !window.confirm(
            "Activating this theme changes the live site theme. Continue?",
          )
        ) {
          event.preventDefault();
          event.stopPropagation();
          event.stopImmediatePropagation();
          return;
        }

        themeButton.dataset.directoristConfirmed = "1";
        window.setTimeout(() => {
          delete themeButton.dataset.directoristConfirmed;
        }, 1000);
      }
    },
    true,
  );

  selectableChecks().each(function () {
    setCheckbox(this, this.checked);
  });

  setupAccountMenu();
  setupNotificationMenu();
  setupRefreshPurchasePanel();
  setupViewSwitcher();
  setupDashboardActivity();
  setupRequiredExtensionsCompatibility();
  setupDashboardQuickActions();
  setupDashboardRecommendations();
  setupDashboardPreview();
  updateFilters();
})(jQuery);
