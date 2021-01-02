<template>
    <div class="settings-wrapper atbdp-settings-panel">
        <form action="#" @submit.prevent="updateData">
            <div class="setting-top-bar">
                <div class="setting-top-bar__search-field">
                    <input type="text" class="setting-search-field__input" placeholder="Search settings here...">
                </div>

                <div class="setting-top-bar__search-actions">
                    <div class="setting-response-feedback">
                        <div class="" v-if="status_message">
                            <span class="atbdp-icon atbdp-icon-fill"
                                :class="getIconClass( status_message.type )"
                                v-html="getIconHTML( status_message.type )"
                            >
                            </span>

                            {{ status_message.message }}
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        class="settings-save-btn"
                        :disabled="submit_button.is_disabled"
                        v-html="submit_button.label">
                    </button>
                </div>
                
            </div>

            <div class="setting-body">
                <sidebar-navigation :menu="layouts" />

                <div class="settings-contents">
                    <tabContents />
                </div>
            </div>
        </form>
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

        this.$store.commit( 'prepareNav' );
    },

    data() {
        return {
            status_message: null,
            form_is_processing: false,
            submit_button: {
                label_default: 'Save Changes',
                label_on_progress: '<i class="fas fa-circle-notch fa-spin"></i> Savivg...',
                label: 'Save Changes',
                is_disabled: false,
            },
        }
    },

    methods: {
        ...mapGetters([
            'getFieldsValue'
        ]),

        updateData() {
            if ( this.form_is_processing ) { console.log( 'Please wait...' ); return; }
            // console.log( 'updateData' );

            let fields = this.getFieldsValue();

            let submission_url  = ( this.$store.state.config && this.$store.state.config.submission && this.$store.state.config.submission.url ) ? this.$store.state.config.submission.url : '';
            let submission_with = ( this.$store.state.config && this.$store.state.config.submission && this.$store.state.config.submission.with ) ? this.$store.state.config.submission.with : '';

            let form_data = new FormData();

            if ( submission_with && typeof submission_with === 'object' ) {
                for ( let data_key in submission_with ) {
                    form_data.append( data_key, submission_with[ data_key ] );
                }
            }

            let field_list = [];
            for ( let field_key in fields ) {
                let value = this.maybeJSON( fields[ field_key ] );

                form_data.append( field_key, value );
                field_list.push( field_key );
            }

            form_data.append( 'field_list', JSON.stringify( field_list ) );

            // Before submit the form
            this.form_is_processing        = true;
            this.submit_button.is_disabled = true;
            this.submit_button.label       = this.submit_button.label_on_progress;
            this.status_message            = null;
            
            const self = this;

            // Submit the form
            axios.post( submission_url, form_data )
                .then( response => {
                    // console.log( { response } );

                    self.form_is_processing        = false;
                    self.submit_button.is_disabled = false;
                    self.submit_button.label       = self.submit_button.label_default;


                    if ( response.data.status && response.data.status.status_log ) {
                        self.status_message = response.data.status.status_log;

                        setTimeout( function() {
                            self.status_message = null;
                        }, 3000 );
                    }

                })
                .catch( error => {
                    console.log( { error } );

                    self.form_is_processing        = false;
                    self.submit_button.is_disabled = false;
                    self.submit_button.label       = self.submit_button.label_default;

                    self.status_message = { type: 'error', message: 'Something went wrong' };

                    setTimeout( function() {
                        self.status_message = null;
                    }, 3000 );
                });
        },

        getIconClass( icon_type ) {
            let icon = ( icon_type ) ? icon_type : '';
            let icon_class_name = { [`icon-${icon}`]: true };

            return icon_class_name;
        },

        getIconHTML( icon_type ) {
            let icon = '';

            switch ( icon_type ) {
                case 'error':
                    icon = '<i class="fas fa-times"></i>';
                    break;
                case 'success':
                    icon = '<i class="fas fa-check"></i>';
                    break;
            }

            return icon;
        },

        maybeJSON( data ) {
            let value = ( typeof data === 'undefined' ) ? '' : data;

            if ( 'object' === typeof value ) {
                value = JSON.stringify( value );
            }

            return value;
        },
    }
}
</script>