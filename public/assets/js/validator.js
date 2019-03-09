jQuery(document).ready(function ($) {
    $('.listing_submit_btn').on('click', function (e) {
        //title
        var title = $("input[name='listing_title']").val();
        var required_title = add_listing_validator.title;
        if ('' === title && '' !== required_title){
            $("input[name='listing_title']").parent().prepend('<span class="atbdp_required">'+required_title+'</span>');
            $([document.documentElement, document.body]).animate({
                scrollTop: 0}, 1000);
            return false;
        }
        //description
      /*  var description = '';
        var required_description = add_listing_validator.description;
        if ('' === description && '' !== required_description){
            $("##wp-listing_content-editor-container").parent().prepend('<span>'+required_description+'</span>');
            $([document.documentElement, document.body]).animate({
                scrollTop: 0}, 1000);
            return false;
        }*/

        //tagline
        var excerpt = $("textarea[name='excerpt']").val();

        var required_excerpt = add_listing_validator.excerpt;
        if ('' === excerpt && '' !== required_excerpt){
            $("textarea[name='excerpt']").parent().prepend('<span class="atbdp_required">'+required_excerpt+'</span>');
            $([document.documentElement, document.body]).animate({
                scrollTop:1000 }, 1000);
            return false;
        }
        //custom fields
        function inArray(needle, haystack) {
            var length = haystack.length;
            for(var i = 0; i < length; i++) {
                if(haystack[i] == needle)
                    return true;
            }
            return false;
        }

        //Price
        var price = $("input[name='price']").val();
        var required_price = add_listing_validator.price;
        if ('' === price && '' !== required_price){
            $("input[name='price']").parent().prepend('<span class="atbdp_required">'+required_price+'</span>');
            $([document.documentElement, document.body]).animate({
                scrollTop:1000 }, 1000);
            return false;
        }

        //Price range
    var price_range = $("select[name='price_range']").val();
        var required_price_range = add_listing_validator.price_range;
        if ('' === price_range && '' !== required_price_range){
            $("select[name='price_range']").parent().prepend('<span class="atbdp_required">'+required_price_range+'</span>');
            $([document.documentElement, document.body]).animate({
                scrollTop:1000 }, 1000);
            return false;
        }

        //tag
        var tag = $("#at_biz_dir-tags").val();
        var required_tag = add_listing_validator.tag;
        if (null === tag && '' !== required_tag){
            $("#at_biz_dir-tags").parent().prepend('<span class="atbdp_required">'+required_tag+'</span>');
            $([document.documentElement, document.body]).animate({
                scrollTop:1200 }, 1000);
            return false;
        }

        //location
        var location = $("#at_biz_dir-location").val();
        var required_location = add_listing_validator.location;
        if (null === location && '' !== required_location){
            $("#at_biz_dir-location").parent().prepend('<span class="atbdp_required">'+required_location+'</span>');
            $([document.documentElement, document.body]).animate({
                scrollTop:1500 }, 1000);
            return false;
        }

        //address
        var address = $("input[name='address']").val();
        var required_address = add_listing_validator.address;
        if ('' === address && '' !== required_address){
            $("input[name='address']").parent().prepend('<span class="atbdp_required">'+required_address+'</span>');
            $([document.documentElement, document.body]).animate({
                scrollTop:1800 }, 1000);
            return false;
        }
        //phone
        var phone = $("input[name='phone']").val();
        var required_phone = add_listing_validator.phone;
        if ('' === phone && '' !== required_phone){
            $("input[name='phone']").parent().prepend('<span class="atbdp_required">'+required_phone+'</span>');
            $([document.documentElement, document.body]).animate({
                scrollTop:1800 }, 1000);
            return false;
        }
        //email
        var email = $("input[name='email']").val();
        var required_email = add_listing_validator.email;
        if ('' === email && '' !== required_email){
            $("input[name='email']").parent().prepend('<span class="atbdp_required">'+required_email+'</span>');
            $([document.documentElement, document.body]).animate({
                scrollTop:2000 }, 1000);
            return false;
        }

        //web
        var web = $("input[name='website']").val();
        var required_web = add_listing_validator.web;
        if ('' === web && '' !== required_web){
            $("input[name='website']").parent().prepend('<span class="atbdp_required">'+required_web+'</span>');
            $([document.documentElement, document.body]).animate({
                scrollTop:2000 }, 1000);
            return false;
        }




        //category
        var category = $("select[name='admin_category_select']").val();
        var required_category = add_listing_validator.category;
        if ('-1' === category && '' !== required_category){
            $("select[name='admin_category_select']").parent().prepend('<span class="atbdp_required">'+required_category+'</span>');
            $([document.documentElement, document.body]).animate({
                scrollTop:1500 }, 1000);
            return false;
        }


        var required_custom_fields = add_listing_validator.required_cus_fields;
        returnValue = true;
        $('[name^="custom_field"]').each(function() {
            var fields = $(this).attr('name');
            var parts = fields.split('[').pop().split(']')[0];
            var match_field = inArray(parts, required_custom_fields);

            if (match_field){
                var value = $(this).val();
                if ('' === value){
                    $(this).parent().prepend('<span class="atbdp_required">This field is required!</span>');
                    $([document.documentElement, document.body]).animate({
                        scrollTop:1500 }, 1000);
                    returnValue = false;
                }
            }
        });
        return returnValue;

    });

});