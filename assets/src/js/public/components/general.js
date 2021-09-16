// Fix listing with no thumb if card width is less than 220px
(function ($) {
    if($('.directorist-listing-no-thumb').innerWidth() <= 220 ){
        $('.directorist-listing-no-thumb').addClass('directorist-listing-no-thumb--fix');
    }

    // Auhtor Profile Listing responsive fix
    if($('.directorist-author-listing-content').innerWidth() <= 750){
        $('.directorist-author-listing-content').addClass('directorist-author-listing-grid--fix');
    }
    // Directorist Archive responsive fix
    if($('.directorist-archive-grid-view').innerWidth() <= 500){
        $('.directorist-archive-grid-view').addClass('directorist-archive-grid--fix');
    }

    /*
        * Add listing form validation
    */
    /* const addListingForm = document.getElementById('directorist-add-listing-form');
    const addListingBtn = document.querySelector('.directorist-form-submit__btn');
    let addListingInputs = addListingForm.querySelectorAll('input[type="text"], input[type="tel"], input[type="number"], input[type="url"], input[type="date"], input[type="time"], input[type="email"], textarea');
    let addListingSelects = addListingForm.querySelectorAll('select');
    if(typeof(addListingForm) != 'undefined' && addListingForm != 'null'){
        document.querySelector('html').style.scrollBehavior = "smooth";
    }
    function formValidate() {
        //Validate inputs
        addListingInputs.forEach((elm, ind) =>{
            if(elm.hasAttribute('required') && elm.value === ''){
                if($(elm).siblings('.directorist-alert-required').length === 0){
                    $('<span class="directorist-alert-required">This Field is Required</span>').insertAfter(elm);
                }
            }
        })
        addListingSelects.forEach((elm, ind) =>{
            if(elm.hasAttribute('required') && elm.value === ''){
                if($(elm).siblings('.directorist-alert-required').length === 0){
                    $('<span class="directorist-alert-required">This Field is Required</span>').insertAfter(elm);
                }
            }
        })
    }
    addListingInputs.forEach((elm, ind) =>{
        elm.addEventListener('keydown', ()=>{
            $(elm).siblings('.directorist-alert-required').remove();
        })
    })

    addListingBtn.addEventListener('click', formValidate); */

})(jQuery)