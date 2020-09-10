<template>
    <div class="cptm-tab-sub-content-item tab-general cptm-tab-content" :class="getActiveClass( itemIndex, activeIndex )">
        Sub Tab General
    </div>
</template>

<script>
import helpers from './../../../../mixins/helpers';

export default {
    name: 'general',
    props: [ 'itemIndex', 'activeIndex' ],
    mixins: [ helpers ],
}
</script>