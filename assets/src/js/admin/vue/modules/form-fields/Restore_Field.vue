<template>
    <component 
        :is="getTheTheme( 'restore-field' )" 
        v-if="canShow"
        v-bind="$props"
        @do-action="$emit( 'do-action', $event )"
    />
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import props from './../../mixins/form-fields/input-field-props';

export default {
    name: 'restore-field',
    mixins: [ props, feild_helper ],
}
</script>
