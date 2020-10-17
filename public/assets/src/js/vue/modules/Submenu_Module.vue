<template>
    <div>
        <div class="cptm-tab-content-header">
            <sub-navigation :navLists="navList" v-model="active_sub_nav"/>
        </div>

        <div class="cptm-tab-content-body">
            <div class="cptm-tab-sub-content-item" 
                :class="{ active: ( active_sub_nav === sub_tab_index ) ? true : false }" 
                v-for="( sub_tab, sub_tab_index ) in subNavigation"
                :key="sub_tab_index"
            >
                <sections-module v-bind="sub_tab" :container="container"/>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import helpers from '../mixins/helpers';

export default {
    'name': 'submenu-module',
    mixins: [ helpers ],

    props: {
        submenu: {
            type: Object
        },
        container: {
            type: String,
            default: '',
        },
    },

    // computed
    computed: {
        subNavigation() {
            if ( ! this.submenu && typeof this.submenu !== 'object' ) { return []; }

            let sub_navigation = [];
            
            for ( let submenu_key in this.submenu ) {
                let submenu = this.submenu[ submenu_key ];

                if ( typeof submenu.label !== 'string' ) {
                    continue;
                }

                if (  ! submenu.sections && typeof submenu.sections !== 'object' ) {
                    continue;
                }

                sub_navigation.push( submenu );
            }

            return sub_navigation;
        },

        navList() {
            if ( ! this.subNavigation && typeof this.subNavigation !== 'object' ) {
                return [];
            }

            return [ ...this.subNavigation ].map( item => {
                return item.label;
            });
        }
    },

    data() {
        return {
            active_sub_nav: 0,
        }
    },


}
</script>