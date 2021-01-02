<template>
    <component 
        :is="getTheTheme( 'select-field' )" 
        v-bind="$props"
        @update="$emit( 'update', $event )"
    >
    </component>
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import select_field from './../../mixins/form-fields/select-field';

export default {
    name: 'select-field',
    mixins: [ feild_helper, select_field ],
}
</script>