<template>
    <div class="cptm-widget-card cptm-widget-badge cptm-has-widget-control">
        <widget-actions @drag="$emit( 'drag' )"  @edit="$emit( 'edit' )"  @trash="$emit( 'trash' )" />

        {{ label }}
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