<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <label v-if="label.length">{{label}}</label>
        <select @change="update_value( $event.target.value )" :value="local_value" class="cptm-form-control">
            <option v-if="default_option" :value="default_option.value">{{ default_option.label }}</option>
            
            <template v-for="( option, option_key ) in the_options">
                <template v-if="option.group && option.the_options">
                    <optgroup :label="option.group" :key="option_key">
                        <option v-for="( sub_option, sub_option_key ) in option.the_options" :key="sub_option_key" :value="sub_option.value">
                            {{ sub_option.label }}
                        </option>
                    </optgroup>
                </template>

                <template v-else>
                    <option :key="option_key" :value="option.value">{{ option.label }}</option>
                </template>
            </template>
        </select>
        
        <div class="cptm-form-group-feedback" v-if="validationMessages">
            <div class="cptm-form-alert" :class="'cptm-' + validationMessages.type">
                {{ validationMessages.message }}
            </div>
        </div>
    </div>
</template>

<script>

import { mapState } from 'vuex';
import helpers from './../../mixins/helpers';
import validation from './../../mixins/validation';

export default {
    name: 'select-field',
    mixins: [ helpers, validation ],
    model: {
        prop: 'value',
        event: 'input'
    },
    props: {
        type: {
            type: String,
            required: false,
            default: 'text',
        },
        label: {
            type: String,
            required: false,
            default: '',
        },
        value: {
            type: [ String, Number ],
            required: false,
            default: '',
        },
        options: {
            type: Array,
            required: false,
        },
        optionsSource: {
            type: Object,
            required: false,
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
        validation: {
            type: Array,
            required: false,
        },
    },

    mounted() {
        this.setup();
    },

    computed: {
        ...mapState({
            fields: 'fields',
        }),

        the_options() {
            if ( this.hasOptionsSource ) {
                return this.hasOptionsSource;
            }

            if ( ! this.options || typeof this.options !== 'object' ) {
                return [ { value: '', label: 'Select...' } ];
            }

            return this.options;
        },

        hasOptionsSource() {
            if ( ! this.optionsSource || typeof this.optionsSource !== 'object' ) {
                return false;
            }

            if ( typeof this.optionsSource.where !== 'string' ) {
                return false;
            }

            let terget_field = null;

            let terget_fields = this.optionsSource.where.split('.');
            let terget_missmatched = false;

            if ( terget_fields && typeof terget_fields === 'object'  ) {
                terget_field = this.fields;

                for ( let key of terget_fields ) {
                    
                    if ( typeof terget_field[ key ] === 'undefined' ) {
                        terget_missmatched = true;
                        break;
                    }

                    terget_field = ( terget_field !== null ) ? terget_field[ key ] : this.fields[ key ];
                }
            }

            if ( terget_missmatched ) { return false; }

            if ( typeof terget_field !== 'object' ) {
                return false;
            }

            if ( typeof this.optionsSource.value_from !== 'string' 
                && typeof this.optionsSource.label_from !== 'string'
            ) {
                return terget_field;
            }

            let options = [];

            if ( typeof this.optionsSource.default_option !== 'undefined' ) {
                this.default_option = this.optionsSource.default_option;
            }

            let value_from = this.optionsSource.value_from;
            let label_from = this.optionsSource.label_from;

            for ( let item of terget_field ) {
                if ( typeof item[ value_from ] !== 'undefined' && typeof item[ label_from ] !== 'undefined' ) {
                    options.push({ 
                        value: item[ value_from ],
                        label: item[ label_from ],
                    });
                }
            }

            if ( ! options || typeof options !== 'object' ) {
                return false;
            }

            // console.log( { terget_field, terget_fields, options } );
            return options;
        },

        hasInvalidValue() {
            let match_found = false;

            if ( this.default_option &&  typeof this.default_option.value !== 'undefined' && 
                this.local_value === this.default_option.value ) {
                    return false;
            }

            for ( let option of this.the_options ) {
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

    data() {
        return {
            local_value: '',
            default_option: { value: '', label: 'Select...' },
        }
    },

    methods: {
        setup() {
            this.local_value = this.value;
            this.$emit( 'update', this.local_value );
        },

        update_value( value ) {
            this.local_value = value;
            this.$emit( 'update', this.local_value );
        },

        syncValidationWithLocalState( validation_log ) {
            if ( this.hasInvalidValue ) {
                validation_log[ 'invalid_value' ].has_error = true;
            }

            return validation_log;
        }   
    },
}
</script>