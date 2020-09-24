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

import grid_view from './Grid_View_Layout.vue';
import list_view from './List_View_Layout.vue';
import options from './Options.vue';

export default {
    name: 'listings-card-layout',
    props: ['index'],
    components: { subNavigation },
    mixins: [ helpers ],

    created() {
        
    },

    // computed
    computed: {
        ...mapState({
            active_nav_index: 'active_nav_index',
        }),
    },

    data() {
        return {
            active_sub_nav: 0,

            sub_navigation: [
                'Grid View',
                'List View',
                'Options',
            ],
            
            sub_contents: [
                grid_view,
                list_view,
                options,
            ],
        }
    },
}
</script>