<template>
    <div class="cptm-widget-actions-tools">
        <a href="#" @click.prevent="" @drag="$emit('drag')">
            <span class="uil uil-expand-arrows"></span>
        </a>
        <a href="#" @click.prevent="$emit('edit')"><span class="uil uil-cog"></span></a>
        <a href="#" @click.prevent="$emit('trash')"><span class="uil uil-trash-alt"></span></a>
    </div>
</template>

<script>
export default {
    name: 'widget-action-tools'
}
</script>