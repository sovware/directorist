<template>
<div class="cptm-form-group">
    <label :for="name">{{ label }}</label>
    <input type="checkbox" :checked="value" @change="$emit('update', $event.target.checked)">
</div>
</template>

<script>
export default {
    name: 'text-field',
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
            type: [ Boolean, String, Number ],
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
            default: 'placeholder',
        },
        rules: {
            type: Object,
            required: false,
            default: null,
        },
    },

    computed: {
        input_type() {
            const supported_types = ['text', 'number', 'password', 'date'];

            if (supported_types.indexOf(this.type)) {
                return this.type;
            }

            return 'text';

        }
    },

    data() {
        return {

        }
    },
}
</script>
