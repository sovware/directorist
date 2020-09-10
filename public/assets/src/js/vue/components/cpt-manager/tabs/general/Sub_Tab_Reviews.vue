<template>
    <div class="cptm-tab-sub-content-item tab-reviews cptm-tab-content" :class="getActiveClass( itemIndex, activeIndex )">
        Sub Tab Reviews
    </div>
</template>

<script>
import helpers from './../../../../mixins/helpers';

export default {
    name: 'reviews',
    props: [ 'itemIndex', 'activeIndex' ],
    mixins: [ helpers ],
}
</script>