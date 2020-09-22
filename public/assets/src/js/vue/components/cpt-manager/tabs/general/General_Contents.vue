<template>
    <div class="atbdp-cptm-tab-item general" :class="getActiveClass(index, active_nav_index)">
        <div class="cptm-tab-content-header">
            <subNavigation :navLists="sub_navigation" v-model="active_sub_nav"/>
        </div>
        <div class="cptm-tab-content-body">
            <template v-for="( sub_tab, index ) in sub_contents" >
                <component :is="sub_tab" :key="index" :item-index="index" :active-index="active_sub_nav"></component>
            </template>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import helpers from './../../../../mixins/helpers';
import subNavigation from './../../../../modules/Sub_Navigation.vue';
import subTabGeneral from './Sub_Tab_General';
import subTabPreveiw from './Sub_Tab_Preview';
import subTabPackages from './Sub_Tab_Packages';
import subTabOther from './Sub_Tab_Other';

export default {
    name: 'general',
    props: ['index'],
    components: { subNavigation },
    mixins: [ helpers ],

    // computed
    computed: {
        ...mapState({
            active_nav_index: 'active_nav_index',
            sub_navigation: state => state.submenu.general,
        }),
    },

    // data
    data() {
        return {
            active_sub_nav: 0,

            sub_contents: [
                subTabGeneral,
                subTabPreveiw,
                subTabPackages,
                subTabOther,
            ],
        }
    },

    methods: {
        
    },
}
</script>