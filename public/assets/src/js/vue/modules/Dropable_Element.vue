<template>
    <div class="cptm-dropable-element" ref="dropable_element" :class="parentClass">
        
        <div class="cptm-dropable-placeholder cptm-dropable-placeholder-before" :class="dropablePlaceholderBeforeClass"></div>
        <div class="cptm-dropable-base-element" :class="{ ['cptm-dropable-inside']: drag_enter_dropable_area_inside }">
            <slot></slot>
        </div>

        <div class="cptm-dropable-placeholder cptm-dropable-placeholder-after" :class="dropablePlaceholderAfterClass"></div>

        <div class="cptm-dropable-area" v-if="dropable">
            <!-- cptm-dropable-area-inside -->
            <span class="cptm-dropable-area-inside" v-if="dropInside"
                @dragover.prevent=""
                @dragenter="drag_enter_dropable_area_inside = true"
                @dragleave="drag_enter_dropable_area_inside = false"
                @drop="handleDroppedInside()"
            >
            </span>

            <!-- cptm-dropable-area-left -->
            <span class = "cptm-dropable-area-left" v-if = "! dropInside && dropDirection === 'horizontal'"
                @dragover.prevent = ""
                @dragenter = "drag_enter_dropable_area_left = true"
                @dragleave = "drag_enter_dropable_area_left = false"
                @drop = "handleDroppedBefore()"
            >
            </span>
            
            <!-- cptm-dropable-area-right -->
            <span class="cptm-dropable-area-right" v-if="! dropInside && dropDirection === 'horizontal'"
                @dragover.prevent=""
                @dragenter="drag_enter_dropable_area_right = true"
                @dragleave="drag_enter_dropable_area_right = false"
                @drop="handleDroppedAfter()"
            >
            </span>
            
            <!-- cptm-dropable-area-top -->
            <span class="cptm-dropable-area-top" v-if="! dropInside && dropDirection === 'vertical'"
                @dragover.prevent=""
                @dragenter="drag_enter_dropable_area_top = true"
                @dragleave="drag_enter_dropable_area_top = false"
                @drop="handleDroppedBefore()"
            >
            </span>

            <!-- cptm-dropable-area-bottom -->
            <span class="cptm-dropable-area-bottom" v-if="! dropInside && dropDirection === 'vertical'"
                @dragover.prevent=""
                @dragenter="drag_enter_dropable_area_bottom = true"
                @dragleave="drag_enter_dropable_area_bottom = false"
                @drop="handleDroppedAfter()"
            >
            </span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'dropable-element',
    props: {
        display: {
            type: String,
            default: 'block',
        },
        wrapperClass: {
            type: String,
            default: '',
        },
        dropablePlaceholderClass: {
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
        dropInside: {
            type: Boolean,
            default: false,
        },
        dropDirection: {
            type: String,
            default: 'vertical',
        },
    },

    computed: {
        dropableBefore() {
            return ( this.drag_enter_dropable_area_top || this.drag_enter_dropable_area_left ) ? true : false;
        },

        dropableAfter() {
            return ( this.drag_enter_dropable_area_right || this.drag_enter_dropable_area_bottom ) ? true : false;
        },

        parentClass() {
            const diplay_class = ( 'block' === this.display ) ? 'cptm-display-block' : 'cptm-display-inline';
            return {
                [ this.wrapperClass ]: true,
                [ diplay_class ]: true,
            }
        },

        dropablePlaceholderBeforeClass() {
            return {
                [ this.dropablePlaceholderClass ]: true,
                [ 'active' ]: ( this.dropableBefore ) ? true : false,
            }
        },
        dropablePlaceholderAfterClass() {
            return {
                [ this.dropablePlaceholderClass ]: true,
                [ 'active' ]: ( this.dropableAfter ) ? true : false,
            }
        },
    },

    data() {
        return {
            dropable_before: false,
            dropable_after: false,

            drag_enter_dropable_area_inside: false,
            drag_enter_dropable_area_right: false,
            drag_enter_dropable_area_left: false,
            drag_enter_dropable_area_top: false,
            drag_enter_dropable_area_bottom: false,
        }
    },

    methods: {
        handleDroppedBefore() {
            this.drag_enter_dropable_area_top = false;
            this.drag_enter_dropable_area_left = false;

            this.$emit('drop', 'dropped-before');
        },

        handleDroppedInside() {
            this.drag_enter_dropable_area_inside = false;

            this.$emit('drop', 'dropped-inside');
        },

        handleDroppedAfter() {
            this.drag_enter_dropable_area_right = false;
            this.drag_enter_dropable_area_bottom = false;

            this.$emit('drop', 'dropped-after');
        },
    }
}
</script>