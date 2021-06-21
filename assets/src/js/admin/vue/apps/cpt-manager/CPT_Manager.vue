<template>
    <div class="atbdp-cpt-manager cptm-p-20">

        <!-- atbdp-cptm-header -->
        <div class="atbdp-cptm-header">
            <component 
                v-if="options.name && options.name.type" 
                :is="options.name.type + '-field'" 
                v-bind="options.name"
                @update="updateOptionsField( { field: 'name', value: $event } )"
            />

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

    computed: {
        ...mapState({
            options: 'options',
        })
    },

    created() {
        if ( this.$root.fields ) {
            this.$store.commit( 'updateFields', this.$root.fields );
        }

        if ( this.$root.layouts ) {
            this.$store.commit( 'updatelayouts', this.$root.layouts );
        }

        if ( this.$root.options ) {
            this.$store.commit( 'updateOptions', this.$root.options );
        }

        // console.log( this.options );

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
        ...mapGetters([
            'getFieldsValue'
        ]),

        updateOptionsField( payload ) {
            if ( ! payload.field ) { return; }
            if ( typeof payload.value === 'undefined' ) { return; }

            this.$store.commit( 'updateOptionsField', payload );
        },      

        saveData() {
            let options = this.$store.state.options;
            let fields  = this.$store.state.fields;

            let form_data = new FormData();
            form_data.append( 'action', 'save_post_type_data' );
            
            if ( this.listing_type_id ) {
                form_data.append( 'listing_type_id', this.listing_type_id );
                this.footer_actions.save.label = 'Update';
            }

            // Get Options Fields Data
            let options_field_list = [];
            for ( let field in options ) {
                let value = this.maybeJSON( options[ field ].value );

                form_data.append( field, value );
                options_field_list.push( field );
            }

            // Get Form Fields Data
            let field_list = [];
            for ( let field in fields ) {
                let value = this.maybeJSON( fields[ field ].value );

                form_data.append( field, value );
                field_list.push( field );
            }

            let field_list_encoded = this.maybeJSON( field_list )
            form_data.append( 'field_list', field_list_encoded );

            this.status_messages = [];
            this.footer_actions.save.showLoading = true;
            this.footer_actions.save.isDisabled = true;
            const self = this;

            // return;
            axios.post( ajax_data.ajax_url, form_data )
                .then( response => {
                    self.footer_actions.save.showLoading = false;
                    self.footer_actions.save.isDisabled = false;

                    console.log( response );
                    // return;
                    
                    if ( response.data.term_id && ! isNaN( response.data.term_id ) ) {
                        self.listing_type_id = response.data.term_id;
                        self.footer_actions.save.label = 'Update';
                        self.listing_type_id = response.data.term_id;

                        if ( response.data.redirect_url ) {
                            window.location = response.data.redirect_url;
                        }
                    }

                    if ( response.data.status && response.data.status.status_log ) {
                        for ( let status_key in response.data.status.status_log  ) {
                            self.status_messages.push({ 
                                type: response.data.status.status_log[ status_key ].type, 
                                message: response.data.status.status_log[ status_key ].message
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

            if ( 'object' === typeof value && Object.keys( value ) || Array.isArray( value ) ) {
                let value_stringify = JSON.stringify( value );
                let value_base64 = btoa( value_stringify );
                value = value_base64;
            }

            return value;
        },

        sazitizeForJSON( data ) {
            let sazitizedData = data;
            
            if ( typeof sazitizedData == 'string' ) {
                sazitizedData = this.sazitizeString( sazitizedData );
            }
            
            if ( sazitizedData && typeof sazitizedData == 'object' ) {
                sazitizedData = this.sazitizeObject( sazitizedData );
            }
            
            if ( Array.isArray( sazitizedData ) ) {
                sazitizedData = this.sazitizeArray( sazitizedData );
            }
        
            return sazitizedData;
        },
        
        sazitizeObject( objectData ) {
            let sazitizedData = objectData;
        
            for ( let data_key in sazitizedData ) {

                if ( typeof sazitizedData[ data_key ] == 'string' ) {
                    sazitizedData[ data_key ] = this.sazitizeString( sazitizedData[ data_key ] );
                }
            
                if (  sazitizedData[ data_key ] && typeof sazitizedData[ data_key ] == 'object' ) {
                    sazitizedData[ data_key ] = this.sazitizeObject( sazitizedData[ data_key ] );
                }
                
                if ( Array.isArray( sazitizedData[ data_key ] ) ) {
                    sazitizedData[ data_key ] = this.sazitizeArray( sazitizedData[ data_key ] );
                }
            }
        
            return sazitizedData;
        },
        
        sazitizeArray( arrayData ) {
            let sazitizedData = arrayData;
            
            let count = 0;
            for ( let data_item of sazitizedData ) {
                if ( typeof data_item == 'string' ) {
                    sazitizedData[ count ] = this.sazitizeString( sazitizedData[ count ] );
                }
            
                if (  sazitizedData[ count ] && typeof sazitizedData[ count ] == 'object' ) {
                    sazitizedData[ count ] = this.sazitizeObject( sazitizedData[ count ] );
                }
                
                if ( Array.isArray( sazitizedData[ count ] ) ) {
                    sazitizedData[ count ] = this.sazitizeArray( sazitizedData[ count ] );
                }
                
                count++;
            }
        
            return sazitizedData;
        },

        sazitizeString( stringData ) {
            return stringData.replace( /"/g, '`DRST_QT');
            // return stringData.replace( /"/g, '`');
            // return stringData.replace( /"/g, '`');
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