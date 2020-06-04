/*
	Name:  ATModal
	Version: 1.0
	Author: AazzTech
	Author URI: http://aazztech.com
*/
window.addEventListener("load", function () {
    setTimeout(function () {
        const aazztechModal1 = (selector) => {
            const element = document.querySelectorAll(selector);
            element.forEach(function (el, index) {

                el.style.display = 'none';

                const item = document.querySelectorAll(`*[data-target="${el.getAttribute('id')}"]`);
				if ( ! item.length ) { return; }

				const first_item = item[0];
				const class_name = first_item.className;

				document.addEventListener('click', function (event) {
					if (event.target.matches('.' + class_name )) {
						event.preventDefault();
						el.style.display = 'block';
                        document.body.classList.add("atm-open");
                        setTimeout(function () {
                            el.classList.add("atm-show");
                        }, 100);
                        document.querySelector("html").style.overflow = "hidden";

					}
				}, false);

                el.querySelector('a.at-modal-close').addEventListener('click', (e) => {
                    e.preventDefault();
                    el.classList.remove("atm-show");
                    document.body.classList.remove("atm-open");
                    setTimeout(function () {
                        el.style.display = 'none';
                    }, 100);
                    document.querySelector("html").removeAttribute("style");
                });
                el.addEventListener('click', function (e) {
                    if (e.target.closest('.atm-contents-inner'))
                        return;
                    el.classList.remove("atm-show");
                    document.body.classList.remove("atm-open");
                    setTimeout(function () {
                        el.style.display = 'none';
                    }, 100);
                    document.querySelector("html").removeAttribute("style");
                })

            });
        };
        aazztechModal1('#dcl-claim-modal, #atbdp-report-abuse-modal, #atpp-plan-change-modal, #pyn-plan-change-modal');
    },500);
});
