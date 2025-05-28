/******/ (function() { // webpackBootstrap
/*!****************************************!*\
  !*** ./assets/src/js/admin/plugins.js ***!
  \****************************************/
jQuery(document).ready(function ($) {
  var update = $('#directorist-update');
  var main_div = $('[data-slug="directorist"]');
  var extensions_area = update.length ? update : main_div;
  extensions_area.after('<tr class="directorist-extensions"></tr>');
  $('.directorist-extensions').append($('<td colspan="4"><div class="ext-all-wrapper"><input type="checkbox" class="select_all"> All Extensions<table class="atbdp_extensions"><tbody class="de-list"></tbody></table></div></td>'));
  var tbody = $('.directorist-extensions').find('.de-list');
  var extWrapper = $('.directorist-extensions').find('.ext-all-wrapper');
  $(extWrapper).append('<div class="ext-more"><a href="" class="ext-more-link">Click to view directorist all extensions</a></div>');
  var moreLink = $('.directorist-extensions').find('.ext-more-link');
  $(moreLink).hide();
  $(tbody).append($('#the-list tr[data-slug^="directorist-"], #the-list tr[data-slug^="addonskit-for-elementor"]'));
  $("body").on('click', '.select_all', function (e) {
    var table = $(e.target).closest('table');
    $('td input:checkbox', table).prop('checked', this.checked);
  });
  if ($(extWrapper).innerHeight() > 250) {
    $(extWrapper).addClass('ext-height-fix');
    $(moreLink).show();
    $(extWrapper).css('padding-bottom', '60px');
  }
  $(moreLink).on('click', function (e) {
    var _this = this;
    e.preventDefault();
    if ($(extWrapper).hasClass('ext-height-fix')) {
      $(extWrapper).animate({
        height: '100%'
      }, 'fast').removeClass('ext-height-fix');
      $(this).html('Click to collapse');
    } else {
      $(extWrapper).animate({
        height: '250px'
      }, 'fast').addClass('ext-height-fix');
      setTimeout(function () {
        $(_this).html('Click to view directorist all extensions');
      }, 1000);
    }
  });
  if ($(tbody).html() === '') {
    $('.directorist-extensions').hide();
  }
});
/******/ })()
;
//# sourceMappingURL=admin-plugins.js.map