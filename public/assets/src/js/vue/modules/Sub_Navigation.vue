<template>
    <ul class="cptm-sub-navigation">
        <li class="cptm-sub-nav__item" v-for="( nav, index ) in navLists" :key="index">
            <a href="#" class="cptm-sub-nav__item-link" :class="getActiveClass( index, active_nav )" @click.prevent="swichNav( index )">
                {{ nav }}
            </a>
        </li>
    </ul>
</template>

<script>
import helpers from './../mixins/helpers';

export default {
    name: 'sub-navigation',
    props: ['navLists', 'active'],
    props: {
        navLists: Array,
        active: {
            type: Number,
            required: false
        }
    },
    mixins: [ helpers ],
    model: {
        prop: 'active',
        event: 'change'
    },

    data() {
        return {
            active_nav: 0,
        }
    },

    methods: {
        swichNav( index ) {
            this.active_nav = index;
            this.$emit('change', index);
        },
    },

}
</script>