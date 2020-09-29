<template>
    <div class="cptm-multi-option-group">
        <h3 class="cptm-multi-option-label">{{ label }}</h3>

        <template v-for="( option_group, option_group_key ) in active_groups">
            <div class="cptm-multi-option-group-section" :key="option_group_key">
                <template v-for="( option, option_key ) in option_group">
                    <component 
                        :is="option.type + '-field'"
                        :key="option_key"
                        v-bind="getSanitizedOption( option )"
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

        <!-- <pre>{{ options }}</pre> -->
        <pre>{{ syncedValue }}</pre>
    </div>
</template>

<script>
export default {
    'name': 'multi_options_field',
    
    props: {
        label: {
            type: String,
            default: '',
        },
        value: {
            default: '',
        },
        options: {
            type: Object,
            default: false,
        },
        addNewButtonLabel: {
            type: String,
            default: 'Add new',
        },
        removeButtonLabel: {
            type: String,
            default: 'Remove',
        },
    },
    

    created() {
        console.log( this.value );

        this.setup();
    },

    data() {
        return {
            active_groups: [],
        }
    },

    computed: {
        finalValue() {
            return JSON.parse( JSON.stringify( this.syncedValue ) );
        },

        syncedValue() {
            let updated_value = [];

            this.active_groups.forEach( option_group => {
                let option_group_item = {};

                for ( let key in option_group ) {
                    option_group_item[ key ] = option_group[ key ].value;
                }

                updated_value.push( option_group_item );
            });

            return updated_value;
            
        },
    },

    methods: {
        setup() {
            // console.log( this.value );

            /* if ( typeof this.value === 'object' ) {
                let options_groups = [];

                this.value.forEach( ( option_group, option_group_index ) => {
                    let group_option_item = {};

                    for ( let option_key in option_group[ option_group_index ] ) {
                        if ( typeof this.options[ option_key ] !== 'undefined' ) {
                            group_option_item[ option_key ] = JSON.parse( JSON.stringify( this.options[ option_key ] ) );
                            group_option_item[ option_key ].value = option_group[ option_group_index ][option_key];
                        }
                    }

                    options_groups.push( group_option_item );
                });

                this.active_groups = options_groups;

            } else  */
            
            if ( this.options && typeof this.options === 'object' ) {
                this.active_groups.push( JSON.parse( JSON.stringify( this.options ) ) );
            }
        },

        updateValue( group_key, field_key, value ) {
            this.active_groups[ group_key ][ field_key ].value = value;
            this.$emit( 'update',  this.finalValue );
        },

        addNewOptionGroup() {
            this.active_groups.push( JSON.parse( JSON.stringify( this.options ) ) );
            this.$emit( 'update',  this.finalValue );
        },

        removeOptionGroup( option_group_key ) {
            this.active_groups.splice( option_group_key, 1 );
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