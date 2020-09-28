<template>
<div class="cptm-form-group">
    <a :href="link" :target="target">
        <button :id="id" :class="extra_class" :type="type"><span v-if="icon" :class="icon"></span> {{ label }}</button>
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
            default: 'button',
        },
        label: {
            type: String,
            required: false,
            default: '',
        },
        link: {
            type: String,
            required: false,
            default: '',
        },
        id: {
            type: String,
            required: false,
            default: '',
        },
        extra_class: {
            type: String,
            required: false,
            default: 'cptm-btn cptm-btn-secondery',
        },
        icon: {
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
    data() {
        return {

        }
    },
}
</script>
