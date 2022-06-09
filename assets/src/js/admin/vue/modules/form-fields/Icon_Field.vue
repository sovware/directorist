<template>
  <div class="icon-picker-wrap">
    <div class="cptm-form-group icon-picker-selector">
      <label for="">Icon</label>
      <input
        type="text"
        placeholder="Click to select icon"
        class="cptm-form-control"
      />
    </div>
    <div class="icon-picker">
      <div class="icon-picker__inner">
        <a href="#" class="icon-picker__close"
          ><span class="fa-solid fa-xmark"></span
        ></a>
        <div class="icon-picker__sidebar">
          <div class="icon-picker__filter">
            <label for="">Filter By Name</label>
            <input type="text" placeholder="Search" />
          </div>
          <div class="icon-picker__filter">
            <label for="">Filter By Icon Pack</label>
            <select>
              <option value="fontAwesome">Font Awesome</option>
              <option value="lineAwesome">Line Awesome</option>
            </select>
          </div>
          <div class="icon-picker__preview" ref="selectedIconWrap"></div>
          <button class="cptm-btn cptm-btn-primary icon-picker__done-btn">
            Done
          </button>
        </div>
        <div class="icon-picker__content" ref="iconPickerElm"></div>
      </div>
    </div>
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
    args.selectedIcon = this.$refs.selectedIconWrap;
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
