<template>
    <div class="cptm-form-group-feedback" v-if="validationMessages">
        <div class="cptm-form-alert" 
            v-for="( alert, alert_key ) in Object.values( validationMessages )"
            :key="alert_key"
            :class="'cptm-' + ( alert.type ? alert.type : '' )"
            v-html="( alert.message ) ? alert.message : ''">
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'form-field-validatior',
    model: {
        prop: 'validationState',
        event: 'validate'
    },
    props: {
        sectionId: {
            default: '',
        },
        fieldId: {
            default: '',
        },
        root: {
            required: false
        },
        value: {
            required: false
        },
        rules: {
            required: false
        },
        validationState: {
            required: false
        },
    },

    created() {
        this.validate();
    },

    watch: {
        value() {
            this.validate();
        }
    },

    computed: {
        ...mapState([ 'fields' ]),
        validationMessages() {
            if ( ! Object.keys( this.validation_state.log ).length ) { return null; }

            let maxAertRange = 1;
            let alerts = {};

            let counter = 0;
            for ( const alert_key in this.validation_state.log ) {
                if ( counter >= maxAertRange ) { console.log( '@', { counter, maxAertRange } ); break; }
                alerts[ alert_key ] = this.validation_state.log[ alert_key ];

                counter++;   
            }

            return alerts;
        }
    },

    data() {
        return {
            validation_state: {
                hasError: false,
                inputErrorClasses: {
                    ['cpt-has-error']: false
                },
                log: {}
            }
        }
    },

    methods: {
        notifyValidationState() {
            this.$emit( 'validate', this.validation_state );
        },

        validate() {
            if ( ! this.rules ) { 
                this.notifyValidationState();
                return;
            }

            let validation_log = {};
            let error_count    = 0;

            for ( let rule in this.rules ) {
                switch ( rule ) {
                    case 'required': {
                        const status = this.checkRequired( this.value, this.rules[rule] );
                        if ( ! status.valid ) {
                            validation_log[ 'required' ] = status.log;
                            error_count++;
                        }
                        break;
                    }

                    case 'min':{
                        const status = this.checkMin( this.value, this.rules[rule] );
                        if ( ! status.valid ) {
                            validation_log[ 'min' ] = status.log;
                            error_count++;
                        }
                        break;
                    }

                    case 'max':{
                        const status = this.checkMax( this.value, this.rules[rule] );
                        if ( ! status.valid ) {
                            validation_log[ 'max' ] = status.log;
                            error_count++;
                        }
                        break;
                    }

                    case 'minLength':{
                        const status = this.checkMinLength( this.value, this.rules[rule] );
                        if ( ! status.valid ) {
                            validation_log[ 'min' ] = status.log;
                            error_count++;
                        }
                        break;
                    }

                    case 'maxLength':{
                        const status = this.checkMaxLength( this.value, this.rules[rule] );
                        if ( ! status.valid ) {
                            validation_log[ 'max' ] = status.log;
                            error_count++;
                        }
                        break;
                    }

                    case 'unique':{
                        const status = this.checkUnique( this.value, this.rules[rule] );
                        if ( ! status.valid ) {
                            validation_log[ 'max' ] = status.log;
                            error_count++;
                        }
                        break;
                    }
                }
            }

            let validation_status = {
                hasError: ( error_count > 0 ) ? true : false,
                log: validation_log,
            };

            if ( validation_status.hasError ) {
                validation_status.inputErrorClasses = { ['cpt-has-error']: true };
            }

            this.validation_state = validation_status;

            this.notifyValidationState();
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

        checkMinLength( value, arg ) {
            let status = { valid: true };

            // If the value is empty
            if ( this.isEmpty( value ) ) { return status; }

            // If the value is not number
            if ( Number.isNaN( value.length ) ) {
                return status;
            }
            
            // Check the length
            if ( value.length < arg ) {
                status.valid = false;
                status.log = { type: 'error', message: 'The field must be minimum of ' + arg };

                return status;
            }

            return status;
        },

        checkMaxLength( value, arg ) {
            let status = { valid: true };

            // If the value is empty
            if ( this.isEmpty( value ) ) { return status; }

            // If the value is not number
            if ( Number.isNaN( value.length ) ) {
                return status;
            }

            // Check the length
            if ( value.length > arg ) {
                status.valid = false;
                status.log = { type: 'error', message: 'The field must be maximum of ' + arg };

                return status;
            }

            return status;
        },
        
        checkUnique( value, arg ) {
            let status = { valid: true };

            if ( ! arg ) { return status; }
            if ( ! this.fieldId ) { return status; }

            // If the value is empty
            if ( this.isEmpty( value ) ) { return status; }
            
            let base = this.fields;
            if ( this.root && typeof this.root === 'object' ) {
                base = this.root;
            }

            for ( const field_key in base ) {
                let has_section_id = ( this.sectionId.length ) ? true : false;
                let has_field_id = ( this.fieldId.length ) ? true : false;

                if ( has_section_id && this.sectionId  === field_key ) { 
                    continue;
                } else if ( ! has_section_id && ( has_field_id && this.fieldId === field_key) ) {
                    continue;
                }
                
                if ( has_section_id && typeof base[ field_key ] === 'object' ) {

                    if ( base[ field_key ][ this.fieldId ] == value ) {
                        status.valid = false;
                        status.log = { type: 'error', message: 'The field must be unique' };

                        return status;
                    }

                    continue;
                }


                if ( typeof base[ field_key ] === 'object' ) {
                    if ( typeof base[ field_key ] === 'string' && value == base[ field_key ] ) {
                        status.valid = false;
                        status.log = { type: 'error', message: 'The field must be unique' };

                        return status;
                    }
                    
                    if ( typeof base[ field_key ].value != 'undefined' && value == base[ field_key ].value ) {
                        status.valid = false;
                        status.log = { type: 'error', message: 'The field must be unique' };

                        return status;
                    }
                }

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
        },
    }
}
</script>
