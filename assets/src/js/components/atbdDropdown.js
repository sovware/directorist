/* custom dropdown */
const atbdDropdown = document.querySelectorAll('.atbd-dropdown');

// toggle dropdown
let clickCount = 0;
if (atbdDropdown !== null) {
  atbdDropdown.forEach(function (el) {
      el.querySelector('.atbd-dropdown-toggle').addEventListener('click', function (e) {
          e.preventDefault();
          clickCount++;
          if (clickCount % 2 === 1) {
              document.querySelectorAll('.atbd-dropdown-items').forEach(function (elem) {
                  elem.classList.remove('atbd-show');
              });
              el.querySelector('.atbd-dropdown-items').classList.add('atbd-show');
          } else {
              document.querySelectorAll('.atbd-dropdown-items').forEach(function (elem) {
                  elem.classList.remove('atbd-show');
              });
          }
      });
  });
}

// remvoe toggle when click outside
document.body.addEventListener('click', function (e) {
  if (e.target.getAttribute('data-drop-toggle') !== 'atbd-toggle') {
      clickCount = 0;
      document.querySelectorAll('.atbd-dropdown-items').forEach(function (el) {
          el.classList.remove('atbd-show');
      });
  }
});

;(function ($) {
    // Dropdown 
    $('body').on('click', '.directorist_dropdown .directorist_dropdown-toggle', function(e){
        e.preventDefault();
        $(this).siblings('.directorist_dropdown-option').toggle();
    });

    // Select Option after click
    $('body').on('click','.directorist_dropdown .directorist_dropdown-option ul li a', function(e){
        e.preventDefault();
        let optionText = $(this).html();
        $(this).children('.directorist_dropdown-toggle__text').html(optionText)
        $(this).closest('.directorist_dropdown-option').siblings('.directorist_dropdown-toggle').children('.directorist_dropdown-toggle__text').html(optionText);
        $('.directorist_dropdown-option').hide();
    });

    // Hide Clicked Anywhere
    $(document).bind('click', function(e) {
        let clickedDom = $(e.target);
        if(!clickedDom.parents().hasClass('directorist_dropdown'))
        $('.directorist_dropdown-option').hide();
    });

    //atbd_dropdown
    $(".atbd_dropdown").on("click", function (e) {
        if ($(this).attr("class") === "atbd_dropdown") {
            e.preventDefault();
            $(this).siblings(".atbd_dropdown").removeClass("atbd_drop--active");
            $(this).toggleClass("atbd_drop--active");
            e.stopPropagation();
        }
    });
    $(document).on("click", function (e) {
        if ($(e.target).is(".atbd_dropdown, .atbd_drop--active") === false) {
            $(".atbd_dropdown").removeClass("atbd_drop--active");
        }
    });
    $(".atbd_dropdown-toggle").on("click", function (e) {
        e.preventDefault();
    });


    // Restructred Dropdown
    // Directorist Dropdown 
    $('body').on('click', '.directorist_dropdown .directorist_dropdown__toggle', function(e){
        e.preventDefault();
        $(this).siblings('.directorist_dropdown__links').toggle();
    });

    // Select Option after click
    $('body').on('click','.directorist_dropdown .directorist_dropdown__links .directorist_dropdown__links--single', function(e){
        e.preventDefault();
        $('.directorist_dropdown__links').hide();
    });

    // Hide Clicked Anywhere
    $(document).bind('click', function(e) {
        let clickedDom = $(e.target);
        if(!clickedDom.parents().hasClass('directorist_dropdown'))
        $('.directorist_dropdown__links').hide();
    });

})(jQuery);