<template>
    <div class="settings-wrapper atbdp-settings-panel">
        <div class="setting-top-bar">
            <div class="setting-top-bar__search-field">
                <input type="text" class="setting-search-field__input" placeholder="Search settings here...">
            </div>
            <a href="#" class="settings-save-btn">Save Changes</a>
        </div>

        <div class="setting-body">
            <sidebar-navigation :menu="layouts" />

            <div class="settings-contents">
                <tabContents />
            </div>
        </div>
    </div>
</template>


<script>
import { mapState } from 'vuex';
import { mapGetters } from 'vuex';
import tabContents from './TabContents.vue';

const axios = require('axios').default;

export default {
    name: 'settings-manager',

    components: {
        tabContents,
    },

    computed: {
        ...mapState({
            fields: 'fields',
            layouts: 'layouts',
        })
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

        this.$store.commit( 'prepareNav' );
        // console.log( this.layouts );
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
        ...mapGetters([
            'getFieldsValue'
        ]),

        getSidebarNav() {
            let sidebar_nav = {};
            let layouts = this.layouts;

            console.log( { layouts } );
            
            for ( let menu_key in this.layouts ) {
                let menu_args = { active: false };
                let menu_item = this.layouts[ menu_key ];

                for ( let menu_opt_key in menu_item ) {
                    // If has submenu
                    if ( 'submenu' === menu_opt_key ) {
                        let submenu = {};
                        
                        for ( let submenu_key in menu_item[ menu_opt_key ] ) {
                            let submenu_item = submenu[ submenu_key ];
                            let submenu_args = { active: false };

                            for ( let submenu_opt_key in submenu_item ) {
                                if ( 'sections' === submenu_opt_key ) { continue; }
                                
                                submenu_args[ submenu_opt_key ] = submenu_item[ submenu_opt_key ];
                            }

                            submenu[ submenu_key ] = submenu_args;
                        }

                        menu_args[ menu_opt_key ] = submenu;
                        continue;
                    }

                    menu_args[ menu_opt_key ] = menu_item[ menu_opt_key ];
                }

                sidebar_nav[ menu_key ] = menu_args;
            }

            return sidebar_nav;
        },

        updateData() {
            console.log( 'updateData' );
            return;
            let fields = this.getFieldsValue();

            let submission_url = this.$store.state.config.submission.url;
            let submission_with = this.$store.state.config.submission.with;

            let form_data = new FormData();

            if ( submission_with && typeof submission_with === 'object' ) {
                for ( let data_key in submission_with ) {
                    form_data.append( data_key, submission_with[ data_key ] );
                }
            }
            
            if ( this.listing_type_id ) {
                form_data.append( 'listing_type_id', this.listing_type_id );
                this.footer_actions.save.label = 'Update';
            }

            for ( let field_key in fields ) {
                let value = this.maybeJSON( fields[ data_key ] );
                form_data.append( data_key,  value );
            }

            console.log( { submission_url, submission_with } );
        },

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