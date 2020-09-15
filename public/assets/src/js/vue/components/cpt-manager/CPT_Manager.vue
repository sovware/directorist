<template>
    <div class="atbdp-cpt-manager">
        <!-- atbdp-cptm-header -->
        <div class="atbdp-cptm-header">
            <headerNavigation />
        </div>

        <!-- atbdp-cptm-body -->
        <div class="atbdp-cptm-body">
            <tabContents />

            <div class="atbdp-cptm-status-feedback" v-if="status_messages.length">
                <div class="atbdp-cptm-alert" :class="'cptm-alert-' + status.type " v-for="(status, index) in status_messages" :key="index">
                    {{ status.message }}
                </div>
            </div>
        </div>

        <div class="atbdp-cptm-footer">
            <div class="atbdp-cptm-progress-bar"></div>
            <div class="atbdp-cptm-footer-actions">
                <button type="button" :disabled="footer_actions.save.isDisabled" class="atbdp-cptm-btn cptm-btn-primary" @click="saveData()">
                    <span v-if="footer_actions.save.showLoading" class="fa fa-spinner fa-spin"></span>
                    {{ footer_actions.save.label }}
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

const axios = require('axios').default;

export default {
    name: 'cpt-manager',
    props: {
        id: { required: false },
        settings: { required: true },
        fields: { required: true }
    },
    components: {
        headerNavigation,
        tabContents,
    },

    computed: {
        ...mapState({
            
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


        if ( this.id && ! isNaN( this.id ) ) {
            const id = parseInt( this.id );

            if ( id > 0 ) {
                this.listing_type_id = id;
                this.footer_actions.save.label = 'Update';
            }
        }
    },

    data() {
        return {
            listing_type_id: null,
            status_messages: [],
            footer_actions: {
                save: { 
                    show: true,
                    label: 'Create',
                    showLoading: false,
                    isDisabled: false,
                },
            },
        }
    },

    methods: {
        saveData() {
            // let settings = this.$store.state.settings;
            let fields = this.$store.state.fields;

            let the_form_fields_row_data = {
                action: 'save_post_type_data'
            };
            console.log( this.listing_type_id );
            if ( this.listing_type_id ) {
                the_form_fields_row_data['listing_type_id'] = this.listing_type_id;
            }

            let field_list = [];

            for ( let field in fields ) {
                the_form_fields_row_data[ field ] = fields[ field ].value;
                field_list.push( field );
            }
            
            the_form_fields_row_data['field_list'] = field_list;


            this.status_messages = [];
            this.footer_actions.save.showLoading = true;
            this.footer_actions.save.isDisabled = true;
            const self = this;

            axios.get( ajax_data.ajax_url, { params: the_form_fields_row_data })
                .then( response => {
                    self.footer_actions.save.showLoading = false;
                    self.footer_actions.save.isDisabled = false;

                    console.log( response.data.post_id );

                    if ( response.data.post_id && ! isNaN( response.data.post_id ) ) {
                    console.log( 'Update isNaN' );
                        self.listing_type_id = response.data.post_id;
                        self.footer_actions.save.label = 'Update';
                    }

                    if ( response.data.status_log ) {
                        for ( let status_key in response.data.status_log  ) {
                            // console.log( response.data.status_log[ status_key ] );

                            self.status_messages.push({ 
                                type: response.data.status_log[ status_key ].type, 
                                message: response.data.status_log[ status_key ].message
                            });
                        }
                    }

                    console.log( response );
                })
                .catch( error => {
                    self.footer_actions.save.showLoading = false;
                    self.footer_actions.save.isDisabled = false;
                    console.log( error );
                });
        }
    }
}
</script>