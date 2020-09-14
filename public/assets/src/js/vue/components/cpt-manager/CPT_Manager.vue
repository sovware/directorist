<template>
    <div class="atbdp-cpt-manager">
        <!-- atbdp-cptm-header -->
        <div class="atbdp-cptm-header">
            <headerNavigation />
        </div>

        <!-- atbdp-cptm-body -->
        <div class="atbdp-cptm-body">
            <tabContents />
        </div>
    </div>
</template>


<script>
import headerNavigation from './Header_Navigation.vue';
import tabContents from './tabs/TabContents.vue';

export default {
    name: 'cpt-manager',
    props: {
        settings: {
            type: String,
            required: true,
        }
    },
    components: {
        headerNavigation,
        tabContents,
    },

    created() {
        if ( this.settings && this.settings.length ) {
            const settings = JSON.parse( this.settings );

            if ( settings ) {
                this.$store.commit( 'updateSettings', settings );
            }
        }
    },

    data() {
        return {
            
        }
    },

    methods: {
        
    }
}
</script>