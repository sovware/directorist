<template>
    <div class="atbdp-cptm-tab-item search-form cptm-tab-content tab-full-width" :class="getActiveClass(index, active_nav_index)">
        <div class="cptm-section" v-for="( section, section_key ) in search_form_sections" :key="section_key">
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
import { mapMutations } from 'vuex';
import helpers from './../../../mixins/helpers';


export default {
    name: 'search-form',
    props: ['index'],
    mixins: [ helpers ],

    // computed
    computed: {
        ...mapState({
            active_nav_index: 'active_nav_index',
            fields: 'fields',
            search_form_sections: state => state.layouts.search_forms.sections,
        }),
    },
}
</script>