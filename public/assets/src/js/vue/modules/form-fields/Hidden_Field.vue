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
    },

    computed: {
        the_props() {
            return {
                type: 'hidden-field',
                value: this.value,
                name: this.name,
            }
        },
    },
}
</script>
