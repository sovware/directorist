<template>
    <component :is="getTheTheme( 'export-field' )" v-if="canShow" v-bind="$props"/>
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import props from './../../mixins/form-fields/input-field-props';

export default {
    name: 'export-field',
    mixins: [ props, feild_helper ],
}
</script>
