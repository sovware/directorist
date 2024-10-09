import props from './input-field-props.js';

export default {
    name: 'button-example-field',
    mixins: [ props ],
    
    created() {
        console.log( 'Button Example Field', props );
        
    },

    data() {
        return {
                        
        }
    },

    methods: {
        
    }
}