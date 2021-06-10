import props from './input-field-props.js';
import lowerCase from 'lodash/lowerCase';

export default {
    mixins: [ props ],
    model: {
        prop: 'value',
        event: 'update'
    },

    computed: {
        shortcode() {
            if ( ! this.root ) return '';
            if ( ! this.root.label ) return '';
            if ( ! this.root.label.length ) return '';

            let label = lowerCase( this.root.label ).replace( /\s/g, '-' );

            return '[directorist_single_listings_section section-key="'+ label +'"]';
        },

        formGroupClass() {
            var validation_classes = ( this.validationLog?.inputErrorClasses ) ? this.validationLog.inputErrorClasses : {};

            return {
                ...validation_classes,
                'cptm-mb-0': ( 'hidden' === this.input_type ) ? true : false,
            }
        },

        formControlClass() {
            let class_names = {};

            if ( this.input_style && this.input_style.class_names  ) {
                class_names[ this.input_style.class_names ] = true;
            }
            
            return class_names;
        }
    },

    data() {
        return {
            successMsg: '',
            generateShortcode: false,
        }
    },

    methods: {
        copyToClip() {
            if (document.selection) {
                document.getSelection().removeAllRanges();  
                var range = document.body.createTextRange();
                range.moveToElementText( this.$refs.shortcode );
                range.select().createTextRange();
                document.execCommand("copy");

                this.successMsg = 'Copied to clipboard';
                setTimeout( this.clearSuccessMessage, 2000 );

              } else if (window.getSelection) {
                var range = document.createRange();
                range.selectNode( this.$refs.shortcode );
                window.getSelection().removeAllRanges();  
                window.getSelection().addRange(range);
                document.execCommand("copy");

                this.successMsg = 'Copied to clipboard';
                setTimeout( this.clearSuccessMessage, 2000 );
              }
        },

        clearSuccessMessage() {
            this.successMsg = '';
        },

        generate(){
            this.generateShortcode = true;
        }
    },
}