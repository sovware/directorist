<template>
<div class="cptm-form-group" :class="formGroupClass">
    <label v-if="( 'hidden' !== type && label.length )" :for="name">{{ label }}</label>
    
    <div class="cptm-form-range-wrap">
        <div class="cptm-form-range-bar">
            <input 
                type="range" 
                :id="fieldId"
                :step="step" 
                :min="min" 
                :max="max" 
                :name="name" 
                v-model="range_value"
            >
        </div>

        <div class="cptm-form-range-output">
            <span class="cptm-form-range-output-text">{{ range_value }}</span>
        </div>
    </div>

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
    name: 'range-field',
    mixins: [ validation ],
    model: {
        prop: 'value',
        event: 'input'
    },
    props: {
        fieldId: {
            type: [ String, Number ],
            required: false,
            default: '',
        },
        type: {
            type: String,
            required: false,
            default: 'text',
        },
        min: {
            type: Number,
            required: false,
            default: 0,
        },
        max: {
            type: Number,
            required: false,
            default: 100,
        },
        step: {
            type: Number,
            required: false,
            default: 1,
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

    created() {
        this.range_value = this.value;
    },

    watch: {
        range_value() {
            this.$emit('update', this.range_value);
        }
    },

    computed: {
        formGroupClass() {
            return {
                ...this.validationClass,
                'cptm-mb-0': ( 'hidden' === this.input_type ) ? true : false,
            }
        },
    },

    data() {
        return {
            range_value: 0,
        }
    },
}
</script>
