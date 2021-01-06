<template>
    <component 
        :is="getTheTheme( 'text-field' )" 
        v-bind="$props"
        v-if="canShow"
        @update="$emit( 'update', $event )"
        @validate="$emit( 'validate', $event )"
    />
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import props from './../../mixins/form-fields/input-field-props';

export default {
    name: 'text-field',
    mixins: [ props, feild_helper ],
}
</script>
