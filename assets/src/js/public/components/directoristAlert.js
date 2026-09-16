(function ($) {
  // Make sure the codes in this file runs only once, even if enqueued twice
  if (typeof window.directorist_alert_executed === "undefined") {
    window.directorist_alert_executed = true;
  } else {
    return;
  }
  /* Directorist alert dismiss */
  $(document).on("click", ".directorist-alert__close", function (event) {
    event.preventDefault();

    const newUrl = window.location.href.replace("notice=1", "");
    history.pushState({}, null, newUrl);
    $(this).closest(".directorist-alert").remove();
  });
})(jQuery);
