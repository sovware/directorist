<template>
    <div class="cptm-option-card" :class="mainWrapperClass">
        <div class="cptm-option-card-header">
            <div class="cptm-option-card-header-title-section">
                <h3 class="cptm-option-card-header-title">Insert Element</h3>

                <div class="cptm-header-action-area">
                    <a href="#" class="cptm-header-action-link cptm-header-action-close" @click.prevent="$emit( 'close' )">
                        <span class="fa fa-times"></span>
                    </a>
                </div>
            </div>

            <!-- <div class="cptm-option-card-header-nav-section">
                <ul class="cptm-option-card-header-nav">
                    <li class="cptm-option-card-header-nav-item active">Preset Field</li>
                    <li class="cptm-option-card-header-nav-item">Custom Field</li>
                </ul>
            </div> -->
        </div>

        <div class="cptm-option-card-body">
            <div v-if="infoTexts.length" class="cptm-info-text-area">
                <p class="cptm-info-text" :class="'cptm-' + info.type" v-for="( info, text_key ) in infoTexts" :key="text_key">
                    {{ info.text }}
                </p>
            </div>

            <ul class="cptm-form-builder-field-list" v-if="Object.keys( unSelectedWidgetsList ).length">
                <li class="cptm-form-builder-field-list-item" 
                    :class="widgetListClass( widget_key )"
                    v-for="(widget, widget_key) in unSelectedWidgetsList" 
                    :key="widget_key"
                    @click="selectWidget( widget_key )">
                        <pre>{{ widget.in_used }}</pre>
                        <span class="cptm-form-builder-field-list-icon">
                            <span :class="widget.icon"></span>
                        </span>
                        <span class="cptm-form-builder-field-list-label">
                            {{ widget.label }}
                        </span>
                </li>
               
            </ul>

            <p v-else class="cptm-info-text">Nothing available</p>
        </div>

        <span class="cptm-anchor-down" v-if="bottomAchhor"></span>
    </div>
</template>

<script>
export default {
    name: 'widgets-window',

    props: {
        id: {
            type: [ String, Number ],
            default: '',
        },
        active: {
            type: Boolean,
            default: false,
        },
        animation: {
            type: String,
            default: 'cptm-animation-slide-up',
        },
        bottomAchhor: {
            type: Boolean,
            default: false,
        },
        availableWidgets: {
            type: Object,
        },
        acceptedWidgets: {
            type: Array,
        },
        activeWidgets: {
            type: Object,
        },
        selectedWidgets: {
            type: Array,
        },
        maxWidget: {
            type: Number,
            default: 0, // Unlimitted
        },
        maxWidgetInfoText: {
            type: String,
            default: 'Up to __DATA__ item{s} can be added',
        },
    },

    created() {
        this.init();
    },

    watch: {
        selectedWidgets() {
            this.localSelectedWidgets = this.selectedWidgets;
        }
    },

    computed: {
        widgetsList() {
            if ( ! this.availableWidgets && typeof this.availableWidgets !== 'object' ) {
                return {};
            }

            if ( ! Object.keys( this.availableWidgets ).length ) {
                return {};
            }

            let availableWidgets = JSON.parse( JSON.stringify( this.availableWidgets ) );
            let accepted_widgets = this.acceptedWidgets;

            if ( ! accepted_widgets && typeof accepted_widgets !== 'object' ) {
                return availableWidgets;
            }

            if ( ! accepted_widgets.length ) {
                return availableWidgets;
            }

            let widgets_list = Object.keys( availableWidgets )
                .filter( key => accepted_widgets.includes( availableWidgets[ key ].widget_name ) )
                .reduce(( obj, key ) => {
                    obj[ key ] = availableWidgets[ key ];

                    return obj;
                }, {});
    
            return widgets_list;
        },

        unSelectedWidgetsList() {
            const self = this;

            if ( ! Object.keys( self.widgetsList ).length ) { return {}; }
            // Filter unselected widgets
            let widgets_list = Object.keys( self.widgetsList )
                .filter( key => ! self.localSelectedWidgets.includes( key ) && typeof self.activeWidgets[ key ] === 'undefined' )
                .reduce(( obj, key ) => {
                    obj[ key ] = self.widgetsList[ key ];

                    return obj;
                }, {});

            let active_widgets_keys = Object.keys( self.activeWidgets );

            return widgets_list;
        },

        maxWidgetLimitIsReached() {
            return this.maxWidget && ( this.localSelectedWidgets.length >= this.maxWidget);
        },

        infoTexts() {
            let info_texts = [];
        
            if ( this.maxWidgetLimitIsReached && Object.keys( this.unSelectedWidgetsList ).length ) {
                info_texts.push({
                    type: 'info', 
                    text: this.decodeInfoText( this.maxWidget, this.maxWidgetInfoText )
                });
            }

            return info_texts;
        },

        mainWrapperClass() {
            return {
                active: this.active,
                [ this.animation ]: true
            }
        }
    },

    data() {
        return {
            localSelectedWidgets: [],
        }
    },

    methods: {
        init() {
            if ( typeof this.selectedWidgets !== 'object' ) { return; }
            
            let unique_selecte_widgets = new Set( this.selectedWidgets );
            this.localSelectedWidgets = [ ...unique_selecte_widgets ];
        },

        close() {
            console.log( 'close' );
            this.$emit( 'close' );
        },

        decodeInfoText( data, text ) {
            let doceded = text.replace(/__DATA__/gi, data);
    
            const filter_single_pare = function( str ) {
            if ( data < 2 ) { return ''; }

                let filtered = str.replace( /{/gi, '' );
                filtered = filtered.replace( /}/gi, '' );

                return filtered;
            };

            const filter_double_pare = function( str ) {
                let pares = str.match( /\w+|w+/gi );
                if ( typeof pares !== 'object' && pares.length < 2 ) { return ''; }
                if ( data < 2 ) { return pares[0]; }
                
                return pares[1];
            };

            let filtered_single_pare = doceded.replace( /({\w+})/gi, filter_single_pare );
            let filtered_double_pare = filtered_single_pare.replace( /({\w+\|\w+})/gi, filter_double_pare );

            return filtered_double_pare;
        },

        selectWidget( key ) {
            if ( this.maxWidgetLimitIsReached ) { return; }
            if ( typeof this.activeWidgets[ key ] !== 'undefined' ) { return; }

            let current_index = this.localSelectedWidgets.indexOf( key );
            if ( current_index != -1 ) {
                this.localSelectedWidgets.splice( current_index, 1 );
                return;
            }

            this.localSelectedWidgets.push( key );
            this.$emit( 'widget-selection', { 
                key, selected_widgets: this.localSelectedWidgets
            });
        },


        widgetListClass( widget_key ) {
            return { 
                'hide': typeof this.activeWidgets[ widget_key ] !== 'undefined', 
                'disabled': this.maxWidgetLimitIsReached || typeof this.activeWidgets[ widget_key ] !== 'undefined', 
                'clickable': ! this.maxWidgetLimitIsReached 
            }
        },
    },
}
</script>