/* range slider */
export var directorist_range_slider = (selector, obj) => {
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

    var touch = "ontouchstart" in document.documentElement;
    if (touch){
        down 	= 'touchstart';
        up 		= 'touchend';
        move 	= 'touchmove';
    }

    //RTL
    var isRTL = (directorist.rtl === 'true');
    var direction;
    if(isRTL){
        direction = 'right';
    }else{
        direction = 'left';
    }

    var slider = document.querySelectorAll(selector);
    slider.forEach((id, index) => {
        var sliderDataMin = min;
        var sliderDataUnit = id.getAttribute('data-slider-unit');
        id.setAttribute('style', `max-width: ${obj.maxWidth}; border: ${obj.barBorder}; width: 100%; height: 4px; background: ${obj.barColor}; position: relative; border-radius: 2px;`);
        id.innerHTML = div;
        let slide1 	= id.querySelector('.directorist-range-slider1'),
            width 	= id.clientWidth;

        slide1.style.background = obj.pointerColor;
        slide1.style.border = obj.pointerBorder;
        id.closest('.directorist-range-slider-wrap').querySelector('.directorist-range-slider-current-value').innerHTML = `<span>${min}</span> ${sliderDataUnit}`;

        var x 			= null,
            count 		= 0,
            slid1_val 	= 0,
            slid1_val2 	= sliderDataMin,
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
        document.body.addEventListener(up, (event2) => {

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
            id.closest('.directorist-range-slider-wrap').querySelector('.directorist-range-slider-current-value span').innerHTML = sliderDataMin;
            id.querySelector('.directorist-range-slider-minimum').value = sliderDataMin;
            id.querySelector('.directorist-rs-active1').style[direction] = onLoadValue <= 0 ? 0 : onLoadValue +'px';
            id.querySelector('.directorist-range-slider-child').style.width = onLoadValue <= 0 ? 0 : onLoadValue +'px';
        }

        document.body.addEventListener(move, (e) => {
            if(isDraging){
                count = !isRTL ? (e.clientX + slid1_val2 * width / max - x) : (- e.clientX + slid1_val2 * width / max + x);
                if (touch){
                    count = !isRTL ? (e.touches[0].clientX + slid1_val2 * width / max - x) : (- e.touches[0].clientX + slid1_val2 * width / max + x);
                }
                if(count < 0){
                    count = 0;
                } else if(count > count2 - 18){
                    count = count2 - 18;
                }
            }
            if(slide1.classList.contains('directorist-rs-active')){
                slid1_val 	= Math.floor(max/ (width -18) * count);
                id.closest('.directorist-range-slider-wrap').querySelector('.directorist-range-slider-current-value').innerHTML = `<span>${slid1_val}</span> ${sliderDataUnit}`;
                id.querySelector('.directorist-range-slider-minimum').value = slid1_val;
                id.closest('.directorist-range-slider-wrap').querySelector('.directorist-range-slider-value').value = slid1_val;
                id.querySelector('.directorist-rs-active').style[direction] = count +'px';
                id.querySelector('.directorist-range-slider-child').style.width = count+'px';
            }
        });
    });
};

export function directorist_callingSlider() {
    const minValueWrapper = document.querySelector('.directorist-range-slider-value');
    var default_args = {
        maxValue: directorist.args.search_max_radius_distance,
        minValue: parseInt(minValueWrapper && minValueWrapper.value),
        maxWidth: '100%',
        barColor: '#d4d5d9',
        barBorder: 'none',
        pointerColor: '#fff',
        pointerBorder: '4px solid #444752',
    };

    var config = ( directorist.slider_config && typeof directorist.slider_config === 'object' ) ? Object.assign( default_args, directorist.slider_config ) : default_args;

    directorist_range_slider ('.directorist-range-slider', config);
}

window.addEventListener("load", function () {
    directorist_callingSlider();
});