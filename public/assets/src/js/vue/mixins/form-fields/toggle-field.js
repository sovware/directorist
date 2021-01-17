import validator from './../validation';
import props from './input-field-props.js';

export default {
    mixins: [ props, validator ],
    model: {
        prop: 'value',
        event: 'input'
    },
    
    created() {
        if ( typeof this.value !== 'undefined' ) {
            this.local_value = ( true === this.value || 'true' === this.value || 1 === this.value || '1' === this.value ) ? true : false;
        }

        this.$emit('update', this.local_value);

        this.setup();
    },

    computed: {
        toggleClass() {
            return {
                'active': this.local_value,
            }
        },

        compLinkIsEnable() {
            if ( ! ( this.componets && this.componets.link ) ) {
                return false;
            }
            
            // check if show
            if ( typeof this.componets.link.show !== 'undefined' &&  ! this.componets.link.show ) { return false; }

            // showIfValueIs
            if ( typeof this.componets.link.showIfValueIs === 'undefined' ) { return true; }
            if ( this.local_value != this.componets.link.showIfValueIs ) { return false; }

            return true;
        },

        compLinkClass() {
            let button_type = this.comp.link.type;

            return {
                [ 'cptm-' + button_type ]: true
            }
        }
    },

    data() {
        return {
            local_value: false,

            comp: {
                link: {
                    enable: false,
                    label: 'Link',
                    type: 'success',
                    url: '#',
                    target: '_self',
                }
            }
        }
    },

    methods: {
        setup() {
            this.loadLinkComponentData();
        },

        loadLinkComponentData() {

            if ( ! ( this.componets && this.componets.link) ) { return; }

            if ( this.componets.link.label ) {
                this.comp.link.label = this.componets.link.label;
            }

            if ( this.componets.link.type ) {
                this.comp.link.type = this.componets.link.type;
            }

            if ( this.componets.link.url ) {
                this.comp.link.url = this.componets.link.url;
            }

            if ( this.componets.link.target ) {
                this.comp.link.target = this.componets.link.target;
            }
        },

        toggleValue() {
            this.local_value = ! this.local_value;
            this.$emit('update', this.local_value);

            this.handleDataOnChange();
        },

        handleDataOnChange() {
            let task = this.dataOnChange;
            let cachedData = this.cachedData;

            if ( ! cachedData ) { return; }
            if ( cachedData.value == this.local_value ) { return; }

            if ( ! ( task && typeof task === 'object' ) ) { return; }
            if ( ! task.action ) { return; }
            if ( typeof task.action !== 'string' ) { return; }

            this.$emit( 'do-action', task );
        },
    }
}