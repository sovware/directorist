<template>
    <div class="cptm-widget-actions-tools">
        <a href="#" v-if="canMove" @click.prevent="" draggable="true" @drag="$emit('drag')" @dragend="$emit('dragend')">
            <span class="uil uil-expand-arrows"></span>
        </a>

        <a href="#" v-if="canEdit" @click.prevent="$emit('edit')">
            <span class="uil uil-cog"></span>
        </a>

        <a href="#" v-if="canTrash" @click.prevent="$emit('trash')">
            <span class="uil uil-trash-alt"></span>
        </a>
    </div>
</template>

<script>
export default {
    name: 'widget-action-tools',

    props: {
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
    }
}
</script>