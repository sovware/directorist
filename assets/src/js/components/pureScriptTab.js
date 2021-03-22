/*
    Plugin: PureScriptTab
    Version: 1.0.0
    License: MIT
*/

const $ = jQuery;
pureScriptTab = (selector1) => {
    var selector = document.querySelectorAll(selector1);
    selector.forEach((el, index) => {
        a = el.querySelectorAll('.directorist-tab__nav__link');
        a.forEach((element, index) => {
            element.style.cursor = 'pointer';
            element.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();

                var ul = event.target.closest('.directorist-tab__nav'),
                    main = ul.nextElementSibling,
                    item_a = ul.querySelectorAll('.directorist-tab__nav__link'),
                    section = main.querySelectorAll('.directorist-tab__pane');

                item_a.forEach((ela, ind) => {
                    ela.classList.remove('directorist-tab__nav__active');
                });
                event.target.classList.add('directorist-tab__nav__active');


                section.forEach((element1, index) => {
                    //console.log(element1);
                    element1.classList.remove('directorist-tab__pane--active');
                });
                var target = event.target.target;
                document.getElementById(target).classList.add('directorist-tab__pane--active');
            });
        });
    });
};

if ( $('.directorist-tab') ) {
    pureScriptTab('.directorist-tab');
}