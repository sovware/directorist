<template>
<div class="cptm-form-group" :class="formGroupClass">
    <label v-if="( ! hidden && label.length )" :for="name">{{ label }}</label>
    <input class="cptm-form-control" :type=" ( ! hidden ) ? 'text' : 'hidden'" :value="theValue" :placeholder="placeholder" @input="updateValue( $event.target.value )">
    
    <div class="cptm-form-group-feedback" v-if="! hidden && hasError">
        <div class="cptm-form-alert" 
            v-for="( alert, alert_key ) in validationMessages"
            :key="alert_key"
            :class="'cptm-' + alert.type"
        >
            {{ alert.message }}
        </div>
    </div>
</div>
</template>

<script>
const axios = require('axios').default;
import { mapState } from 'vuex';

export default {
    name: 'text-field',
    mixins: [],
    model: {
        prop: 'value',
        event: 'input'
    },
    props: {
        test: {
            default: '',
        },
        fieldId: {
            type: [ String, Number ],
            required: false,
            default: '',
        },
        hidden: {
            type: Boolean,
            required: false,
            default: false,
        },
        label: {
            type: String,
            required: false,
            default: '',
        },
        value: {
            type: [String, Number],
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
        validationFeedback: {
            type: Object,
            required: false,
        },
        validation: {
            type: Array,
            required: false,
        },
    },

    mounted() {
        this.syncValue();
    },

    computed: {
        ...mapState([
            'metaKeys',
            'deprecatedMetaKeys',
        ]),

        theValue() {
            return ( this.value ) ? this.value :  this.local_value;  
        },

        hasError() {
            return this.validationMessages.length;
        },

        validationMessages() {
            let messages = [];

            for ( let log in this.validation_log ) {
                if ( ! this.validation_log[ log ].status ) { continue; }
                messages.push( this.validation_log[ log ].alert );
            }

            return messages;
        },

        formGroupClass() {
            return {
                'cpt-has-error': this.hasError,
                'cptm-mb-0': ( this.hidden ) ? true : false,
            }
        },
    },

    data() {
        return {
            local_value: '',
            validation_log: {
                not_empty: {
                    status: false,
                    alert: {
                        type: 'error', message: 'The key must not be empty'
                    }
                },

                key_exists: {
                    status: false,
                    alert: {
                        type: 'error', message: 'The key already exists'
                    }
                },

                has_invalid_char: {
                    status: false,
                    alert: {
                        type: 'error', message: 'Space is not allowed'
                    }
                },
            }
        }
    },

    methods: {
        syncValue() {
            if ( this.hidden ) {
                this.$store.commit( 'setMetaKey', { key: this.fieldId, value: this.value } );
                this.$emit('update', this.value );
                return;
            }

            if ( this.isValid( { value: this.value, verifyDB: false } ) ) {
                this.$store.commit( 'setMetaKey', { key: this.fieldId, value: this.value } );
                this.$emit('update', this.value );
                return;
            }

            this.$store.commit( 'removeMetaKey', { key: this.fieldId } );
            this.$emit('update', '' );
        },

        updateValue( value ) {
            this.local_value = value;
            
            if ( this.isValid( { value: value }  ) ) {
                this.$store.commit( 'setMetaKey', { key: this.fieldId, value: value } );
                this.$emit('update', value );
                return;
            }

            this.$store.commit( 'removeMetaKey', { key: this.fieldId } );
            this.$emit('update', '' );
        },

        isValid( payload ) {
            let default_args = { value: '' };
            let args = Object.assign( default_args, payload );

            let is_valid = true;
            let error_count = 0;
            let log = {
                not_empty: false,
                key_exists: false,
                has_invalid_char: false,
            };

            // not_empty
            if ( ! args.value ) {
                error_count++;
                log.not_empty = true;
            }

            // hasInTheStore
            if ( this.hasInTheStore( args.value ) ) {
                error_count++;
                log.key_exists = true;
            }

            // hasInvalidChar
            if ( this.hasInvalidChar( args.value ) ) {
                error_count++;
                log.has_invalid_char = true;
            }

            this.validation_log.has_invalid_char.status = ( log.has_invalid_char ) ? true : false;
            this.validation_log.key_exists.status = ( log.key_exists ) ? true : false;
            this.validation_log.not_empty.status = ( log.not_empty ) ? true : false;

            // console.log( this.validation_log, log );

            // Status
            if ( error_count ) {
                is_valid = false;
            }

            return is_valid;
        },

        hasInTheStore( value ) {
            for ( let field in this.metaKeys ) {
                if ( value === this.metaKeys[ field ] ) {
                    return true;
                }
            }   

            return false;
        },

        hasInvalidChar( value ) {
            let invalid_chars = /\s/g;

            if ( typeof value === 'number' ) {
                value = value.toString();
            }

            if ( typeof value !== 'string' ) {
                return false;
            }

            if ( value.match( invalid_chars ) ) {
                return true;
            }

            return false;
        },
    },
}
</script>
