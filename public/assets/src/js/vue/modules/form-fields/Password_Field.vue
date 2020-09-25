<template>
    <text-field v-bind="the_props" />
</template>

<script>
export default {
    name: 'hidden-field',
    model: {
        prop: 'value',
        event: 'input'
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
            default: 'Value',
        },
        name: {
            type: [String, Number],
            required: false,
            default: 'Name',
        },
        placeholder: {
            type: [String, Number],
            required: false,
            default: '',
        },
    },

    computed: {
        the_props() {
            return {
                type: 'password-field',
                label: this.label,
                value: this.value,
                name: this.name,
                placeholder: this.placeholder,
            }
        },
    },
}
</script>
