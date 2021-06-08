//custom select
const atbdSelect = document.querySelectorAll('.atbd-drop-select');
if (atbdSelect !== null) {
  atbdSelect.forEach(function (el) {
      el.querySelectorAll('.atbd-dropdown-item').forEach(function (item) {
          item.addEventListener('click', function (e) {
              e.preventDefault();
              el.querySelector('.atbd-dropdown-toggle').textContent = item.textContent;
              el.querySelectorAll('.atbd-dropdown-item').forEach(function (elm) {
                  elm.classList.remove('atbd-active');
              });
              item.classList.add('atbd-active');
          });
      });
  });
}

// select data-status
const atbdSelectData = document.querySelectorAll('.atbd-drop-select.with-sort');
atbdSelectData.forEach(function (el) {
  el.querySelectorAll('.atbd-dropdown-item').forEach(function (item) {
      let ds = el.querySelector('.atbd-dropdown-toggle');
      let itemds = item.getAttribute('data-status');
      item.addEventListener('click', function (e) {
          ds.setAttribute('data-status', `${itemds}`);
      });
  });
});