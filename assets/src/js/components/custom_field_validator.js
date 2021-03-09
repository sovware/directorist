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

});