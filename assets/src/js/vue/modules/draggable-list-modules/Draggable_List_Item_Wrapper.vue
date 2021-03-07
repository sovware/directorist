<template>
    <div class="directorist-draggable-list-item-wrapper" :data-list-id="listId" :style="wrapperStyle">
        <div class="directorist-droppable-area-wrap" :class="className" :style="{ display:  ( droppable ) ? 'flex' : 'none' }">
            <span class="directorist-droppable-area directorist-droppable-area-top"
                v-if="droppableBefore"
                @dragover.prevent=""
                @dragenter="dragenterBeforeItem = true"
                @dragleave="dragenterBeforeItem = false"
                @drop="handleDroppedBefore()"
            >
            </span>

            <span class="directorist-droppable-area directorist-droppable-area-bottom"
                v-if="droppableAfter"
                @dragover.prevent=""
                @dragenter="dragenterAfterItem = true"
                @dragleave="dragenterAfterItem = false"
                @drop="handleDroppedAfter()"
            >
            </span>
        </div>

        <div class="directorist-droppable-item-preview directorist-droppable-item-preview-before" v-if="dragenterBeforeItem"></div>

        <slot></slot>

        <div class="directorist-droppable-item-preview directorist-droppable-item-preview-after" v-if="dragenterAfterItem"></div>
    </div>
</template>

<script>
export default {
    name: 'draggable-list-item-wrapper',
    props: {
        isDraggingSelf: {
            default: false
        },
        listId: {
            default: ''
        },
        droppable: {
            default: false
        },
        droppableBefore: {
            default: true
        },
        droppableAfter: {
            default: true
        },
        className: {
            default: ''
        },
    },

    computed: {
        wrapperStyle() {
            let style = {};
            
            if ( this.isDraggingSelf ) {
                 style.display = 'none';
            }

            return style;
        }
    },

    data() {
        return {
            dragenterBeforeItem: false,
            dragenterAfterItem: false,
        }
    },

    methods: {
        handleDroppedBefore() {
            this.dragenterBeforeItem = false;
            this.dragenterAfterItem = false;

            this.$emit( 'drop', { drop_direction: 'before' } );
        },

        handleDroppedAfter() {
            this.dragenterBeforeItem = false;
            this.dragenterAfterItem = false;
            
            this.$emit( 'drop', { drop_direction: 'after' } );
        },
    }
}
</script>