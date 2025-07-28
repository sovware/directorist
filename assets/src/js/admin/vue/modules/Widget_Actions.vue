<template>
    <div class="cptm-widget-control-wrap">
        <div class="cptm-widget-control">
            <span class="cptm-widget-control-action cptm-widget-control-action-move" draggable="true" @drag="$emit('drag')">
                <span class="uil uil-expand-arrows"></span>
            </span>

            <span class="cptm-widget-control-action cptm-widget-control-action-edit" @click="$emit('edit')">
                <span class="uil uil-cog"></span>
            </span>

            <span class="cptm-widget-control-action cptm-widget-control-action-trash" @click="$emit('trash')">
                <span class="uil uil-trash-alt"></span>
            </span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'widget-actions'
}
</script>