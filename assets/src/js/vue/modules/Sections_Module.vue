<template>
    <div class="cptm-tab-content" :class="containerClass">
        <div class="cptm-section" :class="sectionClass( section )" v-for="( section, section_key ) in sections" :key="section_key">
            <div class="cptm-title-area" :class="sectionTitleAreaClass( section )">
                <h3 v-if="section.title" class="cptm-title" v-html="section.title"></h3>
                <p v-if="section.description" v-html="section.description"></p>
            </div>
            
            <div class="cptm-form-fields" v-if="sectionFields( section )">
                <div v-for="( field, field_key ) in sectionFields( section )" :class="fieldWrapperClass( field, fields[ field ] )" :key="field_key">
                    <component
                        v-if="fields[ field ]"
                        :root="fields"
                        :is="getFormFieldName( fields[ field ].type )" 
                        :field-id="field"
                        :id="menuKey + '__' + section_key + '__' + field"
                        :ref="field"
                        :class="{['highlight-field']: getHighlightState( field ) }"
                        :key="field_key"
                        :cached-data="cached_fields[ field ]"
                        v-bind="fields[ field ]"
                        @update="updateFieldValue( field, $event )"
                        @validate="updateFieldValidationState( field, $event )"
                        @is-visible="updateFieldData( field, 'isVisible' , $event )"
                        @do-action="doAction( $event, 'sections-module' )"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import helpers from './../mixins/helpers';
import { mapState } from 'vuex';

export default {
    name: 'sections-module',
    mixins: [ helpers ],

    props: {
        sections: {
            type: Object
        },
        container: {
            type: String,
            default: '',
        },
        menuKey: {
            type: String,
            default: ''
        },
    },

    computed: {
        ...mapState([
            'metaKeys',
            'fields',
            'cached_fields',
        ]),

        containerClass() {
            return {
                'tab-wide': ( 'wide' === this.container ) ? true : false,
                'tab-full-width': ( 'full-width' === this.container ) ? true : false,
            }
        },
    },

    watch: {
        fields() {
            console.log( 'updated' );
        }
    },

    methods: {
        sectionFields( section ) {
            if ( ! this.isObject( section )) { return false; }
            if ( ! Array.isArray( section.fields )) { return false; }

            return section.fields;
        },

        sectionClass( section ) {
            return {
                'cptm-short-wide': ( 'short-width' === section.container ) ? true : false,
            }
        },

        sectionTitleAreaClass( section ) {
            return {
                'directorist-no-header': ( ! section.title && ! section.description ),
                'cptm-text-center': ( 'center' === section.title_align ) ? true : false,
            }
        },

        fieldWrapperClass( field_key, field ) {
            let type_class = ( field && field.type ) ? 'cptm-field-wraper-type-' + field.type : 'cptm-field-wraper';
            let key_class = 'cptm-field-wraper-key-' + field_key;

            return {
                [ type_class ]: true,
                [ key_class ]: true,
            }
        },
    },
}
</script>