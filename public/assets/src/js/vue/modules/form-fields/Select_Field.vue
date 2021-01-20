<template>
    <component 
        :is="getTheTheme( 'select-field' )" 
        v-if="canShow"
        v-bind="$props"
        @do-action="$emit( 'do-action', $event )"
        @update="$emit( 'update', $event )"
    >
    </component>
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import props from './../../mixins/form-fields/input-field-props';

export default {
    name: 'select-field',
    mixins: [ props, feild_helper ],
    model: {
        prop: 'value',
        event: 'update'
    },
}
</script>