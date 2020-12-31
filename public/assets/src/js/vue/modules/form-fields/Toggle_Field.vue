<template>
    <component :is="getTheTheme( 'toggle-field' )" v-bind="$props"></component>
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import toggle_feild from './../../mixins/form-fields/toggle-field';

export default {
    name: 'toggle-field',
    mixins: [ toggle_feild, feild_helper ]
}
</script>
