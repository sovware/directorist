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
                <button type="button" :disabled="footer_actions.save.isDisabled" class="cptm-btn cptm-btn-primary" @click="saveData()">
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
        fields: { required: true },
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
            let fields = this.$store.state.fields;

            let form_data = new FormData();
            form_data.append( 'action', 'save_post_type_data' );
            
            if ( this.listing_type_id ) {
                form_data.append( 'listing_type_id', this.listing_type_id );
                this.footer_actions.save.label = 'Update';
            }

            let field_list = [];

            for ( let field in fields ) {
                let value = ( typeof fields[ field ].value === 'undefined' ) ? '' : fields[ field ].value;
                
                form_data.append( field, value );
                field_list.push( field );
            }
            
            form_data.append( 'field_list', JSON.stringify( field_list ) );

            this.status_messages = [];
            this.footer_actions.save.showLoading = true;
            this.footer_actions.save.isDisabled = true;
            const self = this;

            let config = {};

            axios.post( ajax_data.ajax_url, form_data, config )
                .then( response => {
                    self.footer_actions.save.showLoading = false;
                    self.footer_actions.save.isDisabled = false;

                    console.log( response.data );
                    
                    if ( response.data.post_id && ! isNaN( response.data.post_id ) ) {
                        self.listing_type_id = response.data.post_id;
                        self.footer_actions.save.label = 'Update';
                        window.location = response.data.redirect_url;
                    }

                    if ( response.data.status_log ) {
                        for ( let status_key in response.data.status_log  ) {
                            self.status_messages.push({ 
                                type: response.data.status_log[ status_key ].type, 
                                message: response.data.status_log[ status_key ].message
                            });
                        }
                    }

                    // console.log( response );
                })
                .catch( error => {
                    self.footer_actions.save.showLoading = false;
                    self.footer_actions.save.isDisabled = false;
                    console.log( error );
                });
        },

        insertParam(key, value) {
            key = encodeURIComponent(key);
            value = encodeURIComponent(value);

            // kvp looks like ['key1=value1', 'key2=value2', ...]
            var kvp = document.location.search.substr(1).split('&');
            let i=0;

            for(; i<kvp.length; i++){
                if (kvp[i].startsWith(key + '=')) {
                    let pair = kvp[i].split('=');
                    pair[1] = value;
                    kvp[i] = pair.join('=');
                    break;
                }
            }

            if(i >= kvp.length){
                kvp[kvp.length] = [key,value].join('=');
            }

            // can return this or...
            let params = kvp.join('&');

            // reload page with new params
            document.location.search = params;
        }
    }
}
</script>