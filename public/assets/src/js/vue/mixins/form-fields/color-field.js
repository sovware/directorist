import InputColorPicker from 'vue-native-color-picker';

import validation from '../validation';
import field_helper from './helper.js';


export default {
    mixins: [ validation, field_helper ],
    components: {
        "v-input-colorpicker": InputColorPicker
    },
    model: {
        prop: 'value',
        event: 'input'
    },
    props: {
        fieldId: {
            type: String,
            default: ''
        },
        theme: {
            type: String,
            default: 'default'
        },
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
        description: {
            type: String,
            required: false,
            default: '',
        },
        value: {
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
        rules: {
            type: Object,
            required: false,
        },
        validationFeedback: {
            type: Object,
            required: false,
        },
        validation: {
            type: Array,
            required: false,
        },
        input_style: {
            type: Object,
            required: false,
        },
    },

    created() {
        this.local_value = this.value;

        console.log( this.value );
    },

    watch: {
        local_value() {
            this.$emit( 'update', this.local_value );
        }
    },

    computed: {
        formGroupClass() {
            return {
                ...this.validationClass,
                'cptm-mb-0': ( 'hidden' === this.input_type ) ? true : false,
            }
        },

        formControlClass() {
            let class_names = {};

            if ( this.input_style && this.input_style.class_names  ) {
                class_names[ this.input_style.class_names ] = true;
            }
            
            return class_names;
        }
    },

    data() {
        return {
            local_value: '#fff'
        }
    },
}