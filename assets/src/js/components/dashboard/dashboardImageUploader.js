;(function ($) {

    // user dashboard image uploader
    var profileMediaUploader = null;
    if ($("#user_profile_pic").length) {
        profileMediaUploader = new EzMediaUploader({
            containerID: "user_profile_pic",
        });
        profileMediaUploader.init();
    }
    
})(jQuery);