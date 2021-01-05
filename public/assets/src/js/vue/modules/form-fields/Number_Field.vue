<template>
    <text-field 
        v-bind="$props" 
        @update="$emit('update', $event)" 
        @validate="$emit( 'validate', $event )"
    />
</template>

<script>
import props from './../../mixins/form-fields/input-field-props';

export default {
    name: 'number-field',
    mixins: [ props ],
    model: {
        prop: 'value',
        event: 'update'
    }
}
</script>
