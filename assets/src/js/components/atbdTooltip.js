;(function ($) {
    
    /* atbd tooltip */
    function atbdp_tooltip(){
        var atbd_tooltip = document.querySelectorAll('.atbd_tooltip');
        atbd_tooltip.forEach(function(el){
            if(el.getAttribute('aria-label') !== " "){
                document.body.addEventListener('mouseover', function(e) {
                    for (var target = e.target; target && target != this; target = target.parentNode) {
                        if (target.matches('.atbd_tooltip')) {
                            el.classList.add('atbd_tooltip_active');
                        }
                    }
                }, false);
            }
        });
    }
    atbdp_tooltip();

})(jQuery);