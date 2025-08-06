<template>
  <component
    :is="getTheTheme('tab-field')"
    v-if="canShow"
    v-bind="$props"
    @update="$emit('update', $event)"
  />
</template>

<script>
import feild_helper from "../../mixins/form-fields/helper";
import props from "../../mixins/form-fields/input-field-props";

export default {
  name: "tab-field",
  mixins: [props, feild_helper],
};
</script>
