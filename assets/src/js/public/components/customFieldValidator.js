jQuery(document).ready(function ($) {
    function to_top(top) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $(top).offset().top
        }, 1000);
    }
    need_post = false;
    if ($("input[name='need_post']").length > 0) {
        $("input[name='need_post']").on('change', function () {
            if ('yes' === this.value) {
                need_post = true;
            }
        });
       var is_need = $("input[name='need_post']:checked").val();
       if (is_need){
           if ('yes' === is_need){
               need_post = true;
           }else{
               need_post = false;
           }
       }

    }

    // @kowsar @todo rebuild validation
    /*
    $('.directorist-form-submit__btn').on('click', function () {
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
        //custom field
        var required_custom_fields = custom_field_validator.required_cus_fields;
        var msg = custom_field_validator.msg;
        returnValue = true;
        $('[name^="custom_field"]').each(function () {
            var fields = $(this).attr('name');
            var parts = fields.split('[').pop().split(']')[0];
            var match_field = inArray(parts, required_custom_fields);
			//console.log(parts);
            if (match_field) {
                var value = $(this).val();
                if ('' === value && !need_post) {
                    $(this).parents(".form-group").append('<span class="atbdp_required">'+ w_icon +msg+'</span>');
                    to_top('.atbdp_custom_field_area');
                    returnValue = false;
                }
            }
        });
        //custom field checkbox
        var cus_check = $('.atbdp-checkbox-list input[type="checkbox"]').is(":checked");
		if(cus_check){
			var required_checkbox = custom_field_validator.cus_check;
			if (false === cus_check && '' !== required_checkbox && !need_post) {
				$('.atbdp-checkbox-list').after('<span class="atbdp_required">'+ w_icon +msg+'</span>');
				to_top('#atbdp_custom_field_area');
				return false;
			}

		}

        //custom field radio
        var cus_radio = $('.atbdp-radio-list input[type="radio"]').is(":checked");
		if(cus_radio){
			var required_radio = custom_field_validator.cus_radio;
			if (false === cus_radio && '' !== required_radio && !need_post) {
				$('.atbdp-radio-list').parents(".form-group").append('<span class="atbdp_required">'+ w_icon +msg+'</span>');
				to_top('#atbdp_custom_field_area');
				return false;
			}
		}


        return returnValue;

    });
    */

});