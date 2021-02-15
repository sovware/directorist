const $ = jQuery;

/*
    Plugin: PureScriptTab
    Version: 1.0.0
    License: MIT
*/

const pureScriptTab = selector1 => {
    const selector = document.querySelectorAll(selector1);
    selector.forEach((el, index) => {
        a = el.querySelectorAll('.directorist-tab__nav__link');

        a.forEach((element, index) => {
            element.style.cursor = 'pointer';
            element.addEventListener('click', event => {
                event.preventDefault();
                event.stopPropagation();

                const ul = event.target.closest('.directorist-tab__nav');
                const main = ul.nextElementSibling;
                const item_a = ul.querySelectorAll('.directorist-tab__nav__link');
                const section = main.querySelectorAll('.directorist-tab__pane');

                item_a.forEach((ela, ind) => {
                    ela.classList.remove('directorist-tab__nav__active');
                });
                event.target.classList.add('directorist-tab__nav__active');

                section.forEach((element1, index) => {
                    // console.log(element1);
                    element1.classList.remove('directorist-tab__pane--active');
                });
                const { target } = event.target;
                document.getElementById(target).classList.add('directorist-tab__pane--active');
            });
        });
    });
};

pureScriptTab('.directorist_builder--tab');

/* Copy shortcodes on click */
$('body').on('click', '.atbdp_shortcodes', function () {
    const $this = $(this);
    const $temp = $('<input>');
    $('body').append($temp);
    $temp.val($(this).text()).select();
    document.execCommand('copy');
    $temp.remove();
    $(this).after(
        "<p class='copy-notify' style='color: #32cc6f; margin-top: 5px;'>Copied to clipboard!</p>"
    );
    setTimeout(function () {
        $this.siblings('.copy-notify').fadeOut(300, function () {
            $(this).remove();
        });
    }, 3000);
});