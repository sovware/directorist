<template>
    <div class="cptm-tab-sub-content-item tab-packages cptm-tab-content" :class="getActiveClass( itemIndex, activeIndex )">
        Sub Tab Packages
    </div>
</template>

<script>
import helpers from './../../../mixins/helpers';

export default {
    name: 'packages',
    props: [ 'itemIndex', 'activeIndex' ],
    mixins: [ helpers ],
}
</script>