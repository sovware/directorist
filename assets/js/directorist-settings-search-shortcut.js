(function () {
  "use strict";

  const searchSelector =
    "#atbdp-settings-manager .settings-sidebar-search__input";
  const isMac = /Mac|iPhone|iPad|iPod/i.test(
    (navigator.userAgentData && navigator.userAgentData.platform) ||
      navigator.platform ||
      "",
  );
  let shortcutBound = false;

  function setupShortcut() {
    const input = document.querySelector(searchSelector);

    if (!input) {
      return;
    }

    input.setAttribute("aria-keyshortcuts", isMac ? "Meta+F" : "Control+F");

    const search = input.closest(".settings-sidebar-search");
    if (search && !search.querySelector(".settings-sidebar-search__shortcut")) {
      const hint = document.createElement("kbd");
      hint.className = "settings-sidebar-search__shortcut";
      hint.setAttribute("aria-hidden", "true");
      hint.innerHTML =
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" focusable="false"><rect x="2.5" y="5" width="19" height="14" rx="2"/><path d="M6.5 9h.01M10 9h.01M13.5 9h.01M17 9h.01M6.5 12h.01M10 12h.01M13.5 12h.01M17 12h.01M7 15.5h10"/></svg>' +
        "<span>" +
        (isMac ? "⌘ F" : "Ctrl F") +
        "</span>";
      search.appendChild(hint);
    }

    if (shortcutBound) {
      return;
    }

    shortcutBound = true;
    document.addEventListener("keydown", function (event) {
      const currentInput = document.querySelector(searchSelector);
      const target = event.target;
      const isEditable =
        target instanceof Element &&
        (target.matches("input, textarea, select") ||
          target.closest('[contenteditable], [role="textbox"]'));

      if (
        !currentInput ||
        !currentInput.getClientRects().length ||
        event.key.toLowerCase() !== "f" ||
        event.altKey ||
        event.shiftKey ||
        (isMac
          ? !event.metaKey || event.ctrlKey
          : !event.ctrlKey || event.metaKey) ||
        (isEditable && target !== currentInput) ||
        document.querySelector('[aria-modal="true"]')
      ) {
        return;
      }

      event.preventDefault();
      currentInput.focus();
      currentInput.select();
    });
  }

  document.addEventListener("directorist:settings-panel:mounted", setupShortcut);

  if (document.readyState === "complete") {
    setupShortcut();
  } else {
    window.addEventListener("load", setupShortcut);
  }
})();
