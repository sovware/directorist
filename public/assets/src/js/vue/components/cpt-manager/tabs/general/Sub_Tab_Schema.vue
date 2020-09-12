<template>
    <div class="cptm-tab-sub-content-item tab-schema cptm-tab-content" :class="getActiveClass( itemIndex, activeIndex )">
        Sub Tab Schema
    </div>
</template>

<script>
import helpers from './../../../../mixins/helpers';

export default {
    name: 'schema',
    props: [ 'itemIndex', 'activeIndex' ],
    mixins: [ helpers ],
}
</script>