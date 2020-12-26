<template>
    <div class="atbdp-cptm-tab-contents">
        <template v-for="( menu, menu_key ) in layouts">
            <div class="atbdp-tab-content-item" :key="menu_key" v-if="menu.active">
                <!-- Main Menu Contents -->
                <h2 v-if="! menu.submenu">{{ menu.label }}</h2>

                <div class="atbdp-tab-content-body" v-if="menu.sections && ! menu.submenu">
                    Has Sections
                </div>

                <!-- Submenu Contents -->
                <div class="atbdp-tab-sub-contents" v-if="menu.submenu">
                    <template v-for="( submenu, submenu_key ) in menu.submenu" >
                        <div class="atbdp-tab-content-item" v-if="submenu.active" :key="submenu_key">
                            <h2>{{ submenu.label }}</h2>
                        </div>
                    </template>
                </div>
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

    created() {
        console.log( this.layouts );
    },

    computed: {
        ...mapState({
            active_nav_index: 'active_nav_index',
            layouts: 'layouts',
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