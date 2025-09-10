<template>
    <div class="cptm-widget-card-wrap cptm-widget-card-inline-wrap cptm-widget-badge-card-wrap">
        <div class="cptm-widget-card cptm-has-widget-control cptm-widget-actions-tools-wrap">
            <p class="cptm-placeholder-author-thumb">
                <div class="cptm-placeholder-author-thumb-wrapper">
                    <svg class="user-icon" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M256 288c79.5 0 144-64.5 144-144S335.5 0 256 0 112 64.5 112 144s64.5 144 144 144zm128 32h-55.1c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16H128C57.3 320 0 377.3 0 448v16c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48v-16c0-70.7-57.3-128-128-128z"></path>
                    </svg>
                </div>
            </p>

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
    name: 'avatar-card-widget',
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