import props from './input-field-props.js';

export default {
    mixins: [ props ],
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

        link() {
            return this.comp.link.url ? lodash.unescape( this.comp.link.url ) : this.comp.link.url;
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
        },

        formGroupClass() {
            var validation_classes = ( this.validationLog.inputErrorClasses ) ? this.validationLog.inputErrorClasses : {};

            return {
                ...validation_classes,
            }
        },
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
            },

            confirmation: {
                show: false,
                onConfirm: null,
            },

            validationLog: {}
        }
    },

    methods: {
        setup() {
            this.loadLinkComponentData();
            this.setupConfirmationModal();
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

        setupConfirmationModal() {
            if ( ! ( this.confirmationModal && typeof this.confirmationModal === 'object') ) { return; }
            if ( ! Object.keys( this.confirmationModal ) ) { return; }

            let marged_data = { ...this.confirmation, ...this.confirmationModal };
            this.confirmation = marged_data;
        },

        toggleValue() {
            const self = this;
            const updateData = function() {
                self.local_value = ! self.local_value;
                self.$emit('update', self.local_value);
                self.handleDataOnChange();
            };

            this.handleDataBeforeChange( updateData );
        },

        handleDataBeforeChange( updateData ) {
            // console.log( 'handleDataBeforeChange', this.confirmBeforeChange );

            // Check Confirmation
            if ( this.confirmBeforeChange ) {
                this.getConfirmation( updateData );
                return;

                // const confirmation_status = this.getConfirmation( updateData );
                // if ( ! confirmation_status ) { return; }
            }

            updateData();
        },

        getConfirmation( callback ) {
            this.confirmation.show = true;
            this.confirmation.onConfirm = callback;

            // let confirmation = confirm( 'Are You Sure?' );
            // if ( confirmation ) { return true; }
            // return false;
        },

        confirmationOnConfirm( callback ) {
            if ( typeof callback !== 'function' ) { return; }

            console.log( 'confirmationOnConfirm' );
            callback();
        },

        confirmationOnCancel() {
            this.confirmation.show = false;
            this.confirmation.onConfirm = null;
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