<template>
    <div class="cptm-form-group" :class="formGroupClass">
        <label v-if="label.length">{{label}}</label>
        <select @change="update_value( $event.target.value )" :value="local_value" class="cptm-form-control">
            <option v-if="default_option" :value="default_option.value">{{ default_option.label }}</option>
            
            <template v-for="( option, option_key ) in theOptions">
                <template v-if="option.group && option.theOptions">
                    <optgroup :label="option.group" :key="option_key">
                        <option v-for="( sub_option, sub_option_key ) in option.theOptions" :key="sub_option_key" :value="sub_option.value">
                            {{ sub_option.label }}
                        </option>
                    </optgroup>
                </template>

                <template v-else>
                    <option :key="option_key" :value="option.value">{{ option.label }}</option>
                </template>
            </template>
        </select>
        
        <div class="cptm-form-group-feedback" v-if="validationMessages">
            <div class="cptm-form-alert" :class="'cptm-' + validationMessages.type">
                {{ validationMessages.message }}
            </div>
        </div>
    </div>
</template>

<script>
import helpers from './../../mixins/helpers';
import validation from './../../mixins/validation';

export default {
    name: 'select-field',
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

    computed: {
        
    },

    data() {
        return {
            local_value: '',
        }
    },

    methods: {
        setup() {
            this.local_value = this.value;
            this.$emit( 'update', this.local_value );
        },

        update_value( value ) {
            this.local_value = value;
            this.$emit( 'update', this.local_value );
        },

        /* syncValidationWithLocalState( validation_log ) {

            return validation_log;
        } */
    },
}
</script>