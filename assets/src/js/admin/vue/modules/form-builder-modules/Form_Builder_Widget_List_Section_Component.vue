<template>
  <div class="cptm-form-builder-preset-fields">
    <div class="cptm-form-builder-preset-fields-header">
      <a
        href="#"
        class="cptm-form-builder-preset-fields-header-action-link"
        @click.prevent="togglePresetExpanded"
      >
        <span class="cptm-form-builder-preset-fields-header-action-text">
          {{ title }}</span
        >
        <span
          class="cptm-form-builder-preset-fields-header-action-icon"
          :class="
            isPresetExpanded ? 'action-collapse-up' : 'action-collapse-down'
          "
        >
          <span aria-hidden="true" class="uil uil-angle-down"></span>
        </span>
      </a>
    </div>

    <slide-up-down :active="isPresetExpanded" :duration="500">
      <ul class="cptm-form-builder-field-list" v-if="filtered_widget_list">
        <draggable-list-item
          list-type="li"
          item-class-name="cptm-form-builder-field-list-item"
          v-for="(widget, widget_key) in filtered_widget_list"
          :key="widget_key"
          :drag-type="allowMultiple || widget.allowMultiple ? 'clone' : 'move'"
          @drag-start="$emit('drag-start', { widget_key, widget })"
          @drag-end="$emit('drag-end', { widget_key, widget })"
        >
          <span class="cptm-form-builder-field-list-icon">
            <span
              v-if="widget.icon && widget.icon.length"
              :class="widget.icon"
            ></span>
          </span>
          <span class="cptm-form-builder-field-list-label">{{
            widget.label
          }}</span>
        </draggable-list-item>
      </ul>
    </slide-up-down>
  </div>
</template>

<script>
import helpers from "../../mixins/helpers";

