<template>
    <div class="cptm-widget-card cptm-widget-title-card cptm-has-widget-control">
        <widget-actions @drag="$emit( 'drag' )"  @edit="$emit( 'edit' )"  @trash="$emit( 'trash' )" />

        <div class="cptm-widget-title-block">
            {{ label }}
        </div>
    </div>
</template>

<script>
export default {
    name: 'title-card-widget',
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