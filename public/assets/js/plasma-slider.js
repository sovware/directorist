/**
 * Plasma Slider
 * Company: Aazz Tech
 * Develoepr: Syed Galib Ahmed
 * Version: 1.0
 * Initial Release: 1 February, 2020
 * */

(function() {
  this.PlasmaSlider = function( args ) {
    // Defaults
    this.options = {
      containerID: null,
      width: 16,
      height: 9,
      images: [],
      backgroundSize: 'cover',
      blurBackground: true,
      backgroundColor: 'gainsboro',
      thumbnailBackgroundColor: '#fff',
      showThumbnails: true,
      rtl: false,
    };

    // Data
    this.container = null;

    // extendDefaults
    this.extendDefaults = function() {
      this.options = syncData(this.options, args);
    };

    // init
    this.init = function() {
      // Extend Defaults
      this.extendDefaults();

      // Set Container
      var id = this.options.containerID;
      var containerElm = document.getElementById(id);
      if (!containerElm) {
        return;
      }
      this.container = containerElm;
      
      if ( hasClass(this.container, 'is-initialized') ) {
        return;
      }

      addClass(this.container, 'is-initialized');

      // Get Markup Options
      this.getMarkupOptions();

      // Prepare DOM
      this.prepareDOM();

      // Attach Events
      this.attachEvents();
    };

    // getMarkupOptions
    this.getMarkupOptions = function() {
      if ( !this.container ) { return; }

      // Extend Settings Data
      var width = this.container.getAttribute('data-width');
      if ( width && isPositiveNumber(width)) {
        this.options.width = isPositiveNumber(width);
      }

      var height = this.container.getAttribute('data-height');
      if ( height && isPositiveNumber(height)) {
        this.options.height = isPositiveNumber(height);
      }

      var show_thumbnails = this.container.getAttribute('data-show-thumbnails');
      if ( show_thumbnails && isBoolean(show_thumbnails) !== null ) {
        this.options.showThumbnails = isBoolean(show_thumbnails);
      }

      var rtl = this.container.getAttribute('data-rtl');
      if ( rtl && isBoolean(rtl) !== null ) {
        this.options.rtl = isBoolean(rtl);
      }

      var background_size = this.container.getAttribute('data-background-size');
      if ( background_size ) {
        this.options.backgroundSize = background_size;
      }

      var blur_background = this.container.getAttribute('data-blur-background');
      if ( blur_background && isBoolean(blur_background) !== null ) {
        this.options.blurBackground = isBoolean(blur_background);
      }

      var background_color = this.container.getAttribute('data-background-color');
      if ( background_color ) {
        this.options.backgroundColor = background_color;
      }

      var thumbnail_background_color = this.container.getAttribute('data-thumbnail-background-color');
      if ( thumbnail_background_color ) {
        this.options.thumbnailBackgroundColor = thumbnail_background_color;
      }

      // Extend Image Data
      var images_data = this.container.querySelectorAll('.plasmaSliderImageItem');
      if ( images_data && images_data.length ) {
        var image_list = [];
        forEach( images_data, function( image ) {
          var src = image.getAttribute('data-src');
          var alt = image.getAttribute('data-alt');
          var data = {};
          if ( src ) {
            data.src = ( src ) ? src : '';
            data.alt = ( alt ) ? alt : '';
            image_list.push(data);
          }
        });

        if ( image_list.length ) {
          this.options.images = image_list;
        }
        
      }
    };

    // prepareDOM
    this.prepareDOM = function() {
      if ( !this.container ) { return; }

      this.container.innerHTML = '';
      addClass(this.container, 'plasmaSlider');

      // Slider Body Elm
      var body_elm = createElementWithClass('plasmaSlider__body');

      var ratio_width = this.options.width;
      var ratio_height = this.options.height;
      var padding_top = (ratio_height / ratio_width) * 100;
      body_elm.setAttribute('style', 'padding-top: ' + padding_top + '%');
      
      if ( this.options.images.length > 1 ) {
        // Slider Right Control
        var right_control = createDOMSliderRightControl();
        body_elm.appendChild(right_control);

        // Slider Left Control
        var left_control = createDOMSliderLeftControl();
        body_elm.appendChild(left_control);
      }
      

      // Slider Contents
      var contents = createDOMSliderContents(this);
      body_elm.appendChild(contents);

      this.container.appendChild(body_elm);

      if ( this.options.showThumbnails && this.options.images.length > 1 ) {
        // Slider Footer
        var footer = createDOMSliderfooter(this);
        this.container.appendChild(footer);
      }
      
    };

    // attachEvents
    this.attachEvents = function() {
      if ( !this.container ) { return; }
      var self = this;

      // Navigation Events
      var arrow_btn = this.container.querySelectorAll(
        '.plasmaSlider__arrowBtn'
      );

      if ( arrow_btn && arrow_btn.length ) {
        forEach( arrow_btn, function(btn) {
          btn.addEventListener('click', function(e) {
            var class_name = e.target.className;
            var target = ( class_name === 'plasmaSliderSkin' ) ? e.target.parentElement : e.target;
            self.handleArrowEvent( target );
          });
        })
      }

      if ( this.options.showThumbnails ) {
        var thumbnail_items = this.container.querySelectorAll('.plasmaSlider__thumbnailListItem');
        if ( thumbnail_items && thumbnail_items.length ) {
          forEach( thumbnail_items, function(thumbnail_item) {
            thumbnail_item.addEventListener('click', function(e) {
              self.handleThumbnailEvent( e.target.parentElement );
            });
          });
        }
      }
      
    };

    // handleArrowEvent
    this.handleArrowEvent = function( target_elm ) {
      var self = this;
      var target = target_elm;
      var direction = target.getAttribute('data-direction');

      var current_item_elm = self.container.querySelectorAll(
        '.plasmaSlider__contentsWrap .plasmaSlider__sliderItem.active'
      );
      var current_index = indexOf(current_item_elm[0]);
      if ( current_index === null || current_index < 0) { return; }

      var next_index = ( direction === 'right' ) ? current_index + 1 : current_index - 1;
      if ( this.options.rtl ) {
        next_index = ( direction === 'right' ) ? current_index - 1 : current_index + 1;
      }
      this.changeSlideTo(next_index);
    };

    // handleThumbnailEvent
    this.handleThumbnailEvent = function ( target_elm ) {
      var get_index = indexOf(target_elm);
      this.changeSlideTo(get_index);
    };

    // changeSlideTo
    this.changeSlideTo = function( next_index ) {
      var total_items = this.options.images.length - 1;
      next_index = ( next_index < 0 ) ? total_items : next_index;
      next_index = ( next_index > total_items ) ? 0 : next_index;

      var width_style = 'width: '+ ((total_items + 1) * 100) +'%;';
      var left = ( next_index === 0 ) ? 'left: ' : 'left: -';
      if ( this.options.rtl ) {
        left = ( next_index === 0 ) ? 'right: ' : 'right: -';
      }
      var style = width_style + left + (next_index * 100) + '%;'
      
      var contents_wrap = this.container.querySelectorAll('.plasmaSlider__contentsWrap');
      contents_wrap[0].setAttribute('style', style);

      var current_item_elm = this.container.querySelectorAll(
        '.plasmaSlider__contentsWrap .plasmaSlider__sliderItem.active'
      );

      removeClass(current_item_elm[0], 'active');
      var next_item = contents_wrap[0].children.item(next_index);
      addClass(next_item, 'active');

      this.updateNavigation(next_index);
    };

    // updateNavigation
    this.updateNavigation = function( next_index ) {
      if ( this.options.showThumbnails ) {
        // Update Thumbnail List Navivation
        var thumbnail_list_items = this.container.querySelectorAll(
          '.plasmaSlider__thumbnailList .plasmaSlider__thumbnailListItem'
        );

        if ( thumbnail_list_items && thumbnail_list_items.length ) {
          forEach( thumbnail_list_items, function(list_item, index) {
            removeClass(list_item, 'active');

            if ( index === next_index ) {
              addClass( list_item, 'active' );
            }
          });

          // Update Scroll Position
          var thumbnail_list = thumbnail_list_items[0].parentElement;
          var thumbnail_list_width = thumbnail_list.clientWidth;
          var scrollLeftMax = thumbnail_list.scrollLeftMax;

          var thumbnail_next_item_offset = thumbnail_list_items[next_index].offsetLeft;
          var thumbnail_next_item_width = thumbnail_list_items[next_index].offsetWidth;
      
          var scroll_left = thumbnail_next_item_offset;
          var scroll_left_w = scroll_left + thumbnail_next_item_width;
          scroll_left = ( scroll_left > scrollLeftMax ) ? scrollLeftMax : scroll_left;
          scroll_left = ( scroll_left < 0 ) ? 0 : scroll_left;

          var current_visible_offset_start = thumbnail_list.scrollLeft;
          var current_visible_offset_end = current_visible_offset_start + thumbnail_list_width;

          if ( scroll_left < current_visible_offset_start || scroll_left_w > current_visible_offset_end ) {
            thumbnail_list.scrollLeft = scroll_left;
          }
        }
      }
    }
  };


  // -----------------------
  // Helpers
  // -----------------------
  // createDOMSliderContents
  function createDOMSliderContents( self ) {
    var contents = createElementWithClass('plasmaSlider__contents');
    var contents_wrap = createElementWithClass('plasmaSlider__contentsWrap');

    if ( Array.isArray(self.options.images) && self.options.images.length ) {
      var width_value = (self.options.images.length) * 100;
      var width = 'width: '+ width_value + '%;';
      var style = width;

      contents_wrap.setAttribute('style', style);
      var images = self.options.images;

      forEach(images, function(image, index) {
        var active = ( index === 0 ) ? true : false;
        var slider_item = createDOMSliderItem({
          src: ( 'src' in image) ? image.src : '',
          alt: ( 'alt' in image) ? image.alt : '',
          active: active,
        }, self);
        contents_wrap.appendChild(slider_item);
      });
    }
    
    contents.appendChild(contents_wrap);
    return contents;
  }

  // createDOMSliderItem
  function createDOMSliderItem( args, self ) {
    var opt = { src: '', alt: '', active: false };
    opt = syncData(opt, args);

    var active_class = ( opt.active ) ? ' active' : '';
    var slider_item = createElementWithClass('plasmaSlider__sliderItem' + active_class);
    var background_size = self.options.backgroundSize;
    var slider_item_bg = createElementWithClass('plasmaSlider__bg');
    var background_color = self.options.backgroundColor;
    slider_item_bg.setAttribute('style', 'background-color: ' + background_color +';' );

    if ( 'contain' === self.options.backgroundSize && self.options.blurBackground ) {
      var slider_item_img_back = createElementWithClass('plasmaSlider__bgImgBlur', 'img');
      slider_item_img_back.src = opt.src;
      slider_item_bg.appendChild(slider_item_img_back);
    }
    

    var slider_item_img_front = createElementWithClass('plasmaSlider__bgImg plasmaSlider__' + background_size, 'img');
    slider_item_img_front.src = opt.src;
    slider_item_img_front.alt = opt.alt;
    slider_item_bg.appendChild(slider_item_img_front);
    
    slider_item.appendChild(slider_item_bg);

    return slider_item;
  }

  function createDOMSliderfooter( self ) {
    var footer = createElementWithClass('plasmaSlider__footer');

    if ( !(Array.isArray(self.options.images) ) ) {
      return footer;
    }

    var thumbnailList = createElementWithClass('plasmaSlider__thumbnailList');
    if ( self.options.thumbnailBackgroundColor.length ) {
      var style = 'background-color: ' + self.options.thumbnailBackgroundColor;
      thumbnailList.setAttribute( 'style', style );
    }
    forEach(self.options.images, function(image, index) {
      var _class_name = 'plasmaSlider__thumbnailListItem';
      var active_class = ( index === 0 ) ? _class_name + ' active' : _class_name;
      var class_name = active_class;

      var thumbnailListItem = createElementWithClass(class_name);
      var thumbnailListItemImg = createElementWithClass('plasmaSlider__thumbnailListItemImg', 'img');

      thumbnailListItemImg.src = ( 'src' in image ) ? image.src : '';
      thumbnailListItemImg.alt = ( 'alt' in image ) ? image.alt : 'Thumbnail Image';

      thumbnailListItem.appendChild(thumbnailListItemImg);
      thumbnailList.appendChild(thumbnailListItem);
    });

    footer.appendChild(thumbnailList);
    return footer;
  }

  // createDOMSliderRightControl
  function createDOMSliderRightControl() {
    var control_right = createElementWithClass('plasmaSlider__controlRight');

    var control_right_arrow_btn = createElementWithClass(
      'plasmaSlider__arrowBtn arrowBtn--right', 
      'button', 
      '<span class="plasmaSliderIcon psi-angle-right"></span><span class="plasmaSliderSkin"></span>'
    );
    control_right_arrow_btn.setAttribute('type', 'button');
    control_right_arrow_btn.setAttribute('data-direction', 'right');
    control_right.appendChild(control_right_arrow_btn);

    return control_right;
  }

  // createDOMSliderLeftControl
  function createDOMSliderLeftControl() {
    var control_left = createElementWithClass('plasmaSlider__controlLeft');
    var control_left_arrow_btn = createElementWithClass(
      'plasmaSlider__arrowBtn arrowBtn--left', 
      'button', 
      '<span class="plasmaSliderIcon psi-angle-left"></span><span class="plasmaSliderSkin"></span>'
    );
    control_left_arrow_btn.setAttribute('type', 'button');
    control_left_arrow_btn.setAttribute('data-direction', 'left');
    control_left.appendChild(control_left_arrow_btn);

    return control_left;
  }

  // syncData
  function syncData( default_data, new_data ) {
    if (!new_data) {
      return default_data;
    }

    if (new_data && typeof new_data !== "object") {
      return defaults;
    }

    var property;
    for (property in new_data) {
      if (default_data.hasOwnProperty(property)) {
        default_data[property] = new_data[property];
      }
    }
    return default_data;
  }

  // isPositiveNumber
  function isPositiveNumber( data ) {
    var data_is_valid = false;

    if ( typeof data === 'string' ) {
      var has_non_digit = data.match(/[^\d]/);
      var chk = (!has_non_digit) ? parseInt(data) : null;
      data_is_valid = (chk && chk > 0) ? true : false;
      data = (data_is_valid) ? chk : data;
    }

    if ( typeof data === 'number' ) {
      data = ( data < 1 ) ? false : data;
      data_is_valid = ( data ) ? true : false;
    }

    return ( data_is_valid ) ? data : false; 
  }

  // isBoolean
  function isBoolean( data ) {
    var value = null;
    if ( data ) {
      switch (data) {
        case '0':
          value = false;
          break;
        case 'false':
          value = false;
          break;
        case '1':
          value = true;
          break;
        case 'true':
          value = true;
          break;
      }
    }

    return value;
  }

  // createElementWithClass
  function createElementWithClass(class_name, elm, child) {
    class_name = ( typeof class_name === 'undefined' ) ? "" : class_name;
    elm = ( typeof elm === 'undefined' ) ? "div" : elm;
    child = ( typeof child === 'undefined' ) ? "" : child;

    var element = document.createElement(elm);
    addClass(element, class_name);

    element.innerHTML = child;
    return element;
  }

  // addClass
  function addClass(element, class_name) {
    if ( !(typeof element === 'object' && 'className' in element) ) { return; }

    var arr = element.className.split(" ");
    if (arr.indexOf(class_name) == -1) {
      element.className += " " + class_name;
      element.className = element.className.trim();
    }
  }

  // removeClass
  function removeClass(element, class_name) {
    if (!element) {
      return;
    }
    var regExp = new RegExp(class_name, "g");
    element.className = element.className.replace(regExp, "");
    element.className = element.className.trim();
  }

  // hasClass
  function hasClass(element, class_name) {
    if (!element) {
      return;
    }
    var regExp = new RegExp(class_name, "g");
    var match = element.className.match(regExp);

    return match;
  }

  // indexOf
  function indexOf( elm ) {
    if ( !elm ) { return null; }
    if ( !('parentElement' in elm && elm.parentElement) ) { return null; }
    
    var parent = elm.parentElement.children;
    var index = [].slice.call(parent).indexOf(elm);
    return index;
  }

  // forEach
  function forEach(array, cb) {
    for (var i = 0; i < array.length; i++) {
      cb(array[i], i);
    }
  }
})();