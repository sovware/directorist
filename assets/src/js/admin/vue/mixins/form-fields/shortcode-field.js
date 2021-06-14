import props from './input-field-props.js';
import helpers from './../helpers';

export default {
    mixins: [ props, helpers ],
    model: {
        prop: 'value',
        event: 'update'
    },

    computed: {
        shortcode() {
            let shortcode = this.applyFilters( this.value, this.filters );

            return shortcode;
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
        applyFilters( value, filters ) {
            if ( ! filters ) return value;

            let filterd_value = value;

            for ( let filter of filters ) {
                if ( typeof this[ filter.type ] !== 'function' ) continue;
                filterd_value = this[ filter.type ]( filterd_value, filter );
            }

            return filterd_value;
        },

        replace( value, args ) {
            if ( ! args.find && ! args.find_regex ) return value;
            if ( ! args.replace && ! args.replace_from ) return value;
            
            let replace_text = '';
            let pattern_find = '';

            if ( args.find ) {
                pattern_find = args.find;
            }

            if ( args.find_regex ) {
                pattern_find = new RegExp( args.find_regex, "g" );
            }

            if ( args.replace && typeof args.replace === 'string' ) {
                replace_text = args.replace;
            }

            if ( args.replace_from && typeof args.replace_from === 'string' ) {
                replace_text = this.getTergetFields( { root: this.root, path: args.replace_from } );
            }

            if ( args.look_for ) {
                let pattern_look_for = new RegExp( args.look_for, 'g' );
                let subject = pattern_look_for.exec( value );

                if ( ! subject ) return value;

                if ( Array.isArray( subject ) ) {
                    subject = subject[0];
                }

                subject = subject.replace( pattern_find, replace_text );
                
                value = value.replace( pattern_look_for, subject );
            } else {
                value = value.replace( pattern_find, replace_text );
            };

            return value;
        },

        lowercase( value, args ) {
            if ( ! args.find && ! args.find_regex ) return value;
            
            let pattern_find = '';

            if ( args.find ) {
                pattern_find = args.find;
            }

            if ( args.find_regex ) {
                pattern_find = new RegExp( args.find_regex, "g" );
            }

            let subject = pattern_find.exec( value );

            if ( ! subject ) return value;

            if ( Array.isArray( subject ) ) {
                subject = subject[0];
            }

            subject = subject.toLowerCase();
            value = value.replace( pattern_find, subject );

            return value;
        },

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