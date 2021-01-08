import validator from './../validator';
import props from './input-field-props.js';

export default {
    mixins: [ props, validator ],
    model: {
        prop: 'value',
        event: 'input'
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