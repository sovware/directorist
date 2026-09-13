"use strict";

document.addEventListener("DOMContentLoaded", function () {
  let activePopup = null;
  let activeButton = null;
  let activeOverlay = null;
  let scrollPosition = 0;

  function setPageState(isOpen) {
    if (isOpen) {
      scrollPosition = window.scrollY;
      document.body.style.setProperty(
        "--directorist-search-popup-scroll-offset",
        `-${scrollPosition}px`,
      );
    }

    document.documentElement.classList.toggle(
      "directorist-search-popup-block-hidden",
      isOpen,
    );
    document.body.classList.toggle(
      "directorist-search-popup-block-hidden",
      isOpen,
    );

    if (isOpen) {
      return;
    }

    document.body.style.removeProperty(
      "--directorist-search-popup-scroll-offset",
    );
    window.scrollTo(0, scrollPosition);
  }

  function closeSearchPopup(restoreFocus = true) {
    if (!activePopup) {
      return;
    }

    const button = activeButton;
    activePopup.classList.remove("show");
    activePopup.setAttribute("aria-hidden", "true");
    activePopup.removeAttribute("style");
    activeOverlay?.classList.remove("show");
    button?.setAttribute("aria-expanded", "false");
    activePopup = null;
    activeButton = null;
    activeOverlay = null;
    setPageState(false);

    if (restoreFocus && button && document.contains(button)) {
      window.requestAnimationFrame(() => button.focus({ preventScroll: true }));
    }
  }

  function openSearchPopup(popup, overlay, button) {
    if (activePopup && activePopup !== popup) {
      closeSearchPopup(false);
    }

    activePopup = popup;
    activeButton = button;
    activeOverlay = overlay;
    popup.classList.add("show");
    popup.setAttribute("role", "dialog");
    popup.setAttribute("aria-modal", "true");
    popup.setAttribute("aria-hidden", "false");
    overlay.classList.add("show");
    button.setAttribute("aria-expanded", "true");
    setPageState(true);

    const focusTarget =
      popup.querySelector(
        'input:not([type="hidden"]):not([disabled]), select:not([disabled]), textarea:not([disabled])',
      ) || popup.querySelector("button:not([disabled]), a[href]");
    const focusSearchControl = () => {
      if (activePopup === popup) {
        focusTarget?.focus({ preventScroll: true });
      }
    };

    window.requestAnimationFrame(focusSearchControl);
    window.setTimeout(() => {
      if (document.activeElement !== focusTarget) {
        focusSearchControl();
      }
    }, 350);
  }

  document
    .querySelectorAll(".directorist-search-popup-block")
    .forEach((searchBlock, index) => {
      const searchButton = searchBlock.querySelector(
        ".directorist-search-popup-block__button",
      );
      const searchPopup = searchBlock.querySelector(
        ".directorist-search-popup-block__popup",
      );
      const closeSearchButton = searchBlock.querySelector(
        ".directorist-search-popup-block__form-close",
      );

      if (!searchButton || !searchPopup || !closeSearchButton) {
        return;
      }

      const popupId = searchPopup.id || `directorist-search-popup-${index + 1}`;
      searchPopup.id = popupId;
      searchPopup.setAttribute("aria-hidden", "true");
      searchButton.setAttribute("aria-controls", popupId);

      const searchOverlay = document.createElement("div");
      searchOverlay.className = "directorist-search-popup-block__overlay";
      searchOverlay.setAttribute("aria-hidden", "true");
      document.body.appendChild(searchOverlay);

      searchButton.addEventListener("click", function (event) {
        event.preventDefault();

        if (activePopup === searchPopup) {
          closeSearchPopup();
          return;
        }

        openSearchPopup(searchPopup, searchOverlay, searchButton);
      });

      searchOverlay.addEventListener("click", () => closeSearchPopup());
      closeSearchButton.addEventListener("click", () => closeSearchPopup());

      let touchStartY = null;
      let touchDistance = 0;

      searchPopup.addEventListener(
        "touchstart",
        (event) => {
          if (
            !window.matchMedia("(max-width: 575px)").matches ||
            event.touches.length !== 1 ||
            event.touches[0].clientY - searchPopup.getBoundingClientRect().top >
              56
          ) {
            return;
          }

          touchStartY = event.touches[0].clientY;
          touchDistance = 0;
          searchPopup.style.transition = "none";
        },
        { passive: true },
      );

      searchPopup.addEventListener(
        "touchmove",
        (event) => {
          if (null === touchStartY || event.touches.length !== 1) {
            return;
          }

          touchDistance = Math.max(0, event.touches[0].clientY - touchStartY);
          searchPopup.style.transform = `translateY(${touchDistance}px)`;
        },
        { passive: true },
      );

      searchPopup.addEventListener("touchend", () => {
        searchPopup.style.removeProperty("transition");
        searchPopup.style.removeProperty("transform");

        if (touchDistance >= 80) {
          closeSearchPopup();
        }

        touchStartY = null;
        touchDistance = 0;
      });
    });

  document.addEventListener("keydown", function (event) {
    if ("Escape" === event.key && activePopup) {
      closeSearchPopup();
    }
  });
});
