(function () {
  "use strict";

  // Keep the current compiled Vue view in sync until its source changes are built.
  document.addEventListener("directorist:settings-panel:mounted", function () {
    const panel = document.getElementById("atbdp-settings-manager");
    const browseUrl = panel && panel.dataset.extensionBrowseUrl;

    if (!browseUrl) {
      return;
    }

    function updateLink() {
      const link = panel.querySelector(".cptm-extension-promotion__button");

      if (link && link.href !== browseUrl) {
        link.href = browseUrl;
      }
    }

    updateLink();
    new MutationObserver(updateLink).observe(panel, {
      childList: true,
      attributes: true,
      attributeFilter: ["href"],
      subtree: true,
    });
  });
})();
