<template>
    <component 
        :is="getTheTheme( 'color-field' )" 
        v-if="canShow"
        v-bind="$props"
        @update="$emit( 'update', $event )"
    />
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';

export default {
    name: 'color-field',
    mixins: [ feild_helper ],
    props: {
        theme: {
            type: String,
            default: 'default'
        },
        type: {
            type: String,
            required: false,
            default: 'text',
        },
        label: {
            type: String,
            required: false,
            default: '',
        },
        description: {
            type: String,
            required: false,
            default: '',
        },
        value: {
            required: false,
            default: '',
        },
        name: {
            type: [String, Number],
            required: false,
            default: '',
        },
        placeholder: {
            type: [String, Number],
            required: false,
            default: '',
        },
        rules: {
            type: Object,
            required: false,
        },
        validationFeedback: {
            type: Object,
            required: false,
        },
        validation: {
            type: Array,
            required: false,
        },
    },
}
</script>
