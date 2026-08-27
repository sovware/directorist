let activeSearchModal = null;
let activeSearchModalTrigger = null;
let searchModalScrollPosition = 0;
let searchModalInitialized = false;

function setSearchModalPageState(isOpen) {
  const contentRoot = document.querySelector(".directorist-content-active");

  if (isOpen) {
    searchModalScrollPosition = window.scrollY;
    document.body.style.setProperty(
      "--directorist-search-modal-scroll-offset",
      `-${searchModalScrollPosition}px`,
    );
  }

  contentRoot?.classList.toggle("directorist-overlay-active", isOpen);
  document.documentElement.classList.toggle(
    "directorist-search-modal-open",
    isOpen,
  );
  document.body.classList.toggle("directorist-search-modal-open", isOpen);

  if (isOpen) {
    return;
  }

  document.body.style.removeProperty(
    "--directorist-search-modal-scroll-offset",
  );
  window.scrollTo(0, searchModalScrollPosition);
}

function getSearchModalFocusableElement(modalContent) {
  return (
    modalContent.querySelector(
      'input:not([type="hidden"]):not([disabled]), select:not([disabled]), textarea:not([disabled])',
    ) || modalContent.querySelector("button:not([disabled]), a[href]")
  );
}

export function openSearchModal(searchModalParent, trigger) {
  if (!searchModalParent) {
    return;
  }

  if (activeSearchModal && activeSearchModal !== searchModalParent) {
    closeSearchModal(activeSearchModal, false);
  }

  const modalContent = searchModalParent.querySelector(
    ".directorist-search-modal__contents",
  );

  if (!modalContent) {
    return;
  }

  activeSearchModal = searchModalParent;
  activeSearchModalTrigger = trigger || document.activeElement;
  searchModalParent.classList.add("directorist-search-modal--open");
  searchModalParent.setAttribute("aria-hidden", "false");
  modalContent.setAttribute("role", "dialog");
  modalContent.setAttribute("aria-modal", "true");
  activeSearchModalTrigger?.setAttribute("aria-expanded", "true");
  setSearchModalPageState(true);

  const focusTarget = getSearchModalFocusableElement(modalContent);
  const focusSearchControl = () => {
    if (activeSearchModal === searchModalParent) {
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

export function closeSearchModal(searchModalParent, restoreFocus = true) {
  if (!searchModalParent) {
    return;
  }

  const modalContent = searchModalParent.querySelector(
    ".directorist-search-modal__contents",
  );
  const modalMinimizer = searchModalParent.querySelector(
    ".directorist-search-modal__minimizer",
  );

  searchModalParent.classList.remove("directorist-search-modal--open");
  searchModalParent.setAttribute("aria-hidden", "true");
  modalContent?.removeAttribute("style");
  modalContent?.removeAttribute("aria-modal");
  modalMinimizer?.classList.remove("minimized");

  if (activeSearchModal === searchModalParent) {
    const trigger = activeSearchModalTrigger;
    trigger?.setAttribute("aria-expanded", "false");
    activeSearchModal = null;
    activeSearchModalTrigger = null;
    setSearchModalPageState(false);

    if (restoreFocus && trigger && document.contains(trigger)) {
      window.requestAnimationFrame(() =>
        trigger.focus({ preventScroll: true }),
      );
    }
  }
}

function initSearchModalSwipeDismiss() {
  document
    .querySelectorAll(".directorist-search-modal__contents")
    .forEach((modalContent) => {
      const swipeHandle =
        modalContent.querySelector(
          ".directorist-search-modal__contents__header",
        ) || modalContent.querySelector(".directorist-search-modal__minimizer");

      if (!swipeHandle || swipeHandle.dataset.swipeDismissInitialized) {
        return;
      }

      swipeHandle.dataset.swipeDismissInitialized = "true";
      let touchStartY = null;
      let touchDistance = 0;

      swipeHandle.addEventListener(
        "touchstart",
        (event) => {
          if (
            !window.matchMedia("(max-width: 575px)").matches ||
            event.touches.length !== 1
          ) {
            return;
          }

          touchStartY = event.touches[0].clientY;
          touchDistance = 0;
          modalContent.style.transition = "none";
        },
        { passive: true },
      );

      swipeHandle.addEventListener(
        "touchmove",
        (event) => {
          if (null === touchStartY || event.touches.length !== 1) {
            return;
          }

          touchDistance = Math.max(0, event.touches[0].clientY - touchStartY);
          modalContent.style.transform = `translate(-50%, ${touchDistance}px)`;
        },
        { passive: true },
      );

      swipeHandle.addEventListener("touchend", () => {
        const searchModalParent = modalContent.closest(
          ".directorist-search-modal",
        );

        modalContent.style.removeProperty("transition");
        modalContent.style.removeProperty("transform");

        if (touchDistance >= 80) {
          closeSearchModal(searchModalParent);
        }

        touchStartY = null;
        touchDistance = 0;
      });
    });
}

export function initSearchModals() {
  initSearchModalSwipeDismiss();

  if (searchModalInitialized) {
    return;
  }

  searchModalInitialized = true;
  document.addEventListener("keydown", (event) => {
    if ("Escape" === event.key && activeSearchModal) {
      closeSearchModal(activeSearchModal);
    }
  });

  document.addEventListener("directorist-search-modal-close", (event) => {
    const searchModalParent = event.target.closest(".directorist-search-modal");

    if (searchModalParent) {
      closeSearchModal(searchModalParent);
    }
  });
}
