import validator from './../validator';
import props from './input-field-props.js';

export default {
    mixins: [ props, validator ],
    model: {
        prop: 'value',
        event: 'input'
    },

    computed: {
        input_type() {
            const supported_types = {
                'text-field': 'text', 
                'number-field': 'number', 
                'password-field': 'password', 
                'date-field': 'date',
                'hidden-field': 'hidden', 
                'text': 'text', 
                'number': 'number', 
                'password': 'password', 
                'date': 'date',
                'hidden': 'hidden', 
            };

            if ( typeof supported_types[ this.type ] !== 'undefined' ) {
                return supported_types[ this.type ];
            }
            
            return 'text';
        },

        valueShouldStringify() {
            if ( ! ( typeof this.value === 'string' || typeof this.value === 'number') ) {
                return true;
            }

            return false;
        },

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

        }
    },
}