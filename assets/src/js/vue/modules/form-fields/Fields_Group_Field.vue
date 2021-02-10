<template>
    <div class="cptm-multi-option-group">
        <h3 class="cptm-multi-option-label" v-if="label.length">{{ label }}</h3>
        <template v-for="( field, field_key ) in local_fields">
            <component 
                :is="field.type + '-field'"
                :key="field_key"
                v-bind="field"
                @update="updateValue( field_key, $event )">
            </component>
        </template>
    </div>
</template>

<script>
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
            local_fields: {},
        }
    },

    computed: {
        finalValue() {
            return this.syncedValue;
        },

        syncedValue() {
            let updated_value = {};

            for( let field in this.local_fields ) {
                updated_value[ field ] = this.local_fields.value;
            }

            return updated_value;
        },
    },

    methods: {
        setup() {
            this.local_fields = this.fields;
            this.$emit( 'update',  this.finalValue );
        },

        updateValue( field_key, value ) {
            this.local_fields[ field_key ].value = value;
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