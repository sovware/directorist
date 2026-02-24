<template>
  <component
    :is="getTheTheme('conditional-logic-field')"
    v-if="canShow"
    v-bind="$props"
    @do-action="$emit('do-action', $event)"
    @update="$emit('update', $event)"
  />
</template>

<script>
import feild_helper from "./../../mixins/form-fields/helper";
import props from "./../../mixins/form-fields/input-field-props";

export default {
  name: "conditional-logic-field",
  mixins: [props, feild_helper],
};
</script>

