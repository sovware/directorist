<template>
<div class="cptm-form-group">
    <label>{{ label }}</label>
    <input type="file" @change="$emit('update', $event.target.files)">
</div>
</template>

<script>
export default {
    name: 'image_picker',
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
        rules: {
            type: Object,
            required: false,
            default: null,
        },
    },
    computed: {
        input_type() {
            const supported_types = ['text', 'number', 'password', 'image_picker'];

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
