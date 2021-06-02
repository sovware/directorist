<template>
    <div class="cptm-form-builder-active-fields-group">
        <!-- Group Header -->
        <form-builder-widget-group-header-component 
            v-bind="$props"
            :widgets-expanded="widgetsExpandState"
            :can-trash="canTrashGroup"
            :current-dragging-group="currentDraggingGroup"
            @update-group-field="$emit( 'update-group-field', $event )"
            @toggle-expand-widgets="toggleExpandWidgets"
            @trash-group="$emit( 'trash-group' )"
            @drag-start="$emit( 'group-drag-start' )"
            @drag-end="$emit( 'group-drag-end' )"
        />
        
        <!-- Group Body -->
        <slide-up-down :active="widgetsExpandState" :duration="500">
            <div class="cptm-form-builder-group-fields">
                <draggable-list-item-wrapper list-id="widget-item" :is-dragging-self="currentDraggingWidget && ( 'active_widgets' === currentDraggingWidget.from && widget_key === currentDraggingWidget.widget_key)" v-for="( widget_key, widget_index ) in groupData.fields" :key="widget_index" 
                    class-name="directorist-draggable-form-list-wrap" 
                    :droppables="true"
                    :droppable="isDroppable( widget_index )"
                    @drop="$emit( 'drop-widget', { widget_key, widget_index, drop_direction: $event.drop_direction } )"
                >
                    <form-builder-widget-component
                        :widget-key="widget_key"
                        :active-widgets="activeWidgets"
                        :avilable-widgets="avilableWidgets"
                        :group-data="groupData"
                        :is-enabled-group-dragging="isEnabledGroupDragging"
                        :untrashable-widgets="untrashableWidgets"
                        @found-untrashable-widget="updateDetectedUntrashableWidgets( widget_key )"
                        @update-widget-field="$emit( 'update-widget-field', $event )"
                        @trash-widget="$emit( 'trash-widget', { widget_key } )"
                        @drag-start="$emit( 'widget-drag-start', { widget_index, widget_key } )" 
                        @drag-end="$emit( 'widget-drag-end', { widget_index, widget_key } )"
                    />
                </draggable-list-item-wrapper>
            </div>

            <form-builder-droppable-placeholder v-if="canShowWidgetDropPlaceholder" @drop="$emit( 'append-widget' )"/>
        </slide-up-down>
    </div>
</template>

<script>
import Vue from "vue";
export default {
    name: "form-builder-widget-group-component",
    props: {
        groupKey: {
            default: '',
        },
        activeWidgets: {
            default: '',
        },
        avilableWidgets: {
            default: '',
        },
        groupData: {
            default: '',
        },
        groupSettings: {
            default: '',
        },
        groupFields: {
            default: '',
        },
        isEnabledGroupDragging: {
            default: false,
        },
        widgetIsDragging: {
            default: '',
        },
        currentDraggingGroup: {
            default: '',
        },
        currentDraggingWidget: {
            default: '',
        },
    },

    created() {
        this.setup();
    },

    computed: {
        widgetsExpandState() {
            let state = this.widgetsExpanded;
            
            if ( this.isEnabledGroupDragging ) {
                state = false;
            }

            return state;
        },

        canTrashGroup() {
            let canTrash = ( this.groupSettings && typeof this.groupSettings.canTrash !== 'undefined' ) ? this.groupSettings.canTrash : true;
            
            if ( this.detectedUntrashableWidgets.length ) {
                canTrash = false;
            }

            return canTrash; 
        },

        canShowWidgetDropPlaceholder() {
            let show = true;

            if ( this.groupData.fields && this.groupData.fields.length ) {
                show = false;
            }

            if ( typeof this.groupData.type !== 'undefined' && this.groupData.type !== 'general_group' ) {
                show = false;
            }

            return show;
        }
    },

    data() {
        return {
            widgetsExpanded: true,
            untrashableWidgets: {},
            activeWidgetsInfo: {},
            detectedUntrashableWidgets: [],
        }
    },

    methods: {
        setup() {
            this.checkIfGroupHasUntrashableWidgets();
        },

        checkIfGroupHasUntrashableWidgets() {
            if ( ! this.groupSettings ) { return; }
            if ( ! this.groupSettings.disableTrashIfGroupHasWidgets ) { return; }
            if ( ! Array.isArray( this.groupSettings.disableTrashIfGroupHasWidgets ) ) { return; }

            this.untrashableWidgets = this.groupSettings.disableTrashIfGroupHasWidgets;
        },

        updateDetectedUntrashableWidgets( widget_key ) {
            this.detectedUntrashableWidgets.push( widget_key );
        },

        toggleExpandWidgets() {
            this.widgetsExpanded = ! this.widgetsExpanded;
        },

        isDroppable( widget_index ) {
            if ( ! this.currentDraggingWidget ) { return false; }

            let droppable = true;
            
            if (  'active_widgets' === this.currentDraggingWidget.from ) {
                if ( this.currentDraggingWidget && this.currentDraggingWidget.widget_group_key === this.groupKey && this.currentDraggingWidget.widget_index === widget_index ) {
                    droppable = false;
                }
            }

            return droppable;
        },

        isDroppableBefore( widget_index ) {
            if ( ! this.currentDraggingWidget ) { return false; }
            if ( ! this.currentDraggingWidget.from ) { return false; }

            if ( 'active_widgets' === this.currentDraggingWidget.from ) {
                let widget_group_key = this.currentDraggingWidget.widget_group_key;
                let dragging_widget_index = this.currentDraggingWidget.widget_index;

                if ( widget_group_key !== this.groupKey ) { return true; }

                let before_item_index = widget_index - 1;
                if ( dragging_widget_index === before_item_index) {
                    return false;
                }
            }

            if ( 'available_widgets' === this.currentDraggingWidget.from ) { return true; }

            return true;
        },

        isDroppableAfter( widget_index ) {
            if ( ! this.currentDraggingWidget ) { return false; }
            if ( ! this.currentDraggingWidget.from ) { return false; }

            if ( 'active_widgets' === this.currentDraggingWidget.from ) {
                let widget_group_key = this.currentDraggingWidget.widget_group_key;
                let dragging_widget_index = this.currentDraggingWidget.widget_index;

                if ( widget_group_key !== this.groupKey ) { return true; }

                let after_item_index = widget_index + 1;
                if ( dragging_widget_index === after_item_index) {
                    return false;
                }
            }

            return true;
        },
    }
};
</script>
