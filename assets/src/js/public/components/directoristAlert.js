;
(function ($) {
    window.addEventListener('DOMContentLoaded', () => {
        /* Directorist alert dismiss */
        let getUrl = window.location.href;
        let newUrl = getUrl.replace('notice=1', '')
        if ($('.directorist-alert__close') !== null) {
            $('.directorist-alert__close').each(function (i, e) {
                $(e).on('click', function (e) {
                    e.preventDefault();
                    history.pushState({}, null, newUrl);
                    $(this).closest('.directorist-alert').remove();
                });
            });
        }
    });
})(jQuery);