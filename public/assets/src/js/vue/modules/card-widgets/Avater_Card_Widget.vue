<template>
    <div class="cptm-widget-card cptm-has-widget-control cptm-widget-actions-tools-wrap">
        <p class="cptm-placeholder-author-thumb">
            <img src="https://via.placeholder.com/150" alt="">
        </p>

        <widget-action-tools @drag="$emit( 'drag' )"  @edit="$emit( 'edit' )"  @trash="$emit( 'trash' )" />
    </div>
</template>

<script>
export default {
    name: 'avater-card-widget',
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