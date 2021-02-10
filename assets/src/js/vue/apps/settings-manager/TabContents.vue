<template>
    <div class="atbdp-cptm-tab-contents">
        <template v-for="( menu, menu_key ) in layouts">
            <div :id="menu_key" class="atbdp-tab-content-item" :key="menu_key" v-if="menu.active">
                
                <template v-if="! menu.submenu">
                    <!-- Main Menu Contents -->
                    <h2 class="cptm-menu-title">{{ menu.label }}</h2>

                    <div class="atbdp-tab-content-body" v-if="menu.sections">
                        <sections-module 
                            v-if="menu.sections" 
                            :menu-key="menu_key" 
                            :sections="menu.sections"
                            @do-action="doAction( $event, 'tab-contents' )"
                        />
                    </div>
                </template>
                

                <!-- Submenu Contents -->
                <div class="atbdp-tab-sub-contents" v-if="menu.submenu">
                    <template v-for="( submenu, submenu_key ) in menu.submenu" >
                        <div :id="menu_key + '__' + submenu_key" class="atbdp-tab-content-item" v-if="submenu.active" :key="submenu_key">
                            
                            <sections-module 
                                v-if="submenu.sections" 
                                :menu-key="menu_key + '__' + submenu_key" 
                                :sections="submenu.sections"
                                @do-action="doAction( $event, 'tab-contents' )"
                            />
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