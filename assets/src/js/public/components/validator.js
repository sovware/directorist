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
    }

    // @kowsar @todo rebuild validation
    /*
    $('.directorist-form-submit__btn').on('click', function (e) {
        $('.atbdp_required').css({display: "none"});
        var w_icon = '<span class="fa fa-exclamation-triangle"></span> ';
        //title
        if ($("input[name='listing_title']").length > 0) {
            var title = $("input[name='listing_title']").val();
            var required_title = add_listing_validator.title;
            if ('' === title && '' !== required_title) {
                $("input[name='listing_title']").after('<span class="atbdp_required">' + w_icon + required_title + '</span>');
                to_top('#atbdp_listing_title');
                return false;
            }
        }
        //description
        if ($("#listing_content_ifr").length > 0) {
            var iframe = $('#listing_content_ifr');
            var description = $('#tinymce[data-id="listing_content"]', iframe.contents()).text();
            var required_description = add_listing_validator.description;
            if ('' === description && '' !== required_description) {
                $("#wp-listing_content-wrap").after('<span class="atbdp_required">' + w_icon + required_description + '</span>');
                to_top('#atbdp_listing_content');
                return false;
            }
        }

        //Price
        if ($("input[name='price']").length > 0) {
            var price = $("input[name='price']").val();
            var required_price = add_listing_validator.price;
            if ('' === price && '' !== required_price && !need_post) {
                $("#atbd_pricing").append('<span class="atbdp_required">' + w_icon + required_price + '</span>');
                to_top('#atbd_pricing');

                return false;
            }
        }

        //Price range
        if ($("select[name='price_range']").length > 0) {
            var price_range = $("select[name='price_range']").val();
            var required_price_range = add_listing_validator.price_range;
            if ('' === price_range && '' !== required_price_range) {
                $("#atbd_pricing").after('<span class="atbdp_required">' + w_icon + required_price_range + '</span>');
                to_top('#atbd_pricing');
                return false;
            }
        }

        //excerpt
        if ($('textarea#atbdp_excerpt').length > 0) {
            var excerpt = $("textarea#atbdp_excerpt").val();
            var required_excerpt = add_listing_validator.excerpt;
            if ('' === excerpt && '' !== required_excerpt && !need_post) {
                $("textarea#atbdp_excerpt").after('<span class="atbdp_required">' + w_icon + required_excerpt + '</span>');
                to_top('#atbdp_excerpt');
                return false;
            }
        }

        //location
        if ($("#at_biz_dir-location").length > 0) {
            var location = $("#at_biz_dir-location").val();
            var required_location = add_listing_validator.location;
            if (null === location && '' !== required_location && !need_post) {
                $("#atbdp_locations").append('<span class="atbdp_required">' + w_icon + required_location + '</span>');
                to_top('#atbdp_locations');
                return false;
            }
        }
        //tag
        if ($("#at_biz_dir-tags").length > 0) {
            var tag = $("#at_biz_dir-tags").val();
            var required_tag = add_listing_validator.tag;
            if (null === tag && '' !== required_tag) {
                $("#atbdp_tags").append('<span class="atbdp_required">' + w_icon + required_tag + '</span>');
                to_top('#atbdp_tags');
                return false;
            }
        }

        //category
        if ($("#at_biz_dir-categories").length > 0) {
            var category = $("#at_biz_dir-categories").val();
            var required_category = add_listing_validator.category;
            if (null === category && '' !== required_category) {
                $("#atbdp_categories").append('<span class="atbdp_required">' + w_icon + required_category + '</span>');
                to_top('#atbdp_categories');
                return false;
            }
        }

        //address
        if ($("input[name='address']").length > 0) {
            var address = $("input[name='address']").val();
            var required_address = add_listing_validator.address;
            if ('' === address && '' !== required_address) {
                $("input[name='address']").after('<span class="atbdp_required">' + w_icon + required_address + '</span>');
                to_top('#atbdp_address');
                return false;
            }
        }
        //phone
        if ($("input[name='phone']").length > 0) {
            var phone = $("input[name='phone']").val();
            var required_phone = add_listing_validator.phone;
            if ('' === phone && '' !== required_phone && !need_post) {
                $("#atbdp_phone").append('<span class="atbdp_required">' + w_icon + required_phone + '</span>');
                to_top('#atbdp_phone');
                return false;
            }
        }
        //phone2
        if ($("input[name='phone2']").length > 0) {
            var phone = $("input[name='phone2']").val();
            var required_phone2 = add_listing_validator.phone2;
            if ('' === phone && '' !== required_phone2 && !need_post) {
                $("#atbdp_phone2").append('<span class="atbdp_required">' + w_icon + required_phone2 + '</span>');
                to_top('#atbdp_phone2');
                return false;
            }
        }
        //fax
        if ($("input[name='fax']").length > 0) {
            var fax = $("input[name='fax']").val();
            var required_fax = add_listing_validator.fax;
            if ('' === fax && '' !== required_fax && !need_post) {
                $("#atbdp_fax_number").append('<span class="atbdp_required">' + w_icon + required_fax + '</span>');
                to_top('#atbdp_fax_number');
                return false;
            }
        }
        //email
        if ($("input[name='email']").length > 0) {
            var email = $("input[name='email']").val();
            var required_email = add_listing_validator.email;
            if ('' === email && '' !== required_email && !need_post) {
                $("#atbdp_emails").append('<span class="atbdp_required">' + w_icon + required_email + '</span>');
                to_top('#atbdp_emails');
                return false;
            }
        }

        //web
        if ($("input[name='website']").length > 0) {
            var web = $("input[name='website']").val();
            var required_web = add_listing_validator.web;
            if ('' === web && '' !== required_web && !need_post) {
                $("#atbdp_webs").append('<span class="atbdp_required">' + w_icon + required_web + '</span>');
                to_top('#atbdp_webs');
                return false;
            }
        }

        //zip
        if ($("input[name='zip']").length > 0) {
            var zip = $("input[name='zip']").val();
            var required_zip = add_listing_validator.zip;
            if ('' === zip && '' !== required_zip && !need_post) {
                $("#atbdp_zip_code").append('<span class="atbdp_required">' + w_icon + required_zip + '</span>');
                to_top('#atbdp_zip_code');
                return false;
            }
        }

        //Sinfo
        if ($("#atbdp_socialInFo").length > 0) {
            var Sinfo = $(".directorist-form-social-fields").length;
            var required_Sinfo = add_listing_validator.Sinfo;
            if (0 === Sinfo && '' !== required_Sinfo && !need_post) {
                $("#atbdp_socialInFo").append('<span class="atbdp_required">' + w_icon + required_Sinfo + '</span>');
                to_top('#atbdp_socialInFo');
                return false;
            }
        }

        //video
        if ($("input[name='videourl']").length > 0) {
            var video = $("input[name='videourl']").val();
            var required_video = add_listing_validator.video;
            if ('' === video && '' !== required_video && !need_post) {
                $("input[name='videourl']").after('<span class="atbdp_required">' + w_icon + required_video + '</span>');
                return false;
            }
        }

        //privacy
        if ($("#privacy_policy").length > 0) {
            var privacy = $("#privacy_policy").is(":checked");
            var required_privacy = add_listing_validator.require_privacy;
            if (false === privacy && '' !== required_privacy) {
                $(".directorist-form-privacy").append('<span class="atbdp_required" style="text-align: center;display: block; margin-bottom: 10px">' + w_icon + required_privacy + '</span>');
                return false;
            }
        }

        // terms and conditions
        if ($("#listing_t").length > 0) {
            var terms = $("#listing_t").is(":checked");
            var required_terms = add_listing_validator.terms;
            if (false === terms && '' !== required_terms) {
                $(".directorist-form-terms").append('<span class="atbdp_required" style="text-align: center;display: block; margin-bottom: 10px">' + w_icon + required_terms + '</span>');
                return false;
            }
        }

        //guest user
        if ($("#guest_user_email").length > 0) {
            var guest_user_email = $("input[name='guest_user_email']").val();
            var allow_guest = add_listing_validator.guest_user;
            if ('' === guest_user_email && '' !== allow_guest) {
                $("input[name='guest_user_email']").after('<span class="atbdp_required">' + w_icon + allow_guest + '</span>');
                return false;
            }
        }
    });
    */

});