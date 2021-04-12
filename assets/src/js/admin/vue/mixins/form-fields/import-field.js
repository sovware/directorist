import props from './input-field-props.js';

import { mapGetters } from 'vuex';
import helpers from './../helpers';

export default {
    name: 'import-field',
    mixins: [ props, helpers ],
    model: {
        prop: 'value',
        event: 'input'
    },
    props: {
        label: {
            type: String,
            required: false,
            default: '',
        },
        validation: {
            type: Array,
            required: false,
        },
    },

    data() {
        return {
            validation_message: null
        }
    },

    methods: {
        ...mapGetters([
            'getFieldsValue'
        ]),

        importJSON( event ) {
            var reader = new FileReader();
            reader.onload = this.onReaderLoad;
            reader.readAsText( event.target.files[0] );
        },
    
        onReaderLoad( event ) {
            var json_data = JSON.parse( event.target.result );

            const self = this;

            if ( ! ( json_data && typeof json_data === 'object' ) ) {
                console.log( 'Invalid JSON' );
                this.validation_message = { type: 'error', message: 'Invalid JSON' };

                setTimeout(() => {
                    self.validation_message = null;
                }, 5000);

                return;
            }
            
            let fields = {};
            for ( let field in json_data ) {
                fields[ field ] = this.maybeJSON( json_data[ field ] );
            }

            // console.log( 'The JSON file has been loaded successfully' );
            // this.validation_message = { type: 'success', message: 'The JSON file has been loaded successfully' };

            // setTimeout(() => {
            //     self.validation_message = null;
            // }, 5000);

            this.$store.commit( 'importFields', fields );
            this.$emit( 'do-action', { action: 'updateData', component: 'root' } );
        },
    }
}