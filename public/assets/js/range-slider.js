/* range slider */
const atbd_slider = (selector, obj) => {
    var isDraging 	= false,
        max 		= obj.maxValue + (Math.ceil(obj.maxValue*1.633/100)),
        min 		= obj.minValue,
        down 		= 'mousedown',
        up 			= 'mouseup',
        move 		= 'mousemove',

        div = `<div class="atbd-slide1" draggable="true"></div>		
		<p class="atbd-min" style="color : ${obj.fontColor}; font-size: ${obj.fontSize} "></p>		
		<input type='hidden' class="atbd-minimum" name="minimum" value=${min} /><div class="atbd-child"></div>
		`;

    if ("ontouchstart" in document.documentElement){
        down 	= 'touchstart';
        up 		= 'touchend';
        move 	= 'touchmove';
    }

    const slider = document.querySelectorAll(selector);

    slider.forEach((id, index) => {
        id.setAttribute('style', `max-width: ${obj.maxWidth}; border: ${obj.barBorder}; width: 100%; height: 10px; background: ${obj.barColor}; position: relative; border-radius: 20px;`);
        id.innerHTML = div;
        let slide1 	= id.querySelector('.atbd-slide1'),
            width 	= id.clientWidth;

        slide1.style.background = obj.pointerColor;
        slide1.style.border = obj.pointerBorder;

        id.querySelector('.atbd-min').innerHTML = min;

        var x 			= null,
            count 		= 0,
            x2 			= null,
            slid1_val 	= 0,
            slid1_val2 	= 0,
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
        window.addEventListener(move, (e) => {
            if(isDraging){
                count = e.clientX + slid1_val2 * width / max - x;
                if ("ontouchmove" in document.documentElement){
                    count = event.touches[0].clientX * width / max - x;
                }
                if(count < 0){
                    count = 0;
                } else if(count > count2 - 30){
                    count = count2 - 30;
                }
            }
            if(slide1.classList.contains('atbd-active')){
                slid1_val 	= Math.floor(max/ width * count);
                id.querySelector('.atbd-min').innerHTML = slid1_val;
                id.querySelector('.atbd-minimum').value = slid1_val;
                id.querySelector('.atbd-active').style.left = count +'px';
                id.querySelector('.atbd-child').style.width = count+'px';
            }
        });

    });
};

function atbd_callingSlider() {
    atbd_slider ('#atbdp-range-slider', {
        maxValue: 500,
        minValue: 0,
        maxWidth: '100%',
        barColor: '#f5548e',
        barBorder: 'none',
        pointerColor: '#000',
        pointerBorder: 'none',
        fontColor: '#f5548e',
        fontSize: '20px'
    });
}
atbd_callingSlider();
