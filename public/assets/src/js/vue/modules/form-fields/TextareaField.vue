<template>
    <component 
        :is="getTheTheme( 'textarea-field' )" 
        v-bind="$props"
        @update="$emit( 'update', $event )"
    >
    </component>
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import textarea_feild from './../../mixins/form-fields/textarea-field';

export default {
    name: 'textarea-field',
    mixins: [ textarea_feild, feild_helper ],
}
</script>
