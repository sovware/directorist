<template>
    <div class="atbdp-cptm-tab-item search-form cptm-tab-content" :class="getActiveClass(index, active_nav_index)">
        Search Forms
    </div>
</template>

<script>
import { mapState } from 'vuex';
import helpers from './../../../mixins/helpers';

export default {
    name: 'search-form',
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