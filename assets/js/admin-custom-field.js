/******/ (function() { // webpackBootstrap
/*!*********************************************!*\
  !*** ./assets/src/js/admin/custom-field.js ***!
  \*********************************************/
window.addEventListener('load', function () {
  (function ($) {
    $('table.posts #the-list, table.pages #the-list').sortable({
      'items': 'tr',
      'axis': 'y',
      'helper': fixHelper,
      'update': function update() {
        $.post(ajaxurl, {
          action: 'update-menu-order',
          order: $('#the-list').sortable('serialize')
        });
      }
    });
    $('table.tags #the-list').sortable({
      'items': 'tr',
      'axis': 'y',
      'helper': fixHelper,
      'update': function update() {
        $.post(ajaxurl, {
          action: 'update-menu-order-tags',
          order: $('#the-list').sortable('serialize')
        });
      }
    });
    var fixHelper = function fixHelper(e, ui) {
      ui.children().children().each(function () {
        $(this).width($(this).width());
      });
      return ui;
    };
  })(jQuery);
});
/******/ })()
;
//# sourceMappingURL=admin-custom-field.js.map