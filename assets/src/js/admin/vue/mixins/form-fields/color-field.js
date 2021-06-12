import InputColorPicker from 'vue-native-color-picker';
import props from './input-field-props.js';

export default {
    mixins: [ props ],
    components: {
        "v-input-colorpicker": InputColorPicker
    },
    model: {
        prop: 'value',
        event: 'input'
    },

    created() {
        this.local_value = this.value;
    },

    watch: {
        local_value() {
            this.$emit( 'update', this.local_value );
        }
    },

    computed: {
        formGroupClass() {
            var validation_classes = ( this.validationLog.inputErrorClasses ) ? this.validationLog.inputErrorClasses : {};

            return {
                ...validation_classes,
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
            local_value: '#fff',
            validationLog: {}
        }
    },
}