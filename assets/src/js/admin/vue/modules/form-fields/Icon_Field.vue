<template>
  <div class="directorist-type-icon-select">
    <label>Icon</label>
    <div class="icon-picker-wrap" ref="iconPickerElm"></div>
  </div>
</template>

<script>
import props from "./../../mixins/form-fields/input-field-props";
import { IconPicker } from "./../../../../lib/icon-picker";
import fontAwesomeIcons from "./../../../../lib/font-awesome.json";
import lineAwesomeIcons from "./../../../../lib/line-awesome.json";

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
    this.iconPicker = new IconPicker(args);
    this.iconPicker.init();
  },
  data() {
    return {
      iconPicker: null,
      icons: {
        fontAwesome: fontAwesomeIcons,
        lineAwesome: lineAwesomeIcons,
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
