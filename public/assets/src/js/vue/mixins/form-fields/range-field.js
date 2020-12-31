import validation from './../../mixins/validation';

export default {
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