<template>
<div class="cptm-form-group">
    <a :href="link" :target="target">
        <button type="button" class="cptm-btn cptm-btn-secondery">{{ label }}</button>
    </a>
</div>
</template>

<script>
export default {
    name: 'button-field',
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
            default: 'Label',
        },
        link: {
            type: String,
            required: false,
            default: '',
        },
        target: {
            type: String,
            required: false,
            default: '',
        },
        rules: {
            type: Object,
            required: false,
            default: null,
        },
    },
    computed: {
        input_type() {
            const supported_types = ['text', 'number', 'password', 'button'];

            if (supported_types.indexOf(this.type)) {
                return this.type;
            }

            return 'text';

        }
    },

    data() {
        return {

        }
    },
}
</script>
