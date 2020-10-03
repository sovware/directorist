<template>
    <text-field v-bind="the_props" @update="$emit('update', $event)"/>
</template>

<script>
export default {
    name: 'hidden-field',
    model: {
        prop: 'value',
        event: 'update'
    },
    props: {
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
        validation: {
            type: Array,
            required: false,
        },
    },

    computed: {
        the_props() {
            return {
                type: 'number-field',
                label: this.label,
                value: this.value,
                name: this.name,
                placeholder: this.placeholder,
                validation: this.validation,
            }
        },
    },
}
</script>
