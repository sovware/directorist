<template>
    <div class="cptm-widget-card-wrap cptm-widget-card-block-wrap cptm-widget-badge-card-wrap">
        <div class="cptm-widget-card cptm-list-item-card cptm-has-widget-control cptm-widget-actions-tools-wrap">
            <div class="cptm-list-item">
                <span class="cptm-list-item-icon">
                    <span :class="listIcon"></span>
                </span>
                <span class="cptm-list-item-label">{{ label }}</span>
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
    name: 'list-item-card-widget',
    props: {
        label: {
            type: String,
        },

        icon: {
            type: String,
            default: '',
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
        },

        listIcon() {
            if ( ! this.options && typeof this.options !== 'object' ) {
                // console.log( 'no options' );
                return this.icon;
            }

            if ( ! this.options.fields && typeof this.options.fields !== 'object' ) {
                // console.log( 'no fields' );
                return this.icon;
            }

            if ( ! this.options.fields.icon && typeof this.options.fields.icon !== 'object' ) {
                // console.log( 'no icon', this.options );
                return this.icon;
            }

            if ( typeof this.options.fields.icon.value !== 'string' && ! this.options.fields.icon.value.length ) {
                // console.log( 'empty icon' );
                return this.icon;
            }

            return this.options.fields.icon.value;
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