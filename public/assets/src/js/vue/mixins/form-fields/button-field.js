import validator from '../validation';
import props from './input-field-props.js';

export default {
    mixins: [ props, validator ],
   
    data() {
        return {
            local_value: false
        }
    },

    methods: {
        
    }
}