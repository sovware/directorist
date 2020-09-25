
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
    },
}