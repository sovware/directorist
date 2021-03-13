<template>
    <div class="directorist-form-fields-area" v-if="field_list && typeof field_list === 'object'">
        <component
            v-for="( field, field_key ) in field_list"
            :key="field_key"
            :is="field.type + '-field'"
            v-bind="field"
            @update="update( { key: field_key, value: $event } )"
        />
    </div>
</template>

<script>
import helpers from './../mixins/helpers';

export default {
    name: 'field-list-compnents',
    mixins: [ helpers ],
    props: {
        fieldList: {
            default: ''
        },
        value: {
            default: ''
        }
    },

    created() {
        this.filtereFieldList();
    },

    watch: {
        fieldList() {
            this.filtereFieldList();
        },
        value() {
            this.filtereFieldList();
        }
    },

    data() {
        return {
            field_list: null
        }
    },

    methods: {
        filtereFieldList() {
            this.field_list = this.getFiltereFieldList( this.fieldList );
        },

        getFiltereFieldList( field_list ) {
            if ( ! field_list ) { return field_list }

            let new_fields = JSON.parse( JSON.stringify( field_list ) );

            for ( let field_key in new_fields ) {
                if ( this.value && (typeof this.value === 'object') && (typeof this.value[ field_key ] !== 'undefined') ) {
                    new_fields[ field_key ].value = this.value[ field_key ];
                }
            }
            
            for ( let field_key in new_fields ) {
                if ( ! new_fields[ field_key ].show_if ) { continue; }

                let checkShowIfCondition = this.checkShowIfCondition({ 
                    root: new_fields, 
                    condition: new_fields[ field_key ].show_if
                });

                if ( ! checkShowIfCondition.status ) {
                    delete new_fields[ field_key ];
                }
            }

            return new_fields;
        },

        update( payload ) {
            this.$emit( 'update', payload );
            this.filtereFieldList();
        }
    }
}
</script>