/**
 * EZ Media Uploader
 * Company: Aazz Tech
 * Develoepr: Syed Galib Ahmed
 * Version: 1.0
 * Initial Release: 24 December, 2019
 * */

/* eslint-disable */
(function() {
  this.EZMediaUploader = function(args) {
    var defaults = {
      containerID: "ez-media-uploader",
      oldFiels: null,
      oldFielsUrl: null,
      maxFileSize: 2048,
      maxTotalFileSize: 4096,
      allowedFileFormats: ["images"],
      allowMultiple: true,
      featured: true
    };

    // Data
    // -----------------------------------------
    this.options = extendDefaults(defaults, args);
    this.oldFiles = [];
    this.files = [];
    this.filesMeta = [];
    this.isDragging = false;
    this.draggingCounter = 0;

    // Elements
    this.container = null;
    this.uploadButtonContainer = null;
    this.mediaPickerSection = null;
    this.previewSection = null;
    this.thumbnailArea = null;
    this.statusSection = null;
    this.loadingSection = null;

    // Methods
    // -----------------------------------------
    // init
    this.init = function() {
      var id = this.options.containerID;
      var container = document.getElementById(id);
      if (!container) {
        return;
      }
      this.container = container;

      this.attachElements();
      this.loadOldFiels();
      this.attachEventListener();
    };

    this.getFiles = function() {
      var final_files = [];
      if (!this.filesMeta.length) {
        return final_files;
      }

      forEach(this.filesMeta, function(file) {
        var meta_data = {
          id: file.id,
          url: file.url,
          oldFile: file.oldFile
        };

        if ("file" in file) {
          meta_data.file = file.file;
          meta_data.name = file.name;
          meta_data.fileSize = file.fileSize;
          meta_data.fileSizeInText = file.fileSizeInText;
          meta_data.type = file.type;
          meta_data.limitExceeded = file.limitExceeded;
        }

        final_files.push(meta_data);
      });

      return final_files;
    };

    this.validateFiles = function() {
      var files = this.filesMeta;
      var error_log = [];

      if (!files.length) {
        updateValidationFeedback(error_log, this.statusSection);
        return true;
      }

      // Validate Max Total File Size
      var maxTotalFileSize = this.options.maxTotalFileSize;
      var max_total_file_size_in_byte = maxTotalFileSize * 1024;
      var max_total_file_size_in_text = maxTotalFileSize > 1023 ? maxTotalFileSize / 1024 + ' MB' : maxTotalFileSize + ' KB';
      var total_file_size_in_byte = 0;

      forEach(files, function(file) {
        if ('fileSize' in file) {
          total_file_size_in_byte += file.fileSize;
        }
      });

      if (total_file_size_in_byte > max_total_file_size_in_byte) {
        error_log.push({
          errorKey: "maxTotalFileSize",
          message: "Max limit for total file size is " + max_total_file_size_in_text
        });
      }

      

      updateValidationFeedback(error_log, this.statusSection);

      if (error_log.length) {
        return error_log;
      }

      return true;
    };

    this.hasValidFiles = function() {
      if (this.validateFiles() === true) {
        return true;
      }

      return false;
    };

    this.loadOldFiels = function() {
      if (!this.container) {
        return;
      }

      var old_fiels = this.options.oldFiels;
      if (!old_fiels) {
        return;
      }

      var validated_files = this.getValidatedPaths(old_fiels);
      if (!validated_files) {
        return;
      }

      for (var i = 0; i < validated_files.length; i++) {
        var file = validated_files[i];
        var filesMeta = {
          id: i,
          url: file.url,
          oldFile: true
        };

        if ("type" in file) {
          filesMeta.type = file.type;
        }

        if ("size" in file) {
          filesMeta.fileSize = file.size * 1024; 
          filesMeta.fileSizeInText = formatedFileSize(file.size * 1024); 
        }

        this.filesMeta.push(filesMeta);
      }
      this.updatePreview();
    };

    this.getValidatedPaths = function(paths) {
      if (!Array.isArray(paths)) {
        return null;
      }

      if (!paths.length) {
        return null;
      }

      var validated_paths = [];
      for (var i = 0; i < paths.length; i++) {
        var path = paths[i];

        if (typeof path !== "object") {
          continue;
        }
        if (!("url" in path)) {
          continue;
        }

        validated_paths.push(path);
      }

      if (!validated_paths.length) {
        return null;
      }

      return validated_paths;
    };

    // attachElements
    this.attachElements = function() {
      if (!this.container) {
        return;
      }
      var container = this.container;
      addClass(container, "ez-media-uploader");
      container.innerHTML = "";

      var drop_zone_section_elm = createDropZoneSection();
      var loading_section_elm = createLoadingSection();
      var media_picker_elm = createMediaPickerSection(this);
      var preview_section_elm = createPreviewSection();
      var status_section_elm = createStatusSection();

      container.appendChild(drop_zone_section_elm);
      container.appendChild(loading_section_elm);
      container.appendChild(media_picker_elm);
      container.appendChild(preview_section_elm);
      container.appendChild(status_section_elm);

      var upload_button_container = container.querySelectorAll(
        ".ezmu__upload-button-wrap"
      );
      var media_picker_section = container.querySelectorAll(
        ".ezmu__media-picker-section"
      );
      var preview_section = container.querySelectorAll(
        ".ezmu__preview-section"
      );
      var thumbnail_area = container.querySelectorAll(".ezmu__thumbnail-area");
      var status_section = container.querySelectorAll(".ezmu__status-section");
      var loading_section = container.querySelectorAll(
        ".ezmu__loading-section"
      );

      this.uploadButtonContainer = upload_button_container ? upload_button_container[0] : null;
      this.mediaPickerSection = media_picker_section ? media_picker_section[0] : null;
      this.previewSection = preview_section ? preview_section[0] : null;
      this.thumbnailArea = thumbnail_area ? thumbnail_area[0] : null;
      this.statusSection = status_section ? status_section[0] : null;
      this.loadingSection = loading_section ? loading_section[0] : null;
    };

    // attachEventListener
    this.attachEventListener = function() {
      if (!this.layoutIsReady()) {
        return;
      }
      var self = this;

      // Attach File Change Listener
      this.attachFileChangeListener();

      // Attach Drag & Drop Listener
      this.attachDragNDropListener();

      document.addEventListener("click", function(e) {
        if (!e.target) {
          return;
        }
        // Close Button Event
        if (hasClass(e.target, "ezmu__front-item__close-btn")) {
          self.removeFile(e);
        }

        // Sort Button Event
        if (hasClass(e.target, "ezmu__front-item__sort-button-skin")) {
          self.changeOrder(e);
        }
      });
    };

    // attachFileChangeListener
    this.attachFileChangeListener = function() {
      var file_input = this.container.querySelectorAll("#ezmu__file-input");
      var fileInputElm = file_input ? file_input[0] : null;

      if (fileInputElm) {
        var self = this;
        fileInputElm.addEventListener("change", function(event) {
          var files = event.target.files;
          if (!files.length) return;

          self.renderFiles(files);
        });
      }
    };

    // attachDragNDropListener
    this.attachDragNDropListener = function() {
      var self = this;
      var drop_area = this.container;
      var drag_events = ["dragenter", "dragleave", "dragover", "drop"];

      var dragEnter = function(e) {
        if (self.draggingCounter < 1) {
          self.draggingCounter++;
        }

        if (self.draggingCounter > 0) {
          addClass(self.container, "highlight");
        }
      };

      var dragLeave = function(e) {
        if (self.draggingCounter > 0) {
          self.draggingCounter--;
        }

        if (self.draggingCounter < 1) {
          removeClass(self.container, "highlight");
        }
      };

      var handleDrop = function(e) {
        var dt = e.dataTransfer;
        var files = dt.files;

        self.renderFiles(files);
      };

      forEach(drag_events, function(item) {
        drop_area.addEventListener(item, preventDefaults, false);
      });

      forEach(["dragenter", "dragover"], function(event_name) {
        document.addEventListener(
          event_name,
          function() {
            addClass(self.container, "drag-enter");
          },
          false
        );
        drop_area.addEventListener(event_name, dragEnter, false);
      });

      forEach(["dragleave", "drop"], function(event_name) {
        document.addEventListener(
          event_name,
          function() {
            removeClass(self.container, "drag-enter");
          },
          false
        );
        drop_area.addEventListener(event_name, dragLeave, false);
      });

      drop_area.addEventListener("drop", handleDrop, false);
    };

    // updateLayout
    this.updateLayout = function(layout) {
      if (!this.layoutIsReady()) {
        return;
      }

      var media_picker_section = this.mediaPickerSection;
      var preview_section = this.previewSection;
      var loading_section = this.loadingSection;

      switch (layout) {
        case "preview":
          removeClass(loading_section, "--show");
          removeClass(media_picker_section, "--show");
          addClass(preview_section, "--show");
          break;
        case "loading":
          removeClass(media_picker_section, "--show");
          // removeClass(preview_section, "--show");
          addClass(loading_section, "--show");
          break;
        default:
          removeClass(loading_section, "--show");
          removeClass(preview_section, "--show");
          addClass(media_picker_section, "--show");
      }
    };

    this.removeFile = function(e) {
      this.updateLayout("loading");

      // 1st Parent: parent_front_item_close_icon
      // 2nd Parent: parent_front_item_close
      // 3rd Parent: parent_front
      // 4th Parent: list_item
      var parent =
        e.target.parentElement.parentElement.parentElement.parentElement;

      var id = parent.getAttribute("data-id");
      var files_meta_index = findIndexByKey(this.filesMeta, "id", id);

      if (files_meta_index === null) {
        this.updatePreview();
        return;
      }

      var files_meta_item = this.filesMeta[files_meta_index];
      var files_index = null;

      if ("name" in files_meta_item) {
        var file_name = files_meta_item.name;
        files_index = findIndexByKey(this.files, "name", file_name);
      }

      this.filesMeta.splice(files_meta_index, 1);
      if (files_index === null) {
        this.updatePreview();
        return;
      }

      this.files.splice(files_index, 1);
      this.updatePreview();
    };

    this.changeOrder = function(e) {
      var total_fiels = this.filesMeta.length;
      if (total_fiels < 2) {
        return;
      }

      var base_elm = e.target.parentElement;
      var parent =
        e.target.parentElement.parentElement.parentElement.parentElement;

      var id = parent.getAttribute("data-id");
      var base_index = findIndexByKey(this.filesMeta, "id", id);

      var target_index;
      if (hasClass(base_elm, "--sort-up")) {
        target_index = base_index + 1;

        if (target_index > total_fiels - 1) {
          return;
        }
      }

      if (hasClass(base_elm, "--sort-down")) {
        target_index = base_index - 1;

        if (target_index < 0) {
          return;
        }
      }

      var base = this.filesMeta[base_index];
      var target = this.filesMeta[target_index];

      this.filesMeta[base_index] = target;
      this.filesMeta[target_index] = base;
      this.updatePreview();
    };

    // renderFiles
    this.renderFiles = function(files) {
      var self = this;

      if (!files.length) {
        this.updateLayout("default");
        return;
      }

      this.updateLayout("loading");
      var temp_files = [];
      for (var i = 0; i < files.length; i++) {
        var file_item = files[i];
        var file_is_valid = validateFileExtension(
          file_item,
          self.options.allowedFileFormats
        );

        var has_no_duplicate = validateDuplicateFile( this.filesMeta, file_item );

        if (file_is_valid && has_no_duplicate) {
          temp_files.push(file_item);
        }
      }

    
      if (!temp_files.length) {
        self.updatePreview();
        return;
      }

      var prevLength = this.filesMeta.length;
      var total_files = temp_files.length;
      var total_rendered = 0;

      forEach(temp_files, function(file_item, index) {
        var reader = new FileReader();
        reader.onloadend = function() {
          var meta = {
            id: prevLength + index,
            file: file_item,
            blob: reader.result,
            name: file_item.name,
            type: file_item.type,
            url: null,
            oldFile: false,
            fileSize: file_item.size,
            fileSizeInText: formatedFileSize(file_item.size)
          };

          var type_is_image = file_item.type.match(/image\//g);
          if (type_is_image) {
            meta.blob = reader.result;
          }

          var maxFileSize = self.options.maxFileSize;
          var limit_exceeded = false;

          if (maxFileSize) {
            var file_size_in_kb = file_item.size / 1024;
            limit_exceeded = file_size_in_kb > maxFileSize ? true : false;
          }
          meta.limitExceeded = limit_exceeded;

          self.filesMeta.push(meta);
          self.files.push(file_item);

          total_rendered++;
          if (total_files === total_rendered) {
            setTimeout(function() {
              self.updatePreview();
            }, 200);
          }
        };

        reader.readAsDataURL(file_item);
      });

      updateFileInputElement(this.uploadButtonContainer, this);
      this.attachFileChangeListener();
    };

    this.updatePreview = function() {
      if (!this.layoutIsReady()) {
        return;
      }

      var files = this.filesMeta;
      if (!files.length) {
        this.updateLayout("default");
        this.validateFiles();
        return;
      }

      var thumbnail_area = this.thumbnailArea;
      thumbnail_area.innerHTML = "";
      var thumbnail_list = createThumbnailListElm();

      forEach(files, function(file) {
        var thumbnail_list_item = createThumbnailListItemElm(file);
        thumbnail_list.appendChild(thumbnail_list_item);
      });

      thumbnail_area.appendChild(thumbnail_list);
      this.updateLayout("preview");
      this.validateFiles();
    };

    // layoutIsReady
    this.layoutIsReady = function() {
      var self = this;
      var status = true;
      var layouts = [
        "container",
        "mediaPickerSection",
        "previewSection",
        "thumbnailArea",
        "statusSection",
        "loadingSection"
      ];

      for (var i = 0; i < layouts.length; i++) {
        var layout = layouts[i];
        if (!self[layout]) {
          status = false;
          break;
        }
      }

      return status;
    };
  };

  // Helper Functions
  //-------------------------------------------
  function createDropZoneSection(dara) {
    var drop_zone_section = document.createElement("div");
    addClass(drop_zone_section, "ezmu__drop-zone-section");
    drop_zone_section.innerHTML = "<h2>Drop Here</h2>";

    return drop_zone_section;
  }

  function createMediaPickerSection(data) {
    var media_picker_section = createElementWithClass(
      "ezmu__media-picker-section --show"
    );

    var media_picker_controls = createElementWithClass(
      "ezmu__media-picker-controls"
    );

    var media_picker_icon = createElementWithClass(
      "ezmu__media-picker-icon", "span"
    );

    var media_picker_icon_img = createElementWithClass(
      'ezmu__media-picker-icon-img', 'img'
    );

    var upload_icon_img = createElementWithClass('ezmu__media-picker-icon-bg', 'span');

    // media_picker_icon_img.src = './wp-content/plugins/directorist/public/assets/images/cloud-upload-alt-solid.svg';
    media_picker_icon.appendChild(upload_icon_img);
    media_picker_controls.appendChild(media_picker_icon);

    var media_picker_buttons = createElementWithClass(
      "ezmu__media-picker-buttons"
    );

    var titles = document.createElement("p");
    var title_1 = createElementWithClass("ezmu__title-1", "p", "Drag & Drop");
    var title_2 = createElementWithClass("ezmu__title-3", "p", "or");
    titles.appendChild(title_1);
    titles.appendChild(title_2);

    var upload_button_wrap = createElementWithClass("ezmu__upload-button-wrap");
    updateFileInputElement(upload_button_wrap, data);

    media_picker_buttons.appendChild(titles);
    media_picker_buttons.appendChild(upload_button_wrap);
    media_picker_controls.appendChild(media_picker_buttons);

    media_picker_section.appendChild(media_picker_controls);
    return media_picker_section;
  }

  function updateFileInputElement(container, data) {
    var accept = "image/*";
    var allowMultiple = true;

    if (data !== null && typeof data === "object") {
      allowMultiple = data.options.allowMultiple;
      var allowedFileFormats = data.options.allowedFileFormats;
      accept = getFileFormats(allowedFileFormats);
    }

    container.innerHTML = "";
    var file_input = document.createElement("input");
    file_input.setAttribute("type", "file");
    file_input.setAttribute("id", "ezmu__file-input");
    file_input.setAttribute("class", "ezmu__file-input");
    file_input.setAttribute("accept", accept);

    if (allowMultiple) {
      file_input.setAttribute("multiple", "true");
    }

    var file_input_label = document.createElement("label");
    file_input_label.setAttribute("for", "ezmu__file-input");
    file_input_label.setAttribute("class", "ezmu__btn ezmu__input-label");
    file_input_label.innerHTML = "Select Files";

    container.appendChild(file_input);
    container.appendChild(file_input_label);
  }

  function getFileFormats(allowedFileFormats) {
    var default_formats = "image/*";

    if (!(Array.isArray(allowedFileFormats) && allowedFileFormats.length)) {
      return default_formats;
    }

    default_formats = "";
    forEach(allowedFileFormats, function(file_format) {
      var images_ext = default_formats + "image/*, ";
      var file_ext = default_formats + "." + file_format + ", ";
      default_formats = file_format === "images" ? images_ext : file_ext;
    });

    default_formats = default_formats.replace(/(,\s?)$/, "");
    return default_formats;
  }

  function createPreviewSection() {
    var preview_section = createElementWithClass("ezmu__preview-section");
    var thumbnail_area = createElementWithClass("ezmu__thumbnail-area");
    var media_picker_buttons = createElementWithClass(
      "ezmu__media-picker-buttons"
    );

    var upload_button_wrap = createElementWithClass("ezmu__upload-button-wrap");
    var label = createElementWithClass(
      "ezmu__btn ezmu__input-label",
      "label",
      "Add More"
    );
    label.setAttribute("for", "ezmu__file-input");
    upload_button_wrap.appendChild(label);
    media_picker_buttons.appendChild(upload_button_wrap);

    preview_section.appendChild(thumbnail_area);
    preview_section.appendChild(media_picker_buttons);

    return preview_section;
  }
  function createStatusSection() {
    var status_section = createElementWithClass("ezmu__status-section");
    return status_section;
  }
  function createLoadingSection(show) {
    // loading_section_elm
    var class_name = show ? "ezmu__loading-section --show" : "ezmu__loading-section";

    var loading_section = createElementWithClass(class_name);
    var loading_icon = createElementWithClass("ezmu__loading-icon", "span");

    var loading_icon_img = createElementWithClass('ezmu__loading-icon-bg', 'span');
    loading_icon.appendChild(loading_icon_img);

    loading_section.appendChild(loading_icon);
    return loading_section;
  }

  function createThumbnailListElm() {
    var thumbnail_list = document.createElement("div");
    addClass(thumbnail_list, "ezmu__thumbnail-list");
    return thumbnail_list;
  }

  function createThumbnailListItemElm(data) {
    var id = data && "id" in data ? data.id : "";

    var thumbnail_list_item = createElementWithClass(
      "ezmu__thumbnail-list-item"
    );
    thumbnail_list_item.setAttribute("data-id", id);

    var thumbnail_list_item_front = createThumbnailListItemFrontElm(data);
    var thumbnail_list_item_back = createThumbnailListItemBackElm(data);

    thumbnail_list_item.appendChild(thumbnail_list_item_front);
    thumbnail_list_item.appendChild(thumbnail_list_item_back);

    return thumbnail_list_item;
  }

  function createThumbnailListItemFrontElm(data) {
    var thumbnail_list_item_front = document.createElement("div");
    addClass(thumbnail_list_item_front, "ezmu__thumbnail-list-item_front");

    var thumbnail_list_item_close = createThumbnailListItemCloseElm(data);
    var thumbnail_list_item_sort_buttons = createThumbnailListItemSortButtonsElm();

    if ( data && "fileSizeInText" in data ) {
      var thumbnail_list_item_size = createThumbnailListItemSizeElm(data);
      thumbnail_list_item_front.appendChild(thumbnail_list_item_size);
    }
    
    thumbnail_list_item_front.appendChild(thumbnail_list_item_close);
    thumbnail_list_item_front.appendChild(thumbnail_list_item_sort_buttons);

    return thumbnail_list_item_front;
  }

  function createThumbnailListItemCloseElm() {
    var thumbnail_list_item_close = document.createElement("div");
    addClass(
      thumbnail_list_item_close,
      "ezmu__thumbnail-front-item ezmu__front-item__close"
    );

    var thumbnail_list_item_close_icon = createElementWithClass('ezmu__front-item__close-icon', 'span');
    var thumbnail_list_item_close_btn = document.createElement("span");
    addClass(thumbnail_list_item_close_btn, "ezmu__front-item__close-btn");

    thumbnail_list_item_close_icon.appendChild(thumbnail_list_item_close_btn);
    thumbnail_list_item_close.appendChild(thumbnail_list_item_close_icon);
    return thumbnail_list_item_close;
  }

  function createThumbnailListItemSizeElm(data) {
    var thumbnail_list_item_size = createElementWithClass(
      "ezmu__thumbnail-front-item ezmu__front-item__thumbnail-size"
    );
    var thumbnail_list_item_size_text = createElementWithClass(
      "ezmu__front-item__thumbnail-size-text",
      "span"
    );

    if (data == null) {
      thumbnail_list_item_size.appendChild(thumbnail_list_item_size_text);
      return thumbnail_list_item_size;
    }

    thumbnail_list_item_size_text.innerHTML =
      "fileSizeInText" in data ? data.fileSizeInText : "";

    thumbnail_list_item_size.appendChild(thumbnail_list_item_size_text);
    return thumbnail_list_item_size;
  }

  function createThumbnailListItemSortButtonsElm() {
    var thumbnail_list_item_sort_buttons = document.createElement("div");
    addClass(
      thumbnail_list_item_sort_buttons,
      "ezmu__thumbnail-front-item ezmu__front-item__sort-buttons"
    );

    var thumbnail_list_item_sort_buttons_down = document.createElement(
      "button"
    );
    addClass(
      thumbnail_list_item_sort_buttons_down,
      "ezmu__front-item__sort-button --sort-down"
    );
    thumbnail_list_item_sort_buttons_down.setAttribute("type", "button");

    thumbnail_list_item_sort_buttons_down.innerHTML = '<span class="ezmu__front-item__sort-button-skin --sort-down"></span>';

    var thumbnail_list_item_sort_buttons_up = document.createElement("button");
    addClass(
      thumbnail_list_item_sort_buttons_up,
      "ezmu__front-item__sort-button --sort-up"
    );
    thumbnail_list_item_sort_buttons_up.setAttribute("type", "button");
    thumbnail_list_item_sort_buttons_up.innerHTML = '<span class="ezmu__front-item__sort-button-skin --sort-up"></span>';

    thumbnail_list_item_sort_buttons.appendChild(
      thumbnail_list_item_sort_buttons_down
    );
    thumbnail_list_item_sort_buttons.appendChild(
      thumbnail_list_item_sort_buttons_up
    );

    return thumbnail_list_item_sort_buttons;
  }

  function createThumbnailListItemBackElm(data) {
    var thumbnail_list_item_back = createElementWithClass(
      "ezmu__thumbnail-list-item_back"
    );

    var thumbnail_list_item_img = getThumbnail(data);
    thumbnail_list_item_back.appendChild(thumbnail_list_item_img);
    return thumbnail_list_item_back;
  }

  function getThumbnail(data) {
    var thumbnail_list_item_img = document.createElement("img");

    var img_src, img_class;
    var default_thumbnail = "./images/file-solid.svg";

    if (typeof data !== "object") {
      thumbnail_list_item_img.setAttribute("class", "ezmu__thumbnail-icon");
      thumbnail_list_item_img.src = default_thumbnail;
      return thumbnail_list_item_img;
    }

    var data_type = "type" in data ? data.type : "file";
    var data_url = "url" in data ? data.url : null;
    var data_blob = "blob" in data ? data.blob : null;

    var type_is_image = data_type.match(/image/g);

    img_src = default_thumbnail;
    img_class = type_is_image ? "ezmu__thumbnail-img" : "ezmu__thumbnail-icon";

    if (type_is_image) {
      img_src = data_url ? data_url : img_src;
      img_src = data_blob ? data_blob : img_src;
    }

    thumbnail_list_item_img.setAttribute("class", img_class);
    thumbnail_list_item_img.src = img_src;

    return thumbnail_list_item_img;
  }

  function fileSizeInText( file_size_in_byte ) {
    var file_size_in_kb = file_size_in_byte * 1024;
    var file_size_in_text = file_size_in_kb > 1023 ? file_size_in_kb / 1024 + ' MB' : file_size_in_kb + ' KB';
    return file_size_in_text;
  }

  function updateValidationFeedback( error_log, container ) {
    container.innerHTML = '';
    
    if ( !error_log.length ) {
      removeClass(container, '--show');
    }
    addClass(container, '--show');

    for ( var i = 0; i < error_log.length; i++ ) {
      var alert_box = createElementWithClass('ezmu_alert ezmu_alert_error');
      alert_box.innerHTML = error_log[i].message;
      container.appendChild(alert_box);
    }
  }

  function createElementWithClass(class_name, elm, child) {
    class_name = ( typeof class_name === 'undefined' ) ? "" : class_name;
    elm = ( typeof elm === 'undefined' ) ? "div" : elm;
    child = ( typeof child === 'undefined' ) ? "" : child;

    var element = document.createElement(elm);
    addClass(element, class_name);

    element.innerHTML = child;
    return element;
  }

  function validateFileExtension(file, allowedFileFormats) {
    allowedFileFormats = ( typeof allowedFileFormats === 'undefined' ) ? [] : allowedFileFormats;

    var status = false;

    if (!Array.isArray(allowedFileFormats)) {
      return null;
    }

    if (!allowedFileFormats.length) {
      return null;
    }

    if (typeof file !== "object") {
      return null;
    }

    if (!("name" in file) || !("type" in file)) {
      return null;
    }

    var name = file.name;
    var lastDot = name.lastIndexOf(".");
    var ext = name.substring(lastDot + 1);

    for (var i = 0; i < allowedFileFormats.length; i++) {
      var file_format = allowedFileFormats[i];
      var file_type_is_image = file.type.match(/image\//g);

      if (file_format === "images" && file_type_is_image) {
        status = true;
        break;
      }

      if (file_format === ext) {
        status = true;
        break;
      }
    }

    return status;
  }

  function validateDuplicateFile( all_files, current_file ) {
    var has_no_duplicate = true;

    for( var i = 0; i < all_files.length; i++ ) {
      var file_item = all_files[i];

      if ( file_item.name === current_file.name ) {
        has_no_duplicate = false;
        break;
      }
    }

    return has_no_duplicate;
  }

  // formatedFileSize
  function formatedFileSize(file_size) {
    file_size = parseFloat(file_size);
    var file_size_in_kb = file_size / 1024;
    var _file_size_in_kb = file_size_in_kb.toFixed(2) + " KB";

    var file_size_in_mb = file_size_in_kb < 1024 ? null : file_size_in_kb / 1024;

    var formated_file_size = file_size_in_mb ? file_size_in_mb.toFixed(2) + " MB" : _file_size_in_kb;
    return formated_file_size;
  }

  // findIndexByKey
  function findIndexByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
      if (array[i][key] == value) {
        return i;
      }
    }

    return null;
  }

  function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }

  // extendDefaults
  function extendDefaults(defaults, args) {
    if (!args) {
      return defaults;
    }

    if (args && typeof args !== "object") {
      return defaults;
    }

    var property;
    for (property in args) {
      if (defaults.hasOwnProperty(property)) {
        defaults[property] = args[property];
      }
    }

    return defaults;
  }

  // forEach
  function forEach(array, cb) {
    for (var i = 0; i < array.length; i++) {
      cb(array[i], i);
    }
  }

  // addClass
  function addClass(element, class_name) {
    if (!element) {
      return;
    }

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
})();
