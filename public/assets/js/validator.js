jQuery(document).ready(function ($) {
    function to_top(top) {
        $([document.documentElement, document.body]).animate({
            scrollTop: top
        }, 1000);
    }
    $('.listing_submit_btn').on('click', function (e) {
        var w_icon = '<span class="fa fa-exclamation-triangle"></span> ';
        //title
        var title = $("input[name='listing_title']").val();
        var required_title = add_listing_validator.title;
        if ('' === title && '' !== required_title) {
            $("input[name='listing_title']").siblings("label").after('<span class="atbdp_required">' + w_icon + required_title + '</span>');
            to_top(0);
            return false;
        }
        //description
        /* var iframe = $('#listing_content_ifr');
        var description = $('#tinymce[data-id="listing_content"]', iframe.contents()).text();
        var required_description = add_listing_validator.description;
        if ('' === description && '' !== required_description) {
            $("#wp-listing_content-editor-container").siblings("label").after('<span>' + required_description + '</span>');
            to_top(1000);
            return false;
        }
 */
        //tagline
        var excerpt = $("textarea#atbdp_excerpt").val();
        var required_excerpt = add_listing_validator.excerpt;
        if ('' === excerpt && '' !== required_excerpt) {
            $("textarea#atbdp_excerpt").siblings("label").after('<span class="atbdp_required">' + w_icon + required_excerpt + '</span>');
            to_top(1000);
            return false;
        }
        //custom fields
        function inArray(needle, haystack) {
            var length = haystack.length;
            for (var i = 0; i < length; i++) {
                if (haystack[i] == needle)
                    return true;
            }
            return false;
        }

        //Price
        var price = $("input[name='price']").val();
        var required_price = add_listing_validator.price;
        if ('' === price && '' !== required_price) {
            $("input[name='price']").siblings("label").after('<span class="atbdp_required">' + w_icon + required_price + '</span>');
            to_top(1000);
            return false;
        }

        //Price range
        var price_range = $("select[name='price_range']").val();
        var required_price_range = add_listing_validator.price_range;
        if ('' === price_range && '' !== required_price_range) {
            $("select[name='price_range']").siblings("label").after('<span class="atbdp_required">' + w_icon + required_price_range + '</span>');
            to_top(1000);
            return false;
        }

        //tag
        var tag = $("#at_biz_dir-tags").val();
        var required_tag = add_listing_validator.tag;
        if (null === tag && '' !== required_tag) {
            $("#at_biz_dir-tags").siblings("label").after('<span class="atbdp_required">' + w_icon + required_tag + '</span>');
            to_top(1000);
            return false;
        }

        //location
        var location = $("#at_biz_dir-location").val();
        var required_location = add_listing_validator.location;
        if (null === location && '' !== required_location) {
            $("#at_biz_dir-location").siblings("label").after('<span class="atbdp_required">' + w_icon + required_location + '</span>');
            to_top(1000);
            return false;
        }

        //address
        var address = $("input[name='address']").val();
        var required_address = add_listing_validator.address;
        if ('' === address && '' !== required_address) {
            $("input[name='address']").siblings("label").after('<span class="atbdp_required">' + w_icon + required_address + '</span>');
            to_top(1000);
            return false;
        }
        //phone
        var phone = $("input[name='phone']").val();
        var required_phone = add_listing_validator.phone;
        if ('' === phone && '' !== required_phone) {
            $("input[name='phone']").siblings("label").after('<span class="atbdp_required">' + w_icon + required_phone + '</span>');
            to_top(1000);
            return false;
        }
        //email
        var email = $("input[name='email']").val();
        var required_email = add_listing_validator.email;
        if ('' === email && '' !== required_email) {
            $("input[name='email']").siblings("label").after('<span class="atbdp_required">' + w_icon + required_email + '</span>');
            to_top(1000);
            return false;
        }

        //web
        var web = $("input[name='website']").val();
        var required_web = add_listing_validator.web;
        if ('' === web && '' !== required_web) {
            $("input[name='website']").siblings("label").after('<span class="atbdp_required">' + w_icon + required_web + '</span>');
            to_top(1000);
            return false;
        }

        //Sinfo

        var Sinfo = $(".atbdp_social_field_wrapper").length;
        var required_Sinfo = add_listing_validator.Sinfo;
        if (0 === Sinfo && '' !== required_Sinfo) {
            $("#social_info_sortable_container").siblings("label").after('<span class="atbdp_required">' + w_icon + required_Sinfo + '</span>');
            to_top(1000);
            return false;
        }
        //listing_prv_img
        var listing_prv_img = $("input[name='listing_prv_img']").val();
        var required_listing_prv_img = add_listing_validator.listing_prv_img;
        if ('' === listing_prv_img && '' !== required_listing_prv_img) {
            $("input[name='listing_prv_img']").siblings("label").after('<span class="atbdp_required">' + w_icon + required_listing_prv_img + '</span>');
            return false;
        }

        //slider
        var gallery_image = $("#no_image_set").length;
        var required_gallery_image = add_listing_validator.gallery_image;
        if (1 === gallery_image && '' !== required_gallery_image) {
            $("#no_images").siblings("label").after('<span class="atbdp_required">' + w_icon + required_gallery_image + '</span>');
            return false;
        }

        //video
        var video = $("input[name='videourl']").val();
        var required_video = add_listing_validator.video;
        if ('' === video && '' !== required_video) {
            $("input[name='videourl']").siblings("label").after('<span class="atbdp_required">' + w_icon + required_video + '</span>');
            return false;
        }

        //terms
        var terms = $("#listing_t").is(":checked");
        var required_terms = add_listing_validator.terms;
        if (false === terms && '' !== required_terms) {
            $("#listing_t").siblings("label").after('<span class="atbdp_required">' + w_icon + required_terms + '</span>');
            return false;
        }


        //category
        var category = $("select[name='admin_category_select']").val();
        var required_category = add_listing_validator.category;
        if ('-1' === category && '' !== required_category) {
            $("select[name='admin_category_select']").siblings("label").after('<span class="atbdp_required">' + w_icon + required_category + '</span>');
            to_top(1000);
            return false;
        }


        var required_custom_fields = add_listing_validator.required_cus_fields;
        returnValue = true;
        $('[name^="custom_field"]').each(function () {
            var fields = $(this).attr('name');
            var parts = fields.split('[').pop().split(']')[0];
            var match_field = inArray(parts, required_custom_fields);

            if (match_field) {
                var value = $(this).val();
                if ('' === value) {
                    $(this).siblings("label").after('<span class="atbdp_required">This field is required!</span>');
                    to_top(1000);
                    returnValue = false;
                }
            }
        });
        return returnValue;

    });

});