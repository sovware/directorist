<template>
    <div class="cptm-form-group">
        <label for="">{{label}}</label>

        <div class="cptm-checkbox-area">
            <div class="cptm-checkbox-item" v-for="( option, option_index ) in getTheOptions()" :key="option_index">
                <input type="checkbox" class="cptm-checkbox" 
                    :name="( option.name && option.name.length ) ? option.name : ''"
                    :value="( option.value && option.value.length ) ? option.value : ''"
                    :id="( option.id && option.id.length ) ? option.id : ''"
                    :checked="getCheckedStatus( option )"
                    @change="updateValue( option_index, $event.target.checked, option )"
                >
                <label :for="( option.id && option.id.length ) ? option.id : ''" class="cptm-checkbox-ui"></label>

                <label :for="( option.id && option.id.length ) ? option.id : ''">
                    {{ option.label }}
                </label>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import helpers from './../../mixins/helpers';
import validation from './../../mixins/validation';

export default {
    name: 'checkbox-field',
    mixins: [ helpers, validation ],
    model: {
        prop: 'value',
        event: 'input'
    },
    props: {
        label: {
            type: String,
            required: false,
            default: '',
        },
        id: {
            type: String,
            required: false,
            default: '',
        },
        name: {
            type: String,
            required: false,
            default: '',
        },
        value: {
            required: false,
        },
        options: {
            required: false,
        },
        optionsSource: {
            required: false,
        },
        placeholder: {
            type: [String, Number],
            required: false,
            default: '',
        },
        validation: {
            type: Array,
            required: false,
        },
    },

    created() {

        if ( this.value && typeof this.value === 'object' && this.value.length ) {
            this.local_value = this.value;
        }

        this.$emit( 'update', this.local_value );
    },

    watch: {
        local_value() {
            this.$emit( 'update', this.local_value );
        }
    },

    computed: {
        ...mapState({
            fields: 'fields',
        }),

        theOptions() {
            if ( this.hasOptionsSource ) {
                return this.hasOptionsSource;
            }

            if ( ! this.options || typeof this.options !== 'object' ) {
                return ( this.defaultOption ) ? [ this.defaultOption ] : [];
            }

            return this.options;
        },

        hasOptionsSource() {
            
            if ( ! this.optionsSource || typeof this.optionsSource !== 'object' ) {
                return false;
            }

            if ( typeof this.optionsSource.where !== 'string' ) {
                return false;
            }

            let terget_fields = this.getTergetFields( this.optionsSource.where );
            
            if ( ! terget_fields || typeof terget_fields !== 'object' ) {
                return false;
            }

            let filter_by = null;
            if ( typeof this.optionsSource.filter_by === 'string' && this.optionsSource.filter_by.length ) {
                filter_by = this.optionsSource.filter_by;
            }

            if ( filter_by ) {
                filter_by = this.getTergetFields( this.optionsSource.filter_by );
            }

            
            let has_sourcemap = false;

            if ( this.optionsSource.source_map && typeof this.optionsSource.source_map === 'object'  ) {
                has_sourcemap = true;
            }

            if ( ! has_sourcemap && ! filter_by ) {
                return terget_fields;
            }

            if ( has_sourcemap ) {
                terget_fields = this.mapDataByMap( terget_fields, this.optionsSource.source_map );
            }

            if ( filter_by ) {
                terget_fields = this.filterDataByValue( terget_fields, filter_by );
            }

            if ( ! terget_fields && typeof terget_fields !== 'object' ) {
                return false;
            }

            return terget_fields;
        },
    },

    data() {
        return {
            local_value: [],
        }
    },

    methods: {
        updateValue( option_index, status, option ) {
            let value       = this.getValue( option );
            let value_index = this.local_value.indexOf( value );
            // let action      = '';

            if ( status && value_index === -1 ) {
                this.local_value.splice( this.local_value.length , 1, value)
                // action = 'added';
            }

            if ( ! status && value_index != -1 ) {
                this.local_value.splice( value_index, 1 );
                // action = 'removed';
            }

            // console.log( {name: this.name, option_index, status, option, action, local_value: this.local_value} );
        },

        getCheckedStatus( option ) {
            let status = ( this.local_value.indexOf( this.getValue( option ) ) > -1 ) ? true : false;

            // console.log( { status } );
            
            return status;
        },

        getValue( option ) {
            return ( option.value && option.value.length ) ? option.value : '';
        },

        getTheOptions() {
            return JSON.parse( JSON.stringify( this.theOptions ) );
        }
    },
}
</script>