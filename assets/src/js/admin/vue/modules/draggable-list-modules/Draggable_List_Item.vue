<template>
    <div v-if="listType === 'div'" class="directorist-draggable-list-item" :class="itemClassName" :draggable="canDrag" :style="listItemStyle" @dragstart="dragStart" @dragend="dragEnd">
        <div class="directorist-draggable-list-item-slot" :style="slotStyle">
            <slot></slot>
        </div>
    </div>

    <li v-else class="directorist-draggable-list-item" draggable="canDrag" :class="itemClassName" :style="listItemStyle" @dragstart="dragStart" @dragend="dragEnd">
        <div class="directorist-draggable-list-item-slot" :style="slotStyle">
            <slot></slot>
        </div>
    </li>
</template>

<script>
export default {
    name: 'draggable-list-item',
    props: {
        canDrag: {
            default: true // move | clone
        },
        dragType: {
            default: 'move' // move | clone
        },
        itemClassName: {
            default: ''
        },
        listType: {
            default: 'div' // div | li
        },
    },

    computed: {
        listItemStyle() {
            let style = {
                cursor: ( this.canDrag ) ? 'move' : '', 
            };

            if ( this.dragging && 'move' === this.dragType ) {
                style.height = '0';
                style.padding = '0';
                style.overflow = 'hidden';
            }

            if ( this.dragging && 'clone' === this.dragType ) {
                style.border = '2px dashed gray';
            }

            return style;
        },

        slotStyle() {
            return {
                opacity: ( this.dragging ) ? 0 : 1 
            }
        },
    },

    data() {
        return {
            dragging: false,
        }
    },

    methods: {
        dragStart() {
            const self = this;
            setTimeout( function() { 
                self.dragging = true; 
                self.$emit( 'drag-start' );
            }, 0 );
        },

        dragEnd() {
            this.dragging = false;
            this.$emit( 'drag-end' );
        },
    }
}
</script>