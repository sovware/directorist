<template>
    <div class="atbdp-cptm-tab-item submission-form cptm-tab-content tab-full-width" :class="getActiveClass(index, active_nav_index)">
        <div class="cptm-section" v-for="( section, section_key ) in submission_form_sections" :key="section_key">
            <div class="cptm-title-area">
                <h3 v-if="section.title.length" class="cptm-title" v-html="section.title"></h3>
                <p v-if="section.description" v-html="section.description"></p>
            </div>
            
            <div class="cptm-form-builder cptm-row">
                <div class="cptm-col-6">
                    <div class="cptm-form-builder-active-fields">
                        <h3 class="cptm-title-3">Active Fields</h3>
                        <p class="cptm-description-text"> Click on a field to edit, Drag & Drop to reorder </p>
                        
                        <div class="cptm-form-builder-active-fields-container">
                            <div class="cptm-form-builder-active-fields-group" v-for="( group, group_key ) in form_groups" :key="group_key">
                                <h3 class="cptm-form-builder-group-title">{{ group.label }}</h3>

                                <div class="cptm-form-builder-group-fields">
                                    <div class="cptm-form-builder-group-field-item" v-for="( field_key, field_index ) in group.fields" :key="field_index">
                                        <div class="cptm-form-builder-group-field-item-actions">
                                            <a href="#" class="cptm-form-builder-group-field-item-action-link action-trash"
                                                v-if="! form_active_fields[ field_key ].lock"
                                                @click.prevent="trashActiveFieldItem( field_key, field_index, group_key )"
                                                    ><span class="fa fa-trash-o" aria-hidden="true"></span>
                                            </a>
                                        </div>
                                        
                                        <div class="cptm-form-builder-group-field-item-header" draggable="true" @drag="activeFieldOnDragStart( field_key, field_index, group_key )">
                                            <h4 class="cptm-title-3">{{ form_active_fields[ field_key ].label }}</h4>
                                            <div class="cptm-form-builder-group-field-item-header-actions">
                                                <a href="#" class="cptm-form-builder-header-action-link action-collapse-up" 
                                                    :class="( form_active_fields[ field_key ].show ) ? 'action-collapse-up' : 'action-collapse-down'" 
                                                    @click.prevent="toggleActiveFieldCollapseState( field_key, group_key )">
                                                    <span class="fa fa-angle-up" aria-hidden="true"></span>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <slide-up-down :active="form_active_fields[ field_key ].show" :duration="300">
                                            <div class="cptm-form-builder-group-field-item-body">
                                                <template v-for="( option, option_key ) in form_active_fields[ field_key ].options">
                                                    <component 
                                                        :is="field_widgets[ option.type ]" 
                                                        :key="option_key"
                                                        v-bind="option"
                                                    >
                                                    </component>
                                                </template>
                                            </div>
                                        </slide-up-down>

                                        <div class="cptm-form-builder-group-field-item-drop-area"
                                            :class="( field_key === active_drop_area ) ? 'drag-enter' : ''"
                                            @dragenter="activeFieldOnDragEnter( field_key, field_index, group_key )"
                                            @dragover.prevent="activeFieldOnDragOver( field_key, field_index, group_key )"
                                            @dragleave="activeFieldOnDragLeave( field_key, field_index, group_key )"
                                            @drop.prevent="activeFieldOnDrop( { field_key, field_index, group_key } )">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cptm-col-6">
                    <div class="cptm-form-builder-preset-fields">
                        <h3 class="cptm-title-3">Preset Fields</h3>
                        <p class="cptm-description-text">Click on a field to use it</p>
                        
                        <ul class="cptm-form-builder-field-list">
                            <template v-for="( field, field_key ) in form_fields.preset">
                                <li class="cptm-form-builder-field-list-item"
                                    draggable="true"
                                    @drag="presetFieldOnDrag( field_key )"
                                    v-if="! groupHasKey( field_key )" :key="field_key"
                                    >
                                    <span class="cptm-form-builder-field-list-icon">
                                        <span v-if="(field.icon && field.icon.length )" :class="field.icon"></span>
                                    </span>
                                    <span class="cptm-form-builder-field-list-label">{{ field.label }}</span>
                                </li>
                            </template>
                        </ul>

                    </div>

                    <div class="cptm-form-builder-custom-fields">
                        <h3 class="cptm-title-3">Custom Fields</h3>
                        <p class="cptm-description-text">Click on a field type you want to create</p>

                        <ul class="cptm-form-builder-field-list">
                            <li class="cptm-form-builder-field-list-item" v-for="( field, field_key ) in form_fields.custom" :key="field_key">
                                <span class="cptm-form-builder-field-list-icon">
                                    <span v-if="(field.icon && field.icon.length )" :class="field.icon"></span>
                                </span>
                                <span class="cptm-form-builder-field-list-label">{{ field.label }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import { mapMutations } from 'vuex';

import helpers from './../../../mixins/helpers';
import field_widgets from './../../../mixins/form-fields';

export default {
    name: 'submission-form',
    props: ['index'],
    mixins: [ helpers ],

    created() {
        this.$store.commit( 'updateActiveFieldsCollapseState' );
    },

    // computed
    computed: {
        ...mapState({
            active_nav_index: 'active_nav_index',
            submission_form_sections: state => state.settings.submission_form.sections,
            form_groups: state => state.fields.submission_form_fields.value.groups,
            form_active_fields: state => state.fields.submission_form_fields.value.active_fields,
            form_fields: 'form_fields',
            fields: 'fields',
        }),
    },

    data() {
        return {
            field_widgets,
            
            active_drop_area: '',
            current_dragging_item: {}
        }
    },

    methods: {
        ...mapMutations([
            'toggleActiveFieldCollapseState',
        ]),

        activeFieldOnDragStart( field_key, field_index, group_key ) {
            // console.log( 'activeFieldOnDragStart', {field_key, group_key} );
            this.current_dragging_item = { field_key, field_index, group_key };
        },

        activeFieldOnDragOver( field_key, field_index, group_key,  ) {
            // console.log( 'activeFieldOnDragOver', {field_key, field_index, group_key} );
            this.active_drop_area = field_key;
        },

        activeFieldOnDragEnter( field_key, field_index, group_key ) {
            this.active_drop_area = field_key;
            
            // console.log( 'activeFieldOnDragEnter', {field_key, field_index, group_key} );
        },

        activeFieldOnDragLeave( field_key, field_index, group_key ) {
            this.active_drop_area = '';
        },

        presetFieldOnDrag( field_key ) {
            this.current_dragging_item = { field_key, from: 'preset' };
            // console.log( field_key );
        },

        activeFieldOnDrop( args ) {
            // console.log( 'activeFieldOnDrop', {field_key: args.field_key, field_index: args.field_index, group_key: args.group_key} );

            if ( this.current_dragging_item.group_key !== 'undefined' && ( args.group_key === this.current_dragging_item.group_key ) ) {
                this.$store.commit( 'reorderActiveFieldsItems', { 
                    group_index: args.group_key, 
                    origin_field_index: this.current_dragging_item.field_index,   
                    destination_field_index: args.field_index,
                });
            }

            if ( this.current_dragging_item.from ) {
                this.$store.commit( 'appendActiveFieldsItem', { 
                    group_index: args.group_key,
                    field_index: args.field_index,
                    appending_from: this.current_dragging_item.from,   
                    appending_field_key: this.current_dragging_item.field_key,   
                });
            }

            

            this.active_drop_area = '';
            this.current_dragging_item = {};
        },

        trashActiveFieldItem( field_key, field_index, group_key ) {
            this.$store.commit( 'trashActiveFieldItem', { 
                field_index: field_index,   
                group_index: group_key,   
            });
        },

        groupHasKey( field_key ) {
            let match_found = false;

            for ( let i = 0; i < this.form_groups.length; i++ ) {
                if ( this.form_groups[ i ].fields.indexOf( field_key ) > -1 ) {
                    match_found = true;
                    break;
                }
            }

            return match_found;
        }
    },
}
</script>