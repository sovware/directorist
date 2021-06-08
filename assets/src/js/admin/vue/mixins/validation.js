
export default {
    props: {
        validation: {
            type: Array,
            required: false,
        },
    },

    computed: {
        validationLog() {
            let validation_log = {
                invalid_value: {
                    has_error: false,
                    error_msg: 'The field has invalid value',
                },
                duplicate_value: {
                    has_error: false,
                    error_msg: 'The field must be unique',
                },
            }
            
            validation_log = this.syncValidationWithProps( validation_log );

            if ( this.hasInvalidValue() ) {
                validation_log[ 'invalid_value' ].has_error = true;
            }
            
            if ( typeof this.syncValidationWithLocalState === 'function' ) {
                validation_log = this.syncValidationWithLocalState( validation_log );
            }

            // console.log( { validation_log } );
            
            return validation_log;
        },

        validationStatus() {
            let the_status = { has_error: false, messages: [] };
            for ( let status_key in this.validationLog ) {
                if ( this.validationLog[ status_key ].has_error ) {
                    the_status.has_error = true

                    the_status.messages.push({
                        type: 'error', 
                        message: this.validationLog[ status_key ].error_msg
                    });
                }
            }

            return the_status;
        },

        validationMessages() {
            if ( ! this.validationStatus.messages || typeof this.validationStatus.messages !== 'object' ) {
                return false;
            }

           if ( ! this.validationStatus.messages.length ) {
                 return false;
            }

            return this.validationStatus.messages[0];
        },

        validationClass() {
            return {
                'cpt-has-error': this.validationStatus.has_error
            }
        },

        formGroupClass() {
            return {
                ...this.validationClass
            }
        },
    },

    methods: {
        syncValidationWithProps( validation_log ) {
            if ( this.validation && typeof this.validation === 'object' ) {
                for ( let validation_item of this.validation ) {
                    if ( typeof validation_item.error_key === 'undefined' ) {
                        continue;
                    }

                    if ( typeof validation_log[ validation_item.error_key ] === 'undefined' ) {
                        validation_log[ validation_item.error_key ] = { error_msg: '' };
                    }

                    validation_log[ validation_item.error_key ].has_error = true;

                    if ( typeof validation_item.has_error !== 'undefined') {
                        validation_log[ validation_item.error_key ].has_error = validation_item.has_error;
                    }

                    if ( typeof validation_item.error_msg !== 'undefined') {
                        validation_log[ validation_item.error_key ].error_msg = validation_item.error_msg;
                    }
                }
            }

            return validation_log;
        },

        hasInvalidValue() {
            let match_found = false;

            if ( this.default_option && typeof this.default_option.value !== 'undefined' && 
                this.local_value === this.default_option.value ) {
                    return false;
            }

            if ( ! this.theOptions || typeof this.theOptions !== 'object' ) {
                return false;
            }

            for ( let option of this.theOptions ) {
                if ( typeof option.options !== 'undefined' ) {
                    for ( let sub_option of option.options ) {
                        if ( sub_option.value === this.local_value ) {
                            match_found = true;
                        }
                    }
                } else {
                    if ( option.value === this.local_value ) {
                        match_found = true;
                    }
                }
            }

            return ! match_found;
        },
    },
}