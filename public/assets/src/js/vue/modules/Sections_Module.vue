<template>
    <div class="cptm-tab-content" :class="containerClass">
        <div class="cptm-section" v-for="( section, section_key ) in sections" :key="section_key">
            <div class="cptm-title-area">
                <h3 v-if="typeof section.title === 'string'" class="cptm-title" v-html="section.title"></h3>
                <p v-if="typeof section.description === 'string'" v-html="section.description"></p>
            </div>
            
            <div class="cptm-form-fields">
                <template v-for="( field, field_key ) in section.fields">
                    <component 
                        :is="getFormFieldName( fields[ field ].type )" 
                        :field-id="field"
                        :key="field_key"
                        v-bind="fields[ field ]"
                        @update="updateFieldValue( field, $event )">
                    </component>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import helpers from './../mixins/helpers';

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
    },

    computed: {
        containerClass() {
            return {
                'tab-wide': ( 'wide' === this.container ) ? true : false,
                'tab-full-width': ( 'full-width' === this.container ) ? true : false,
            }
        }
    },
}
</script>