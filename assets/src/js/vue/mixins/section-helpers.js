import { mapState } from 'vuex';
import validator from './validator';

export default {
    mixins: [ validator ],
    computed: {
        ...mapState({
            fields: 'fields',
            cached_fields: 'cached_fields',
            highlighted_field_key: 'highlighted_field_key',
        }),
    },

    methods: {
        updateFieldValue( field_key, value ) {
            this.$store.commit( 'updateFieldValue', { field_key, value } );
            this.validateField( field_key );
        },

        getFormFieldName( field_type ) {
            return field_type + '-field';
        },

        getHighlightState( field_key ) {
            return this.highlighted_field_key === field_key;
        },
    }
}