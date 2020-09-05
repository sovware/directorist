export default {
    methods: {
        getActiveClass( item_index, active_index ) {
            return ( item_index === active_index ) ? 'active' : '';
        }
    },
}