export default {
  name: "form-builder-widget-list-section-component",
  mixins: [helpers],
  props: {
    fieldId: {
      default: "",
    },
    title: {
      default: "",
    },
    description: {
      default: "",
    },
    widgetGroup: {
      default: "",
    },
    widgets: {
      default: "",
    },
    template: {
      default: "",
    },
    allowMultiple: {
      default: true,
    },
    selectedWidgets: {
      default: "",
    },
    activeWidgetGroups: {
      default: "",
    },
    presetExpanded: {
      default: false,
    },
  },

  created() {
    this.$parent.$on("active-widgets-updated", this.filtereWidgetList);
    this.filtereWidgetList();
  },

  data() {
    return {
      base_widget_list: {},
      filtered_widget_list: {},
      isPresetExpanded: true,
    };
  },

  watch: {
    activeWidgetGroups() {
      this.filtereWidgetList();
    },
  },

  methods: {
    togglePresetExpanded() {
      this.isPresetExpanded = !this.isPresetExpanded;
    },

    // filtereWidgetList
    filtereWidgetList() {
      // Add widget group and widget name
      let widget_list = this.widgets;

      for (let widget_key in widget_list) {
        widget_list[widget_key].options.widget_group = {
          type: "hidden",
          value: this.widgetGroup,
        };

        widget_list[widget_key].options.widget_name = {
          type: "hidden",
          value: widget_key,
        };

        if (widget_list[widget_key].widgets) {
          for (let sub_widget_key in widget_list[widget_key].widgets) {
            widget_list[widget_key].widgets[
              sub_widget_key
            ].options.widget_group = {
              type: "hidden",
              value: this.widgetGroup,
            };

            widget_list[widget_key].widgets[
              sub_widget_key
            ].options.widget_name = {
              type: "hidden",
              value: widget_key,
            };

            widget_list[widget_key].widgets[
              sub_widget_key
            ].options.widget_child_name = {
              type: "hidden",
              value: sub_widget_key,
            };
          }
        }
      }

      // filter Widgets By Template
      this.base_widget_list = this.getFilteredWidgetsByTemplate(widget_list);

      // Filtered Widgets By Selected Widgets
      if (!this.allowMultiple) {
        this.filtered_widget_list = this.getFilteredWidgeBySelectedWidgets(
          this.base_widget_list
        );
      } else {
        this.filtered_widget_list = this.base_widget_list;
      }

      this.$emit("update-widget-list", {
        widget_group: this.widgetGroup,
        base_widget_list: this.base_widget_list,
        filtered_widget_list: this.filtered_widget_list,
      });
    },

    // getFilteredWidgetsByTemplate
    getFilteredWidgetsByTemplate(widget_list) {
      if (!this.template.length) {
        return widget_list;
      }
      if (!widget_list) {
        return widget_list;
      }
      if (typeof widget_list !== "object") {
        return widget_list;
      }

      let template_field = this.getTergetFields({ path: this.template });
      template_field = this.isObject(template_field)
        ? this.cloneObject(template_field)
        : null;

      if (!template_field) {
        return widget_list;
      }

      let template_fields =
        this.isObject(template_field) && template_field.value
          ? template_field.value
          : null;
      template_fields =
        this.isObject(template_fields) && template_fields.fields
          ? template_fields.fields
          : null;

      if (!template_fields) {
        return widget_list;
      }

      let template_widgets = {};
      for (let widget_key in template_fields) {
        let _widget_group = template_fields[widget_key].widget_group;
        let _widget_name = template_fields[widget_key].widget_name;
        let _widget_label = "Not Available";

        try {
          _widget_label = this.fields[this.template]["widgets"][_widget_group][
            "widgets"
          ][_widget_name]["label"]
            ? this.fields[this.template]["widgets"][_widget_group]["widgets"][
                _widget_name
              ]["label"]
            : "";
        } catch (error) {
          console.log({
            template: this.template,
            widget_group: _widget_group,
            widget_name: _widget_name,
            template_widgets: this.fields[this.template]["widgets"][
              _widget_group
            ]["widgets"],
            error,
          });
        }

        if (!widget_list[_widget_name]) {
          continue;
        }

        let template_root_options =
          template_field.widgets[_widget_group].widgets[_widget_name];
        if (!template_root_options) {
          continue;
        }

        if (typeof template_root_options.options !== "undefined") {
          delete template_root_options.options;
        }

        if (typeof template_root_options.lock !== "undefined") {
          delete template_root_options.lock;
        }

        let widget_label = widget_list[_widget_name].label
          ? widget_list[_widget_name].label
          : "";
        let template_widget_label =
          template_fields[widget_key].label &&
          template_fields[widget_key].label.length
            ? template_fields[widget_key].label
            : widget_label;

        widget_label =
          widget_label && widget_label.length
            ? widget_label
            : template_widget_label;
        template_root_options.label = widget_label.length
          ? widget_label
          : _widget_label;

        let new_widget_list = this.cloneObject(widget_list);
        Object.assign(new_widget_list[_widget_name], template_root_options);

        if (!new_widget_list[_widget_name].options) {
          new_widget_list[_widget_name].options = {};
        }

        let widgets_options = new_widget_list[_widget_name].options;

        if (typeof widgets_options.label !== "undefined") {
          let sync =
            typeof widgets_options.label.sync !== "undefined"
              ? widgets_options.label.sync
              : true;
          widgets_options.label.value = sync
            ? widget_label
            : widgets_options.label.value;
        }

        widgets_options.widget_group = {
          type: "hidden",
          value: this.widgetGroup,
        };

        widgets_options.widget_name = {
          type: "hidden",
          value: _widget_name,
        };

        widgets_options.original_widget_key = {
          type: "hidden",
          value: widget_key,
        };

        if (!new_widget_list[_widget_name].label) {
          new_widget_list[_widget_name].label = "Not available";
        }

        new_widget_list[_widget_name].options = widgets_options;
        template_widgets[widget_key] = new_widget_list[_widget_name];
      }

      return template_widgets;
    },

    // getFilteredWidgeBySelectedWidgets
    getFilteredWidgeBySelectedWidgets(widget_list) {
      if (!widget_list) {
        return widget_list;
      }
      if (typeof widget_list !== "object") {
        return widget_list;
      }

      let active_widget_groups_keys = [];
      if (this.activeWidgetGroups.length) {
        for (let group of this.activeWidgetGroups) {
          if (!group.widget_name) {
            continue;
          }
          active_widget_groups_keys.push(group.widget_name);
        }
      }

      let selected_widget_keys = [];
      if (this.selectedWidgets && typeof this.selectedWidgets === "object") {
        selected_widget_keys = Object.keys(this.selectedWidgets);
      }

      let new_widget_list = this.cloneObject(widget_list);
      for (let widget_key in new_widget_list) {
        if (new_widget_list[widget_key].allowMultiple) continue;

        if (
          selected_widget_keys.includes(widget_key) ||
          active_widget_groups_keys.includes(widget_key)
        ) {
          delete new_widget_list[widget_key];
        }
      }

      return new_widget_list;
    },

    // cloneObject
    cloneObject(obj) {
      return JSON.parse(JSON.stringify(obj));
    },
  },
};
</script>
