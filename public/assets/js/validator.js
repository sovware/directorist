jQuery(document).ready(function ($) {
    $('.listing_submit_btn').on('click', function () {
        var title = $("input[name='listing_title']").val();
       // var title = $("input[name='listing_title']").val();



        var required_title = add_listing_validator.title;
        if ('' === title && '' !== required_title){
            $(".title_required").attr("style", "display:inline-block").append('<span>'+required_title+'</span>');
            $([document.documentElement, document.body]).animate({
                scrollTop: 0}, 1000);
            return false;
        }

    });

});