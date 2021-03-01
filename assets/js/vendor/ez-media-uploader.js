/**
 * EZ Media Uploader
 * Company: Aazz Tech
 * Develoepr: Syed Galib Ahmed
 * Version: 1.0
 * Initial Release: 24 December, 2019
 * */

/* eslint-disable */
(function () {
  this.EzMediaUploader = function (args) {
    var defaults = {
      containerID: "ez-media-uploader",
      oldFiels: null,
      oldFielsUrl: null,
      maxFileSize: 0,
      maxTotalFileSize: 4096,
      minFileItems: null,
      maxFileItems: null,
      allowedFileFormats: ["images"],
      allowMultiple: true,
      featured: true,
      allowSorting: true,
      showAlerts: true,
      showFileSize: true,
      showInfo: true,
      uploaderType: 'files', // files | avater
      dictionary: {
        // Label Texts
        label: {
          featured: 'Featured',
          dragNDrop: 'Drag & Drop',
          or: 'or',
          dropHere: 'Drop Here',
          selectFiles: 'Select Files',
          addMore: 'Add More',
          change: 'Change',
        },
        // Alerts Texts
        alert: {
          maxFileSize: 'The maximum limit for a file is __DT__',
          maxTotalFileSize: 'The minimum limit for total file size is  __DT__',
          minFileItems: 'The minimum limit for total file is __DT__',
          maxFileItems: 'The maximum limit for total file is __DT__',
        },
        // Info Texts
        info: {
          maxFileSize: {
            text: 'The maximum allowed size per file is __DT__',
            show: true, featured: false, pin: false
          },
          maxTotalFileSize: {
            text: 'The maximum total allowed file size is __DT__',
            show: true, featured: false, pin: false
          },
          minFileItems: {
            text: 'The minimum __DT__ files are required',
            show: true, featured: false, pin: false
          },
          maxFileItems: {
            text: 'The maximum __DT__ files are allowed',
            show: true, featured: false, pin: false
          },
          allowedFileFormats: {
            text: 'Allowed file types are __DT__',
            show: true, featured: false, pin: false
          },
        }
      },

    };

    // Data
    // -----------------------------------------
    if (typeof args === 'object' && args !== null) {
      this.options = extendDefaults(defaults, args);
    }
    this.debugLog = [];
    this.isClean = true;
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
    this.infoSection = null;
    this.loadingSection = null;

    // Methods
    // -----------------------------------------
    // init
    this.init = function () {
      var id = this.options.containerID;
      var container = document.getElementById(id);
      if (!container) {
        return;
      }
      this.container = container;

      this.getMarkupOptions();
      this.getMarkupDictionary();
      this.loadOldFiels();
      this.attachElements();
      this.updatePreview();
      this.attachEventListener();
    };

    this.getMarkupOptions = function () {
      if (!this.container) { return null; }

      var container = this.container;
      var self = this;

      var options = [
        { key: 'maxFileSize', dataAttr: 'max-file-size', dataType: 'int' },
        { key: 'maxTotalFileSize', dataAttr: 'max-total-file-size', dataType: 'int' },
        { key: 'minFileItems', dataAttr: 'min-file-items', dataType: 'int' },
        { key: 'maxFileItems', dataAttr: 'max-file-items', dataType: 'int' },
        { key: 'allowedFileFormats', dataAttr: 'type', dataType: 'array' },
        { key: 'allowMultiple', dataAttr: 'allow-multiple', dataType: 'bool' },
        { key: 'showAlerts', dataAttr: 'show-alerts', dataType: 'bool' },
        { key: 'showFileSize', dataAttr: 'show-file-size', dataType: 'bool' },
        { key: 'featured', dataAttr: 'featured', dataType: 'bool' },
        { key: 'allowSorting', dataAttr: 'allow-sorting', dataType: 'bool' },
        { key: 'showInfo', dataAttr: 'show-info', dataType: 'bool' },
        { key: 'uploaderType', dataAttr: 'uploader-type', dataType: 'string' },
      ];

      forEach(options, function (option) {
        var option_arrt = container.getAttribute('data-' + option.dataAttr);
        var has_data = (option_arrt && option_arrt.length) ? true : false;

        // String
        if (has_data && option.dataType === 'string') {
          self.options[option.key] = option_arrt;
        }
        // Integer
        if (has_data && option.dataType === 'int') {
          var option_value = parseInt(option_arrt);
          self.options[option.key] = (option_value < 1) ? false : option_value;
        }
        // Boolean
        if (has_data && option.dataType === 'bool') {
          var option_value = self.options[option.key];
          switch (option_arrt) {
            case '0':
              option_value = false;
              break;
            case 'false':
              option_value = false;
              break;
            case '1':
              option_value = true;
              break;
            case 'true':
              option_value = true;
              break;
          }

          self.options[option.key] = option_value;
        }
        // Array
        if (has_data && option.dataType === 'array') {
          var sanitize_value = option_arrt.replace(/,+$/, '').replace(/\s/g, '');
          var option_value = sanitize_value.split(',');
          self.options[option.key] = option_value;
        }
      });
    };

    this.getMarkupDictionary = function () {
      if (!this.container) { return null; }

      var container = this.container;
      var self = this;

      var label_classes = [
        { key: 'featured', class: 'ezmu-dictionary-label-featured' },
        { key: 'dragNDrop', class: 'ezmu-dictionary-label-drag-n-drop' },
        { key: 'or', class: 'ezmu-dictionary-label-or' },
        { key: 'dropHere', class: 'ezmu-dictionary-label-drop-here' },
        { key: 'selectFiles', class: 'ezmu-dictionary-label-select-files' },
        { key: 'addMore', class: 'ezmu-dictionary-label-add-more' },
        { key: 'change', class: 'ezmu-dictionary-label-change' },
      ];
      var alert_classes = [
        { key: 'maxFileSize', class: 'ezmu-dictionary-alert-max-file-size' },
        { key: 'maxTotalFileSize', class: 'ezmu-dictionary-alert-max-total-file-size' },
        { key: 'minFileItems', class: 'ezmu-dictionary-alert-min-file-items' },
        { key: 'maxFileItems', class: 'ezmu-dictionary-alert-max-file-items' },
      ];
      var info_classes = [
        { key: 'maxFileSize', class: 'ezmu-dictionary-info-max-file-size' },
        { key: 'maxTotalFileSize', class: 'ezmu-dictionary-info-max-total-file-size' },
        { key: 'minFileItems', class: 'ezmu-dictionary-info-min-file-items' },
        { key: 'maxFileItems', class: 'ezmu-dictionary-info-max-file-items' },
        { key: 'allowedFileFormats', class: 'ezmu-dictionary-info-type' },
      ];

      // Fetch Labels
      forEach(label_classes, function (item) {
        var elm = container.querySelectorAll('.' + item.class);
        if (elm && elm.length) {
          var elm_dic = elm[0].innerHTML.trim();
          self.options.dictionary.label[item.key] = elm_dic;
        }
      });

      // Fetch Alerts
      forEach(alert_classes, function (item) {
        var elm = container.querySelectorAll('.' + item.class);
        if (elm && elm.length) {
          var elm_dic = elm[0].innerHTML.trim();
          self.options.dictionary.alert[item.key] = elm_dic;
        }
      });

      // Fetch Info
      forEach(info_classes, function (item) {
        var elm = container.querySelectorAll('.' + item.class);
        if (elm && elm.length) {
          var elm_dic = elm[0].innerHTML.trim();
          self.options.dictionary.info[item.key].text = elm_dic;

          var show = elm[0].getAttribute('data-show');
          var featured = elm[0].getAttribute('data-featured');
          var pin = elm[0].getAttribute('data-pin');
          self.options.dictionary.info[item.key].show = (show === '0' || show === 'false') ? false : true;
          self.options.dictionary.info[item.key].featured = (featured === '1' || featured === 'true') ? true : false;
          self.options.dictionary.info[item.key].pin = (pin === '1' || pin === 'true') ? true : false;
        }
      });
    };

    this.getTheFiles = function () {
      if (!this.container) { return null; }
      this.isClean = false;

      var final_files = [];

      if (!this.filesMeta.length) {
        return final_files;
      }

      forEach(this.filesMeta, function (file) {
        if ("file" in file) {
          final_files.push(file.file);
        }
      });

      return final_files;
    };

    this.getFilesMeta = function () {
      if (!this.container) { return null; }
      this.isClean = false;

      var final_files_meta = [];
      if (!this.filesMeta.length) {
        return final_files_meta;
      }

      forEach(this.filesMeta, function (file) {

        var meta_data = {
          id: file.id,
          url: file.url,
          oldFile: file.oldFile,
        };

        if ("attachmentID" in file) {
          meta_data.attachmentID = file.attachmentID;
        }

        if ("url" in file) {
          meta_data.url = file.url;
        }

        if ("file" in file) {
          meta_data.name = file.name;
          meta_data.fileSize = file.fileSize;
          meta_data.fileSizeInText = file.fileSizeInText;
          meta_data.type = file.type;
          meta_data.limitExceeded = file.limitExceeded;
        }

        final_files_meta.push(meta_data);
      });

      return final_files_meta;
    };

    this.validateFiles = function () {
      if (!this.container) { return null; }

      var self = this;
      var filesMeta = this.filesMeta;
      var alerts = this.options.dictionary.alert;
      var error_log = [];

      // Validate Min File Items
      var min_file_items = this.options.minFileItems;
      var valid_min_file_items = isPositiveNumber(min_file_items);
      min_file_items = (valid_min_file_items) ? valid_min_file_items : min_file_items;
      
      if (valid_min_file_items && (filesMeta.length < min_file_items)) {
        error_log.push({
          errorKey: "minFileItems",
          message: alerts.minFileItems.replace(/(__DT__)/g, min_file_items)
        });
      }

      // Validate Max File Items
      var max_file_items = this.options.maxFileItems;
      var valid_max_file_items = isPositiveNumber(max_file_items);
      max_file_items = (valid_max_file_items) ? valid_max_file_items : max_file_items;

      if (valid_max_file_items && (filesMeta.length > max_file_items)) {
        error_log.push({
          errorKey: "maxFileItems",
          message: alerts.maxFileItems.replace(/(__DT__)/g, max_file_items)
        });
      }

      // Validate Max File Size
      forEach( filesMeta, function( file ) { 
        if ( (typeof file === 'object' && file !== null) && 'limitExceeded' in file ) {
          if ( file.limitExceeded ) {
            var max_file_size = self.options.maxFileSize;
            var max_file_size_in_text = formatedFileSize(max_file_size * 1024);
            error_log.push({
              errorKey: "maxFileSize",
              message: alerts.maxFileSize.replace(/(__DT__)/g, max_file_size_in_text)
            });
          }
        }
      });

      // Validate Max Total File Size
      var maxTotalFileSize = this.options.maxTotalFileSize;
      var valid_maxTotalFileSize = isPositiveNumber(maxTotalFileSize);
      maxTotalFileSize = (valid_maxTotalFileSize) ? valid_maxTotalFileSize : maxTotalFileSize;

      if (valid_maxTotalFileSize) {
        var max_total_file_size_in_byte = maxTotalFileSize * 1024;
        var max_total_file_size_in_text = formatedFileSize(maxTotalFileSize * 1024);
        var total_file_size_in_byte = 0;

        forEach(filesMeta, function (file) {
          if ((typeof file === 'object' && file !== null) && 'fileSize' in file) {
            total_file_size_in_byte += file.fileSize;
          }
        });

        if (total_file_size_in_byte > max_total_file_size_in_byte) {
          error_log.push({
            errorKey: "maxTotalFileSize",
            message: alerts.maxTotalFileSize.replace(/(__DT__)/g, max_total_file_size_in_text)
          });
        }
      }

      if ( this.options.showAlerts && !this.isClean ) {
        updateValidationFeedback(error_log, this.statusSection);
      }

      var info_elm = self.container.querySelectorAll('.ezmu__info-list-item');
      if (info_elm && info_elm.length) {
        forEach(info_elm, function (info_elm) {
          removeClass(info_elm, 'has-error');
        });
      }

      if (!this.isClean && this.options.showInfo && error_log.length) {
        forEach(error_log, function (item) {
          var info_elm = self.container.querySelectorAll('.ezmu__info-list-item.' + item.errorKey);
          if (info_elm && info_elm.length) {
            addClass(info_elm[0], 'has-error');
          }
        });
      }

      if (error_log.length) {
        return error_log;
      }

      return true;
    };

    this.hasValidFiles = function () {
      this.isClean = false;

      if (this.validateFiles() === true) {
        return true;
      }

      return false;
    };

    this.loadOldFiels = function () {
      if (!this.container) { return null; }

      var old_fiels = [];

      if (this.options.oldFiels) {
        old_fiels = this.getValidatedPaths(this.options.oldFiels);
      }

      if (getMarkupsFilesMeta(this.container)) {
        old_fiels = getMarkupsFilesMeta(this.container);
      }

      if (!old_fiels.length) {
        return;
      }

      this.isClean = false;

      for (var i = 0; i < old_fiels.length; i++) {
        var file = old_fiels[i];
        var filesMeta = {
          id: i,
          url: file.url,
          oldFile: true
        };

        if ("attachmentID" in file) {
          filesMeta.attachmentID = file.attachmentID;
        }

        if ("type" in file) {
          filesMeta.type = file.type;
        }

        if ("size" in file) {
          filesMeta.fileSize = file.size * 1024;
          filesMeta.fileSizeInText = formatedFileSize(file.size * 1024);

          if ( this.options.maxFileSize && ( file.size > this.options.maxFileSize ) ) {
            filesMeta.limitExceeded = true;
          }
        }

        this.filesMeta.push(filesMeta);
      }

    };

    this.getValidatedPaths = function (paths) {
      if (!this.container) { return null; }

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
    this.attachElements = function () {
      if (!this.container) { return null; }

      var container = this.container;
      addClass(container, "ez-media-uploader");
      container.innerHTML = "";

      this.fileInputID = createFileInputID();

      var drop_zone_section_elm = createDropZoneSection(this);
      var loading_section_elm = createLoadingSection();
      var media_picker_elm = createMediaPickerSection(this);
      var preview_section_elm = createPreviewSection(this);

      container.appendChild(drop_zone_section_elm);
      container.appendChild(loading_section_elm);
      container.appendChild(media_picker_elm);
      container.appendChild(preview_section_elm);

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
      var loading_section = container.querySelectorAll(".ezmu__loading-section");

      this.uploadButtonContainer = upload_button_container ? upload_button_container[0] : null;
      this.mediaPickerSection = media_picker_section ? media_picker_section[0] : null;
      this.previewSection = preview_section ? preview_section[0] : null;
      this.thumbnailArea = thumbnail_area ? thumbnail_area[0] : null;
      this.loadingSection = loading_section ? loading_section[0] : null;

      if (this.options.showAlerts) {
        var status_section_elm = createStatusSection();
        container.appendChild(status_section_elm);
        var status_section = container.querySelectorAll(".ezmu__status-section");
        this.statusSection = status_section ? status_section[0] : null;
      }

      if (this.options.showInfo) {
        var info_section_elm = createInfoSection(this.options);
        container.appendChild(info_section_elm);

        var info_section = container.querySelectorAll(".ezmu__info-section");
        this.infoSection = info_section ? info_section[0] : null;
      }
    };

    // attachEventListener
    this.attachEventListener = function () {
      if (!this.layoutIsReady()) {
        return;
      }
      var self = this;

      // Attach File Change Listener
      this.attachFileChangeListener();

      // Attach Drag & Drop Listener
      this.attachDragNDropListener();

      this.container.addEventListener("click", function (e) {
        if (!e.target) {
          return;
        }
        // Close Button Event
        if (hasClass(e.target, "ezmu__front-item__close-btn")) {
          self.removeFile(e);
        }

        // Sort Button Event
        if (hasClass(e.target, "ezmu__front-item__sort-button")) {
          self.changeOrder(e.target);
        } else if (hasClass(e.target, "ezmu__front-item__sort-button-skin")) {
          self.changeOrder(e.target.parentElement);
        }
      });
    };

    // attachFileChangeListener
    this.attachFileChangeListener = function () {
      if (!this.container) { return null; }

      var file_input = this.container.querySelectorAll("#" + this.fileInputID);
      var fileInputElm = file_input ? file_input[0] : null;

      if (fileInputElm) {
        var self = this;
        fileInputElm.addEventListener("change", function (event) {
          self.isClean = false;
          var files = event.target.files;
          if (!files.length) return;

          self.renderFiles(files);
        });
      }
    };

    // attachDragNDropListener
    this.attachDragNDropListener = function () {
      if (!this.container) { return null; }

      var self = this;
      var drop_area = this.container;
      var drag_events = ["dragenter", "dragleave", "dragover", "drop"];

      var dragEnter = function (e) {
        if (self.draggingCounter < 1) {
          self.draggingCounter++;
        }

        if (self.draggingCounter > 0) {
          addClass(self.container, "highlight");
        }
      };

      var dragLeave = function (e) {
        if (self.draggingCounter > 0) {
          self.draggingCounter--;
        }

        if (self.draggingCounter < 1) {
          removeClass(self.container, "highlight");
        }
      };

      var handleDrop = function (e) {
        var dt = e.dataTransfer;
        var files = dt.files;

        self.renderFiles(files);
      };

      forEach(drag_events, function (item) {
        drop_area.addEventListener(item, preventDefaults, false);
      });

      forEach(["dragenter", "dragover"], function (event_name) {
        document.addEventListener(
          event_name,
          function () {
            addClass(self.container, "drag-enter");
          },
          false
        );
        drop_area.addEventListener(event_name, dragEnter, false);
      });

      forEach(["dragleave", "drop"], function (event_name) {
        document.addEventListener(
          event_name,
          function () {
            removeClass(self.container, "drag-enter");
          },
          false
        );
        drop_area.addEventListener(event_name, dragLeave, false);
      });

      drop_area.addEventListener("drop", handleDrop, false);
    };

    // updateLayout
    this.updateLayout = function (layout) {
      if (!this.layoutIsReady()) {
        return;
      }

      var media_picker_section = this.mediaPickerSection;
      var preview_section = this.previewSection;
      var loading_section = this.loadingSection;

      switch (layout) {
        case "preview":
          removeClass(loading_section, "ezmu--show");
          removeClass(media_picker_section, "ezmu--show");
          addClass(preview_section, "ezmu--show");
          break;
        case "loading":
          removeClass(media_picker_section, "ezmu--show");
          // removeClass(preview_section, "ezmu--show");
          addClass(loading_section, "ezmu--show");
          break;
        default:
          removeClass(loading_section, "ezmu--show");
          removeClass(preview_section, "ezmu--show");
          addClass(media_picker_section, "ezmu--show");
      }
    };

    this.removeFile = function (e) {
      if (!this.container) { return null; }
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

    this.changeOrder = function (target) {
      if (!this.container) { return null; }

      var total_fiels = this.filesMeta.length;
      if (total_fiels < 2) {
        return;
      }

      var base_elm = target;
      var parent_ie = target.parentElement.parentElement.parentElement;
      var parent = parent_ie.parentElement;

      var id = parent.getAttribute("data-id");
      if (!id) {
        id = parent_ie.getAttribute("data-id");
      }
      if (!id) { return; }

      var base_index = findIndexByKey(this.filesMeta, "id", id);

      var target_index;
      if (hasClass(base_elm, "ezmu--sort-up")) {
        target_index = base_index + 1;

        if (target_index > total_fiels - 1) {
          return;
        }
      }

      if (hasClass(base_elm, "ezmu--sort-down")) {
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
    this.renderFiles = function (files) {
      if (!this.container) { return null; }
      var self = this;

      if (!this.options.allowMultiple && this.filesMeta.length > 0) {
        this.filesMeta = [];
        this.files = [];
      }

      if (!files.length) {
        this.updateLayout("default");
        return;
      }

      this.isClean = false;
      this.updateLayout("loading");

      var temp_files = [];
      for (var i = 0; i < files.length; i++) {
        var file_item = files[i];
        var file_is_valid = validateFileExtension(
          file_item,
          self.options.allowedFileFormats
        );

        var has_no_duplicate = validateDuplicateFile(this.filesMeta, file_item);

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

      forEach(temp_files, function (file_item, index) {
        var reader = new FileReader();
        reader.onloadend = function () {
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
            setTimeout(function () {
              self.updatePreview();
            }, 200);
          }
        };

        reader.readAsDataURL(file_item);
      });

      updateFileInputElement(this.uploadButtonContainer, this);
      this.attachFileChangeListener();
    };

    this.updatePreview = function () {
      if (!this.layoutIsReady()) {
        return;
      }
      var self = this;
      var files = this.filesMeta;
      if (!files.length) {
        this.updateLayout("default");
        this.validateFiles();
        return;
      }

      if ( ! this.options.allowMultiple && this.filesMeta.length ) {
        label_txt = this.options.dictionary.label.change;
        var button = this.container.querySelectorAll('.ezmu__update-file-btn');
        button[0].innerHTML = label_txt;
      }

      var thumbnail_area = this.thumbnailArea;
      thumbnail_area.innerHTML = "";
      var thumbnail_list = createThumbnailListElm();

      forEach(files, function (file, index) {
        var meta_data = { index: index, options: self.options };
        var thumbnail_list_item = createThumbnailListItemElm(file, meta_data);
        thumbnail_list.appendChild(thumbnail_list_item);
      });

      thumbnail_area.appendChild(thumbnail_list);

      this.updateLayout("preview");
      this.validateFiles();
    };

    // layoutIsReady
    this.layoutIsReady = function () {
      var self = this;
      var status = true;
      var layouts = [
        "container",
        "mediaPickerSection",
        "previewSection",
        "thumbnailArea",
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
  function createDropZoneSection(data) {
    var drop_zone_section = document.createElement("div");
    addClass(drop_zone_section, "ezmu__drop-zone-section");
    drop_zone_section.innerHTML = "<h2>" + data.options.dictionary.label.dropHere + "</h2>";

    return drop_zone_section;
  }

  function createMediaPickerSection( data ) {

    if ( data.options.uploaderType === 'avater' ) {
      return createMediaPickerAvaterSection( data );
    }

    return createMediaPickerDragNDropSection( data );
  }

  function createMediaPickerDragNDropSection( data ) {
    var media_picker_section = createElementWithClass(
      "ezmu__media-picker-section ezmu--show"
    );

    var media_picker_controls = createElementWithClass(
      "ezmu__media-picker-controls"
    );

    var media_picker_icon_wrap = createElementWithClass(
      "ezmu__media-picker-icon-wrap-upload", "span"
    );

    var media_picker_icon = createElementWithClass(
      'ezmu__icon ezmu-icon-upload', 'span'
    );
    media_picker_icon_wrap.appendChild(media_picker_icon);

    media_picker_controls.appendChild(media_picker_icon_wrap);

    var media_picker_buttons = createElementWithClass(
      "ezmu__media-picker-buttons"
    );

    var titles_area = createElementWithClass('ezmu__titles-area');
    var title_1 = createElementWithClass("ezmu__title-1", "p", data.options.dictionary.label.dragNDrop);
    var title_2 = createElementWithClass("ezmu__title-3", "p", data.options.dictionary.label.or);
    titles_area.appendChild(title_1);
    titles_area.appendChild(title_2);

    var upload_button_wrap = createElementWithClass("ezmu__upload-button-wrap");
    updateFileInputElement(upload_button_wrap, data);

    media_picker_buttons.appendChild(titles_area);
    media_picker_buttons.appendChild(upload_button_wrap);
    media_picker_controls.appendChild(media_picker_buttons);

    media_picker_section.appendChild(media_picker_controls);
    return media_picker_section;
  }

  function createMediaPickerAvaterSection( data ) {
    // media_picker_section
    var media_picker_section = createElementWithClass(
      "ezmu__media-picker-section ezmu--show"
    );
    
    // media_picker_controls
    var media_picker_controls = createElementWithClass(
      "ezmu__media-picker-controls"
    );

    // media_picker_icon wrapper
    var media_picker_icon_wrap = createElementWithClass(
      "ezmu__media-picker-icon-wrap-avater", "span"
    );
    
    // media_picker_icon
    var media_picker_icon = createElementWithClass(
      'ezmu__icon ezmu-icon-avater', 'span'
    );
    media_picker_icon_wrap.appendChild(media_picker_icon);

    media_picker_controls.appendChild(media_picker_icon_wrap);

    var media_picker_buttons = createElementWithClass(
      "ezmu__media-picker-buttons"
    );

    var upload_button_wrap = createElementWithClass("ezmu__upload-button-wrap");
    updateFileInputElement(upload_button_wrap, data);

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
    file_input.setAttribute("id", data.fileInputID);
    file_input.setAttribute("class", "ezmu__file-input");
    file_input.setAttribute("accept", accept);

    if (allowMultiple) {
      file_input.setAttribute("multiple", "");
    }

    var file_input_label = document.createElement("label");
    file_input_label.setAttribute("for", data.fileInputID);
    file_input_label.setAttribute("class", "ezmu__btn ezmu__input-label");
    file_input_label.innerHTML = data.options.dictionary.label.selectFiles;

    container.appendChild(file_input);
    container.appendChild(file_input_label);
  }

  function createFileInputID() {
    var the_id = 'ezmu__file-input';
    var file_input = document.querySelectorAll('.ezmu__file-input');

    if (file_input.length) {
      the_id = 'ezmu__file-input-' + (file_input.length + 1);
    }

    return the_id;
  }

  function getFileFormats(allowedFileFormats) {
    var default_formats = "image/*";

    if (!(Array.isArray(allowedFileFormats) && allowedFileFormats.length)) {
      return default_formats;
    }

    default_formats = "";
    forEach(allowedFileFormats, function (file_format) {
      var images_ext = default_formats + "image/*, ";
      var file_ext = default_formats + "." + file_format + ", ";
      default_formats = file_format === "images" ? images_ext : file_ext;
    });

    default_formats = default_formats.replace(/(,\s?)$/, "");
    return default_formats;
  }

  function createPreviewSection(data) {
    var preview_section = createElementWithClass("ezmu__preview-section");
    var thumbnail_area = createElementWithClass("ezmu__thumbnail-area");
    var media_picker_buttons = createElementWithClass(
      "ezmu__media-picker-buttons"
    );

    var label_txt = data.options.dictionary.label.addMore;

    if ( !data.options.allowMultiple && data.filesMeta.length ) {
      label_txt = data.options.dictionary.label.change;
    }

    var upload_button_wrap = createElementWithClass("ezmu__upload-button-wrap");
    var label = createElementWithClass(
      "ezmu__btn ezmu__input-label ezmu__update-file-btn", "label", label_txt
    );

    label.setAttribute("for", data.fileInputID);
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

  function createInfoSection(data) {
    var info_section = createElementWithClass("ezmu__info-section");
    var info_dictionary = data.dictionary.info;

    var info_list = createElementWithClass("ezmu__info-list", 'ul');
    var item_count = 0;

    for (var info in info_dictionary) {
      if ( info_dictionary[info].show && (data[info] || info_dictionary[info].pin) ) {
        var dictionary_data = getDictionaryData(info, data);
        var text = info_dictionary[info].text.replace(/(__DT__)/g, dictionary_data);
        var class_name = "ezmu__info-list-item " + info;
        class_name = (info_dictionary[info].featured) ? class_name + ' is-featured' : class_name;
        var li = createElementWithClass(class_name, 'li', text);
        info_list.appendChild(li);
        item_count++;
      }
    }

    if (item_count) {
      info_section.appendChild(info_list);
    }

    return info_section;
  }

  function getDictionaryData(item, options) {
    var option_name = item;
    var data = options[option_name];

    if (option_name === 'maxFileSize' && data) {
      return formatedFileSize(data * 1024);
    }

    if (option_name === 'maxTotalFileSize' && data) {
      return formatedFileSize(data * 1024);
    }

    if (option_name === 'allowedFileFormats' && Array.isArray(data) && data.length) {
      var formats = '';

      forEach(data, function (item) {
        formats += item + ', ';
      });

      return formats.replace(/(, )$/, '');
    }
    return data;
  }

  function createLoadingSection(show) {
    // loading_section_elm
    var class_name = show ? "ezmu__loading-section ezmu--show" : "ezmu__loading-section";

    var loading_section = createElementWithClass(class_name);
    var loading_icon = createElementWithClass("ezmu__loading-icon", "span");

    var loading_icon_img_bg = createElementWithClass('ezmu__loading-icon-img-bg', 'span');
    loading_icon.appendChild(loading_icon_img_bg);

    loading_section.appendChild(loading_icon);
    return loading_section;
  }

  function createThumbnailListElm() {
    var thumbnail_list = document.createElement("div");
    addClass(thumbnail_list, "ezmu__thumbnail-list");
    return thumbnail_list;
  }

  function createThumbnailListItemElm(data, metaData) {
    var id = data && "id" in data ? data.id : "";

    var class_name = 'ezmu__thumbnail-list-item';
    if (metaData.options.uploaderType === 'avater') {
      class_name = class_name + ' ezmu__thumbnail_avater';
    }

    var thumbnail_list_item = createElementWithClass(class_name);
    thumbnail_list_item.setAttribute("data-id", id);

    var thumbnail_list_item_front = createThumbnailListItemFrontElm(data, metaData);
    var thumbnail_list_item_back = createThumbnailListItemBackElm(data);

    thumbnail_list_item.appendChild(thumbnail_list_item_front);
    thumbnail_list_item.appendChild(thumbnail_list_item_back);

    return thumbnail_list_item;
  }

  function createThumbnailListItemFrontElm(data, metaData) {
    var thumbnail_list_item_front = document.createElement("div");
    addClass(thumbnail_list_item_front, "ezmu__thumbnail-list-item_front");

    if (metaData.options.featured && 0 === metaData.index) {
      var tag_txt = metaData.options.dictionary.label.featured;
      var featured_elm = createElementWithClass('ezmu__featured_tag', 'span', tag_txt);
      thumbnail_list_item_front.appendChild(featured_elm);
    }

    var thumbnail_list_item_close = createThumbnailListItemCloseElm(data);


    if (metaData.options.showFileSize && (data && "fileSizeInText" in data)) {
      var thumbnail_list_item_size = createThumbnailListItemSizeElm(data);
      thumbnail_list_item_front.appendChild(thumbnail_list_item_size);
    }

    if (metaData.options.allowSorting) {
      var thumbnail_list_item_sort_buttons = createThumbnailListItemSortButtonsElm();
      thumbnail_list_item_front.appendChild(thumbnail_list_item_sort_buttons);
    }


    thumbnail_list_item_front.appendChild(thumbnail_list_item_close);

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
    
    var limit_exceeded = ( (typeof data === 'object') && ('limitExceeded' in data) && data.limitExceeded ) ? true : false;
    var state_class = ( limit_exceeded ) ? ' has-error' : '';
    var thumbnail_list_item_size_text = createElementWithClass(
      "ezmu__front-item__thumbnail-size-text" + state_class,
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
      "ezmu__front-item__sort-button ezmu--sort-down"
    );
    thumbnail_list_item_sort_buttons_down.setAttribute("type", "button");

    thumbnail_list_item_sort_buttons_down.innerHTML = '<span class="ezmu__front-item__sort-button-skin ezmu--sort-down"></span>';

    var thumbnail_list_item_sort_buttons_up = document.createElement("button");
    addClass(
      thumbnail_list_item_sort_buttons_up,
      "ezmu__front-item__sort-button ezmu--sort-up"
    );
    thumbnail_list_item_sort_buttons_up.setAttribute("type", "button");
    thumbnail_list_item_sort_buttons_up.innerHTML = '<span class="ezmu__front-item__sort-button-skin ezmu--sort-up"></span>';

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
    var thumbnail_list_item_img = createElementWithClass('ezmu__thumbnail-img', 'img');
    var thumbnail_list_item_img_bg = createElementWithClass('ezmu__thumbnail-img-bg', 'span');

    if (typeof data !== "object") {
      return thumbnail_list_item_img_bg;
    }

    var data_type = "type" in data ? data.type : "file";
    var data_url = "url" in data ? data.url : null;
    var data_blob = "blob" in data ? data.blob : null;

    var type_is_image = data_type.match(/image/g);

    if (type_is_image) {
      var img_src = data_url ? data_url : img_src;
      img_src = data_blob ? data_blob : img_src;
      thumbnail_list_item_img.src = img_src;
      thumbnail_list_item_img.alt = 'image';
      return thumbnail_list_item_img;
    }

    return thumbnail_list_item_img_bg;
  }

  function getMarkupsFilesMeta(container) {
    if (!container) {
      return false;
    }

    var markup_files_meta = container.querySelectorAll('.ezmu__old-files-meta');
    var files_meta = [];

    if (!markup_files_meta.length) {
      return false;
    }

    for (var i = 0; i < markup_files_meta.length; i++) {
      var elm = markup_files_meta[i];
      var url = elm.getAttribute('data-url');
      var attachment_id = elm.getAttribute('data-attachment-id');
      var size = elm.getAttribute('data-size');
      var type = elm.getAttribute('data-type');

      if (!(url && url.length)) {
        continue;
      }

      var meta = {};
      meta.url = url;

      if (attachment_id && attachment_id.length) {
        meta.attachmentID = attachment_id;
      }

      if (size && size.length) {
        meta.size = parseInt(size);
      }

      if (type && type.length) {
        meta.type = type;
      }

      files_meta.push(meta);
    }

    return files_meta;
  }

  function updateValidationFeedback(error_log, container) {
    container.innerHTML = '';
    if (!error_log.length) {
      removeClass(container, 'ezmu--show');
      return;
    }
    addClass(container, 'ezmu--show');

    var alert_box = createElementWithClass('ezmu_alert ezmu_alert_error');
    alert_box.innerHTML = error_log[0].message;
    container.appendChild(alert_box);

    // for ( var i = 0; i < error_log.length; i++ ) {
    //   var alert_box = createElementWithClass('ezmu_alert ezmu_alert_error');
    //   alert_box.innerHTML = error_log[i].message;
    //   container.appendChild(alert_box);
    // }
  }

  function createElementWithClass(class_name, elm, child) {
    class_name = (typeof class_name === 'undefined') ? "" : class_name;
    elm = (typeof elm === 'undefined') ? "div" : elm;
    child = (typeof child === 'undefined') ? "" : child;

    var element = document.createElement(elm);
    addClass(element, class_name);

    element.innerHTML = child;
    return element;
  }

  function validateFileExtension(file, allowedFileFormats) {
    allowedFileFormats = (typeof allowedFileFormats === 'undefined') ? [] : allowedFileFormats;

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

  function validateDuplicateFile(all_files, current_file) {
    var has_no_duplicate = true;

    for (var i = 0; i < all_files.length; i++) {
      var file_item = all_files[i];

      if (file_item.name === current_file.name) {
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
    var _d0 = file_size_in_kb.toFixed();
    var _d2 = file_size_in_kb.toFixed(2);
    var _file_size = (_d0 == _d2) ? _d0 : _d2;
    var _file_size_in_kb = _file_size + " KB";


    var file_size_in_mb = file_size_in_kb < 1024 ? null : file_size_in_kb / 1024;
    var _file_size_in_mb = '';

    if (file_size_in_mb) {
      var _d0 = file_size_in_mb.toFixed();
      var _d2 = file_size_in_mb.toFixed(2);
      var _file_size = (_d0 == _d2) ? _d0 : _d2;
      _file_size_in_mb = _file_size + " MB";
    }


    var formated_file_size = file_size_in_mb ? _file_size_in_mb : _file_size_in_kb;
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

  // isPositiveNumber
  function isPositiveNumber(data) {
    var data_is_valid = false;

    if (typeof data === 'string') {
      var has_non_digit = data.match(/[^\d]/);
      var chk = (!has_non_digit) ? parseInt(data) : null;
      data_is_valid = (chk && chk > 0) ? true : false;
      data = (data_is_valid) ? chk : data;
    }

    if (typeof data === 'number') {
      data = (data < 1) ? false : data;
      data_is_valid = (data) ? true : false;
    }

    return (data_is_valid) ? data : false;
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
        if (property === 'dictionary') {
          // for ( var dictionaryItem in args[property] ) {
          //   if (args[property].hasOwnProperty(dictionaryItem)) {
          //     defaults[property][dictionaryItem] = args[property][dictionaryItem];
          //   }
          // }
        } else {
          defaults[property] = args[property];
        }

      }
    }
    return defaults;
  }



  // addClass
  function addClass(element, class_name) {
    if (!(typeof element === 'object' && 'className' in element)) { return; }

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

  // forEach
  function forEach(array, cb) {
    for (var i = 0; i < array.length; i++) {
      cb(array[i], i);
    }
  }
})();