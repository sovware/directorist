<template>
    <div class="atbdp-cptm-tab-item listings-page-layout cptm-tab-content tab-full-width" :class="getActiveClass(itemIndex, activeIndex)">
        <div class="cptm-section" v-for="( section, section_key ) in sections" :key="section_key">
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
    name: 'list-view-Layout',
    props: [ 'itemIndex', 'activeIndex' ],
    mixins: [ helpers ],

    created() {
        
    },

    // computed
    computed: {
        ...mapState({
            fields: 'fields',
            sections: state => state.layouts.listings_card_layout.submenu.list_view.sections,
        }),
    },

    data() {
        return {
            
        }
    },
}
</script>