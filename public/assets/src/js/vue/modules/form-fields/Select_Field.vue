<template>
<div class="cptm-form-group">
    <label for="">{{label}}</label>
    <select v-if="multiple" multiple @change="$emit('update', $event.target.value)">
        <option v-for="( option, option_key ) in options" :key="option_key" :value="option.value">{{ option.label }}</option>
    </select>
    <select v-else @change="$emit('update', $event.target.value)">
        <option v-for="( option, option_key ) in options" :key="option_key" :value="option.value">{{ option.label }}</option>
    </select>
    </select>
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
            default: '',
        },
        value: {
            type: [ String, Number ],
            required: false,
            default: '',
        },
        multiple: {
            type: [ Boolean, String, Number ],
            required: false,
            default: '',
        },
        options: {
            type: Array,
            required: false,
            default: null,
        },
        name: {
            type: [String, Number],
            required: false,
            default: '',
        },
        placeholder: {
            type: [String, Number],
            required: false,
            default: '',
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