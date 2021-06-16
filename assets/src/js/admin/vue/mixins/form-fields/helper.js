import { mapState } from 'vuex';
import helper from './../helpers';

export default {
    mixins: [ helper ],

    computed: {
        ...mapState({
            config: 'config',
        }),

        canShow() {
            let is_visible = true;

            if ( this.showIf || this.show_if ) {
                let show_if_condition = ( this.showIf ) ? this.showIf : this.show_if;
                let show_if_cond = this.checkShowIfCondition({
                    condition: show_if_condition,
                    root: this.root,
                });
                
                is_visible = show_if_cond.status;
            }
            

            this.$emit( 'is-visible', is_visible );
            return is_visible;
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