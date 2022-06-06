/* Initialize wpColorPicker */
(function($){
    $(document).ready(function(){
        /* Initialize wp color picker */
        function colorPickerInit(){
            let wpColorPicker = document.querySelectorAll('.directorist-color-picker-wrap');
            wpColorPicker.forEach(elm=>{
                if(elm !== null){
                    let dColorPicker = $('.directorist-color-picker');
                    dColorPicker.value !== '' ? dColorPicker.wpColorPicker() : dColorPicker.wpColorPicker().empty();
                }
            })
        }
        colorPickerInit();
        /* Initialize on Directory type change */
        document.body.addEventListener('directorist-search-form-nav-tab-reloaded', colorPickerInit)
    })
})(jQuery)