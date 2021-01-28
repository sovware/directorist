;(function ($) {

  //dashboard content responsive fix
  
  var tabContentWidth = $(".atbd_dashboard_wrapper .atbd_tab-content").innerWidth();
  if(tabContentWidth < 650){
    $(".atbd_dashboard_wrapper .atbd_tab-content").addClass("atbd_tab-content--fix");
  }

})(jQuery);