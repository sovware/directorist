<template>
    <component 
        :is="getTheTheme( 'note-field' )" 
        v-if="canShow"
        v-bind="$props"
        @update="$emit( 'update', $event )"
    >
    </component>
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import props from './../../mixins/form-fields/input-field-props';

export default {
    name: 'note-field',
    mixins: [ props, feild_helper ]
}
</script>
