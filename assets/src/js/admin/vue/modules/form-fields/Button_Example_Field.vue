<template>
    <component 
        :is="getTheTheme( 'button-example-field' )" 
        v-if="canShow"
        v-bind="$props"
        @do-action="$emit( 'do-action', $event )"
        @update="$emit( 'update', $event )"
    />
</template>

<script>
import feild_helper from '../../mixins/form-fields/helper';
import props from '../../mixins/form-fields/input-field-props';

export default {
    name: 'button-example-field',
    mixins: [ props, feild_helper ],
}
</script>
