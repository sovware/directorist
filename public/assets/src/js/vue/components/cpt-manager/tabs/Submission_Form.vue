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
                                            <h4 class="cptm-title-3">{{ ( form_active_fields[ field_key ].options.label && form_active_fields[ field_key ].options.label.value.length ) ? form_active_fields[ field_key ].options.label.value : form_active_fields[ field_key ].label }}</h4>
                                            <div class="cptm-form-builder-group-field-item-header-actions">
                                                <a href="#" class="cptm-form-builder-header-action-link action-collapse-up" 
                                                    :class="getActiveFieldCollapseClass( field_key )"
                                                    @click.prevent="toggleActiveFieldCollapseState( field_key )">
                                                    <span class="fa fa-angle-up" aria-hidden="true"></span>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <slide-up-down :active="getActiveFieldCollapseState( field_key )" :duration="300">
                                            <div class="cptm-form-builder-group-field-item-body">
                                                <template v-for="( option, option_key ) in form_active_fields[ field_key ].options">
                                                    <component 
                                                        :is="field_widgets[ option.type ]" 
                                                        :key="option_key"
                                                        v-bind="option"
                                                        @update="updateActiveFieldsOptionData( field_key, option_key, $event )"
                                                    >
                                                    </component>
                                                </template>
                                            </div>
                                        </slide-up-down>

                                        <div class="cptm-form-builder-group-field-item-drop-area"
                                            :class="( field_key === active_field_drop_area ) ? 'drag-enter' : ''"
                                            @dragenter="activeFieldOnDragEnter( field_key, field_index, group_key )"
                                            @dragover.prevent="activeFieldOnDragOver( field_key, field_index, group_key )"
                                            @dragleave="activeFieldOnDragLeave()"
                                            @drop.prevent="activeFieldOnDrop( { field_key, field_index, group_key } )">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="cptm-form-builder-group-field-drop-area"
                                    :class="( group_key === active_group_drop_area ) ? 'drag-enter' : ''"
                                    v-if="! group.fields.length"
                                    @dragenter="activeGroupOnDragEnter( group_key )"
                                    @dragover.prevent="activeGroupOnDragOver( group_key )"
                                    @dragleave="activeGroupOnDragLeave( group_key )"
                                    @drop="activeFieldOnDrop( { group_key } )">
                                    <p class="cptm-form-builder-group-field-drop-area-label">Drop Here</p>
                                </div>
                            </div>


                            <div class="cptm-form-builder-active-fields-footer">
                                <button type="button" class="cptm-btn cptm-btn-secondery" @click="addNewActiveFieldSection()">
                                    Add new section
                                </button>
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
                                <li class="cptm-form-builder-field-list-item" draggable="true" v-if="! groupHasKey( field_key )" :key="field_key"
                                    @drag="presetFieldOnDrag( field_key )">
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
                            <li class="cptm-form-builder-field-list-item" draggable="true" v-for="( field, field_key ) in form_fields.custom" :key="field_key"
                                @drag="customFieldOnDrag( field_key )">
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
            
            active_field_drop_area: '',
            active_group_drop_area: '',
            current_dragging_item: {},
            ative_field_collapse_states: {}
        }
    },

    methods: {
        ...mapMutations([
            // 'toggleActiveFieldCollapseState',
        ]),

        toggleActiveFieldCollapseState( field_key ) {

            if ( typeof this.ative_field_collapse_states[field_key] === 'undefined' ) {
                this.$set( this.ative_field_collapse_states, field_key, {} );
                this.$set( this.ative_field_collapse_states[ field_key ], 'collapsed', false );
            }

            this.ative_field_collapse_states[field_key].collapsed = ! this.ative_field_collapse_states[field_key].collapsed;
        },

        getActiveFieldCollapseClass( field_key ) {

            if ( typeof this.ative_field_collapse_states[field_key] === 'undefined' ) {
                return 'action-collapse-down';
            }

            return ( this.ative_field_collapse_states[field_key].collapsed ) ? 'action-collapse-up' : 'action-collapse-down';
        },

        getActiveFieldCollapseState( field_key ) {

            if ( typeof this.ative_field_collapse_states[field_key] === 'undefined' ) {
                return false;
            }

            return ( this.ative_field_collapse_states[field_key].collapsed ) ? true : false;
        },

        activeFieldOnDragStart( field_key, field_index, group_key ) {
            this.current_dragging_item = { field_key, field_index, group_key };
        },

        activeFieldOnDragOver( field_key, field_index, group_key,  ) {
            this.active_field_drop_area = field_key;
        },

        activeFieldOnDragEnter( field_key, field_index, group_key ) {
            this.active_field_drop_area = field_key;
        },

        activeFieldOnDragLeave() {
            this.active_field_drop_area = '';
        },

        activeGroupOnDragOver( group_key,  ) {
            this.active_field_drop_area = group_key;
        },

        activeGroupOnDragEnter( group_key ) {
            this.active_group_drop_area = group_key;
        },

        activeGroupOnDragLeave() {
            this.active_group_drop_area = '';
        },

        presetFieldOnDrag( field_key ) {
            this.current_dragging_item = { inserting_field_key: field_key, inserting_from: 'preset' };
            // console.log( inserting_field_key:  );
        },

        customFieldOnDrag( field_key ) {
            this.current_dragging_item = { inserting_field_key: field_key, inserting_from: 'custom' };
            // console.log( field_key );
        },

        activeFieldOnDrop( args ) {
            // console.log( 'activeFieldOnDrop', {field_key: args.field_key, field_index: args.field_index, group_key: args.group_key} );

            const inserting_from          = this.current_dragging_item.inserting_from;
            const inserting_field_key     = this.current_dragging_item.inserting_field_key;
            const origin_group_index      = this.current_dragging_item.group_key;
            const origin_field_index      = this.current_dragging_item.field_index;
            const destination_group_index = args.group_key;
            const destination_field_index = args.field_index;

            /* console.log({
                inserting_from,
                inserting_field_key,
                origin_group_index,
                origin_field_index,
                destination_group_index,
                destination_field_index,
            }); */

            // Reorder
            if ( 
                typeof origin_group_index !== 'undefined' &&
                typeof origin_field_index !== 'undefined' &&
                typeof destination_group_index !== 'undefined' &&
                typeof destination_field_index !== 'undefined' &&
                origin_group_index === destination_group_index
            ) {
                // console.log( 'Reorder' );
                this.$store.commit( 'reorderActiveFieldsItems', { 
                    group_index: destination_group_index,
                    origin_field_index ,
                    destination_field_index,
                });
            }


            // Move
            if ( 
                typeof origin_group_index !== 'undefined' &&
                typeof origin_field_index !== 'undefined' &&
                typeof destination_group_index !== 'undefined' &&
                origin_group_index !== destination_group_index
            ) {
                // console.log( 'Move' );
                this.$store.commit( 'moveActiveFieldsItems', { 
                    origin_group_index,
                    origin_field_index,
                    destination_group_index,
                    destination_field_index,
                });
            }

            // Insert
            if ( 
                typeof inserting_from !== 'undefined' &&
                typeof inserting_field_key !== 'undefined' &&
                typeof destination_group_index !== 'undefined'
            ) {
                // console.log( 'Insert' );
                this.$store.commit( 'insertActiveFieldsItem', { 
                    inserting_from,
                    inserting_field_key,
                    destination_group_index,
                    destination_field_index,   
                });
            }

            this.active_field_drop_area = '';
            this.current_dragging_item = {};
        },

        trashActiveFieldItem( field_key, field_index, group_key ) {

            if ( typeof this.ative_field_collapse_states[ field_key ] !== 'undefined' ) {
                delete this.ative_field_collapse_states[ field_key ];
            }

            this.$store.commit( 'trashActiveFieldItem', { 
                field_index: field_index,   
                group_index: group_key,   
            });
        },

        // updateActiveFieldsOptionData
        updateActiveFieldsOptionData( field_key, option_key, value ) {
            this.$store.commit( 'updateActiveFieldsOptionData', {field_key, option_key, value} );
        },

        addNewActiveFieldSection() {
            this.$store.commit( 'addNewActiveFieldSection' );
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