import { mapState } from 'vuex';

export default {
    computed: {
        ...mapState({
            fields: 'fields',
        }),

        validationMessages() {
            // console.log( this.validationState );

            if ( ! this.validationState ) { return false; }
            if ( ! this.validationState.log ) { return false; }
            if ( typeof this.validationState.log !== 'object' ) { return false; }
            if ( ! Object.keys( this.validationState.log ).length ) { return false; }

            // console.log( this.validationState.log, Object.keys( this.validationState.log ) );

            let messages = [];
            for ( let log of this.validationState.log ) {
                messages.push( { type: log.type, message: log.message, } );
            }

            console.log( messages );

            return false;

            return messages;
        }
    },

    watch: {
        value() {
            this.validate( { value: this.value, rules: this.rules } );
        }
    },

    methods: {
        validate( args ) {
            if ( ! args.rules ) { return; }

            let validation_log = {};
            let error_count    = 0;

            for ( let rule in args.rules ) {
                switch ( rule ) {
                    case 'required':
                        let status = this.checkRequired( args.value );

                        if ( ! status.valid ) {
                            validation_log[ 'required' ] = status.log;
                            error_count++;
                        }
                        break;
                }
            }

            let validation_status = {
                hasError: ( error_count > 0 ) ? true : false,
                log: validation_log,
            }

            // console.log( { validation_status } );
            
            this.$emit( 'validate', validation_status );
            
            // console.log( this.validationState );
        },

        validateField( field_key ) {
            if ( ! this.fields[ field_key ].rules ) { return; }
            let value = this.fields[ field_key ].value;

            let validation_log = {};
            let error_count    = 0;

            for ( let rule in this.fields[ field_key ].rules ) {
                switch ( rule ) {
                    case 'required':
                        let status = this.checkRequired( value );

                        if ( ! status.valid ) {
                            validation_log[ 'required' ] = status.log;
                            error_count++;
                        }
                        break;
                }
            }

            let validation_status = {
                hasError: ( error_count > 0 ) ? true : false,
                log: validation_log,
            }

            this.$store.commit( 'updateFieldData', {
                field_key: field_key,
                option_key: 'validationFeedback',
                value: validation_status,
            });

            // console.log( validation_status, error_count );
        },

        // checkRequired
        checkRequired( value ) {
            let status = { valid: true };
            let error_msg = { type: 'error', message: 'The field is required' };

            if ( typeof value === 'string' && ! value.length ) {
                status.valid = false;
                status.log = error_msg;

                return status;
            }

            if ( typeof value === 'number' && ! value.toString().length ) {
                status.valid = false;
                status.log = error_msg;

                return status;
            }

            if ( ! value ) {
                status.valid = false;
                status.log = error_msg;
            }

            return status;
        }
    },
}