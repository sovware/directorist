import { mapState } from 'vuex';

export default {
    mounted() {
        this.validate();
    },

    computed: {
        ...mapState({
            fields: 'fields',
        }),

        validationMessages() {

            if ( ! this.validationState ) { return false; }
            if ( ! this.validationState.log ) { return false; }
            if ( typeof this.validationState.log !== 'object' ) { return false; }
            if ( ! Object.keys( this.validationState.log ).length ) { return false; }
            
            let messages = [];
            for ( let log_key in this.validationState.log ) {
                let status_log = this.validationState.log[ log_key ];
                messages.push( { type: status_log.type, message: status_log.message, } );
            }

            if ( ! messages.length ) { return false; }

            return messages[0];
        },

        validationClass() {
            return {
                'cpt-has-error': this.validationMessages.length
            }
        },

        formGroupClass() {
            return {
                ...this.validationClass
            }
        },
    },

    watch: {
        value() {
            this.validate();
        }
    },

    methods: {
        validate() {
            if ( ! this.rules ) { return; }

            let validation_log = {};
            let error_count    = 0;

            for ( let rule in this.rules ) {
                switch ( rule ) {
                    case 'required':
                        var status = this.checkRequired( this.value, this.rules[rule] );
                        if ( ! status.valid ) {
                            validation_log[ 'required' ] = status.log;
                            error_count++;
                        }
                        break;

                    case 'min':
                        var status = this.checkMin( this.value, this.rules[rule] );
                        if ( ! status.valid ) {
                            validation_log[ 'min' ] = status.log;
                            error_count++;
                        }
                        break;

                    case 'max':
                        var status = this.checkMax( this.value, this.rules[rule] );
                        if ( ! status.valid ) {
                            validation_log[ 'max' ] = status.log;
                            error_count++;
                        }
                        break;
                }
            }

            let validation_status = {
                hasError: ( error_count > 0 ) ? true : false,
                log: validation_log,
            }

            this.$emit( 'validate', validation_status );
        },

        // checkRequired
        checkRequired( value, arg ) {
            let status = { valid: true };

            if ( ! arg ) { return status; }

            if ( this.isEmpty( value ) ) {
                status.valid = false;
                status.log = { type: 'error', message: 'The field is required' };

                return status;
            }

            return status;
        },

        checkMin( value, arg ) {
            let status = { valid: true };

            // If the value is empty
            if ( this.isEmpty( value ) ) { return status; }

            let value_in_number = Number( value );

            // If the value is not number
            if ( Number.isNaN( value_in_number ) ) {
                status.valid = false;
                status.log = { type: 'error', message: 'The field must be number'};

                return status;
            }

            // Check the length
            if ( value_in_number < arg ) {
                status.valid = false;
                status.log = { type: 'error', message: 'The field must be minimum of ' + arg };

                return status;
            }

            return status;
        },

        checkMax( value, arg ) {
            let status = { valid: true };

            // If the value is empty
            if ( this.isEmpty( value ) ) { return status; }

            let value_in_number = Number( value );

            // If the value is not number
            if ( Number.isNaN( value_in_number ) ) {
                status.valid = false;
                status.log = { type: 'error', message: 'The field must be number'};

                return status;
            }

            // Check the length
            if ( value_in_number > arg ) {
                status.valid = false;
                status.log = { type: 'error', message: 'The field must be maximum of ' + arg };

                return status;
            }

            return status;
        },

        isEmpty( value ) {
            if ( typeof value === 'string' && ! value.length ) {
                return true;
            }

            if ( typeof value === 'number' && ! value.toString().length ) {
                return true;
            }

            if ( ! value ) {
                return true;
            }

            return false;
        }
    },
}