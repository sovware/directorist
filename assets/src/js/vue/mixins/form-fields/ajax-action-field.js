import Vue from 'vue';
import validator from '../validator';
import helpers from '../helpers';
import props from './input-field-props.js';
import { mapState } from 'vuex';
const axios = require('axios').default;

export default {
    mixins: [ props, validator, helpers ],
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
    },

    data() {
        return {
            validation_message: null,
            option_fields: null,
            local_value: {},
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

            if ( this.optionFields ) {
                this.option_fields = this.optionFields;
            }

            if ( this.saveOptionData ) {
                this.loadOldData();
            }
        },

        loadOldData() {
            if ( ! ( this.value && this.option_fields ) ) { return; }

            for ( let field_key in this.value ) {
                if ( typeof this.option_fields[ field_key ] === 'undefined') {
                    continue;
                }
                this.option_fields[ field_key ].value = this.value[ field_key ];
            }
        },

        updateOptionData( value ) {
            this.local_value = value;

            if ( this.saveOptionData ) {
                this.$emit( 'update', this.local_value );
            }
        },

        submitAjaxRequest() {
            if ( this.button.is_processing ) { return; }

            // console.log( 'submitAjaxRequest' );

            let ajax_url = ( this.config && this.config.submission && this.config.submission.url ) ? this.config.submission.url : '';
            let action   = this.action;

            if ( ! ajax_url ) { return; }

            let form_data = new FormData();
            form_data.append( 'action', action );

            // Append if has option field
            if ( this.local_value && typeof this.local_value === 'object' && Object.keys( this.local_value ) ) {
                for ( let field_key in this.local_value ) {
                    form_data.append( field_key, this.local_value[ field_key ] );
                }
            }

            const self = this;
            this.button.is_processing = true;
            this.button.is_disabled = true;
            this.button.label = this.buttonLabelOnProcessing;

            // Submit the form
            axios.post( ajax_url, form_data )
                .then( response => {
                    console.log( response );

                    let message = ( response.data.data ) ? response.data.data : null;
                    message = ( response.data.message ) ? response.data.message : message;

                    if ( response.data.success && message ) {
                        message = { type: 'success', message: message };
                    } else {
                        let msg = ( message ) ? message : 'Sorry, something went wrong';
                        message = { type: 'error', message: msg };
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