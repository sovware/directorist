<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <label v-if="label.length">{{label}}</label>
        <pre>{{ selected }}</pre>

        <multiselect
            v-model="selected"
            :options="options_1">
        </multiselect>
        
        <div class="cptm-form-group-feedback" v-if="validationMessages">
            <div class="cptm-form-alert" :class="'cptm-' + validationMessages.type">
                {{ validationMessages.message }}
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import helpers from './../../mixins/helpers';
import validation from './../../mixins/validation';
import Multiselect from 'vue-multiselect';

export default {
    name: 'select2-field',
    mixins: [ helpers, validation ],
    components: { Multiselect },
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
        value: {
            type: [ String, Number ],
            required: false,
            default: '',
        },
        options: {
            type: Array,
            required: false,
        },
        defaultOption: {
            type: Object,
            required: false,
        },
        optionsSource: {
            type: Object,
            required: false,
        },
        name: {
            type: [String, Number],
            required: false,
            default: '',
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

    mounted() {
        this.setup();
    },

    watch: {
        local_value() {
            this.$emit( 'update', this.local_value );
        },

        theOptions() {
            if ( ! this.valueIsValid( this.local_value ) ) {
                this.local_value = '';
            }
        },
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

            let terget_fields = this.getTergetFields( { path: this.optionsSource.where } );
            
            if ( ! terget_fields || typeof terget_fields !== 'object' ) {
                return false;
            }

            let filter_by = null;
            if ( typeof this.optionsSource.filter_by === 'string' && this.optionsSource.filter_by.length ) {
                filter_by = this.optionsSource.filter_by;
            }

            if ( filter_by ) {
                filter_by = this.getTergetFields( { path: this.optionsSource.filter_by } );
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
            local_value: '',
            selected: null,
            options_1: ['list', 'of', 'options'],

            result: '',
            options_2: [
                {
                    label: "group1",
                    options: [{
                        text: "name1",
                        value: "value1"
                    }, {
                        text: "name2",
                        value: "value2"
                    }, {
                        text: "name3",
                        value: "value3"
                    }]
                },
                {
                    label: "group2",
                    options: [{
                        text: "name4",
                        value: "value4"
                    }, {
                        text: "name5",
                        value: "value5"
                    }, {
                        text: "name6",
                        value: "value6"
                    }]
                }
            ],
        }
    },

    methods: {
        setup() {
            if ( this.defaultOption || typeof this.defaultOption === 'object' ) {
                this.default_option = this.defaultOption;
            }

            if ( this.valueIsValid( this.value ) ) {
                this.local_value = this.value;
            }
            
        },

        update_value( value ) {
            this.local_value = ( ! isNaN( Number( value ) ) ) ? Number( value ) : value;
        },

        valueIsValid( value ) {
            let options_values = this.theOptions.map( option => {
                if ( typeof option.value !== 'undefined' ) {
                    return ( ! isNaN( Number( option.value ) ) ) ? Number( option.value ) : option.value
                }
            });

            return options_values.includes( value );
        }

        /* syncValidationWithLocalState( validation_log ) {

            return validation_log;
        } */
    },
}
</script>