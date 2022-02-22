/* Initialize wpColorPicker */
(function($){
    $(document).ready(function(){
        let wpColorPicker = document.querySelectorAll('.directorist-color-picker-wrap');
        wpColorPicker.forEach(elm=>{
            if(elm !== null){
                let dColorPicker = $('.directorist-color-picker');
                dColorPicker.value !== '' ? dColorPicker.wpColorPicker() : dColorPicker.wpColorPicker().empty();
            }
        })
    })
})(jQuery)