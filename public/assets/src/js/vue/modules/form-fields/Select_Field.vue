<template>
<div class="cptm-form-group">
    <label for="">{{label}}</label>
    <select @change="update_value( $event.target.value )" :value="local_value">
        <template v-for="( option, option_key ) in options">
            <template v-if="option.group && option.options">
                <optgroup :label="option.group" :key="option_key">
                    <option v-for="( sub_option, sub_option_key ) in option.options" :key="sub_option_key" :value="sub_option.value">
                        {{ sub_option.label }}
                    </option>
                </optgroup>
            </template>

            <template v-else>
                <option :key="option_key" :value="option.value">{{ option.label }}</option>
            </template>
        </template>
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

    created() {
        this.local_value = this.value;
        this.$emit( 'update', this.local_value );
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
            local_value: '',
        }
    },

    methods: {
        update_value( value ) {
            this.local_value = value;
            this.$emit( 'update', this.local_value );
        }
    },
}
</script>