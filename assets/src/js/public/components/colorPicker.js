let wpColorPicker = document.querySelectorAll('.directorist-color-picker-wrap');
wpColorPicker.forEach(elm=>{
    if(elm !== null){
        let dColorPicker = elm.querySelector('.directorist-color-picker');
        dColorPicker.value !== '' ? dColorPicker.wpColorPicker() : dColorPicker.wpColorPicker().empty();
    }
})
