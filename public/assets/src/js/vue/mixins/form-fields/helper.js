import { mapState } from 'vuex';

export default {
    computed: {
        ...mapState({
            config: 'config'
        })
    },

    methods: {
        getTheTheme( field ) {
            var the_theme = 'default';

            if ( this.config && this.config.fields_theme ) {
                the_theme = this.config.fields_theme;
            }

            if ( this.theme && 'default' !== this.theme ) {
                the_theme =  this.theme;
            }
            
            return field +'-theme-' + the_theme;
        }
    },
}