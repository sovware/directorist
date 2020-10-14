<template>
    <div class="cptm-widget-card-wrap cptm-widget-card-inline-wrap cptm-widget-badge-wrap">
        <div class="cptm-widget-card cptm-widget-badge cptm-has-widget-control cptm-widget-actions-tools-wrap">
            {{ label }}
            <widget-action-tools @drag="$emit( 'drag' )" @dragend="$emit( 'dragend' )" @edit="$emit( 'edit' )"  @trash="$emit( 'trash' )" />
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
    name: 'badge-card-widget',
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
        }
    },

    computed: {
        dropAppendClass() {
            return {
                'dropable': this.drop_append_dropable || this.widgetDropable,
                'drag-enter': this.drop_append_drag_enter,
            }
        }
    },

    data() {
        return {
            drop_append_dropable: false,
            drop_append_drag_enter: false,
        }
    },

    methods: {
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

            this.drop_append_dropable = false;
            this.drop_append_drag_enter = false;
        },
    },
}
</script>