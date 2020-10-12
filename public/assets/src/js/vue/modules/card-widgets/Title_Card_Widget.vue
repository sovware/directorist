<template>
    <div class="cptm-widget-card cptm-widget-title-card cptm-has-widget-control cptm-widget-actions-tools-wrap">
        <div class="cptm-widget-title-block">
            {{ label }}
        </div>

        <widget-action-tools @drag="$emit( 'drag' )"  @edit="$emit( 'edit' )"  @trash="$emit( 'trash' )" />
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