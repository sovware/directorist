<template>
    <component 
        :is="getTheTheme( 'shortcode-field' )" 
        v-bind="$props"
        v-if="canShow"
        @update="$emit( 'update', $event )"
        @do-action="$emit( 'do-action', $event )"
        @validate="$emit( 'validate', $event )"
    />
</template>

<script>
import feild_helper from '../../mixins/form-fields/helper';
import props from '../../mixins/form-fields/input-field-props';

export default {
    name: 'shortcode-field',
    mixins: [ props, feild_helper ],
}
</script>
