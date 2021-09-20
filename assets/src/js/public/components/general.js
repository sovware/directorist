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

    /* Add listing - hide owner contact information */
    $('#hide_contact_owner').on('change', function(){
        let siblings = $(this).closest('.directorist-form-group').siblings('.directorist-form-group');
        if($(this).is(":checked")){
            $(siblings).hide();
            $(siblings).addClass('directorist-form-group--hidden');
        }else{
            $(siblings).show()
            $(siblings).removeClass('directorist-form-group--hidden');
        }
    })

    /* ================================
        * Add listing form validation
    =================================== */
    const addListingForm = document.getElementById('directorist-add-listing-form');
    const addListingBtn = document.querySelector('.directorist-form-submit__btn');
    let addListingInputs = addListingForm.querySelectorAll('.directorist-form-element:not(select.directorist-form-element)');
    let addListingSelect2 = addListingForm.querySelectorAll('.directorist-form-element--select2');
    let addListingInputDateTime = addListingForm.querySelectorAll('.directorist-form-element[type="date"], .directorist-form-element[type="time"]')

    if(typeof(addListingForm) != 'undefined' && addListingForm != 'null'){
        document.querySelector('html').style.scrollBehavior = "smooth";
    }
    /* Insert alert after select2 element */
    function insertAfterSelect2(elm) {
        $('<span class="directorist-alert-required">This Field is Required</span>').insertAfter($(elm).siblings('.select2-container'));
    }


    function formValidate() {
        if($('#hide_contact_owner').closest('.directorist-form-group').siblings('.directorist-form-group--hidden').find('.directorist-form-element').attr('required')){
            $('#hide_contact_owner').closest('.directorist-form-group').siblings('.directorist-form-group--hidden').find('.directorist-form-element').removeAttr('required');
        }

        //Validate inputs
        addListingInputs.forEach((elm, ind) =>{
            if(elm.hasAttribute('required') && elm.value === ''){
                if($(elm).siblings('.directorist-alert-required').length === 0){
                    $('<span class="directorist-alert-required">This Field is Required</span>').insertAfter(elm);
                }
            }
        })
        addListingSelect2.forEach((elm, ind) =>{
            if(elm.hasAttribute('required') && elm.value === ''){
                if($(elm).siblings('.directorist-alert-required').length === 0){
                    insertAfterSelect2(elm);
                }
            }
        })
    }

    /* Remove alert on keydown */
    function removeAlertOnKeyDown(selector){
        selector.forEach((elm, ind) =>{
            elm.addEventListener('keydown', ()=>{
                $(elm).siblings('.directorist-alert-required').remove();
            })
        })
    }
    //removeAlertOnKeyDown(addListingInputs);

    /* Input=text validation */
    addListingInputs.forEach((elm, ind) =>{
        elm.addEventListener('keydown', ()=>{
            $(elm).siblings('.directorist-alert-required').remove();
        })
        elm.addEventListener('keyup', ()=>{
            if($(elm).val() === ""){
                $('<span class="directorist-alert-required">This Field is Required</span>').insertAfter(elm);
            }
        })
    })

    /* Input=date/time validation */
    addListingInputDateTime.forEach((elm, id) =>{
        elm.addEventListener('change', ()=>{
            $(elm).siblings('.directorist-alert-required').remove();
        })
    })

    /* Select2 validation */
    $(".directorist-form-element--select2").each(function(id, elm){
        $(elm).on('select2:select', function(){
            $(elm).siblings('.directorist-alert-required').remove();
        })
        $(elm).on('select2:unselect', function(){
            if($(this).select2('data').length === 0){
                insertAfterSelect2(elm);
            }
        })
    })

    addListingBtn.addEventListener('click', formValidate);

})(jQuery)