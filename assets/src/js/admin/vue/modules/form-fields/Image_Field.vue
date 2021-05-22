<template>
<div class="cptm-form-group">
    <label>{{ label }}</label>
    <input type="file" @change="$emit('update', $event.target.files)">
</div>
</template>

<script>
import props from './../../mixins/form-fields/input-field-props';

export default {
    name: 'image-field',
    mixins: [ props ],
    model: {
        prop: 'value',
        event: 'input'
    },
}
</script>
