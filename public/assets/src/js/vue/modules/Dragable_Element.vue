<template>
    <div class="cptm-dragable-element" :class="className" :draggable="draggable" @dragstart="$emit('dragstart')" @dragend="$emit('dragend')">
        <div class="cptm-dropable-placeholder cptm-dropable-placeholder-before" v-if="dropable_before"></div>
        <slot></slot>
        <div class="cptm-dropable-placeholder cptm-dropable-placeholder-after" v-if="dropable_after"></div>
        
        <div class="cptm-dropable-area" v-if="dropable">
            <span class="cptm-dropable-area-right" v-if="dropDirection === 'horizontal'"></span>
            <span class="cptm-dropable-area-left" v-if="dropDirection === 'horizontal'"></span>
            <span class="cptm-dropable-area-top" v-if="dropDirection === 'vertical'"></span>
            <span class="cptm-dropable-area-bottom" v-if="dropDirection === 'vertical'"></span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'dragable-element',
    props: {
        className: {
            type: String,
            default: '',
        },
        draggable: {
            type: Boolean,
            default: true,
        },
        dropable: {
            type: Boolean,
            default: false,
        },
        dropDirection: {
            type: String,
            default: 'vertical',
        },
    },

    data() {
        return {
            dropable_before: false,
            dropable_after: false,
        }
    }
}
</script>