import { mapState } from 'vuex';

export default {
    computed: {
        ...mapState({
            fields: 'fields',
            cached_fields: 'cached_fields',
            highlighted_field_key: 'highlighted_field_key',
        }),
    },

    methods: {
        isObject( the_var ) {
            if ( typeof the_var === 'undefined' ) { return false }
            if ( the_var === null ) { return false }
            if ( typeof the_var !== 'object' ) { return false }
            if ( Array.isArray( the_var ) ) { return false }

            return the_var;
        },
    }
}