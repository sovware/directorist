/*
	Name:  ATModal
	Version: 1.0
	Author: AazzTech
	Author URI: http://aazztech.com
*/

const aazztechModal = (selector) => {
	const element = document.querySelectorAll(selector);
	element.forEach(function (el, index) {
		el.style.display = 'none';

		const item = document.querySelectorAll(`*[data-target="${el.getAttribute('id')}"]`);
		item.forEach(function (clickitem, index) {
			clickitem.style.cursor = 'pointer';
			clickitem.addEventListener('click', (e) => {
				e.preventDefault();
				el.style.display = 'block';
				document.body.classList.add("atm-open");
				setTimeout(function () {
					el.classList.add("atm-show");
				}, 100)
				document.querySelector("html").style.overflow = "hidden";
			});
		});

		el.querySelector('a.at-modal-close').addEventListener('click', (e) => {
			e.preventDefault();
			el.classList.remove("atm-show");
        	document.body.classList.remove("atm-open");
			setTimeout(function () {
				el.style.display = 'none';
			}, 100)
        	document.querySelector("html").removeAttribute("style");
		});
		el.addEventListener('click', function (e) {
			if (e.target.closest('.atm-contents-inner'))
				return;
			el.classList.remove("atm-show");
            document.body.classList.remove("atm-open");
			setTimeout(function () {
				el.style.display = 'none';
			}, 100)
            document.querySelector("html").removeAttribute("style");
		})

	});
}

aazztechModal('#dcl-claim-modal, #modal-login-alert');