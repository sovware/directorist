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

        <div class="atbdp-cptm-footer">
            <div class="atbdp-cptm-progress-bar"></div>
            <div class="atbdp-cptm-footer-actions">
                <button type="button" class="atbdp-cptm-btn cptm-btn-secondery" v-if="fotter_actions.prev.show">Prev</button>
                <button type="button" class="atbdp-cptm-btn cptm-btn-primary" v-if="fotter_actions.save.show">Save</button>
                <button type="button" class="atbdp-cptm-btn cptm-btn-secondery" v-if="fotter_actions.next.show">Next</button>
            </div>
        </div>
    </div>
</template>


<script>
import { mapState } from 'vuex';
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

    computed: {
        ...mapState({
            fotter_actions: 'fotter_actions'
        })
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