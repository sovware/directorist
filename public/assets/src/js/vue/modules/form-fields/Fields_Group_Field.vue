<template>
    <div class="cptm-multi-option-group">
        <h3 class="cptm-multi-option-label" v-if="label.length">{{ label }}</h3>
        <template v-for="( field, field_key ) in fields">
            <component 
                :is="field.type + '-field'"
                :key="field_key"
                v-bind="getSanitizedOption( field )"
                :value="field.value"
                @update="updateValue( field_key, $event )">
            </component>
        </template>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import helpers from '../../mixins/helpers';

export default {
    'name': 'fields-group-field',
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
        fields: {
            type: Object,
        },
        validation: {
            type: Array,
            required: false,
        },
    },
    

    created() {
        this.setup();
    },

    data() {
        return {
            active_fields_groups: [],
        }
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
            if ( typeof this.value !== 'object' ) { return false; }
            if ( ! this.value.length ) { return false; }

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
    }
}
</script>