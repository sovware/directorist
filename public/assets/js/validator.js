jQuery(document).ready(function ($) {
    function to_top(top) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $(top).offset().top
        }, 1000);
    }
    $('.listing_submit_btn').on('click', function (e) {
        $('.atbdp_required').css({ display: "none" });
        var w_icon = '<span class="fa fa-exclamation-triangle"></span> ';
        //title
        var title = $("input[name='listing_title']").val();
        var required_title = add_listing_validator.title;
        if ('' === title && '' !== required_title) {
            $("input[name='listing_title']").after('<span class="atbdp_required">' + w_icon + required_title + '</span>');
            to_top('#atbdp_listing_title');
            return false;
        }
        //description
         var iframe = $('#listing_content_ifr');
        var description = $('#tinymce[data-id="listing_content"]', iframe.contents()).text();
        var required_description = add_listing_validator.description;
        if ('' === description && '' !== required_description) {
            $("#wp-listing_content-wrap").after('<span class="atbdp_required">' + w_icon  + required_description + '</span>');
            to_top('#atbdp_listing_content');
            return false;
        }

        //Price
        var price = $("input[name='price']").val();
        var required_price = add_listing_validator.price;
        if ('' === price && '' !== required_price) {
            $("#atbd_pricing").append('<span class="atbdp_required">' + w_icon + required_price + '</span>');
            to_top('#atbd_pricing');

            return false;
        }

        //Price range
        var price_range = $("select[name='price_range']").val();
        var required_price_range = add_listing_validator.price_range;
        if ('' === price_range && '' !== required_price_range) {
            $("#atbd_pricing").after('<span class="atbdp_required">' + w_icon + required_price_range + '</span>');
            to_top('#atbd_pricing');
            return false;
        }

        //excerpt
        var excerpt = $("textarea#atbdp_excerpt").val();
        var required_excerpt = add_listing_validator.excerpt;
        if ('' === excerpt && '' !== required_excerpt) {
            $("textarea#atbdp_excerpt").after('<span class="atbdp_required">' + w_icon + required_excerpt + '</span>');
            to_top('#atbdp_excerpt');
            return false;
        }

        //location
        var location = $("#at_biz_dir-location").val();
        var required_location = add_listing_validator.location;
        if (null === location && '' !== required_location) {
            $("#atbdp_locations").append('<span class="atbdp_required">' + w_icon + required_location + '</span>');
            to_top('#atbdp_locations');
            return false;
        }
        //tag
        var tag = $("#at_biz_dir-tags").val();
        var required_tag = add_listing_validator.tag;
        if (null === tag && '' !== required_tag) {
            $("#atbdp_tags").append('<span class="atbdp_required">' + w_icon + required_tag + '</span>');
            to_top('#atbdp_tags');
            return false;
        }

        //category
        var category = $("select[name='admin_category_select']").val();
        var required_category = add_listing_validator.category;
        if ('-1' === category && '' !== required_category) {
            $("select[name='admin_category_select']").after('<span class="atbdp_required">' + w_icon + required_category + '</span>');
            to_top('#atbdp_categories');
            return false;
        }

        //address
        var address = $("input[name='address']").val();
        var required_address = add_listing_validator.address;
        if ('' === address && '' !== required_address) {
            $("input[name='address']").after('<span class="atbdp_required">' + w_icon + required_address + '</span>');
            to_top('#atbdp_address');
            return false;
        }
        //phone
        var phone = $("input[name='phone']").val();
        var required_phone = add_listing_validator.phone;
        if ('' === phone && '' !== required_phone) {
            $("#atbdp_phone").append('<span class="atbdp_required">' + w_icon + required_phone + '</span>');
            to_top('#atbdp_phone');
            return false;
        }
        //email
        var email = $("input[name='email']").val();
        var required_email = add_listing_validator.email;
        if ('' === email && '' !== required_email) {
            $("#atbdp_emails").append('<span class="atbdp_required">' + w_icon + required_email + '</span>');
            to_top('#atbdp_emails');
            return false;
        }

        //web
        var web = $("input[name='website']").val();
        var required_web = add_listing_validator.web;
        if ('' === web && '' !== required_web) {
            $("#atbdp_webs").append('<span class="atbdp_required">' + w_icon + required_web + '</span>');
            to_top('#atbdp_webs');
            return false;
        }

        //zip
        var zip = $("input[name='zip']").val();
        var required_zip = add_listing_validator.zip;
        if ('' === zip && '' !== required_zip) {
            $("#atbdp_zip").append('<span class="atbdp_required">' + w_icon + required_zip + '</span>');
            to_top('#atbdp_zip');
            return false;
        }

        //Sinfo

        var Sinfo = $(".atbdp_social_field_wrapper").length;
        var required_Sinfo = add_listing_validator.Sinfo;
        if (0 === Sinfo && '' !== required_Sinfo) {
            $("#atbdp_socialInFo").append('<span class="atbdp_required">' + w_icon + required_Sinfo + '</span>');
            to_top('#atbdp_socialInFo');
            return false;
        }
        //listing_prv_img
        var listing_prv_img = $("input[name='listing_prv_img']").val();
        var required_listing_prv_img = add_listing_validator.listing_prv_img;
        if ('' === listing_prv_img && '' !== required_listing_prv_img) {
            $("input[name='listing_prv_img']").parents('.form-group').append('<span style="text-align: center;display: block" class="atbdp_required">' + w_icon + required_listing_prv_img + '</span>');
            return false;
        }

        //slider
        var gallery_image = $("#no_image_set").length;
        var required_gallery_image = add_listing_validator.gallery_image;
        if (1 === gallery_image && '' !== required_gallery_image) {
            $(".listing-img-container").append('<span class="atbdp_required">' + w_icon + required_gallery_image + '</span>');
            return false;
        }

        //video
        var video = $("input[name='videourl']").val();
        var required_video = add_listing_validator.video;
        if ('' === video && '' !== required_video) {
            $("input[name='videourl']").after('<span class="atbdp_required">' + w_icon + required_video + '</span>');
            return false;
        }

        //terms
        var terms = $("#listing_t").is(":checked");
        var required_terms = add_listing_validator.terms;
        if (false === terms && '' !== required_terms) {
            $(".atbd_term_and_condition_area").append('<span class="atbdp_required" style="text-align: center;display: block; margin-bottom: 10px">' + w_icon + required_terms + '</span>');
            return false;
        }


    });

});