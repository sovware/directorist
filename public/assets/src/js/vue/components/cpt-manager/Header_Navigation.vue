<template>
    <div class="">
        <ul class="cptm-header-navigation">
            <!-- cptm-header-nav__list-item -->
            <li class="cptm-header-nav__list-item" v-for="( nav, index ) in headerNavigation" :key="index">
                <a href="#" class="cptm-header-nav__list-item-link" :class="getActiveClass(index, active_nav_index)" @click.prevent="swichNav(index)">
                    <span class="cptm-header-nav__icon">
                        <span :class="nav.icon_class"></span>
                    </span>

                    <span class="cptm-header-nav__label" v-html="nav.label"></span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import { mapMutations } from 'vuex';
import helpers from './../../mixins/helpers';

export default {
    name: 'header-navigation',
    mixins: [ helpers ],

    // computed
    computed: {
        ...mapState({
            active_nav_index: 'active_nav_index',
            headerNavigation: state => {
                let header_navigation = [];

                for ( let nav_item in state.settings ) {
                    header_navigation.push({
                        label: ( state.settings[ nav_item ].label ) ? state.settings[ nav_item ].label : '',
                        icon_class: ( state.settings[ nav_item ].icon ) ? state.settings[ nav_item ].icon : '',
                    });
                }

                return header_navigation;
            },
        }),
    },
    
    // methods
    methods: {
        ...mapMutations([
            'swichNav'
        ]),
    },
}
</script>