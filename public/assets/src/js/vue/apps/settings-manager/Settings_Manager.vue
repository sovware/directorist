<template>
    <div class="settings-wrapper atbdp-settings-panel">
        <form action="#" @submit.prevent="updateData">
            <div class="setting-top-bar">
                <div class="setting-top-bar__search-field">
                    <input 
                        type="text" 
                        class="setting-search-field__input" 
                        placeholder="Search settings here..."
                        v-model="search_query"
                    >

                    <div class="setting-search-suggestions" v-if="searchSuggestions">
                        <ul class="search-suggestions-list">
                            <li class="search-suggestions-list--list-item" v-for="( field_key, field_index ) in Object.keys( searchSuggestions )" :key="field_index">
                                <a href="#" class="search-suggestions-list--link" @click.prevent="jumpToSearchResult( searchSuggestions[ field_key ] )">
                                    {{ searchSuggestions[ field_key ].label }}
                                </a>
                            </li>
                        </ul>
                    </div>
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

            <div class="setting-body" @click="resetStates()">
                <sidebar-navigation :menu="layouts" />

                <div class="settings-contents">
                    <tabContents ref="tab_contents" />

                    <div class="settings-footer">
                        <div class="settings-footer-actions">
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
            cached_fields: 'cached_fields',
            layouts: 'layouts',
        }),

        searchSuggestions() {
            if ( ! this.search_query.length ) {
                return false;
            }

            let search_suggestions = {};
            let query = this.search_query.toLowerCase();

            for ( let field in this.cached_fields ) {
                if ( ! this.cached_fields[ field ].label ) { continue; }
                
                let label = this.cached_fields[ field ].label.toLowerCase();
                let match = label.match( query );

                if ( match ) {
                    search_suggestions[ field ] = this.cached_fields[ field ];
                }
            }

            // console.log( {search_suggestions}, this.cached_fields );

            if ( ! Object.keys( search_suggestions ) ) {
                return false;
            }

            return search_suggestions;
        }
    },

    mounted() {
        // const self = this;
        // this.$el.addEventListener( 'click', function( e ) {
        //     // self.$store.commit( 'resetHighlightedFieldKey' );
        // })
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

        this.$store.commit( 'cacheFieldsData' );
        this.$store.commit( 'prepareNav' );

        this.updateCurrentPage();
    },

    data() {
        return {
            status_message: null,
            form_is_processing: false,

            search_query: '',
            search_suggestions: false,

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

        resetStates() {
            this.$store.commit( 'resetHighlightedFieldKey' );
        },

        jumpToSearchResult( field ) {
            if ( ! field.layout_path ) { return; }

            this.$store.commit( 'swichToNav', {
                menu_key: field.layout_path.menu_key,
                submenu_key: field.layout_path.submenu_key,
                hash: field.layout_path.hash,
            });

            this.search_query = '';
        },

        updateCurrentPage() {
            var hash = window.location.hash;

            if ( typeof hash !== 'string' ) { return; }

            hash = hash.replace( /#/g, '' );
            var pages = hash.split('__');

            if ( ! pages ) { return; }
            if ( ! Array.isArray( pages ) ) { return; }
            
            let menu_keys        = Object.keys( this.layouts );
            let main_menu_key    = pages[0];
            let submain_menu_key = ( pages.length > 1 ) ? pages[1] : null;

            let swich_nav_args = { menu_key: '', submenu_key: '', hash: hash }; 

            if ( menu_keys.includes( main_menu_key ) ) {
                swich_nav_args.menu_key = main_menu_key;
            }

            if ( submain_menu_key && this.layouts[ main_menu_key ].submenu ) {
                let submenu_keys = Object.keys( this.layouts[ main_menu_key ].submenu );

                if ( submenu_keys.includes( submain_menu_key ) ) {
                    swich_nav_args.submenu_key = submain_menu_key;
                }
            }

            this.$store.commit( 'swichToNav', swich_nav_args );

        },

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
                let new_value    = this.maybeJSON( fields[ field_key ] );
                let cahced_value = this.maybeJSON( this.cached_fields[ field_key ].value );

                if ( cahced_value == new_value ) { continue; }

                form_data.append( field_key, new_value );
                field_list.push( field_key );

                this.$store.commit( 'updateCachedFieldData', {
                    key: field_key,
                    value: new_value,
                });
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
                        }, 5000 );
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
                    }, 5000 );
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