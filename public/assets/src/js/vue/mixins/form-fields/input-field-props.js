export default {
    props: {
        fieldId: {
            type: [String, Number],
            required: false,
            default: '',
        },
        type: {
            type: String,
            required: false,
            default: '',
        },
        label: {
            type: [String, Number],
            required: false,
            default: '',
        },
        description: {
            type: [ String ],
            required: false,
            default: '',
        },
        id: {
            type: [String, Number],
            required: false,
            default: '',
        },
        name: {
            type: [String, Number],
            required: false,
            default: '',
        },
        value: {
            // type: [String, Number],
            default: '',
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
        infoTextForNoOption: {
            type: String,
            required: false,
            default: 'Nothing available',
        },
        rules: {
            type: Object,
            required: false,
        },
        validationState: {
            type: Object,
            required: false,
        },
        validation: {
            type: Array,
            required: false,
        },
    },
}