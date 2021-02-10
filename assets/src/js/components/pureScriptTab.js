/*
    Plugin: PureScriptTab
    Version: 1.0.0
    License: MIT
*/

const $ = jQuery;
pureScriptTab = (selector1) => {
    var selector = document.querySelectorAll(selector1);
    selector.forEach((el, index) => {
        a = el.querySelectorAll('.atbd_tn_link');
        a.forEach((element, index) => {
            element.style.cursor = 'pointer';
            element.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();

                var ul = event.target.closest('.atbd_tab_nav'),
                    main = ul.nextElementSibling,
                    item_a = ul.querySelectorAll('.atbd_tn_link'),
                    section = main.querySelectorAll('.atbd_tab_inner');

                item_a.forEach((ela, ind) => {
                    ela.classList.remove('tabItemActive');
                });
                event.target.classList.add('tabItemActive');


                section.forEach((element1, index) => {
                    //console.log(element1);
                    element1.classList.remove('tabContentActive');
                });
                var target = event.target.target;
                document.getElementById(target).classList.add('tabContentActive');
            });
        });
    });
};

pureScriptTabChild = (selector1) => {
    var selector = document.querySelectorAll(selector1);
    selector.forEach((el, index) => {
        a = el.querySelectorAll('.pst_tn_link');


        a.forEach((element, index) => {

            element.style.cursor = 'pointer';
            element.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();

                var ul = event.target.closest('.pst_tab_nav'),
                    main = ul.nextElementSibling,
                    item_a = ul.querySelectorAll('.pst_tn_link'),
                    section = main.querySelectorAll('.pst_tab_inner');

                item_a.forEach((ela, ind) => {
                    ela.classList.remove('pstItemActive');
                });
                event.target.classList.add('pstItemActive');


                section.forEach((element1, index) => {
                    //console.log(element1);
                    element1.classList.remove('pstContentActive');
                });
                var target = event.target.target;
                document.getElementById(target).classList.add('pstContentActive');
            });
        });
    });
};

pureScriptTabChild2 = (selector1) => {
    var selector = document.querySelectorAll(selector1);
    selector.forEach((el, index) => {
        a = el.querySelectorAll('.pst_tn_link-2');


        a.forEach((element, index) => {

            element.style.cursor = 'pointer';
            element.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();

                var ul = event.target.closest('.pst_tab_nav-2'),
                    main = ul.nextElementSibling,
                    item_a = ul.querySelectorAll('.pst_tn_link-2'),
                    section = main.querySelectorAll('.pst_tab_inner-2');

                item_a.forEach((ela, ind) => {
                    ela.classList.remove('pstItemActive2');
                });
                event.target.classList.add('pstItemActive2');


                section.forEach((element1, index) => {
                    //console.log(element1);
                    element1.classList.remove('pstContentActive2');
                });
                var target = event.target.target;
                document.getElementById(target).classList.add('pstContentActive2');
            });
        });
    });
};

if ( $('.atbd_tab') ) {
    pureScriptTab('.atbd_tab');
}

pureScriptTab('.directorist_userDashboard-tab');
pureScriptTabChild('.atbdp-bookings-tab');
pureScriptTabChild2('.atbdp-bookings-tab-inner');