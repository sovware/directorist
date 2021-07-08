import props from './input-field-props.js';
import helpers from './../helpers';

export default {
    mixins: [ props, helpers ],
    computed: {
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
        },

        generateButtonLabel() {
            if ( this.buttonLabel && this.buttonLabel.length ) {
                return this.buttonLabel;
            }
            
            return '<i class="fas fa-magic"></i>';
        },
    },

    data() {
        return {
            shortcodes: [],
            successMsg: '',
            dirty: false,
        }
    },

    methods: {
        generateShortcode() {
            this.shortcodes = [];

            if ( typeof this.value === 'string' ) {
                this.dirty = true;
                this.shortcodes.push( this.value );
                return;
            }

            if ( Array.isArray( this.value ) ) {
                for ( let shortcode_item of this.value ) {
                    if ( typeof shortcode_item === 'string' ) {
                        this.shortcodes.push( shortcode_item );
                        continue;
                    }

                    if ( typeof shortcode_item === 'object' ) {
                        if ( ! shortcode_item.shortcode ) { continue; }
                        let _shortcode = shortcode_item.shortcode;

                        if ( shortcode_item.mapAtts ) {
                            _shortcode = this.applyAttsMapping( shortcode_item );
                        } 

                        if ( typeof _shortcode === 'string' ) {
                            this.shortcodes.push( _shortcode );
                            continue;
                        }

                        if ( Array.isArray( _shortcode ) ) {
                            this.shortcodes = this.shortcodes.concat( _shortcode );
                        }
                    }
                }
            }

            this.dirty = true;
        },

        applyAttsMapping( shortcode_args ) {
            if ( ! shortcode_args.shortcode ) {
                return '';
            }

            if ( ! shortcode_args.mapAtts ) {
                return shortcode_args.shortcode;
            }

            var mapped_shortcode = shortcode_args.shortcode;

            for ( let map of shortcode_args.mapAtts  ) {
                if ( map.map ) {
                    mapped_shortcode = this.applyMap( map, mapped_shortcode );
                    continue
                }

                if ( map.mapAll ) {
                    mapped_shortcode = this.applyMapAll( map, mapped_shortcode );
                }
            }

            return mapped_shortcode;
        },

        applyMap( args, value ) {
            var shortcode = value;
            const source = this.getTergetFields( { root: this.root, path: args.map } );

            if ( ! source ) { return value; }

            if ( args.where && ! Array.isArray( args.where ) ) {
                let _shortcode = shortcode;
                let key = source[ args.where.key ];

                if ( typeof key !== 'string' ) {
                    return shortcode;
                }

                if ( args.where.applyFilter ) {
                    key = this.applyFilters( key, args.where.applyFilter );
                }

                if ( args.where.mapTo ) {
                    _shortcode = _shortcode.replace( args.where.mapTo, key );
                }

                shortcode = _shortcode;

                return shortcode;
            }

            if ( args.where && Array.isArray( args.where ) ) {
                var _shortcode = shortcode;

                for ( let cond of args.where ) {
                    let key = source[ cond.key ];

                    if ( typeof key !== 'string' ) { continue; }

                    if ( cond.applyFilter ) {
                        key = this.applyFilters( key, cond.applyFilter );
                    }

                    if ( cond.mapTo ) {
                        _shortcode = _shortcode.replace( cond.mapTo, key );
                    }
                }

                shortcode = _shortcode;
                return shortcode;
            }
        },

        applyMapAll( args, value ) {
            let shortcodes = [];
            const source = this.getTergetFields( { root: this.root, path: args.mapAll } );

            if ( ! source ) { return value; }
            if ( Array.isArray( ! source ) ) { return value; }

            for ( let group of source ) {
                if ( args.where &&  ! Array.isArray( args.where ) ) {
                    let _shortcode = value;
                    let key = group[ args.where.key ];

                    if ( args.where.applyFilter ) {
                        key = this.applyFilters( key, args.where.applyFilter );
                    }

                    if ( args.where.mapTo ) {
                        _shortcode = _shortcode.replace( args.where.mapTo, key );
                    }

                    shortcodes.push( _shortcode );
                    continue;
                }

                if ( args.where && Array.isArray( args.where ) ) {
                    
                    var _shortcode = value;

                    for ( let cond of args.where ) {
                        let key = group[ cond.key ];

                        if ( cond.applyFilter ) {
                            key = this.applyFilters( key, cond.applyFilter );
                        }

                        if ( cond.mapTo ) {
                            _shortcode = _shortcode.replace( cond.mapTo, key );
                        }
                    }

                    shortcodes.push( _shortcode );
                    continue;
                }
                
            }
            
            return shortcodes;
        },

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
            if ( ! args.find && ! args.find_regex ) {
                return value.toLowerCase();
            }
            
            let pattern_find = '';

            if ( args.find ) {
                pattern_find = args.find;
            }

            if ( args.find_regex ) {
                pattern_find = new RegExp( args.find_regex, "g" );
            }

            if ( ! pattern_find ) {
                return value.toLowerCase();
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

        copyToClip( ref, index ) {
            let ref_elm = ( ref ) ? this.$refs[ref] : null;
            ref_elm = ( typeof index === 'number' ) ?  this.$refs[ref][index]: ref_elm;

            if ( ! ref_elm ) {
                return;
            }

            if (document.selection) {
                document.getSelection().removeAllRanges();  
                var range = document.body.createTextRange();
                range.moveToElementText( ref_elm );
                range.select().createTextRange();
                document.execCommand("copy");

                this.successMsg = 'Copied';
                setTimeout( this.clearSuccessMessage, 2000 );

              } else if (window.getSelection) {
                var range = document.createRange();
                range.selectNode( ref_elm );
                window.getSelection().removeAllRanges();  
                window.getSelection().addRange(range);
                document.execCommand("copy");

                this.successMsg = 'Copied';
                setTimeout( this.clearSuccessMessage, 2000 );
              }
        },

        clearSuccessMessage() {
            this.successMsg = '';
        },

        generate(){
            this.hasShortcode = true;
        }
    },
}