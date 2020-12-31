<template>
    <component :is="getTheTheme( 'range-field' )" v-bind="$props"></component>
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import range_feild from './../../mixins/form-fields/range-field';

export default {
    name: 'range-field',
    mixins: [ feild_helper, range_feild ],
}
</script>
