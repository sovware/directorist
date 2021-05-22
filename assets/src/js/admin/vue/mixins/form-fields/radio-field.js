import { mapState } from 'vuex';
import helpers from './../helpers';
import props from './input-field-props.js';

export default {
    mixins: [ props, helpers ],
    model: {
        prop: 'value',
        event: 'input'
    },

    created() {
        if ( typeof this.value === 'string' || typeof this.value === 'number' ) {
            this.local_value = this.value;
        }

        this.$emit( 'update', this.local_value );
    },

    watch: {
        local_value() {
            this.$emit( 'update', this.local_value );
        },

        hasOptionsSource() {
            let has_deprecated_value = this.hasDeprecatedValue( this.local_value );

            if ( has_deprecated_value ) {
                this.local_value = this.removeDeprecatedValue( this.local_value, has_deprecated_value );
            }
        }
    },

    computed: {
        ...mapState({
            fields: 'fields',
        }),

        theOptions() {
            if ( this.hasOptionsSource ) {
                return this.hasOptionsSource;
            }

            if ( ! this.options || typeof this.options !== 'object' ) {
                return ( this.defaultOption ) ? [ this.defaultOption ] : [];
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

            let terget_fields = this.getTergetFields( this.optionsSource.where );
            const id_prefix = ( typeof this.optionsSource.id_prefix === 'string' ) ? this.optionsSource.id_prefix + '-' : this.name + '-';
            
            if ( ! terget_fields || typeof terget_fields !== 'object' ) {
                return false;
            }

            let filter_by = null;
            if ( typeof this.optionsSource.filter_by === 'string' && this.optionsSource.filter_by.length ) {
                filter_by = this.optionsSource.filter_by;
            }

            if ( filter_by ) {
                filter_by = this.getTergetFields( this.optionsSource.filter_by );
            }

            let has_sourcemap = false;

            if ( this.optionsSource.source_map && typeof this.optionsSource.source_map === 'object'  ) {
                has_sourcemap = true;
            }

            if ( ! has_sourcemap && ! filter_by ) {
                return terget_fields;
            }

            if ( has_sourcemap ) {
                terget_fields = this.mapDataByMap( terget_fields, this.optionsSource.source_map );
            }

            if ( filter_by ) {
                terget_fields = this.filterDataByValue( terget_fields, filter_by );
            }

            if ( ! terget_fields && typeof terget_fields !== 'object' ) {
                return false;
            }
            
            let i = 0;
            for ( let option of terget_fields ) {
                let id = ( typeof option.id !== 'undefined' ) ? option.id : '';
                
                terget_fields[ i ].id = id_prefix + id;
                i++;
            }
            
            return terget_fields;
        },

        formGroupClass() {
            var validation_classes = ( this.validationLog.inputErrorClasses ) ? this.validationLog.inputErrorClasses : {};

            return {
                ...validation_classes,
            }
        },
    },

    data() {
        return {
            local_value: '',
            validationLog: {}
        }
    },

    methods: {
        getCheckedStatus( option ) {
            // console.log( { name: this.name, local_value: this.local_value, value: this.getValue( option ) } );
            return this.local_value.includes( this.getValue( option ) );
        },

        getValue( option ) {
            return ( typeof option.value !== 'undefined' ) ? option.value : '';
        },

        getTheOptions() {
            return JSON.parse( JSON.stringify( this.theOptions ) );
        },

        filtereValue( value ) {
            if ( ! value && typeof value !== 'object' ) {
                return [];
            }

            console.log( value );

            return [];

            let options_values = this.theOptions.map( option => {
                if ( typeof option.value !== 'undefined' ) { return option.value; }
            });
            return value.filter( value_elm => {
                return options_values.includes( value_elm );
            });
        },

        hasDeprecatedValue( values ) {
            if ( ! values && typeof values !== 'object' ) {
                return [];
            }

            let flatten_values = JSON.parse( JSON.stringify( values ) );
            let options_values = this.theOptions.map( option => {
                if ( typeof option.value !== 'undefined' ) { return option.value; }
            });

            let deprecated_value = flatten_values.filter( value_elm => {
                return ! options_values.includes( value_elm );
            });

            if ( ! deprecated_value && typeof deprecated_value !== 'object' ) {
                return false;
            }

            if ( ! deprecated_value.length ) {
                return false;
            }

            return deprecated_value;
        },

        removeDeprecatedValue( _original_value, _deprecated_value ) {
            let original_value = JSON.parse( JSON.stringify( _original_value ) );

            return original_value.filter( value_elm => {
                return ! _deprecated_value.includes( value_elm );
            });
        }

    },
}