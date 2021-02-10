<template>
    <div class="cptm-widget-card-wrap cptm-widget-card-block-wrap cptm-widget-title-card-wrap">
        <div class="cptm-widget-card cptm-widget-title-card cptm-has-widget-control cptm-widget-actions-tools-wrap">
            <div class="cptm-widget-title-block">
                {{ label }}
            </div>
            
            <widget-action-tools
                :canEdit="canEdit"
                :canMove="canMove"
                :canTrash="canTrash"
                @drag="dragStart()" 
                @dragend="dragEnd()" 
                @edit="$emit( 'edit' )" 
                @trash="$emit( 'trash' )"
            />
        </div>

        <span class="cptm-widget-card-drop-append"
            :class="dropAppendClass"
            @dragover.prevent=""
            @dragenter="handleDragEnter()"
            @dragleave="handleDragLeave()" 
            @drop="handleDrop()"
        >
        </span>
    </div>
</template>

<script>
export default {
    name: 'section-title-card-widget',
    props: {
        label: {
            type: String,
        },

        options: {
            type: Object,
        },

        widgetDropable: {
            type: Boolean,
            default: false,
        },

        canMove: {
            type: Boolean,
            default: true,
        },

        canEdit: {
            type: Boolean,
            default: true,
        },

        canTrash: {
            type: Boolean,
            default: true,
        },
    },

    computed: {
        dropAppendClass() {
            return {
                'dropable': ( ! this.dragging && (this.drop_append_dropable || this.widgetDropable) ),
                'drag-enter': this.drop_append_drag_enter,
            }
        }
    },

    data() {
        return {
            drop_append_dropable: false,
            drop_append_drag_enter: false,
            dragging: false,
        }
    },

    methods: {
        dragStart() {
            this.dragging = true;
            this.$emit( 'drag' );
        },

        dragEnd() {
            this.dragging = false;
            this.$emit( 'dragend' )
        },

        handleDragEnter() {
            this.$emit( 'dragenter' );
            this.drop_append_drag_enter = true;
        },

        handleDragLeave() {
            this.$emit( 'dragleave' );
            this.drop_append_drag_enter = false;
        },

        handleDrop() {
            this.$emit( 'drop' );
            
            this.dragging = false;
            this.drop_append_dropable = false;
            this.drop_append_drag_enter = false;
        },
    },
}
</script>