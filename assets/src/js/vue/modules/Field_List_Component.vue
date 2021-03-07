<template>
    <div class="directorist-form-fields-area" v-if="fields && typeof fields === 'object'">
        <component
            v-for="( field, field_key ) in fields"
            :key="field_key"
            :is="field.type + '-field'"
            v-bind="field"
            :value="( value && (typeof value === 'object') && (typeof value[ field_key ] !== 'undefined') ) ? value[ field_key ] : field.value"
            @update="$emit( 'update', { key: field_key, value: $event } )"
        />
    </div>
</template>

<script>
export default {
    name: 'field-list-compnents',
    props: {
        fields: {
            default: ''
        },
        value: {
            default: ''
        }
    },

    created() {
        // console.log( this.fields );
    }
}
</script>