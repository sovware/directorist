<template>
    <div class="cptm-tab-sub-content-item tab-other cptm-tab-content" :class="getActiveClass( itemIndex, activeIndex )">
         <div class="" v-for="( section, section_key ) in general_sections" :key="section_key">
            <div class="cptm-title-area">
                <h3 v-if="section.title.length" class="cptm-title" v-html="section.title"></h3>
                <p v-if="section.description" v-html="section.description"></p>
            </div>
            
            <div class="cptm-form-fields">
                <template v-for="( field, field_key ) in section.fields">
                    <component 
                        :is="getFormFieldName( fields[ field ].type )" 
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
import { mapState } from 'vuex';
import helpers from './../../../../mixins/helpers';

export default {
    name: 'other',
    props: [ 'itemIndex', 'activeIndex' ],
    mixins: [ helpers ],
    computed: {
        ...mapState({
            general_sections: state => state.layouts.general.submenu.other.sections.labels,
            fields: state => state.fields,
        }),
    },
}
</script>