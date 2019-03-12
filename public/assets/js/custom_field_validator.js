jQuery(document).ready(function ($) {
    function to_top(top) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $(top).offset().top
        }, 1000);
    }
    $('.listing_submit_btn').on('click', function () {
        var w_icon = '<span class="fa fa-exclamation-triangle"></span> ';

        //custom fields
        function inArray(needle, haystack) {
            var length = haystack.length;
            for (var i = 0; i < length; i++) {
                if (haystack[i] == needle)
                    return true;
            }
            return false;
        }
        //@todo later check checkbox and radio if multiple and one require another not required....  validate only the required one
        //custom field
        var required_custom_fields = custom_field_validator.required_cus_fields;
        returnValue = true;
        $('[name^="custom_field"]').each(function () {
            var fields = $(this).attr('name');
            var parts = fields.split('[').pop().split(']')[0];
            var match_field = inArray(parts, required_custom_fields);

            if (match_field) {
                var value = $(this).val();
                if ('' === value) {
                    $(this).parents(".form-group").append('<span class="atbdp_required">'+ w_icon +'This field is required!</span>');
                    to_top('#atbdp_custom_field_area');
                    returnValue = false;
                }
            }
        });
        //custom field checkbox
        var cus_check = $('.atbdp-checkbox-list input[type="checkbox"]').is(":checked");
        var required_checkbox = custom_field_validator.cus_check;
        if (false === cus_check && '' !== required_checkbox) {
            $('.atbdp-checkbox-list').after('<span class="atbdp_required">'+ w_icon +'This field is required!</span>');
            to_top('#atbdp_custom_field_area');
            return false;
        }

        //custom field radio
        var cus_radio = $('.atbdp-radio-list input[type="radio"]').is(":checked");
        var required_radio = custom_field_validator.cus_radio;
        if (false === cus_radio && '' !== required_radio) {
            $('.atbdp-radio-list').parents(".form-group").append('<span class="atbdp_required">'+ w_icon +'This field is required!</span>');
            to_top('#atbdp_custom_field_area');
            return false;
        }

        return returnValue;

    });

});