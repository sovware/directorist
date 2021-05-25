(function () {
    this.swbdRangeSlider = function (selector, args) {
        var default_args = {
            rangeUnitLabel: ' Kilometers',
            maxValue: 1000,
            minValue: 0,
            maxWidth: '100%',
            barColor: '#d4d5d9',
            barBorder: 'none',
            pointerColor: '#fff',
            pointerBorder: '4px solid #444752',
            isRTL: false,
        };

        var obj = (typeof args !== 'undefined') ? Object.assign(default_args, args) : default_args;
        
        var isDraging = false,
            max = obj.maxValue,
            min = obj.minValue,
            down = 'mousedown',
            up = 'mouseup',
            move = 'mousemove',

            div = `
            <div class="atbd-slide1" draggable="true"></div>	
            <input type='hidden' class="atbd-minimum" name="minimum" value=${min} />
            <div class="atbd-child"></div>
		`;

        var touch = "ontouchstart" in document.documentElement;
        if (touch) {
            down = 'touchstart';
            up = 'touchend';
            move = 'touchmove';
        }

        var slider = document.querySelectorAll(selector);
        slider.forEach((id, index) => {
            id.setAttribute('style', `max-width: ${obj.maxWidth}; border: ${obj.barBorder}; width: 100%; height: 4px; background: ${obj.barColor}; position: relative; border-radius: 2px;`);
            id.innerHTML = div;
            let slide1 = id.querySelector('.atbd-slide1'),
                width = id.clientWidth;

            slide1.style.background = obj.pointerColor;
            slide1.style.border = obj.pointerBorder;
            document.querySelector('.atbd-current-value').innerHTML = `<span>${min}</span> ${obj.rangeUnitLabel}`;

            var x = null,
                count = 0,
                slid1_val = 0,
                slid1_val2 = obj.minValue,
                count2 = width;

            if (window.outerWidth < 600) {
                id.classList.add('m-device');
                slide1.classList.add('m-device2');
            }
            slide1.addEventListener(down, (event) => {
                if (!touch) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                x = event.clientX;
                if (touch) {
                    x = event.touches[0].clientX;
                }
                isDraging = true;
                event.target.classList.add('atbd-active');
            });
            window.addEventListener(up, (event2) => {
                if (!touch) {
                    event2.preventDefault();
                    event2.stopPropagation();
                }
                isDraging = false;
                slid1_val2 = slid1_val;
                slide1.classList.remove('atbd-active');
            });

            slide1.classList.add('atbd-active1');
            count = (width / max);
            if (slide1.classList.contains('atbd-active1')) {
                var onLoadValue = count * min;
                document.querySelector('.atbd-current-value span').innerHTML = obj.minValue;
                id.querySelector('.atbd-minimum').value = obj.minValue;

                if ( ! obj.isRTL ) {
                    id.querySelector('.atbd-active1').style.left = onLoadValue <= 0 ? 0 : onLoadValue + 'px';
                } else {
                    id.querySelector('.atbd-active1').style.right = onLoadValue <= 0 ? 0 : onLoadValue +'px';
                }


                id.querySelector('.atbd-child').style.width = onLoadValue <= 0 ? 0 : onLoadValue + 'px';
            }

            window.addEventListener(move, (e) => {
                if (isDraging) {
                    if ( ! obj.isRTL ) {
                        count = e.clientX + slid1_val2 * width / max - x;
                        if (touch) {
                            count = e.touches[0].clientX + slid1_val2 * width / max - x;
                        }
                        if (count < 0) {
                            count = 0;
                        } else if (count > count2 - 18) {
                            count = count2 - 18;
                        }
                    } else {
                        count = - e.clientX + slid1_val2 * width / max + x;

                        if (touch){
                            count = - e.touches[0].clientX + slid1_val2 * width / max + x;
                        }

                        if (count < 0){
                            count = 0;
                        } else if(count > count2 - 19){
                            count = count2 - 19;
                        }
                    }
                    
                }
                if (slide1.classList.contains('atbd-active')) {
                    if ( ! obj.isRTL ) {
                        slid1_val = Math.floor(max / (width - 18) * count);
                        document.querySelector('.atbd-current-value').innerHTML = `<span>${slid1_val}</span> ${obj.rangeUnitLabel}`;
                        id.querySelector('.atbd-minimum').value = slid1_val;
                        document.querySelector('.atbdrs-value').value = slid1_val;
                        id.querySelector('.atbd-active').style.left = count + 'px';
                        id.querySelector('.atbd-child').style.width = count + 'px';
                    } else {
                        slid1_val = Math.floor(max/ (width -19) * count);
                        document.querySelector('.atbd-current-value').innerHTML = `<span>${slid1_val}</span> ${obj.rangeUnitLabel}`;
                        id.querySelector('.atbd-minimum').value = slid1_val;
                        document.querySelector('.atbdrs-value').value = slid1_val;
                        id.querySelector('.atbd-active').style.right = count +'px';
                        id.querySelector('.atbd-child').style.width = count + 'px';
                    }
                }
            });

        });
    };
})();