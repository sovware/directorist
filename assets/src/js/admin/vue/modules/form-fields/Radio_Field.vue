<template>
    <component 
        :is="getTheTheme( 'radio-field' )" 
        v-if="canShow"
        v-bind="$props"
        @update="$emit( 'update', $event )"
    />
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import props from './../../mixins/form-fields/input-field-props';

export default {
    name: 'radio-field',
    mixins: [ props, feild_helper ]
}
</script>