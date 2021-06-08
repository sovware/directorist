import props from './input-field-props.js';
import { mapGetters } from 'vuex';
import helpers from '../../mixins/helpers';

export default {
    name: 'export-field',
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

        exportJSON() {
            // console.log( this.getFieldsValue() );
            let dataStr = JSON.stringify( this.getFieldsValue() );
            let dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);

            let exportFileDefaultName = this.exportFileName + '.json';

            let linkElement = document.createElement('a');
            linkElement.setAttribute('href', dataUri);
            linkElement.setAttribute('download', exportFileDefaultName);
            linkElement.click();
        },
    }
}