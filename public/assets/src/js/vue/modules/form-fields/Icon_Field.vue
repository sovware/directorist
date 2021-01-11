<template>
    <text-field v-bind="$props" @update="$emit('update', $event)" @do-action="$emit( 'do-action', $event )"/>
</template>

<script>
import props from './../../mixins/form-fields/input-field-props';

export default {
    name: 'icon-field',
    mixins: [ props ],
    model: {
        prop: 'value',
        event: 'input'
    },
}
</script>
