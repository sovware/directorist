/* range slider */
const directorist_range_slider = (selector, obj) => {
    var isDraging 	= false,
        max 		= obj.maxValue,
        min 		= obj.minValue,
        down 		= 'mousedown',
        up 			= 'mouseup',
        move 		= 'mousemove',

        div = `
            <div class="directorist-range-slider1" draggable="true"></div>
            <input type='hidden' class="directorist-range-slider-minimum" name="minimum" value=${min} />
            <div class="directorist-range-slider-child"></div>
		`;

    const touch = "ontouchstart" in document.documentElement;
    if (touch){
        down 	= 'touchstart';
        up 		= 'touchend';
        move 	= 'touchmove';
    }

    const slider = document.querySelectorAll(selector);

    slider.forEach((id, index) => {
        var sliderData = JSON.parse(id.getAttribute('data-slider'));
        min = sliderData.minValue;
        id.setAttribute('style', `max-width: ${obj.maxWidth}; border: ${obj.barBorder}; width: 100%; height: 4px; background: ${obj.barColor}; position: relative; border-radius: 2px;`);
        id.innerHTML = div;
        let slide1 	= id.querySelector('.directorist-range-slider1'),
            width 	= id.clientWidth;

        slide1.style.background = obj.pointerColor;
        slide1.style.border = obj.pointerBorder;
        document.querySelector('.directorist-range-slider-current-value').innerHTML = `<span>${min}</span> ${sliderData.miles}`;

        var x 			= null,
            count 		= 0,
            slid1_val 	= 0,
            slid1_val2 	= sliderData.minValue,
            count2 		= width;

        if(window.outerWidth < 600){
            id.classList.add('m-device');
            slide1.classList.add('m-device2');
        }
        slide1.addEventListener(down, (event) => {
            if(!touch){
                event.preventDefault();
                event.stopPropagation();
            }
            x = event.clientX;
            if (touch){
                x = event.touches[0].clientX;
            }
            isDraging = true;
            event.target.classList.add('directorist-rs-active');
        });
        window.addEventListener(up, (event2) => {
            if(!touch){
                event2.preventDefault();
                event2.stopPropagation();
            }
            isDraging 	= false;
            slid1_val2 	= slid1_val;
            slide1.classList.remove('directorist-rs-active');
        });

        slide1.classList.add('directorist-rs-active1');
        count = (width / max);
        if(slide1.classList.contains('directorist-rs-active1')){
            var onLoadValue 	= count * min;
            document.querySelector('.directorist-range-slider-current-value span').innerHTML = sliderData.minValue;
            id.querySelector('.directorist-range-slider-minimum').value = sliderData.minValue;
            id.querySelector('.directorist-rs-active1').style.right = onLoadValue <= 0 ? 0 : onLoadValue +'px';
            id.querySelector('.directorist-range-slider-child').style.width = onLoadValue <= 0 ? 0 : onLoadValue +'px';
        }

        window.addEventListener(move, (e) => {
            if(isDraging){
                count = - e.clientX + slid1_val2 * width / max + x;

                if (touch){
                    count = - e.touches[0].clientX + slid1_val2 * width / max + x;
                }
                if(count < 0){
                    count = 0;
                } else if(count > count2 - 19){
                    count = count2 - 19;
                }
            }
            if(slide1.classList.contains('directorist-rs-active')){
                slid1_val 	= Math.floor(max/ (width -19) * count);
                document.querySelector('.directorist-range-slider-current-value').innerHTML = `<span>${slid1_val}</span> ${sliderData.miles}`;
                id.querySelector('.directorist-range-slider-minimum').value = slid1_val;
                document.querySelector('.directorist-range-slider-value').value = slid1_val;
                id.querySelector('.directorist-rs-active').style.right = count +'px';
                id.querySelector('.directorist-range-slider-child').style.width = count+'px';

            }
        });

    });
};

function directorist_callingSlider() {
    var default_args = {
        maxValue: 1000,
        minValue: 0,
        maxWidth: '100%',
        barColor: '#d4d5d9',
        barBorder: 'none',
        pointerColor: '#fff',
        pointerBorder: '4px solid #444752',
    };

    var config = ( ( typeof atbdp_range_slider !== 'undefined' ) && atbdp_range_slider.slider_config && typeof atbdp_range_slider.slider_config === 'object' ) ? Object.assign( default_args, atbdp_range_slider.slider_config ) : default_args;

    directorist_range_slider ('.directorist-range-slider', config);
}

window.addEventListener("load", function () {
    directorist_callingSlider();
});

