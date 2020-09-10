<template>
    <div class="atbdp-cptm-tab-item submission-form cptm-tab-content" :class="getActiveClass(index, active_nav_index)">
        Submission Form
    </div>
</template>

<script>
import { mapState } from 'vuex';
import helpers from './../../../mixins/helpers';

export default {
    name: 'submission-form',
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