<template>
    <div class="atbdp-cptm-tab-contents">
        <template v-for="( tab, tab_index ) in tabContents">
            <div class="atbdp-cptm-tab-item" :key="tab_index" v-if="tab_index === active_nav_index" :class="getActiveClass(tab_index, active_nav_index)">
                <component :is="tab.type" v-bind="tab"></component>
            </div>
        </template>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import helpers from './../../mixins/helpers';

export default {
    name: 'tab-area',
    mixins: [ helpers ],

    computed: {
        ...mapState({
            active_nav_index: 'active_nav_index',
            tabContents: state => {
                let contents = [];

                for ( let menu_key in state.layouts ) {
                    let menu = state.layouts[ menu_key ];

                    let args = { ...menu };
                    args.key = menu_key;

                    if ( menu.submenu && typeof menu.submenu === 'object' ) {
                        args.type = 'submenu-module';
                    } else if ( menu.sections && typeof menu.sections === 'object' ) {
                        args.type = 'sections-module';
                    }

                    contents.push( args );
                }

                return contents;
            }
        }),
    },
}
</script>