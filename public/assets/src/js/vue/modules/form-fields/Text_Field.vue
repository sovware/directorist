<template>
<div class="cptm-form-group" :class="( 'hidden' === input_type ) ? 'cptm-mb-0' : ''">
    <label v-if="( 'hidden' !== input_type && label.length )" :for="name">{{ label }}</label>
    <input class="cptm-form-control" :type="input_type" :value="value" :placeholder="placeholder" @input="$emit('update', $event.target.value)">
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
            type: [String, Number],
            required: false,
            default: '',
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
    },

    computed: {
        input_type() {
            const supported_types = {
                'text-field': 'text', 
                'number-field': 'number', 
                'password-field': 'password', 
                'date-field': 'date',
                'hidden-field': 'hidden', 
            };

            if ( typeof supported_types[ this.type ] !== 'undefined' ) {
                return supported_types[ this.type ];
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
