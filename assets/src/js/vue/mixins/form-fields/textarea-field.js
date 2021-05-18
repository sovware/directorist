import props from './input-field-props.js';

export default {
    mixins: [ props ],
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
            };

            if ( typeof supported_types[ this.type ] !== 'undefined' ) {
                return supported_types[ this.type ];
            }
            
            return 'text';
        },

        formGroupClass() {
            var validation_classes = ( this.validationLog.inputErrorClasses ) ? this.validationLog.inputErrorClasses : {};

            return {
                ...validation_classes,
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
            validationLog: {},
        }
    },
}