<template>
    <div class="cptm-tab-sub-content-item tab-general cptm-tab-content" :class="getActiveClass( itemIndex, activeIndex )">
        <div class="" v-for="( section, section_index ) in general_sections" :key="section_index">
            <div class="cptm-title-area">
                <h3 v-if="section.title.length" class="cptm-title" v-html="section.title"></h3>
                <p v-if="section.description" v-html="section.description"></p>
            </div>
            
            <div class="cptm-form-fields">
                <template v-for="( field, field_index ) in section.fields">
                    <component 
                        :is="fields[ field.type ]" 
                        :key="field_index"
                        v-bind="field"
                        v-model="field.value">
                    </component>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import helpers from './../../../../mixins/helpers';
import fields from './../../../../mixins/form-fields';

export default {
    name: 'general',
    props: [ 'itemIndex', 'activeIndex' ],
    mixins: [ helpers ],

    computed: {
        ...mapState({
            
        }),

        general_sections() {
            return this.objectToSectionArray( this.$store.state.settings.general.submenu.general.sections );
        }
    },

    data() {
        return {
            fields,
        }
    },
}
</script>