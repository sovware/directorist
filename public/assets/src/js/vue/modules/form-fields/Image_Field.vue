<template>
<div class="cptm-form-group">
    <label>{{ label }}</label>
    <input type="file" @change="$emit('update', $event.target.files)">
</div>
</template>

<script>
export default {
    name: 'image-field',
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
            default: '',
        },
        value: {
            type: [ Boolean, String, Number ],
            required: false,
            default: '',
        },
        validation: {
            type: Array,
            required: false,
        },
    },
    computed: {
    
    },

    data() {
        return {

        }
    },
}
</script>
