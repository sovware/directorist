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

            <div class="cptm-option-card-header-nav-section">
                <ul class="cptm-option-card-header-nav">
                    <li class="cptm-option-card-header-nav-item active">Preset Field</li>
                    <li class="cptm-option-card-header-nav-item">Custom Field</li>
                </ul>
            </div>
        </div>

        <div class="cptm-option-card-body">
            <div v-if="infoTexts.length" class="cptm-info-text-area">
                <p class="cptm-info-text" :class="'cptm-' + info.type" v-for="( info, text_key ) in infoTexts" :key="text_key">
                    {{ info.text }}
                </p>
            </div>

            <ul class="cptm-form-builder-field-list" v-if="Object.keys( unSelectedWidgetsList ).length">
                <li class="cptm-form-builder-field-list-item clickable" v-for="(widget, widget_key) in unSelectedWidgetsList" :key="widget_key" @click="selectWidget( widget_key )">
                    <span class="cptm-form-builder-field-list-icon" v-html="widget.icon"></span>
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

    model: {
        prop: 'active',
        event: 'update'
    },

    props: {
        id: {
            type: [ String, Number],
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
        selectedWidgets: {
            type: Array,
        },
    },

    mounted() {
        this.init();
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
                .filter( key => accepted_widgets.includes( key ) )
                .reduce(( obj, key ) => {
                    obj[ key ] = availableWidgets[ key ];

                    return obj;
                }, {});
    
            return widgets_list;
        },

        unSelectedWidgetsList() {
            const self = this;

            // console.log( this.id, self.widgetsList );
            if ( ! Object.keys( self.widgetsList ).length ) { return {}; }

            let widgets_list = Object.keys( self.widgetsList )
                .filter( key => ! self.localSelectedWidgets.includes( key ) )
                .reduce(( obj, key ) => {
                    obj[ key ] = self.widgetsList[ key ];

                    return obj;
                }, {});

            return widgets_list;
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
            infoTexts: [
                // { type: 'info', text: 'Up to 2 items can be added' }
            ],

            localSelectedWidgets: [],
        }
    },

    methods: {
        init() {
            if ( typeof this.selectedWidgets !== 'object' ) {
                return;
            }
            
            let unique_selecte_widgets = new Set( this.selectedWidgets );
            this.localSelectedWidgets = [ ...unique_selecte_widgets ];
        },

        selectWidget( key ) {
            let current_index = this.localSelectedWidgets.indexOf( key );
            
            if ( current_index != -1 ) {
                this.localSelectedWidgets.splice( current_index, 1 );
                return;
            }

            this.localSelectedWidgets.push( key );

            this.$emit( 'widget-selection', { 
                key, selected_widgets: this.localSelectedWidgets
            });
        }
    },
}
</script>