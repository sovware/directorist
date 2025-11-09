<template>
    <component 
        :is="getTheTheme( 'select-api-field' )" 
        v-if="canShow"
        v-bind="$props"
        @do-action="$emit( 'do-action', $event )"
        @update="$emit( 'update', $event )"
        @resync="handleResync"
    >
    </component>
</template>

<script>
import feild_helper from './../../mixins/form-fields/helper';
import props from './../../mixins/form-fields/input-field-props';

export default {
    name: 'select-api-field',
    mixins: [ props, feild_helper ],
    model: {
        prop: 'value',
        event: 'update'
    },
    props: {
        apiPath: {
            type: String,
            required: true,
            default: ''
        },
        apiMethod: {
            type: String,
            default: 'GET'
        },
        apiParams: {
            type: Object,
            default: () => ({})
        },
        resyncLabel: {
            type: String,
            default: 'Resync'
        },
        showResyncButton: {
            type: Boolean,
            default: true
        },
        enableInfiniteScroll: {
            type: Boolean,
            default: true
        },
        perPage: {
            type: Number,
            default: 15
        },
        pageParam: {
            type: String,
            default: 'page'
        },
        perPageParam: {
            type: String,
            default: 'per_page'
        },
        scrollThreshold: {
            type: Number,
            default: 100
        }
    },
    methods: {
        handleResync() {
            this.$emit('resync');
        }
    }
}
</script>

