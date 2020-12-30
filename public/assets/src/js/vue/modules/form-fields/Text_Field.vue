<template>
    <component :is="getTheTheme()" v-bind="$props"></component>
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import text_feild from './../../mixins/form-fields/text-field';

export default {
    name: 'text-field',
    mixins: [ feild_helper, text_feild ],
}
</script>
