<template>
    <div class="cptm-widget-card">
        <p class="cptm-placeholder-author-thumb">
            <img src="https://via.placeholder.com/150" alt="">
        </p>
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