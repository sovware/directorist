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
