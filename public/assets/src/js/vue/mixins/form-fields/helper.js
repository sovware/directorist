import { mapState } from 'vuex';
import helper from './../helpers';

export default {
    mixins: [ helper ],
    computed: {
        ...mapState({
            config: 'config',
            fields: 'fields',
        }),

        canShow() {
            if ( this.showIf ) {
                let show_if_cond = this.checkShowIfCondition({
                    condition: this.showIf
                });
                
                return  show_if_cond.status;
            }

            return true;
        }
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
        },
    },
}