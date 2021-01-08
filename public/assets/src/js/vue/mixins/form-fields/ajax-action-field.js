import validator from '../validator';
import props from './input-field-props.js';
import { mapState } from 'vuex';
const axios = require('axios').default;

export default {
    mixins: [ props, validator ],
    model: {
        prop: 'value',
        event: 'input'
    },

    created() {
        this.setup();
    },

    computed: {
        ...mapState({
            config: 'config'
        }),

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
            validation_message: null,
            button: {
                label: '',
                is_processing: false,
                is_disabled: false,
            }
        }
    },

    methods: {
        setup() {
            this.button.label = this.buttonLabel;
        },

        submitAjaxRequest() {
            if ( this.button.is_processing ) { return; }

            console.log( 'submitAjaxRequest' );

            let ajax_url = ( this.config && this.config.submission && this.config.submission.url ) ? this.config.submission.url : '';
            let action   = this.action;

            if ( ! ajax_url ) { return; }

            let form_data = new FormData();
            form_data.append( 'action', action );

            const self = this;
            this.button.is_processing = true;
            this.button.is_disabled = true;
            this.button.label = this.buttonLabelOnProcessing;

            // Submit the form
            axios.post( ajax_url, form_data )
                .then( response => {
                    console.log( response );

                    let message = null;
                    if ( response.data.success && response.data.data ) {
                        message = { type: 'success', message: response.data.data };
                    } else {
                        message = { type: 'error', message: 'Sorry, something went wrong' };
                    }

                    self.validation_message = message;

                    setTimeout( function() {
                        self.validation_message = null;
                    }, 5000);

                    self.button.is_processing = false;
                    self.button.is_disabled = false;
                    self.button.label = self.buttonLabel;
                })
                .catch( error => {
                    console.log( error );

                    let message = { type: 'error', message: 'Sorry, something went wrong' };
                    self.validation_message = message;

                    setTimeout( function() {
                        self.validation_message = null;
                    }, 5000);

                    self.button.is_processing = false;
                    self.button.is_disabled = false;
                    self.button.label = self.buttonLabel;
                })
        }
    },
}