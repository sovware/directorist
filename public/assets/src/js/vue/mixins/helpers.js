
export default {
    methods: {
        getFormFieldName( field_type ) {
            return field_type + '-field';
        },

        updateFieldValue( field_key, value ) {
            this.$store.commit( 'updateFieldValue', { field_key, value } );
        },
        
        getActiveClass( item_index, active_index ) {
            return ( item_index === active_index ) ? 'active' : '';
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
}