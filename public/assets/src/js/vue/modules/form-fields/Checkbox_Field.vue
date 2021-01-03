<template>
    <component 
        :is="getTheTheme( 'checkbox-field' )" 
        v-bind="$props"
        @update="$emit( 'update', $event )"
    />
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import checkbox_feild from './../../mixins/form-fields/checkbox-field';

export default {
    name: 'checkbox-field',
    mixins: [ checkbox_feild, feild_helper ],
}
</script>