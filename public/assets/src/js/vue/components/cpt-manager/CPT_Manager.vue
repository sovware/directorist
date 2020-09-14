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
                <button 
                    type="button" class="atbdp-cptm-btn cptm-btn-primary" 
                    v-if="footer_actions.save.show"
                    @click="saveData()">
                        Save
                </button>
            </div>
        </div>
    </div>
</template>


<script>
import { mapState } from 'vuex';
import { mapGetters } from 'vuex';
import headerNavigation from './Header_Navigation.vue';
import tabContents from './tabs/TabContents.vue';

export default {
    name: 'cpt-manager',
    props: {
        settings: {
            type: String,
            required: true,
        },
        fields: {
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
            footer_actions: 'footer_actions'
        })
    },

    created() {
        if ( this.settings && this.settings.length ) {
            const settings = JSON.parse( this.settings );

            if ( settings ) {
                this.$store.commit( 'updateSettings', settings );
            }
        }

        if ( this.fields && this.fields.length ) {
            const fields = JSON.parse( this.fields );

            if ( fields ) {
                this.$store.commit( 'updateFields', fields );
            }
        }
    },

    data() {
        return {
            
        }
    },

    methods: {
        saveData() {
            let settings = this.$store.state.settings;
            let fields = this.$store.state.fields;

            let the_form_fields_row_data = {};
            let validation = {};
        }
    }
}
</script>