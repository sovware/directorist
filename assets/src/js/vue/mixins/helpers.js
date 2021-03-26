import { mapState } from 'vuex';

export default {
    computed: {
        ...mapState({
            fields: 'fields',
            cached_fields: 'cached_fields',
            highlighted_field_key: 'highlighted_field_key',
        }),
    },

    methods: {
        doAction( payload, component_key ) {
            if ( ! payload.action ) { return; }
            if ( this[ payload.component ] !== component_key ) { 
                this.$emit( 'do-action', payload );
                return;
            }

            if ( typeof this[ payload.action ] !== "function" ) { return; }

            this[ payload.action ]( payload.args );
        },

        maybeJSON( data ) {
            try {
                JSON.parse( data );
            } catch (e) {
                return data;
            }
        
            return JSON.parse( data );
        },
        
        isObject( the_var ) {
            if ( typeof the_var === 'undefined' ) { return false }
            if ( the_var === null ) { return false }
            if ( typeof the_var !== 'object' ) { return false }
            if ( Array.isArray( the_var ) ) { return false }

            return the_var;
        },

        getHighlightState( field_key ) {
            return this.highlighted_field_key === field_key;
        },

        getOptionID( option, field_index, section_index ) {
            let option_id = '';

            if ( section_index ) {
                option_id = section_index;
            }

            if ( this.fieldId ) {
                option_id = option_id + '_' + this.fieldId;
            }

            if ( typeof option.id !== 'undefined' ) {
                option_id = option_id + '_' + option.id;
            }

            if ( typeof field_index !== 'undefined' ) {
                option_id = option_id + '_' + field_index;
            }

            return option_id;
        },

        mapDataByMap( data, map ) {
            const flatten_data = JSON.parse( JSON.stringify( data ) );
            const flatten_map = JSON.parse( JSON.stringify( map ) );
           
            let mapped_data = flatten_data.map( element => {
                let item = {};

                for ( let key in flatten_map) {
                    if ( typeof element[ key ] !== 'undefined' ) {
                        item[ key ] = element[ flatten_map[ key ] ];
                    }
                }

                return item;
            });

            return mapped_data;
        },

        filterDataByValue( data, value ) {
            let value_is_array = ( value && typeof value === 'object' ) ? true : false;
            let value_is_text  = ( typeof value === 'string' || typeof value === 'number' ) ? true : false;
            let flatten_data   = JSON.parse( JSON.stringify( data ) );

            return flatten_data.filter( item => {
                if ( value_is_text && value ===  item.value ) {
                    // console.log( 'value_is_text', item.value, value );
                    return item;
                }
                
                if ( value_is_array && value.includes( item.value ) ) {
                    // console.log( 'value_is_array', item.value, value );
                    return item;
                }

                if ( ! value_is_text && ! value_is_array ) {
                    // console.log( 'no filter', item.value, value );   
                    return item;
                }

            });
        },

        checkShowIfCondition( payload ) {
            let args = { condition: null };
            Object.assign( args, payload );
            

            let condition = args.condition;

            let root = this.fields;
            if ( this.isObject( args.root ) ) {
                root = args.root;
            }
            
            let failed_cond_count   = 0;
            let success_cond_count  = 0;
            let accepted_comparison = [ 'and', 'or', ];
            let compare             = 'and';
            let matched_data        = [];

            let state = {
                status: false,
                failed_conditions: failed_cond_count,
                successed_conditions: success_cond_count,
                matched_data: matched_data,
            };
            
            let target_field = this.getTergetFields( { root: root, path: condition.where } );

            
            if ( ! ( condition.conditions && Array.isArray( condition.conditions ) && condition.conditions.length ) ) { return state; }
            if ( ! this.isObject( target_field ) ) { return state; }

        
            if ( typeof condition.compare === 'string' && accepted_comparison.indexOf( condition.compare ) ) {
                compare = condition.compare;
            }

            for ( let sub_condition of condition.conditions ) {
        
                if ( typeof sub_condition.key !== 'string' ) {
                    continue;
                }
        
                let sub_condition_field_path = sub_condition.key.split('.');
                let sub_condition_field = null;
                let sub_condition_error = 0;
                let sub_compare = ( typeof sub_condition.compare === 'string' ) ? sub_condition.compare : '=';
                
                if ( ! sub_condition_field_path.length ) {
                    continue;
                }

                // ---
                if ( sub_condition_field_path[0] !== '_any' ) {
                    sub_condition_field = target_field[ sub_condition_field_path[0] ];
                    let is_hidden = ( typeof target_field.hidden !== 'undefined' ) ? target_field.hidden : false;

                    if ( sub_condition_field_path.length > 1 && ! this.isObject( sub_condition_field ) ) {
                        sub_condition_error++;
                    }

                    if ( sub_condition_field_path.length > 1 && ! sub_condition_error ) {
                        sub_condition_field = target_field[ sub_condition_field_path[0] ][ sub_condition_field_path[1] ];
                        is_hidden = ( typeof target_field[ sub_condition_field_path[0] ].hidden !== 'undefined' ) ? target_field[ sub_condition_field_path[0] ].hidden : false ;
                    }
            
                    if ( is_hidden ) {
                        sub_condition_error++;
                    }

                    if ( typeof sub_condition_field === 'undefined' ) {
                        sub_condition_error++;
                    }

                    if ( sub_condition_error ) {
                        failed_cond_count++;
                        continue;
                    }

                    if ( ! this.checkComparison( { data_a: sub_condition_field, data_b: sub_condition.value, compare: sub_compare } ) ) {
                        failed_cond_count++;
                        continue;
                    }

                    if ( ! this.checkComparison( { data_a: sub_condition_field, data_b: sub_condition.value, compare: sub_compare } ) ) {
                        failed_cond_count++;
                        continue;
                    }
            
                    matched_data.push( target_field[ sub_condition_field_path[0] ] );
                    success_cond_count++;
                    continue;
                }
        
                // Check if has _any condition
                if ( sub_condition_field_path[0] === '_any' ) {
                let failed_any_cond_count = 0;
                let success_any_cond_count = 0;
        
                for ( let field in target_field ) {
                    let any_cond_error = 0;
                    
                    sub_condition_field = target_field[ field ];
        
                    if ( sub_condition_field_path.length > 1 && ! this.isObject( sub_condition_field ) ) {
                        any_cond_error++;
                    }
        
                    if ( sub_condition_field_path.length > 1 && ! any_cond_error ) {
                        sub_condition_field = sub_condition_field[ sub_condition_field_path[1] ];
                    } 
        
                    if ( typeof sub_condition_field === 'undefined' ) {
                        any_cond_error++;
                    }
        
                    if ( any_cond_error ) {
                        failed_any_cond_count++;
                        continue;
                    }
    
                    if ( ! this.checkComparison( { data_a: sub_condition_field, data_b: sub_condition.value, compare: sub_compare } ) ) {
                        failed_any_cond_count++;
                        continue;
                    }
                    
                    matched_data.push( target_field[ field ] );
                    success_any_cond_count++;
                }
        
                if ( ! success_any_cond_count ) { failed_cond_count++; } 
                    else { success_cond_count++; }
                }
        
            }
        
            // Get Status
            let status = false;
            switch ( compare ) {
                case 'and':
                status = ( failed_cond_count ) ? false : true;
                break;
                case 'or':
                status = ( success_cond_count ) ? true : false;
                break;
            }
        
            state = {
                status: status,
                failed_conditions: failed_cond_count,
                successed_conditions: success_cond_count,
                matched_data: matched_data,
            };
        
            /* if ( 'enable_similar_listings__logics' === args.condition.id ) {
                console.log( { state } );
            } */
        
            return state;
        },

        checkComparison( payload ) {
            let args = { data_a: '', data_b: '', compare: '=' };
            Object.assign( args, payload );

            let status = false;

            switch ( args.compare ) {
                case '=':
                    status = ( args.data_a == args.data_b ) ? true : false;
                    break;
                case '==':
                    status = ( args.data_a === args.data_b ) ? true : false;
                    break;
                case '!=':
                    status = ( args.data_a !== args.data_b ) ? true : false;
                    break;
                case 'not':
                    status = ( args.data_a !== args.data_b ) ? true : false;
                    break;
                case '>':
                    status = ( args.data_a > args.data_b ) ? true : false;
                    break;
                case '<':
                    status = ( args.data_a < args.data_b ) ? true : false;
                    break;
                case '>=':
                    status = ( args.data_a >= args.data_b ) ? true : false;
                    break;
                case '<=':
                    status = ( args.data_a <= args.data_b ) ? true : false;
                    break;
            }

            return status;
        },

        getFormFieldName( field_type ) {
            return field_type + '-field';
        },

        updateFieldValue( field_key, value ) {
            this.$store.commit( 'updateFieldValue', { field_key, value } );
        },

        updateFieldValidationState( field_key, value ) {
            this.$store.commit( 'updateFieldData', { field_key, option_key: 'validationState', value } );
        },
        
        updateFieldData( field_key, option_key, value ) {
            this.$store.commit( 'updateFieldData', { field_key, option_key, value } );
        },
        
        getActiveClass( item_index, active_index ) {
            return ( item_index === active_index ) ? 'active' : '';
        },

        getTergetFields( payload ) {

            let args = { root: this.fields, path: '' };
            
            if ( this.isObject( payload ) ) {
                Object.assign( args, payload );
            }

            if ( typeof args.path !== 'string' ) { return null; }
            let terget_field = null;

            let terget_fields = args.path.split('.');
            let terget_missmatched = false;

            if ( terget_fields && typeof terget_fields === 'object'  ) {
                terget_field = this.fields;

                for ( let key of terget_fields ) {
                    if ( ! key.length ) { continue; }

                    if ( 'self' === key ) {
                        terget_field = args.root;
                        continue;
                    }
                    
                    if ( typeof terget_field[ key ] === 'undefined' ) {
                        terget_missmatched = true;
                        break;
                    }

                    if ( typeof terget_field[ key ].isVisible !== 'undefined' && ! terget_field[ key ].isVisible ) {
                        terget_missmatched = true;
                        break;
                    }

                    terget_field = ( terget_field !== null ) ? terget_field[ key ] : args.root[ key ];
                }
            }

            if ( terget_missmatched ) { return false; }

            

            return JSON.parse( JSON.stringify( terget_field ) );
        },

        getSanitizedProps( props ) {

            if ( props && typeof props === 'object' ) {
                let _props = JSON.parse( JSON.stringify( props ) );
                delete _props.value;

                return _props;
            }

            return props;
        }
    },

    data() {
        return {
            default_option: { value: '', label: 'Select...' },
        }
    },
}