<template>
    <text-field v-bind="$props" @update="$emit('update', $event)"/>
</template>

<script>
export default {
    name: 'hidden-field',
    model: {
        prop: 'value',
        event: 'update'
    },
    props: {
        type: {
            type: String,
            required: false,
            default: 'number',
        },
        label: {
            type: String,
            required: false,
            default: 'Label',
        },
        value: {
            type: [String, Number],
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
