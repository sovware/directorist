<template>
<div class="cptm-form-group" :class="formGroupClass">
    <label v-if="( 'hidden' !== input_type && label.length )" :for="name">{{ label }}</label>
    <input class="cptm-form-control" :type="input_type" :value="value" :placeholder="placeholder" @input="$emit('update', $event.target.value)">

    <div class="cptm-form-group-feedback" v-if="validationMessages">
        <div class="cptm-form-alert" :class="'cptm-' + validationMessages.type">
            {{ validationMessages.message }}
        </div>
    </div>
</div>
</template>

<script>
import validation from './../../mixins/validation';

export default {
    name: 'text-field',
    mixins: [ validation ],
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
        validation: {
            type: Array,
            required: false,
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
        },

        formGroupClass() {
            return {
                ...this.validationClass,
                'cptm-mb-0': ( 'hidden' === this.input_type ) ? true : false,
            }
        },
    },

    data() {
        return {

        }
    },
}
</script>
