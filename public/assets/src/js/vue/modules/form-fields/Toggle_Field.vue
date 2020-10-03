<template>
<div class="cptm-form-group">
    <div class="cptm-input-toggle-wrap">
        <label :for="name">{{ label }}</label>

        <span class="cptm-input-toggle" :class="toggleClass" @click="toggleValue()"></span>
        <input type="checkbox" :id="name" :name="name" style="display: none;" :checked="local_value">
    </div>
    
</div>
</template>

<script>
export default {
    name: 'toggle-field',
    model: {
        prop: 'value',
        event: 'input'
    },
    props: {
        type: {
            type: String,
            required: false,
            default: 'text',
        },
        label: {
            type: String,
            required: false,
            default: '',
        },
        value: {
            required: false,
            default: false,
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

    created() {
        if ( typeof this.value !== 'undefined' ) {
            this.local_value = ( true === this.value || 'true' === this.value || 1 === this.value || '1' === this.value ) ? true : false;
        }

        this.$emit('update', this.local_value)
    },

    computed: {
        toggleClass() {
            return {
                'active': this.local_value,
            }
        }
    },

    data() {
        return {
            local_value: false
        }
    },

    methods: {
        toggleValue() {
            this.local_value = ! this.local_value;
            this.$emit('update', this.local_value);
        }
    }
}
</script>
