<template>
    <div class="atbdp-cptm-tab-item listings-page-layout cptm-tab-content" :class="getActiveClass(index, active_nav_index)">
        Listings Page layout
    </div>
</template>

<script>
import { mapState } from 'vuex';
import helpers from './../../mixins/helpers';

export default {
    name: 'listings-page-layout',
    props: ['index'],
    mixins: [ helpers ],

    // computed
    computed: {
        ...mapState([
            'active_nav_index',
        ])
    },
}
</script>