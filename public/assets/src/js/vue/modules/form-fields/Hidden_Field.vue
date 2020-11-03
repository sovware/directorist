<template>
    <text-field v-bind="the_props" @update="$emit('update', $event)"/>
</template>

<script>
export default {
    name: 'hidden-field',
    model: {
        prop: 'value',
        event: 'input'
    },
    props: {
        fieldId: {
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
        validation: {
            type: Array,
            required: false,
        },
    },

    computed: {
        the_props() {
            return {
                type: 'hidden-field',
                value: this.value,
                name: this.name,
                validation: this.validation,
            }
        },
    },
}
</script>
