;(function ($) {

    // Review Attatchment
    function handleFiles(files) {
        var preview = document.getElementById('atbd_up_preview');
        for (var i = 0; i < files.length; i++) {
            var file = files[i];

            if (!file.type.startsWith('image/')) {
                continue
            }

            var img = document.createElement("img");
            img.classList.add("atbd_review_thumb");

            var imgWrap = document.createElement('div');
            imgWrap.classList.add('atbd_up_prev');

            preview.appendChild(imgWrap); // Assuming that "preview" is the div output where the content will be displayed.
            imgWrap.appendChild(img);
            $(imgWrap).append('<span class="rmrf">x</span>');


            var reader = new FileReader();
            reader.onload = (function (aImg) {
                return function (e) {
                    aImg.src = e.target.result;
                };
            })(img);
            reader.readAsDataURL(file);
        }
    }

    $('#atbd_review_attachment').on('change', function (e) {
        handleFiles(this.files);
    });
    
})(jQuery);