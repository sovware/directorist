<template>
    <div class="atbdp-cptm-tab-item single-page-layout cptm-tab-content" :class="getActiveClass(index, active_nav_index)">
        Single Page Layout
    </div>
</template>

<script>
import { mapState } from 'vuex';
import helpers from './../../../mixins/helpers';

export default {
    name: 'single-page-layout',
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