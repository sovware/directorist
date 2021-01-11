<template>
    <div class="cptm-fields" v-if="option_fields">
        <template v-for="( field_key, field_index ) in Object.keys( option_fields ) ">
            <component
                :root="option_fields"
                :is="option_fields[ field_key ].type + '-field'" 
                :field-id="field_key"
                :ref="field_key"
                :key="field_index"
                v-bind="option_fields[ field_key ]"
                @update="updateOptionFieldValue( field_key, $event )"
                @validate="updateOptionFieldValidationState( field_key, $event )"
                @is-visible="updateOptionFieldData( field, 'isVisible' , $event )"
                @do-action="doAction( $event, 'sub-fields' )"
            />
        </template>
    </div>
</template>

<script>
import Vue from 'vue';
import helper from '../mixins/helpers';

export default {
    name: 'sub-fields-module',
    mixins: [ helper ],

    props: {
        optionFields: {
            required: false
        }
    },

    created() {
        if ( this.optionFields ) {
            this.option_fields = this.optionFields;
        }
    },

    watch: {
        option_fields() {
            let value = this.getOptionFieldsValue();
            this.$emit('update', value);
        }
    },

    data() {
        return {
            option_fields: null,
        }
    },

    methods: {
        updateOptionFieldValue( option_key, value ) {
            Vue.set( this.option_fields[ option_key ], 'value', value );
            this.sync();
        },

        updateOptionFieldValidationState( option_key, value ) {
            Vue.set( this.option_fields[ option_key ], 'validationState', value );
        },

        updateOptionFieldData( field_key, option_key , value ) {
            Vue.set( this.option_fields[ field_key ], option_key, value );
        },

        sync() {
            let value = this.getOptionFieldsValue();
            this.$emit('update', value);
        },

        getOptionFieldsValue() {
            if ( ! this.option_fields ) { return ''; }

            let fields_value = {};
            for ( let field_key in this.option_fields ) {
                fields_value[ field_key ] = this.option_fields[ field_key ].value;
            }

            return fields_value;
        },
    },
}
</script>