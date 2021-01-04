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

export default {
    name: 'text-field',
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
        input_style: {
            type: Object,
            required: false,
        },
    },

    watch: {
        validationFeedback( value ) {
            console.log( { value } );
        }
    }
}
</script>
