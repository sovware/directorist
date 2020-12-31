<template>
    <component :is="getTheTheme( 'wp-media-picker-field' )" v-bind="$props"></component>
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import wp_media_picker_field from './../../mixins/form-fields/wp-media-picker-field';

export default {
    name: 'wp-media-picker-field',
    mixins: [ feild_helper, wp_media_picker_field ],
}
</script>
