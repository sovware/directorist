<template>
    <div class="cptm-tab-sub-content-item tab-general cptm-tab-content" :class="getActiveClass( itemIndex, activeIndex )">
        <div class="" v-for="( section, section_key ) in general_sections" :key="section_key">
            <div class="cptm-title-area">
                <h3 v-if="section.title.length" class="cptm-title" v-html="section.title"></h3>
                <p v-if="section.description" v-html="section.description"></p>
            </div>
            
            <div class="cptm-form-fields">
                <template v-for="( field, field_key ) in section.fields">
                    <component 
                        :is="form_fields[ field.type ]" 
                        :key="field_key"
                        v-bind="field"
                        @update="updateSectionData( section_key, field_key, $event )">
                    </component>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import helpers from './../../../../mixins/helpers';
import form_fields from './../../../../mixins/form-fields';

export default {
    name: 'general',
    props: [ 'itemIndex', 'activeIndex' ],
    mixins: [ helpers ],

    computed: {
        ...mapState({
            general_sections: state => state.settings.general.submenu.general.sections
        }),
    },

    data() {
        return {
            form_fields,
        }
    },

    methods: {
        updateSectionData( section_key, field_key, value ) {
            console.log(  { section_key, field_key, value } );
            this.$store.commit( 'updateGeneralSectionData', { section_key, field_key, value } );
        }
    }
}
</script>