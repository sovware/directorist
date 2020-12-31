<template>
    <component :is="getTheTheme( 'radio-field' )" v-bind="$props"></component>
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import radio_feild from './../../mixins/form-fields/radio-field';

export default {
    name: 'radio-field',
    mixins: [ radio_feild, feild_helper ]
}
</script>