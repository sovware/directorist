<template>
    <div class="cptm-form-builder-group-header-section">
        <!-- Group Header Top -->
        <div class="cptm-form-builder-group-header">
            <!-- Group Header Titlebar -->
            <draggable-list-item :can-drag="isEnabledGroupDragging" @drag-start="$emit( 'drag-start' )" @drag-end="$emit( 'drag-end' )">
                <form-builder-widget-group-titlebar-component 
                    v-bind="$props"
                    :widgets-expanded="widgetsExpanded"
                    @toggle-expand-group="toggleGroupFieldsExpand"
                    @toggle-expand-widgets="$emit('toggle-expand-widgets')"
                />
            </draggable-list-item>

            <!-- Group Header Actions -->
            <div class="cptm-form-builder-group-actions">
                <a href="#" class="cptm-form-builder-group-field-item-action-link action-trash" v-if="canTrash" @click.prevent="$emit( 'trash-group' )">
                    <span class="uil uil-trash-alt" aria-hidden="true"></span>
                </a>
            </div>
        </div>

        <!-- Group Header Body -->
        <slide-up-down :active="groupFieldsExpandState" :duration="500">
            <div class="cptm-form-builder-group-options">
                <field-list-component 
                    :field-list="groupFields" 
                    :value="groupData" 
                    @update="$emit( 'update-group-field', $event)"
                />
            </div>
        </slide-up-down>
    </div>
</template>

<script>
export default {
    name: 'form-builder-widget-group-header-component',
    props: {
        groupData: {
            default: '',
        },
        groupSettings: {
            default: '',
        },
        groupFields: {
            default: '',
        },
        widgetsExpanded: {
            default: '',
        },
        canTrash: {
            default: false,
        },
        currentDraggingGroup: {
            default: '',
        },
        isEnabledGroupDragging: {
            default: false,
        },
        forceExpandStateTo: {
            default: '',
        },
    },

    created() {
        this.setup();
    },

    computed: {
        groupFieldsExpandState() {
            let state = this.groupFieldsExpanded;
            
            if ( 'expand' === this.forceExpandStateTo ) {
                state = true;
            }

            if ( this.isEnabledGroupDragging ) {
                state = false;
            }

            return state;
        }
    },

    data() {
        return {
            header_title_component_props: {},
            groupFieldsExpanded: false,
        }
    },

    methods: {
        setup() {

        },

        toggleGroupFieldsExpand() {
            this.groupFieldsExpanded = ! this.groupFieldsExpanded;
        }
    }
}
</script>