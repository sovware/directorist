<template>
    <div class="cptm-dropable-element" :class="parentClass">
        <div class="cptm-dropable-placeholder cptm-dropable-placeholder-before" v-if="dropableBefore">dropableBefore</div>
        
        <div class=""><slot></slot></div>
        
        <div class="cptm-dropable-placeholder cptm-dropable-placeholder-after" v-if="dropableAfter">dropableAfter</div>
        
        <div class="cptm-dropable-area" v-if="dropable">
            <!-- cptm-dropable-area-left -->
            <span class="cptm-dropable-area-left" v-if="dropDirection === 'horizontal'"
                @dragover.prevent=""
                @dragenter="drag_enter_dropable_area_left = true"
                @dragleave="drag_enter_dropable_area_left = false"
                @drop="$emit('drop', 'dropped-before')"
            >
            </span>
            
            <!-- cptm-dropable-area-right -->
            <span class="cptm-dropable-area-right" v-if="dropDirection === 'horizontal'"
                @dragover.prevent=""
                @dragenter="drag_enter_dropable_area_right = true"
                @dragleave="drag_enter_dropable_area_right = false"
                @drop="$emit('drop', 'dropped-after')"
            >
            </span>
            
            <!-- cptm-dropable-area-top -->
            <span class="cptm-dropable-area-top" v-if="dropDirection === 'vertical'"
                @dragover.prevent=""
                @dragenter="drag_enter_dropable_area_top = true"
                @dragleave="drag_enter_dropable_area_top = false"
                @drop="$emit('drop', 'dropped-before')"
            >
            </span>

            <!-- cptm-dropable-area-bottom -->
            <span class="cptm-dropable-area-bottom" v-if="dropDirection === 'vertical'"
                @dragover.prevent=""
                @dragenter="drag_enter_dropable_area_bottom = true"
                @dragleave="drag_enter_dropable_area_bottom = false"
                @drop="$emit('drop', 'dropped-after')"
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
                [ this.className ]: true,
                [ diplay_class ]: true,
            }
        },
    },

    data() {
        return {
            dropable_before: false,
            dropable_after: false,

            drag_enter_dropable_area_right: false,
            drag_enter_dropable_area_left: false,
            drag_enter_dropable_area_top: false,
            drag_enter_dropable_area_bottom: false,
        }
    }
}
</script>