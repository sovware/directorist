<template>
    <component 
        :is="getTheTheme( 'ajax-action-field' )" 
        v-if="canShow"
        v-bind="$props"
        @update="$emit( 'update', $event )"
        @do-action="$emit( 'do-action', $event )"
        @validate="$emit( 'validate', $event )"
    >
    </component>
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import props from './../../mixins/form-fields/input-field-props';

export default {
    name: 'ajax-action-field',
    mixins: [ props, feild_helper ],
}
</script>