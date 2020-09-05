<template>
    <div class="cptm-tab-sub-content-item tab-other cptm-tab-content" :class="getActiveClass( itemIndex, activeIndex )">
        Sub Tab Other
    </div>
</template>

<script>
import helpers from './../../../mixins/helpers';

export default {
    name: 'other',
    props: [ 'itemIndex', 'activeIndex' ],
    mixins: [ helpers ],
}
</script>