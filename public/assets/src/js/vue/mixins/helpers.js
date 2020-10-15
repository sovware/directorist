import { mapState } from 'vuex';

export default {
    computed: {
        ...mapState({
            fields: 'fields',
        }),
    },

    methods: {
        mapDataByMap( data, map ) {
            const flatten_data = JSON.parse( JSON.stringify( data ) );
            const flatten_map = JSON.parse( JSON.stringify( map ) );
           
            let mapped_data = flatten_data.map( element => {
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
            let value_is_array = ( value && typeof value === 'object' ) ? true : false;
            let value_is_text  = ( typeof value === 'string' || typeof value === 'number' ) ? true : false;
            let flatten_data   = JSON.parse( JSON.stringify( data ) );

            return flatten_data.filter( item => {
                if ( value_is_text && value ===  item.value ) {
                    // console.log( 'value_is_text', item.value, value );
                    return item;
                }
                
                if ( value_is_array && value.includes( item.value ) ) {
                    // console.log( 'value_is_array', item.value, value );
                    return item;
                }

                if ( ! value_is_text && ! value_is_array ) {
                    // console.log( 'no filter', item.value, value );   
                    return item;
                }

            });
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