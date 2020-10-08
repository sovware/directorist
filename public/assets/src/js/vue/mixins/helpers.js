import { mapState } from 'vuex';

export default {
    mounted() {
        if ( this.defaultOption || typeof this.defaultOption === 'object' ) {
            this.default_option = this.defaultOption;
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

            if ( ! terget_fields || typeof terget_fields !== 'object' ) {
                return false;
            }

            return terget_fields;
        },
    },


    methods: {
        mapDataByMap( data, map ) {
            let flatten_data = JSON.parse( JSON.stringify( data ) );
           
            let mapped_data = flatten_data.map( element => {
                let flatten_map = JSON.parse( JSON.stringify( map ) );
                let item = {};

                for ( let key in flatten_map) {
                    if ( typeof element[ key ] !== 'undefined' ) {
                        item[ key ] = element[ flatten_map[ key ] ];
                    }
                }

                return item;
            });

            return mapped_data;
        },

        filterDataByValue( data, value ) {
            let value_is_array = ( value && typeof value === 'object' && value.length ) ? true : false;
            let value_is_text  = ( typeof value === 'string' || typeof value === 'number' ) ? true : false;
            let flatten_data   = JSON.parse( JSON.stringify( data ) );

            let filtered_data = flatten_data.filter( item => {
                if ( value_is_text && value ===  item.value ) {
                    // console.log( 'value_is_text', item.value, value );
                    return item;
                }
                
                if ( value_is_array && value.indexOf( item.value ) != -1 ) {
                    // console.log( 'value_is_array', item.value, value );
                    return item;
                }

                if ( ! value_is_text && ! value_is_array ) {
                    // console.log( 'no filter', item.value, value );
                    return item;
                }

            });

            return filtered_data;
        },

        getFormFieldName( field_type ) {
            return field_type + '-field';
        },

        updateFieldValue( field_key, value ) {
            this.$store.commit( 'updateFieldValue', { field_key, value } );
        },
        
        getActiveClass( item_index, active_index ) {
            return ( item_index === active_index ) ? 'active' : '';
        },

        getTergetFields( fields ) {
            
            if ( typeof fields !== 'string' ) { return null; }
            let terget_field = null;

            let terget_fields = fields.split('.');
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

            return JSON.parse( JSON.stringify( terget_field ) );
        },

        getSanitizedProps( props ) {

            if ( props && typeof props === 'object' ) {
                let _props = JSON.parse( JSON.stringify( props ) );
                delete _props.value;

                return _props;
            }

            return props;
        }
    },

    data() {
        return {
            default_option: { value: '', label: 'Select...' },
        }
    },
}