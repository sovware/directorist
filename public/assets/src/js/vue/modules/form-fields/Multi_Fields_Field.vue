<template>
    <div class="cptm-multi-option-group">
        <h3 class="cptm-multi-option-label">{{ label }}</h3>

        <template v-for="( option_group, option_group_key ) in theActiveGroups">
            <div class="cptm-multi-option-group-section" :key="option_group_key">
                <template v-for="( option, option_key ) in option_group">
                    <component 
                        :is="option.type + '-field'"
                        :key="option_key"
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
export default {
    'name': 'multi-fields-field',
    
    props: {
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
    

    mounted() {
        this.setup();
    },

    data() {
        return {
            active_fields_groups: [],
        }
    },

    computed: {
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
            let active_fields_groups = this.active_fields_groups;

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
            if ( ! this.loadOldData() && this.fields && typeof this.fields === 'object' ) {
                this.active_fields_groups.push( JSON.parse( JSON.stringify( this.fields ) ) );
            }
        },

        hasDuplicateKey( array ) {
            if ( ! array || typeof array !== 'object' ) { return null; }

            return new Set(array).size !== array.length;
        },

        getValidation( option_key, option_group_key, option ) {
            let validation = [];

            // console.log({ option_key, unique, hasDuplicateKey });
            
            let unique = option.unique;
            let value_length = option.value.length;
            let hasDuplicateFeildValue = this.hasDuplicateFeildValue( option_key, option.value, option_group_key );

            // if ( 'plan_id' === option_key ) {
            //     console.log( { unique, value_length, option_key, hasDuplicateFeildValue, option_group_key, option } );
            // }
            
            if ( option.unique && hasDuplicateFeildValue ) {
                // if ( 'plan_id' === option_key ) {
                //     // console.log( 'duplicate_value_found' );
                // }
                validation.push({
                    error_key: 'duplicate_value'
                });
            }

            // if ( 'the_plan_id' === option_key ) {
            //    console.log( {validation} );
            // }

            return validation;
        },

        hasDuplicateFeildValue( current_field_key, current_value, current_group_index ) {
            if ( ! current_value.length ) { return false; }

            let matched_fields = [];
            let has_duplicate = false;

            this.theActiveGroups.forEach(( item, group_index ) => {
                if ( group_index === current_group_index ) {
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
                let fields = JSON.parse( JSON.stringify( this.fields ) );

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
            this.$emit( 'update',  this.finalValue );
        },

        addNewOptionGroup() {
            this.active_fields_groups.push( JSON.parse( JSON.stringify( this.fields ) ) );
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