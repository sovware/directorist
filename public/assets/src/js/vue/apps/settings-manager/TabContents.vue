<template>
    <div class="atbdp-cptm-tab-contents">
        <template v-for="( menu, menu_key ) in layouts">
            <div :id="'#'+ menu_key" class="atbdp-tab-content-item" :key="menu_key" v-if="menu.active">
                <!-- Main Menu Contents -->
                <h2 class="cptm-menu-title" v-if="! menu.submenu">{{ menu.label }}</h2>

                <div class="atbdp-tab-content-body" v-if="menu.sections && ! menu.submenu">
                    <sections-module v-if="submenu.sections" :sections="menu.sections"></sections-module>
                </div>

                <!-- Submenu Contents -->
                <div class="atbdp-tab-sub-contents" v-if="menu.submenu">
                    <template v-for="( submenu, submenu_key ) in menu.submenu" >
                        <div :id="'#'+ menu_key + '__' + submenu_key" class="atbdp-tab-content-item" v-if="submenu.active" :key="submenu_key">
                            <h2 class="cptm-menu-title cptm-submenu-title">{{ submenu.label }}</h2>
                            
                            <sections-module v-if="submenu.sections" :sections="submenu.sections"></sections-module>
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

    computed: {
        ...mapState({
            layouts: 'layouts',
        }),
    },
}
</script>