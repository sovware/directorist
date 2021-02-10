// on load of the page: switch to the currently selected tab
var tab_url = window.location.href.split("/").pop();
if (tab_url.startsWith("#active_")) {
  var urlId = tab_url.split("#").pop().split("active_").pop();
  if (urlId !== 'my_listings') {
      document.querySelector(`a[target=${urlId}]`).click();
  }
}