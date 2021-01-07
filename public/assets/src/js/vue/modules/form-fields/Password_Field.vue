<template>
    <text-field v-bind="$props" @update="$emit('update', $event)"/>
</template>

<script>
import props from './../../mixins/form-fields/input-field-props';

export default {
    name: 'hidden-field',
    mixins: [ props ],
    model: {
        prop: 'value',
        event: 'input'
    },
}
</script>
