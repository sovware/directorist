<template>
    <div class="cptm-widget-card cptm-widget-badge">
        {{ label }}
        <div class="cptm-widget-badge-tools">
            <a href="#" @click.prevent="" @drag="$emit('drag')">
                <span class="uil uil-expand-arrows"></span>
            </a>
            <a href="#" @click.prevent="$emit('edit')"><span class="uil uil-cog"></span></a>
            <a href="#" @click.prevent="$emit('trash')"><span class="uil uil-trash-alt"></span></a>
        </div>
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
        }
    },

    computed: {
        
    },

    data() {
        return {
            
        }
    },
}
</script>