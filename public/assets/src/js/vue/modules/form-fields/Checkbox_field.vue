<template>
<div class="cptm-form-group">
    <label :for="name">{{ label }}</label>
    <input class="cptm-form-control" :type="input_type" :value="value" :name="name" @input="$emit('update', $event.target.value)">
</div>
</template>

<script>
export default {
    name: 'checkbox',
    model: {
        prop: 'value',
        event: 'input'
    },
    props: {
        type: {
            type: String,
            required: false,
            default: 'text',
        },
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
        rules: {
            type: [Object],
            required: false,
            default: null,
        },
    },

    computed: {
        input_type() {

            return 'checkbox';

        }
    },

    data() {
        return {

        }
    },
}
</script>
