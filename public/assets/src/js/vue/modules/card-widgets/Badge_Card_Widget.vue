<template>
    <div class="cptm-widget-card cptm-widget-badge cptm-has-widget-control cptm-widget-actions-tools-wrap">
        {{ label }} 
        
        <widget-action-tools @drag="$emit( 'drag' )"  @edit="$emit( 'edit' )"  @trash="$emit( 'trash' )" />
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