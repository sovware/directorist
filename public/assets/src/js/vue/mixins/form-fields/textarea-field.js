import validation from '../validation';

export default {
    mixins: [ validation ],
    model: {
        prop: 'value',
        event: 'input'
    },
    props: {
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
        cols: {
            type: [String, Number],
            required: false,
            default: '30',
        },
        rows: {
            type: [String, Number],
            required: false,
            default: '10',
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
            }
        },
    },

    watch: {
        local_value() {
            this.$emit( 'update', this.local_value );
        }
    },

    created() {
        this.local_value = this.value;
    },

    data() {
        return {
            local_value: '',
        }
    },
}