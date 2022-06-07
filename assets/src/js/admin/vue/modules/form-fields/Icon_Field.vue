<template>
  <!-- <text-field v-bind="$props" @update="$emit('update', $event)" @do-action="$emit( 'do-action', $event )"/> -->
  <div class="icon-picker" ref="iconPickerElm"></div>
</template>

<script>
import props from "./../../mixins/form-fields/input-field-props";
import { IconPicker } from "./../../../../lib/icon-picker";

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
        fontAwesome: {
          label: "Font Awesome",
          iconTypes: {
            solid: {
              label: "Solid",
              key: "fa-solid",
            },
            regular: {
              label: "Solid",
              key: "fa-regular",
            },
            brand: {
              label: "Brand",
              key: "fa-brands",
            },
          },
          icons: [
            {
              key: "fa-home",
              types: ["solid", "regular"],
            },
            {
              key: "fa-car",
              types: ["solid", "regular"],
            },
            {
              key: "fa-facebook",
              types: ["brand"],
            },
          ],
        },
        lineAwesome: {
          label: "Line Awesome",
          iconTypes: {
            solid: {
              label: "Solid",
              key: "fa-solid",
            },
            regular: {
              label: "Solid",
              key: "fa-regular",
            },
            brand: {
              label: "Brand",
              key: "fa-brands",
            },
          },
          icons: [
            {
              key: "la-home",
              types: ["solid", "regular"],
            },
            {
              key: "la-car",
              types: ["solid", "regular"],
            },
            {
              key: "la-facebook",
              types: ["brand"],
            },
          ],
        },
      },
    };
  },
  methods: {
    onSelectIcon(value) {
      console.log("onSelectIcon", value);
      this.$emit("update", value);
    },
  },
};
</script>
