<template>
    <component 
        :is="getTheTheme( 'text-field' )" 
        v-bind="$props"
        @update="$emit( 'update', $event )"
    >
    </component>
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import text_feild from './../../mixins/form-fields/text-field';

export default {
    name: 'text-field',
    mixins: [ feild_helper, text_feild ],
}
</script>
