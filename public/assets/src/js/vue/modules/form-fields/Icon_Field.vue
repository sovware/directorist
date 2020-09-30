<template>
    <text-field v-bind="the_props" />
</template>

<script>
export default {
    name: 'icon-field',
    model: {
        prop: 'value',
        event: 'input'
    },
    props: {
        label: {
            type: String,
            required: false,
            default: '',
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
                type: 'icon-field',
                label: this.label,
                value: this.value,
                name: this.name,
                placeholder: this.placeholder,
            }
        },
    },
}
</script>
