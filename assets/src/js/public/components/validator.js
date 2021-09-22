jQuery(document).ready(function ($) {
    function to_top(top) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $(top).offset().top
        }, 1000);
    }

    /* ================================
        * Add listing form validation
    =================================== */
    const addListingForm = document.getElementById('directorist-add-listing-form');
    const addListingBtn = document.querySelector('.directorist-form-submit__btn');
    let addListingSelect2 = addListingForm.querySelectorAll('.directorist-form-element--select2');


    if(typeof(addListingForm) != 'undefined' && addListingForm != 'null'){
        document.querySelector('html').style.scrollBehavior = "smooth";
    }
    /* Insert alert after select2 element */
    function insertAfterSelect2(elm) {
        $('<span class="directorist-alert-required">This Field is Required</span>').insertAfter($(elm).siblings('.select2-container'));
    }

    /* Event delegation */
    function eventDelegation(event, selector, program) {
        console.log(selector);
        document.body.addEventListener(event, function(e) {
                document.querySelectorAll(selector).forEach(elem => {
                    if (e.target === elem) {
                            program(e);
                    }
                })
        });
    }

    /* Form validate function */
    function formValidate() {
        let addListingInputs = addListingForm.querySelectorAll('.directorist-form-element:not(select.directorist-form-element):not(.directorist-color-field-js)');
        let addListingInputColor = addListingForm.querySelectorAll('.directorist-color-field-js');
        let formActionCheckboxes = addListingForm.querySelectorAll('.directorist-add-listing-form__action input[type="checkbox"]');

        //Validate input fields
        addListingInputs.forEach((elm, ind) =>{
            if(elm.hasAttribute('required') && elm.value === ''){
                if($(elm).siblings('.directorist-alert-required').length === 0){
                    $('<span class="directorist-alert-required">This Field is Required</span>').insertAfter(elm);
                }
            }
        })

        //validate color fields
        addListingInputColor.forEach((elm, ind)=>{
            if(elm.hasAttribute('required') && elm.value === ''){
                if($(elm).closest('.wp-picker-input-wrap').siblings('.directorist-alert-required').length === 0){
                    $('<span class="directorist-alert-required">This Field is Required</span>').insertAfter($(elm).closest('.wp-picker-input-wrap').siblings('.wp-picker-holder'));
                }
            }
        })

        //validate select2 fields
        addListingSelect2.forEach((elm, ind) =>{
            if(elm.hasAttribute('required') && elm.value === ''){
                if($(elm).siblings('.directorist-alert-required').length === 0){
                    insertAfterSelect2(elm);
                }
            }
        })

        //form action checkbox validation
        formActionCheckboxes.forEach(elm =>{
            if(elm.hasAttribute('required') && !elm.checked){
                if($(elm).siblings('.directorist-alert-required').length === 0){
                    $('<span class="directorist-alert-required">This Field is Required</span>').insertAfter($(elm).siblings('.directorist-form-required'));
                }
            }
        })
    }

    /* Toggle required notification in Input fields */
    eventDelegation('keydown', '.directorist-form-element:not(select.directorist-form-element--select2):not(.directorist-color-field-js)', function(e){
        if(e.target.hasAttribute('required')){
            $(e.target).siblings('.directorist-alert-required').remove();
        }
    })
    eventDelegation('keyup', '.directorist-form-element:not(select.directorist-form-element):not(.directorist-color-field-js)', function(e){
        if(e.target.hasAttribute('required') && e.target.value === ""){
            if($(e.target).siblings('.directorist-alert-required').length === 0){
                $('<span class="directorist-alert-required">This Field is Required</span>').insertAfter($(e.target));
            }
        }
    })


    /* Toggle required notification in Color fields */
    eventDelegation('keydown', '.directorist-color-field-js', function(e){
        if(e.target.hasAttribute('required')){
            $(e.target).closest('.wp-picker-input-wrap').siblings('.directorist-alert-required').remove();
        }
    });
    eventDelegation('keyup', '.directorist-color-field-js', function(e){
        if(e.target.hasAttribute('required') && e.target.value === ""){
            $('<span class="directorist-alert-required">This Field is Required</span>').insertAfter($(e.target).closest('.wp-picker-input-wrap').siblings('.wp-picker-holder'));
        }
    })

    /* Toggle required notification in Input=date/time Fields */
    eventDelegation('change', '.directorist-form-element[type="date"]', function(e){
        $(e.target).siblings('.directorist-alert-required').remove();
    })

    /* Toggle required notification in Select2 fields */
    $(".directorist-form-element--select2").each(function(id, elm){
        if($(elm).attr('required')){
            $(elm).on('select2:select', function(){
                $(elm).siblings('.directorist-alert-required').remove();
            })
            $(elm).on('select2:unselect', function(){
                if($(this).select2('data').length === 0){
                    insertAfterSelect2(elm);
                }
            })
        }
    })

    /* Toggle required notification in form action checkboxes */
    eventDelegation('change', '.directorist-add-listing-form__action input[type="checkbox"]', function(e){
        if(e.target.checked){
            $(e.target).siblings('.directorist-alert-required').remove();
        }else if(e.target.hasAttribute('required') && !e.target.checked){
            $('<span class="directorist-alert-required">This Field is Required</span>').insertAfter($(e.target).siblings('.directorist-form-required'));
        }
    })

    addListingBtn.addEventListener('click', formValidate);

});