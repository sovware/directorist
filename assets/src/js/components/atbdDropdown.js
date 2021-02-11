/* custom dropdown */
const atbdDropdown = document.querySelectorAll('.directorist-dropdown-select');

// toggle dropdown
let clickCount = 0;
if (atbdDropdown !== null) {
  atbdDropdown.forEach(function (el) {
      el.querySelector('.directorist-dropdown-select-toggle').addEventListener('click', function (e) {
          e.preventDefault();
          clickCount++;
          if (clickCount % 2 === 1) {
              document.querySelectorAll('.directorist-dropdown-select-items').forEach(function (elem) {
                  elem.classList.remove('directorist-dropdown-select-show');
              });
              el.querySelector('.directorist-dropdown-select-items').classList.add('directorist-dropdown-select-show');
          } else {
              document.querySelectorAll('.directorist-dropdown-select-items').forEach(function (elem) {
                  elem.classList.remove('directorist-dropdown-select-show');
              });
          }
      });
  });
}

// remvoe toggle when click outside
document.body.addEventListener('click', function (e) {
  if (e.target.getAttribute('data-drop-toggle') !== 'directorist-dropdown-select-toggle') {
      clickCount = 0;
      document.querySelectorAll('.directorist-dropdown-select-items').forEach(function (el) {
          el.classList.remove('directorist-dropdown-select-show');
      });
  }
});

;(function ($) {
    // Dropdown
    $('body').on('click', '.directorist-dropdown .directorist-dropdown-toggle', function(e){
        e.preventDefault();
        $(this).siblings('.directorist-dropdown-option').toggle();
    });

    // Select Option after click
    $('body').on('click','.directorist-dropdown .directorist-dropdown-option ul li a', function(e){
        e.preventDefault();
        let optionText = $(this).html();
        $(this).children('.directorist-dropdown-toggle__text').html(optionText)
        $(this).closest('.directorist-dropdown-option').siblings('.directorist-dropdown-toggle').children('.directorist-dropdown-toggle__text').html(optionText);
        $('.directorist-dropdown-option').hide();
    });

    // Hide Clicked Anywhere
    $(document).bind('click', function(e) {
        let clickedDom = $(e.target);
        if(!clickedDom.parents().hasClass('directorist-dropdown'))
        $('.directorist-dropdown-option').hide();
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
    $('body').on('click', '.directorist-dropdown-js .directorist-dropdown__toggle-js', function(e){
        e.preventDefault();
        $('.directorist-dropdown__links').hide();
        $(this).siblings('.directorist-dropdown__links-js').toggle();
    });

    // Select Option after click
    // $('body').on('click','.directorist-dropdown .directorist-dropdown__links .directorist-dropdown__links--single', function(e){
    //     e.preventDefault();
    //     if($(this).parents().hasClass('.directorist-dropdown-update-js')){
    //         console.log("yes");
    //     }
    //     $('.directorist-dropdown__links').hide();
    // });

    // Hide Clicked Anywhere
    $(document).bind('click', function(e) {
        let clickedDom = $(e.target);
        if(!clickedDom.parents().hasClass('directorist-dropdown-js'))
        $('.directorist-dropdown__links-js').hide();
    });

})(jQuery);