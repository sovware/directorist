<template>
    <div class="cptm-multi-option-group">
        <h3 class="cptm-multi-option-label">{{ label }}</h3>
        <template v-for="( option_group, option_group_key ) in theActiveGroups">
            <div class="cptm-multi-option-group-section" :key="option_group_key">
                <h3># {{ ( option_group_key + 1 ) }}</h3>
                <template v-for="( option, option_key ) in option_group">
                    <component 
                        :is="option.type + '-field'"
                        :root="option_group"
                        :key="`${fieldId}_${option_key}`"
                        v-bind="getSanitizedOption( option )"
                        :validation="getValidation( option_key, option_group_key, option )"
                        :value="option.value"
                        @update="updateValue( option_group_key, option_key, $event )">
                    </component>
                </template>

                <p style="text-align: right">
                    <button type="button" class="cptm-btn cptm-btn-secondery" @click="removeOptionGroup( option_group_key )">
                        {{ removeButtonLabel }}
                    </button>
                </p>
            </div>
        </template>
        
        <button type="button" class="cptm-btn cptm-btn-primary" @click="addNewOptionGroup()">
            {{ addNewButtonLabel }}
        </button>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import helpers from '../../mixins/helpers';

export default {
    'name': 'multi-fields-field',
    mixins: [ helpers ],
    props: {
        fieldId: {
            type: [String, Number],
            required: false,
            default: '',
        },
        name: {
            type: String,
            default: '',
        },
        label: {
            type: String,
            default: '',
        },
        value: {
            default: '',
        },
        options: {
            type: Object,
        },
        addNewButtonLabel: {
            type: String,
            default: 'Add new',
        },
        removeButtonLabel: {
            type: String,
            default: 'Remove',
        },
        validation: {
            type: Array,
            required: false,
        },
    },
    
    created() {
        console.log( '@CHK-1: value', { 
            fieldId: this.fieldId,
            name: this.name,
            value: this.value,
        } );
        this.setup();
    },

    data() {
        return {
            active_fields_groups: [],
        }
    },

    watch: {
        value() {
            console.log( '@CHK-2: value', { 
                fieldId: this.fieldId,
                name: this.name,
                value: this.value,
             } );

            this.loadOldData();
        },
    },

    computed: {
        ...mapState({
            fields: 'fields',
        }),

        finalValue() {
            return this.syncedValue;
        },

        valuesByFieldKey() {
            let values = {};

            for ( let group of this.active_fields_groups ) {
                for ( let field_key in group ) {
                    if ( typeof values[ field_key ] === 'undefined' ) {
                        values[ field_key ] = [];
                    }

                    values[ field_key ].push( group[ field_key ].value );

                }
            }

            return values;
        },

        theActiveGroups() {
            let active_fields_groups = JSON.parse( JSON.stringify( this.active_fields_groups ) );

            let group_count = 0; 
            for ( let group of active_fields_groups ) {
                for ( let field of Object.keys( group ) ) {

                    if ( ! this.isObject( group[ field ].show_if )  ) {
                        continue;
                    }

                    let show_if_cond = this.checkShowIfCondition({
                        root: JSON.parse( JSON.stringify( group ) ),
                        condition: group[ field ].show_if
                    });
                
                    if ( ! show_if_cond.status ) {
                        delete group[ field ];
                    }
                }

                group_count++;
            }

            return active_fields_groups;
        },

        syncedValue() {
            let updated_value = [];

            this.theActiveGroups.forEach( field_group_item => {
                let option_group_item = {};

                for ( let key in field_group_item ) {
                    option_group_item[ key ] = field_group_item[ key ].value;
                }

                updated_value.push( option_group_item );
            });

            return updated_value;
            
        },
    },

    methods: {
        setup() {
            this.loadOldData();
            /* if ( ! this.loadOldData() && this.options && typeof this.options === 'object' ) {
                this.active_fields_groups.push( JSON.parse( JSON.stringify( this.options ) ) );
            } */
        },

        hasDuplicateKey( array ) {
            if ( ! array || typeof array !== 'object' ) { return null; }

            return new Set(array).size !== array.length;
        },

        getValidation( option_key, option_group_key, option ) {
            let validation = [];

            
            let unique = option.unique;
            let value_length = option.value.length;
            let hasDuplicateFeildValue = this.hasDuplicateFeildValue( option_key, option.value, option_group_key );
            
            if ( option.unique && hasDuplicateFeildValue ) {
                validation.push({
                    error_key: 'duplicate_value'
                });
            }

            return validation;
        },

        hasDuplicateFeildValue( current_field_key, current_value, current_group_index ) {
            if ( current_value === '' ) { return false; }

            let matched_fields = [];
            let has_duplicate = false;

            this.theActiveGroups.forEach(( item, group_index ) => {
                if ( group_index === current_group_index ) {
                    return;
                }
                
                

                if ( typeof item[current_field_key] === 'undefined' ) {
                    /* console.log( this.name, {
                        item,
                        group_index,
                        current_field_key, 
                        current_value, 
                        current_group_index
                    }); */
                    return;
                }

                let terget_value = item[current_field_key].value;

                if ( terget_value === current_value ) {
                    if ( 'the_plan_id' === current_field_key ) {
                        console.log( 'terget_value_matched' );
                        console.log( { current_field_key, terget_value, group_index, current_value} );
                    }
                    
                    has_duplicate = true;
                    return;
                }

            });

            return has_duplicate;
        },

        loadOldData() {
            if ( typeof this.value !== 'object' ) { 
                this.active_fields_groups = [];
                return false; 
            }

            if ( ! this.value.length ) {
                this.active_fields_groups = []; 
                return false; 
            }

            let fields_groups = [];

            for ( let option_group_item of this.value ) {
                let fields = JSON.parse( JSON.stringify( this.options ) );

                for ( let value_key in option_group_item ) {
                    if ( typeof fields[ value_key ] !== 'undefined' ) {
                        fields[ value_key ].value = option_group_item[ value_key ];
                    }
                }
                
                fields_groups.push( fields );
            }

            this.active_fields_groups = fields_groups;

            return true;
        },

        updateValue( group_key, field_key, value ) {
            this.active_fields_groups[ group_key ][ field_key ].value = value;
            // console.log( { field_key, value } );
            this.$emit( 'update',  this.finalValue );
        },

        addNewOptionGroup() {
            this.active_fields_groups.push( JSON.parse( JSON.stringify( this.options ) ) );
            this.$emit( 'update',  this.finalValue );
        },

        removeOptionGroup( option_group_key ) {
            this.active_fields_groups.splice( option_group_key, 1 );
            this.$emit( 'update',  this.finalValue );
        },

        getSanitizedOption( option ) {
            if ( typeof option.value !== 'undefined' ) {
                let sanitized_option = JSON.parse( JSON.stringify( option ) );
                delete sanitized_option.value;

                return sanitized_option;
            }

            return option;
        },

        __checkShowIfCondition( option_key, option, option_group_key ) {
            if ( ! option.show_if ) { return true; }

            let accepted_condition_comparations = [ 'or', 'and' ];
            let accepted_value_comparations = [ '=', 'not' ];

            let success_conditions = 0;
            let faild_conditions = 0;

            for ( let condition of option.show_if ) {
                let terget_fields = 'self';
                let condition_compare_type = 'or';
                let condition_status = null;

                if ( condition.where && condition.where.length ) {
                    terget_fields = condition.where;
                }

                if ( condition.compare && accepted_condition_comparations.indexOf( condition.compare ) ) {
                    condition_compare_type = condition.compare;
                }

                terget_fields = terget_fields.split( '.' );
                
                let base_field = this.finalValue[ option_group_key ];
                let base_terget_missmatched = false;

                if ( 'self' !== terget_fields[0] ) {
                    base_field = this.fields;
                }

                for ( let field of terget_fields ) {
                    if ( 'self' === field || 'root' === field ) { continue; }

                    if ( typeof base_field[ field ] === 'undefined' ) {
                        base_terget_missmatched = true;
                        break;
                    }

                    base_field = base_field[ field ];
                }

                if ( base_terget_missmatched ) {
                    return true;
                }

                let success_subconditions = 0;
                let faild_subconditions = 0;

                for ( let sub_condition of condition.conditions ) {
                    let terget_value = base_field[ sub_condition.key ];
                    let compare_value = sub_condition.value;
                    let compare_type = ( sub_condition.compare ) ? sub_condition.compare : '=';

                    if ( '=' === compare_type ) {
                        if ( terget_value === compare_value ) {
                            success_subconditions++;
                        } else {
                            faild_subconditions++;
                        }
                    }

                    if ( 'not' === compare_type ) {
                        if ( terget_value !== compare_value ) {
                            success_subconditions++;
                        } else {
                            faild_subconditions++;
                        }
                    }
                }

                let status = false;

                if ( 'or' === condition_compare_type && success_subconditions ) {
                    status = true;
                }

                if ( 'and' === condition_compare_type && ! faild_subconditions ) {
                    status = true;
                }

                if ( ! status ) {
                    faild_conditions++;
                }

                // console.log( {option_key, condition_compare_type, faild_conditions, success_conditions, status} );
                // console.log( {option_key, option, terget_fields, base_field, option_group_key, base_terget_missmatched} );
            }

            // console.log( { option_key, faild_conditions } );

            if ( faild_conditions ) {
                return false;
            }

            return true;
        }
    }
}
</script>