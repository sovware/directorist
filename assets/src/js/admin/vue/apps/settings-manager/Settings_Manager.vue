<template>
    <div class="settings-wrapper atbdp-settings-panel">
        <form action="#" @submit.prevent="updateData">
            <nav class="settings-panel-breadcrumb" aria-label="Settings breadcrumb">
                <template v-for="( item, index ) in theBreadcrumbNav">
                    <span
                        :key="`settings-breadcrumb-${index}`"
                        class="settings-panel-breadcrumb__item"
                        :class="{ active: item.active }"
                    >
                        {{ item.label }}
                    </span>
                </template>
            </nav>

            <div class="setting-body" @click="resetStates()">
                <sidebar-navigation
                    :menu="layouts"
                    :search-query="search_query"
                    :search-suggestions="searchSuggestions"
                    @update-search-query="search_query = $event"
                    @jump-to-search-result="jumpToSearchResult"
                />

                <div class="settings-contents">
                    <tabContents @do-action="doAction( $event )" />

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

                            <span
                                class="settings-footer-unsaved"
                                v-if="hasUnsavedChanges"
                            >
                                <span class="settings-footer-unsaved__dot" aria-hidden="true"></span>
                                Unsaved changes
                            </span>

                            <button 
                                type="submit" 
                                class="settings-save-btn"
                                :disabled="saveButtonIsDisabled"
                                v-html="submit_button.label">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div
            class="cptm-modal-container cptm-toggle-modal settings-leave-modal active"
            v-if="leaveConfirmIsOpen"
            @keydown.esc.prevent.stop="stayOnSettingsPage"
        >
            <div class="cptm-modal-wrap" @click.self="stayOnSettingsPage">
                <div
                    class="cptm-modal"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="settings-leave-modal-title"
                    aria-describedby="settings-leave-modal-description"
                    @click.stop
                >
                    <div class="cptm-modal-content">
                        <div class="cptm-modal-header">
                            <h3
                                id="settings-leave-modal-title"
                                class="cptm-modal-header-title"
                            >
                                Unsaved changes
                            </h3>

                            <div class="cptm-modal-actions">
                                <a
                                    href="#"
                                    class="cptm-modal-action-link"
                                    ref="leaveConfirmClose"
                                    aria-label="Stay on this page"
                                    @click.prevent="stayOnSettingsPage"
                                >
                                    <span class="fa fa-times"></span>
                                </a>
                            </div>
                        </div>

                        <div class="cptm-modal-body cptm-center-content cptm-content-wide">
                            <form
                                action="#"
                                method="post"
                                class="cptm-import-directory-form"
                                @submit.prevent="saveAndLeave"
                            >
                                <div
                                    class="cptm-form-group-feedback cptm-text-center cptm-mb-10 settings-leave-modal__error"
                                    v-if="leaveModalErrorMessage"
                                >
                                    {{ leaveModalErrorMessage }}
                                </div>

                                <h2
                                    id="settings-leave-modal-description"
                                    class="cptm-modal-confirmation-title"
                                >
                                    Save changes before leaving?
                                </h2>

                                <p class="settings-leave-modal__description">
                                    Unsaved changes will be lost.
                                </p>

                                <div class="cptm-file-input-wrap">
                                    <button
                                        type="button"
                                        class="cptm-btn cptm-btn-rounded cptm-btn-secondery"
                                        :disabled="leaveSaveIsProcessing"
                                        @click="leaveWithoutSaving"
                                    >
                                        Leave without saving
                                    </button>

                                    <button
                                        type="submit"
                                        class="cptm-btn cptm-btn-rounded cptm-btn-primary"
                                        :disabled="leaveSaveIsProcessing"
                                    >
                                        <span
                                            class="settings-leave-modal__spinner"
                                            v-if="leaveSaveIsProcessing"
                                            aria-hidden="true"
                                        ></span>
                                        {{ leaveSaveButtonLabel }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import { mapState } from 'vuex';
import { mapGetters } from 'vuex';
import tabContents from './TabContents.vue';
import {
    applySettingsRedesignFieldOverrides,
    buildSettingsRedesignLayout,
    resolveSettingsHashTarget,
} from './settings-redesign-map';

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

        theBreadcrumbNav() {
            let nav = [{ label: 'Settings' }];

            for ( let menu_key in this.layouts ) {
                if ( ! this.layouts[ menu_key ].active ) { continue; }

                let label = ( this.layouts[ menu_key ].label ) ? this.layouts[ menu_key ].label : '';
                let menu_nav_args = { label: label };

                if ( ! this.layouts[ menu_key ].submenu ) {
                    menu_nav_args.active = true;
                }

                nav.push( menu_nav_args );

                for ( let submenu_key in this.layouts[ menu_key ].submenu ) {
                    if ( ! this.layouts[ menu_key ].submenu[ submenu_key ].active ) { continue; }

                    let label = ( this.layouts[ menu_key ].submenu[ submenu_key ].label ) ? this.layouts[ menu_key ].submenu[ submenu_key ].label : '';
                    let sub_nav_args = { label: label, active: true };
                    nav.push( sub_nav_args );
                }
            }

            return nav;
        },

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

            if ( ! Object.keys( search_suggestions ).length ) {
                return false;
            }

            return search_suggestions;
        },

        hasUnsavedChanges() {
            for ( let field_key in this.fields ) {
                if ( ! this.cached_fields[ field_key ] ) { continue; }

                if (
                    ! this.settingsValuesAreSame(
                        this.cached_fields[ field_key ].value,
                        this.fields[ field_key ].value
                    )
                ) {
                    return true;
                }
            }

            return false;
        },

        saveButtonIsDisabled() {
            return this.submit_button.is_disabled || ! this.hasUnsavedChanges;
        },

        leaveSaveButtonLabel() {
            return this.leaveSaveIsProcessing ? 'Saving...' : 'Save & leave';
        }
    },

    mounted() {
        document.body.classList.add( 'directorist-settings-redesign-page' );
        document.addEventListener( 'click', this.handleDocumentNavigationClick, true );
        window.addEventListener( 'beforeunload', this.handleBeforeUnload );
    },

    beforeDestroy() {
        document.body.classList.remove( 'directorist-settings-redesign-page' );
        document.removeEventListener( 'click', this.handleDocumentNavigationClick, true );
        window.removeEventListener( 'beforeunload', this.handleBeforeUnload );
    },

    created() {
        const redesignedFields = applySettingsRedesignFieldOverrides( this.$root.fields || {} );

        if ( this.$root.fields ) {
            this.$store.commit( 'updateFields', redesignedFields );
        }

        if ( this.$root.layouts ) {
            this.$store.commit(
                'updatelayouts',
                buildSettingsRedesignLayout( this.$root.layouts, redesignedFields )
            );
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
            leaveConfirmIsOpen: false,
            pendingNavigationUrl: '',
            leaveGuardBypass: false,
            leaveSaveIsProcessing: false,
            leaveModalErrorMessage: '',
            lastLeaveTrigger: null,

            submit_button: {
                label_default: 'Save changes',
                label_on_progress: '<i class="fas fa-circle-notch fa-spin"></i> Saving...',
                label: 'Save changes',
                is_disabled: false,
            },
        }
    },

    methods: {
        ...mapGetters([
            'getFieldsValue'
        ]),

        doAction( payload ) {
            if ( ! payload.action ) { return; }
            if ( typeof this[ payload.action ] !== "function" ) { return; }

            this[ payload.action ]( payload.args );
        },

        testTask( args ) {
            console.log( 'Working...', args );
        },

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

        handleDocumentNavigationClick( event ) {
            if ( this.leaveGuardBypass || ! this.hasUnsavedChanges ) { return; }
            if ( this.leaveConfirmIsOpen ) { return; }
            if ( event.defaultPrevented || 0 !== event.button ) { return; }
            if ( event.metaKey || event.ctrlKey || event.shiftKey || event.altKey ) { return; }
            if ( ! event.target || ! event.target.closest ) { return; }

            const link = event.target.closest( 'a[href]' );

            if ( ! link ) { return; }
            if ( this.$el && this.$el.contains( link ) ) { return; }
            if ( this.linkShouldBypassLeaveGuard( link ) ) { return; }

            const navigation_url = this.getNavigationUrlFromLink( link );

            if ( ! navigation_url ) { return; }

            event.preventDefault();
            event.stopPropagation();

            this.openLeaveConfirm( navigation_url, link );
        },

        handleBeforeUnload( event ) {
            if ( this.leaveGuardBypass || ! this.hasUnsavedChanges ) { return; }

            event.preventDefault();
            event.returnValue = '';

            return '';
        },

        linkShouldBypassLeaveGuard( link ) {
            const target = ( link.getAttribute( 'target' ) || '' ).toLowerCase();

            if ( target && '_self' !== target ) { return true; }
            if ( link.hasAttribute( 'download' ) ) { return true; }

            return false;
        },

        getNavigationUrlFromLink( link ) {
            let href = link.getAttribute( 'href' );

            if ( ! href ) { return ''; }

            href = href.trim();

            if ( ! href || '#' === href || '#' === href.charAt( 0 ) ) { return ''; }

            const lower_href = href.toLowerCase();

            if (
                0 === lower_href.indexOf( 'javascript:' ) ||
                0 === lower_href.indexOf( 'mailto:' ) ||
                0 === lower_href.indexOf( 'tel:' )
            ) {
                return '';
            }

            let target_url = null;

            try {
                target_url = new URL( href, window.location.href );
            } catch ( error ) {
                return '';
            }

            if ( target_url.href === window.location.href ) { return ''; }

            const current_url = new URL( window.location.href );
            const is_same_page_hash =
                target_url.origin === current_url.origin &&
                target_url.pathname === current_url.pathname &&
                target_url.search === current_url.search &&
                target_url.hash;

            if ( is_same_page_hash ) { return ''; }

            return target_url.href;
        },

        openLeaveConfirm( navigation_url, trigger ) {
            this.pendingNavigationUrl = navigation_url;
            this.lastLeaveTrigger = trigger || null;
            this.leaveModalErrorMessage = '';
            this.leaveConfirmIsOpen = true;

            this.$nextTick( () => {
                if ( this.$refs.leaveConfirmClose && this.$refs.leaveConfirmClose.focus ) {
                    this.$refs.leaveConfirmClose.focus();
                }
            });
        },

        stayOnSettingsPage() {
            if ( this.leaveSaveIsProcessing ) { return; }

            this.leaveConfirmIsOpen = false;
            this.pendingNavigationUrl = '';
            this.leaveModalErrorMessage = '';
            this.restoreLeaveTriggerFocus();
        },

        restoreLeaveTriggerFocus() {
            const trigger = this.lastLeaveTrigger;
            this.lastLeaveTrigger = null;

            this.$nextTick( () => {
                if ( trigger && document.body.contains( trigger ) && trigger.focus ) {
                    trigger.focus();
                }
            });
        },

        leaveWithoutSaving() {
            if ( ! this.pendingNavigationUrl ) { return; }

            this.leaveGuardBypass = true;
            window.location.href = this.pendingNavigationUrl;
        },

        saveAndLeave() {
            if ( this.leaveSaveIsProcessing ) { return; }
            if ( ! this.pendingNavigationUrl ) { return; }

            this.leaveSaveIsProcessing = true;
            this.leaveModalErrorMessage = '';

            this.saveSettingsData()
                .then( () => {
                    this.leaveSaveIsProcessing = false;
                    this.leaveGuardBypass = true;
                    window.location.href = this.pendingNavigationUrl;
                })
                .catch( error => {
                    this.leaveSaveIsProcessing = false;
                    this.leaveModalErrorMessage = error && error.message
                        ? error.message
                        : 'Could not save changes. Please try again.';
                });
        },

        updateCurrentPage() {
            var hash = window.location.hash;

            if ( typeof hash !== 'string' ) { return; }

            hash = hash.replace( /#/g, '' );
            const swich_nav_args = resolveSettingsHashTarget(
                hash,
                this.layouts,
                this.cached_fields
            );

            if ( ! swich_nav_args ) { return; }

            this.$store.commit( 'swichToNav', swich_nav_args );

        },

        getSettingsSavePayload() {
            if ( ! this.hasUnsavedChanges ) {
                return {
                    form_data: null,
                    field_list: [],
                    changed_fields: {},
                    error_count: 0,
                };
            }

            let fields = this.getFieldsValue();
            let form_data = new FormData();
            let field_list = [];
            let error_count = 0;
            let changed_fields = {};

            for ( let field_key in fields ) {
                if ( ! this.fields[ field_key ] ) { continue; }
                if ( ! this.cached_fields[ field_key ] ) { continue; }

                let new_value    = fields[ field_key ];
                let cahced_value = this.cached_fields[ field_key ].value;

                if ( this.fields[ field_key ].validationState && this.fields[ field_key ].validationState.hasError ) {
                    error_count++;
                }

                if (
                    ! this.fields[ field_key ].forceUpdate &&
                    this.settingsValuesAreSame( cahced_value, new_value )
                ) {
                    continue;
                }

                form_data.append( field_key, this.maybeJSON( [new_value] ) );
                field_list.push( field_key );
                changed_fields[ field_key ] = new_value;
            }

            form_data.append( 'field_list', this.maybeJSON( field_list ) );

            return {
                form_data,
                field_list,
                changed_fields,
                error_count,
            };
        },

        updateData( args ) {
            this.saveSettingsData()
                .then( () => {
                    if ( args && args.reload_after_save ) {
                        window.location.reload();
                    }
                })
                .catch( () => {} );
        },

        saveSettingsData() {
            if ( this.form_is_processing ) {
                return Promise.reject( new Error( 'Please wait...' ) );
            }

            if ( ! this.hasUnsavedChanges ) {
                this.status_message = null;

                return Promise.resolve( { skipped: true } );
            }

            let submission_url  = ( this.$store.state.config && this.$store.state.config.submission && this.$store.state.config.submission.url ) ? this.$store.state.config.submission.url : '';
            let submission_with = ( this.$store.state.config && this.$store.state.config.submission && this.$store.state.config.submission.with ) ? this.$store.state.config.submission.with : '';
            let payload = this.getSettingsSavePayload();

            if ( ! payload.field_list.length ) {
                this.status_message = null;

                return Promise.resolve( { skipped: true } );
            }

            if ( payload.error_count ) {
                const message = 'The form has invalid data';

                this.status_message = {
                    type: 'error',
                    message,
                };

                let self = this;
                setTimeout( function() {
                    self.status_message = null;
                }, 5000 );

                return Promise.reject( new Error( message ) );
            }

            if ( submission_with && typeof submission_with === 'object' ) {
                for ( let data_key in submission_with ) {
                    payload.form_data.append( data_key, submission_with[ data_key ] );
                }
            }

            // Before submit the form
            this.form_is_processing        = true;
            this.submit_button.is_disabled = true;
            this.submit_button.label       = this.submit_button.label_on_progress;
            this.status_message            = null;
            
            const self = this;

            // Submit the form
            return axios.post( submission_url, payload.form_data )
                .then( response => {
                    self.form_is_processing        = false;
                    self.submit_button.is_disabled = false;
                    self.submit_button.label       = self.submit_button.label_default;

                    const response_status = response && response.data ? response.data.status : null;

                    if ( ! response_status ) {
                        const message = self.getSaveResponseMessage( response ? response.data : null );

                        self.status_message = {
                            type: 'error',
                            message,
                        };

                        const error = new Error( message );
                        error.directoristHandled = true;
                        throw error;
                    }

                    if ( response_status && ! response_status.success ) {
                        const message = response_status.status_log && response_status.status_log.message
                            ? response_status.status_log.message
                            : self.getSaveResponseMessage( response.data );

                        self.status_message = {
                            ...( response_status.status_log || {} ),
                            type: 'error',
                            message,
                        };

                        const error = new Error( message );
                        error.directoristHandled = true;
                        throw error;
                    }

                    if ( response_status && response_status.success ) {
                        Object.keys( payload.changed_fields ).forEach( function( field_key ) {
                            self.$store.commit( 'updateCachedFieldData', {
                                key: field_key,
                                value: payload.changed_fields[ field_key ],
                            });
                        });
                    }

                    if ( response_status && response_status.status_log ) {
                        if ( 'success' === response_status.status_log.type ) {
                            self.status_message = null;
                        } else {
                            self.status_message = response_status.status_log;

                            setTimeout( function() {
                                self.status_message = null;
                            }, 5000 );
                        }
                    }

                    return {
                        success: true,
                        response,
                    };
                })
                .catch( error => {
                    self.form_is_processing        = false;
                    self.submit_button.is_disabled = false;
                    self.submit_button.label       = self.submit_button.label_default;

                    if ( ! error.directoristHandled ) {
                        console.log( { error } );

                        self.status_message = {
                            type: 'error',
                            message: self.getSaveErrorMessage( error ),
                        };

                        setTimeout( function() {
                            self.status_message = null;
                        }, 5000 );
                    }

                    return Promise.reject( error );
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

        getSaveResponseMessage( response_data ) {
            if ( response_data && response_data.status && response_data.status.status_log && response_data.status.status_log.message ) {
                return response_data.status.status_log.message;
            }

            if ( response_data && response_data.status_log && response_data.status_log.message ) {
                return response_data.status_log.message;
            }

            if ( response_data && response_data.message ) {
                return response_data.message;
            }

            if ( typeof response_data === 'string' ) {
                let message = response_data.replace( /<[^>]*>/g, ' ' ).replace( /\s+/g, ' ' ).trim();

                if ( message.length ) {
                    return message.substring( 0, 180 );
                }
            }

            return 'Unexpected save response. Please refresh and try again.';
        },

        getSaveErrorMessage( error ) {
            let response_data = error && error.response ? error.response.data : null;

            if ( response_data && response_data.status && response_data.status.status_log ) {
                return response_data.status.status_log.message || 'Something went wrong';
            }

            if ( response_data && response_data.message ) {
                return response_data.message;
            }

            if ( typeof response_data === 'string' ) {
                let message = response_data.replace( /<[^>]*>/g, ' ' ).replace( /\s+/g, ' ' ).trim();

                if ( message.length ) {
                    return message.substring( 0, 180 );
                }
            }

            if ( error && error.message ) {
                return error.message;
            }

            return 'Something went wrong';
        },

        settingsValuesAreSame( old_value, new_value ) {
            const old_is_object = old_value && typeof old_value === 'object';
            const new_is_object = new_value && typeof new_value === 'object';

            if ( old_is_object || new_is_object ) {
                return JSON.stringify( old_value || null ) === JSON.stringify( new_value || null );
            }

            return old_value == new_value;
        },

        maybeJSON( data ) {
            let value = ( typeof data === 'undefined' ) ? '' : data;

            if ( 'object' === typeof value && Object.keys( value ) || Array.isArray( value ) ) {
                let json_encoded_value = JSON.stringify( value );
                let base64_encoded_value = this.encodeUnicodedToBase64( json_encoded_value );
                value = base64_encoded_value;
            }

            return value;
        },

        encodeUnicodedToBase64(str) {
            // first we use encodeURIComponent to get percent-encoded UTF-8,
            // then we convert the percent encodings into raw bytes which
            // can be fed into btoa.
            return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
                function toSolidBytes(match, p1) {
                    return String.fromCharCode('0x' + p1);
            }));
        }
    }
}
</script>
