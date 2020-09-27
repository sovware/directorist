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
