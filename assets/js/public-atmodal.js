/******/ (function() { // webpackBootstrap
/*!*****************************************!*\
  !*** ./assets/src/js/public/atmodal.js ***!
  \*****************************************/
/*
        Name:  ATModal
        Version: 1.0
        Author: Sovware
        Author URI: https://sovware.com/
*/
/* disable-eslint */
var aazztechModal1 = function aazztechModal1(selector) {
  var element = document.querySelectorAll(selector);
  element.forEach(function (el, index) {
    el.style.display = 'none';
    document.addEventListener('click', function (event) {
      var current_elm = event.target;
      var target_id = current_elm.getAttribute('data-target');
      var el_id = el.getAttribute('id');
      if (target_id === el_id) {
        event.preventDefault();
        el.style.display = 'block';
        document.body.classList.add('atm-open');
        setTimeout(function () {
          el.classList.add('atm-show');
        }, 100);
        document.querySelector('html').style.overflow = 'hidden';
      }
    }, false);
    el.querySelector('a.at-modal-close').addEventListener('click', function (e) {
      e.preventDefault();
      el.classList.remove('atm-show');
      document.body.classList.remove('atm-open');
      setTimeout(function () {
        el.style.display = 'none';
      }, 100);
      document.querySelector('html').removeAttribute('style');
    });
    el.addEventListener('click', function (e) {
      if (e.target.closest('.atm-contents-inner')) return;
      el.classList.remove('atm-show');
      document.body.classList.remove('atm-open');
      setTimeout(function () {
        el.style.display = 'none';
      }, 100);
      document.querySelector('html').removeAttribute('style');
    });
  });
};
function initModal() {
  aazztechModal1('#dcl-claim-modal, #atbdp-report-abuse-modal, #atpp-plan-change-modal, #pyn-plan-change-modal');
}
window.addEventListener('load', function () {
  setTimeout(function () {
    initModal();
  }, 500);
});
/******/ })()
;
//# sourceMappingURL=public-atmodal.js.map