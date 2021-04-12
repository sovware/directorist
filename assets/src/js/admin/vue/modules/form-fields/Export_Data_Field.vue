<template>
    <component 
        :is="getTheTheme( 'export-data-field' )" 
        v-if="canShow"
        v-bind="$props"
        @do-action="$emit( 'do-action', $event )"
    >
    </component>
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import props from './../../mixins/form-fields/input-field-props';

export default {
    name: 'export-data-field',
    mixins: [ props, feild_helper ],
}
</script>