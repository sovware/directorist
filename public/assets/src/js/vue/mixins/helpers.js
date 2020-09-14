export default {
    methods: {
        updateFieldValue( field_key, value ) {
            this.$store.commit( 'updateFieldValue', { field_key, value } );
        },
        
        getActiveClass( item_index, active_index ) {
            return ( item_index === active_index ) ? 'active' : '';
        },

        // objectToSectionArray
        objectToSectionArray( sections_obj ) {
            if ( ! sections_obj ) { return null; }
            
            const helpers = {
                isset: ( item ) => ( item && item.length ) ? item : '',
            };
            

            let sections_array = [];
            for ( let section in sections_obj ) {
                let section_item = {
                    title: helpers.isset( sections_obj[ section ].title ),
                    description: helpers.isset( sections_obj[ section ].description ),
                };

                let fields = [];

                if ( sections_obj[ section ].fields ) {
                    for ( let field in sections_obj[ section ].fields ) {
                        sections_obj[ section ].fields[ field ].name = field;
                        fields.push( sections_obj[ section ].fields[ field ] );
                    }
                }

                section_item.fields = fields;

                sections_array.push( section_item );
            }

            return sections_array;
        }
    },
}