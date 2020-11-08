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
                <div class="cptm-alert" :class="'cptm-alert-' + status.type " v-for="(status, index) in status_messages" :key="index">
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
import tabContents from './TabContents.vue';

const axios = require('axios').default;

export default {
    name: 'cpt-manager',

    components: {
        headerNavigation,
        tabContents,
    },

    created() {
        if ( this.$root.fields ) {
            this.$store.commit( 'updateFields', this.$root.fields );
        }

        if ( this.$root.layouts ) {
            this.$store.commit( 'updatelayouts', this.$root.layouts );
        }

        if ( this.$root.config ) {
            this.$store.commit( 'updateConfig', this.$root.config );
        }

        if ( this.$root.id && ! isNaN( this.$root.id ) ) {
            const id = parseInt( this.$root.id );

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
            let config = this.$store.state.config;

            let form_data = new FormData();
            form_data.append( 'action', 'save_post_type_data' );
            
            if ( this.listing_type_id ) {
                form_data.append( 'listing_type_id', this.listing_type_id );
                this.footer_actions.save.label = 'Update';
            }

            let field_list = [];
            let skipped_fields = [];
            let log = [];

            if ( config.fields_group && typeof config.fields_group === 'object' ) {
                for ( let group_key in config.fields_group ) {
                    let group_value = {};

                    for ( let field_key in config.fields_group[ group_key ]  ) {
                        let field_key_value = config.fields_group[ group_key ][field_key];
                        let field_key_type  = typeof config.fields_group[ group_key ][field_key];
                        
                        if ( 'string' === field_key_type && typeof fields[ field_key_value ] !== 'undefined' ) {
                            group_value[ field_key_value ] = fields[ field_key_value ].value;
                            skipped_fields.push( field_key_value );
                        }

                        if ( 'object' === field_key_type ) {
                            group_value[ field_key ] = {};

                            for ( let sub_field_key of field_key_value ) {
                                group_value[ field_key ][ sub_field_key ] = fields[ sub_field_key ].value;
                                skipped_fields.push( sub_field_key );
                            }
                        }
                    }
                    
                    form_data.append( group_key, JSON.stringify( group_value ) );
                    field_list.push( group_key );
                }
            }

            for ( let field in fields ) {
                if ( skipped_fields.indexOf( field ) !== -1 ) { continue; }

                let value = this.maybeJSON( fields[ field ].value );
                form_data.append( field, value );
                field_list.push( field );
                log.push( field, value  );
            }

            // console.log( field_list, skipped_fields );
            form_data.append( 'field_list', JSON.stringify( field_list ) );

            this.status_messages = [];
            this.footer_actions.save.showLoading = true;
            this.footer_actions.save.isDisabled = true;
            const self = this;

            // console.log( { log } );

            // return;
            axios.post( ajax_data.ajax_url, form_data )
                .then( response => {
                    self.footer_actions.save.showLoading = false;
                    self.footer_actions.save.isDisabled = false;

                    // console.log( response.data );
                    // return;
                    
                    if ( response.data.post_id && ! isNaN( response.data.post_id ) ) {
                        self.listing_type_id = response.data.post_id;
                        self.footer_actions.save.label = 'Update';
                        self.listing_type_id = response.data.post_id;
                        // window.location = response.data.redirect_url;
                    }

                    if ( response.data.status_log ) {
                        for ( let status_key in response.data.status_log  ) {
                            self.status_messages.push({ 
                                type: response.data.status_log[ status_key ].type, 
                                message: response.data.status_log[ status_key ].message
                            });
                        }

                        setTimeout( function() {
                            self.status_messages = [];
                        }, 5000 );
                    }

                    // console.log( response );
                })
                .catch( error => {
                    self.footer_actions.save.showLoading = false;
                    self.footer_actions.save.isDisabled = false;
                    console.log( error );
                });
        },

        maybeJSON( data ) {
            let value = ( typeof data === 'undefined' ) ? '' : data;

            if ( 'object' === typeof value ) {
                value = JSON.stringify( value );
            }

            return value;
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