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
        if ( this.value.length ) {
            this.local_value = this.value;
        }

        this.$emit( 'update', this.local_value );
    },

    watch: {
        local_value() {
            // this.$emit( 'update', this.local_value );
            // let dd = this.getTheOptions();
            // console.log( this.name, dd, this.local_value );
        }
    },

    computed: {
        
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
            let action      = '';

            if ( status && value_index === -1 ) {
                // this.local_value.splice( this.local_value.length , 1, value)
                action = 'added';
            }

            if ( ! status && value_index != -1 ) {
                // this.local_value.splice( value_index, 1 );
                action = 'removed';
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