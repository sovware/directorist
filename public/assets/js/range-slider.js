/* range slider */
const atbd_slider = (selector, obj) => {
    var isDraging 	= false,
        max 		= obj.maxValue + (Math.ceil(obj.maxValue*2.285714285714286/100)),
        min 		= obj.minValue + (Math.ceil(obj.minValue*1.633/100)),
        down 		= 'mousedown',
        up 			= 'mouseup',
        move 		= 'mousemove',

        div = `
            <div class="atbd-slide1" draggable="true"></div>	
            <input type='hidden' class="atbd-minimum" name="minimum" value=${min} />
            <div class="atbd-child"></div>
		`;

    if ("ontouchstart" in document.documentElement){
        down 	= 'touchstart';
        up 		= 'touchend';
        move 	= 'touchmove';
    }

    const slider = document.querySelectorAll(selector);

    slider.forEach((id, index) => {
        id.setAttribute('style', `max-width: ${obj.maxWidth}; border: ${obj.barBorder}; width: 100%; height: 4px; background: ${obj.barColor}; position: relative; border-radius: 2px;`);
        id.innerHTML = div;
        let slide1 	= id.querySelector('.atbd-slide1'),
            width 	= id.clientWidth;

        slide1.style.background = obj.pointerColor;
        slide1.style.border = obj.pointerBorder;
        document.querySelector('.atbd-current-value').innerHTML = `<span>${min}</span> ${atbdp_range_slider.Miles}`;

        var x 			= null,
            count 		= 0,
            x2 			= null,
            slid1_val 	= 0,
            slid1_val2 	= obj.minValue,
            count2 		= width;

        if(window.outerWidth < 600){
            id.classList.add('m-device');
            slide1.classList.add('m-device2');
            //slide2.classList.add('m-device2');
        }
        slide1.addEventListener(down, (event) => {
            event.preventDefault();
            event.stopPropagation();
            x = event.clientX;
            if ("ontouchstart" in document.documentElement){
                x = event.touches[0].clientX;
            }
            isDraging = true;
            event.target.classList.add('atbd-active');
        });
        window.addEventListener(up, (event2) => {
            event2.preventDefault();
            event2.stopPropagation();
            isDraging 	= false;
            slid1_val2 	= slid1_val;
            slide1.classList.remove('atbd-active');
        });
        window.addEventListener('load', (e) => {
            slide1.classList.add('atbd-active1');
            count = (width / max);
            if(slide1.classList.contains('atbd-active1')){
                var onLoadValue 	= count * min;
                document.querySelector('.atbd-current-value span').innerHTML = obj.minValue;
                id.querySelector('.atbd-minimum').value = obj.minValue;
                id.querySelector('.atbd-active1').style.left = onLoadValue <= 0 ? 0 : onLoadValue - 18 +'px';
                id.querySelector('.atbd-child').style.width = onLoadValue <= 0 ? 0 : onLoadValue - 18 +'px';
            }
        });
        window.addEventListener(move, (e) => {
            if(isDraging){
                count = e.clientX + slid1_val2 * width / max - x;
                if ("ontouchmove" in document.documentElement){
                    count = event.touches[0].clientX * width / max - x;
                }
                if(count < 0){
                    count = 0;
                } else if(count > count2 - 18){
                    count = count2 - 18;
                }
            }
            if(slide1.classList.contains('atbd-active')){
                slid1_val 	= Math.floor(max/ width * count);
                document.querySelector('.atbd-current-value').innerHTML = `<span>${slid1_val}</span> ${atbdp_range_slider.Miles}`;
                id.querySelector('.atbd-minimum').value = slid1_val;
                document.querySelector('.atbdrs-value').value = slid1_val;
                id.querySelector('.atbd-active').style.left = count +'px';
                id.querySelector('.atbd-child').style.width = count+'px';
            }
        });

    });
};
function atbd_callingSlider() {
    atbd_slider ('#atbdp-range-slider', {
        maxValue: 1000,
        minValue: 50,
        maxWidth: '100%',
        barColor: '#d4d5d9',
        barBorder: 'none',
        pointerColor: '#fff',
        pointerBorder: '4px solid #444752',
    });
}
atbd_callingSlider();
