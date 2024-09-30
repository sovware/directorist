<template>
  <div class="cptm-form-group directorist-type-icon-select">
    <div class="icon-picker-wrap" ref="iconPickerElm"></div>
  </div>
</template>

<script>
import props from "./../../mixins/form-fields/input-field-props";

export default {
  name: "icon-field",
  mixins: [props],
  model: {
    prop: "value",
    event: "input",
  },
  mounted() {
    let args = {};
    args.container = this.$refs.iconPickerElm;
    args.onSelect = this.onSelectIcon;
    args.icons = this.icons;
    args.value = this.value;
    args.labels = directorist_admin.icon_picker_labels;

    this.iconPicker = new IconPicker(args);
    this.iconPicker.init();
  },
  data() {
    return {
      iconPicker: null,
      icons: {
        fontAwesome: directoriistFontAwesomeIcons,
        lineAwesome: directoriistLineAwesomeIcons,
      },
    };
  },
  methods: {
    onSelectIcon(value) {
      this.$emit("update", value);
    },
  },
};
</script>
