<template>
    <div class="cptm-form-builder cptm-row">
        <div class="cptm-col-6">
            <div class="cptm-form-builder-active-fields">
                <h3 class="cptm-title-3">Active Fields</h3>
                <p class="cptm-description-text">Click on a field to edit, Drag & Drop to reorder </p>
            
                <div class="cptm-form-builder-active-fields-container">
                    <div class="cptm-form-builder-active-fields-group" v-for="( group, group_key ) in groups" :key="group_key">
                        <h3 class="cptm-form-builder-group-title">{{ group.label }}</h3>

                        <div class="cptm-form-builder-group-fields">
                            <div class="cptm-form-builder-group-field-item" v-for="( field_key, field_index ) in group.fields" :key="field_index">
                                <div class="cptm-form-builder-group-field-item-actions">
                                    
                                    <a href="#" class="cptm-form-builder-group-field-item-action-link action-trash"
                                        v-if="! getActiveFieldsSettings( field_key, 'lock' )"
                                        @click.prevent="trashActiveFieldItem( field_key, field_index, group_key )"
                                            ><span class="fa fa-trash-o" aria-hidden="true"></span>
                                    </a>
                                </div>
                                
                                <div class="cptm-form-builder-group-field-item-header" draggable="true" @drag="activeFieldOnDragStart( field_key, field_index, group_key )">
                                    <h4 class="cptm-title-3">{{ getActiveFieldsHeaderTitle( field_key ) }}</h4>
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
                                        <template v-for="( option, option_key ) in getActiveFieldsSettings( field_key, 'options' )">
                                            <component 
                                                :is="field_widgets[ option.type ]" 
                                                :key="option_key"
                                                v-bind="getActiveFieldsOptions( option )"
                                                :value="active_fields[ field_key ][ option_key ]"
                                                @update="updateActiveFieldsOptionData( { field_key, option_key, value: $event } )"
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
                    <template v-for="( field, field_key ) in preset_fields">
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
                    <li class="cptm-form-builder-field-list-item" draggable="true" v-for="( field, field_key ) in custom_fields" :key="field_key"
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
</template>

<script>
import field_widgets from './../mixins/form-fields';

export default {
    name: 'form-builder',
    props: {
        widgets: {
            required: true,
        },
        value: {
            required: true,
        },
    },

    created() {
        this.parseLocalWidgets();
        this.groups = this.value.groups;
        this.active_fields = this.value.fields;
    },

    computed: {
        preset_fields() {
            return this.local_widgets.preset;
        },

        custom_fields() {
            return this.local_widgets.custom;
        },

        updated_value() {
            return { active_fields: this.active_fields, groups: this.groups };
        },
    },

    data() {
        return {
            field_widgets,
            local_widgets: {},
            groups: [],
            active_fields: {},

            active_fields_ref: {},

            active_field_drop_area: '',
            active_group_drop_area: '',
            current_dragging_item: {},
            ative_field_collapse_states: {}
        }
    },

    methods: {
        parseLocalWidgets() {
            let widgets = { ...this.widgets };

            let preset_fields = {};
            for ( let preset_field in widgets.preset ) {
                let preset_field_data = widgets.preset[ preset_field ];

                if ( typeof preset_field_data.options === 'undefined' ) {
                    preset_field_data.options = {};
                }

                preset_field_data.options.widget_type = { type: 'hidden', value: 'preset' };
                preset_field_data.options.widget_name = { type: 'hidden', value: preset_field } ;

                preset_fields[ preset_field ] = preset_field_data;
            }

            widgets.preset = preset_fields;

            let custom_fields = {};
            for ( let custom_field in widgets.custom ) {
                let custom_field_data = widgets.custom[ custom_field ];

                if ( typeof custom_field_data.options === 'undefined' ) {
                    custom_field_data.options = {};
                }

                custom_field_data.options.widget_type = { type: 'hidden', value: 'custom' };
                custom_field_data.options.widget_name = { type: 'hidden', value: 'custom_field' };

                custom_fields[ custom_field ] = custom_field_data;
            }

            widgets.custom = custom_fields;
            this.local_widgets = widgets;
        },

        getActiveFieldsOption( field_key, data_key ) {
            return this.active_fields[ field_key ][data_key];
        },

        getActiveFieldsSettings( field_key, data_key ) {
            const widget_type = this.active_fields[ field_key ].widget_type;
            const widget_name = this.active_fields[ field_key ].widget_name;

            return this.local_widgets[ widget_type ][ widget_name ][ data_key ];
        },

        getActiveFieldsOptions( widget_options ) {
            const options = { ...widget_options };
            delete options.value;

            return options;
        },

        getActiveFieldsHeaderTitle( field_key ) {
            const settings_label = this.getActiveFieldsSettings( field_key, 'label' );
            const option_label = this.active_fields[ field_key ]['label'];
            
            return ( option_label && option_label.length ) ? option_label : settings_label;
        },


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
                console.log( 'Reorder' );
                this.reorderActiveFieldsItems({ 
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
                console.log( 'Move' );
                this.moveActiveFieldsItems( { 
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
                console.log( 'Insert' );
                this.insertActiveFieldsItem({ 
                    inserting_from,
                    inserting_field_key,
                    destination_group_index,
                    destination_field_index,   
                });
            }

            this.$emit( 'update', this.updated_value );

            this.active_field_drop_area = '';
            this.current_dragging_item = {};
        },

        trashActiveFieldItem( field_key, field_index, group_key ) {

            if ( typeof this.ative_field_collapse_states[ field_key ] !== 'undefined' ) {
                delete this.ative_field_collapse_states[ field_key ];
            }

            const the_field_key = this.groups[ group_key ].fields[ field_index ];

            this.groups[ group_key ].fields.splice( field_index, 1 );
            delete this.active_fields[ the_field_key ];

            this.$emit( 'update', this.updated_value );
        },

        updateActiveFieldsOptionData( payload ) {
            this.active_fields[ payload.field_key ][ payload.option_key ] = payload.value;
            this.$emit( 'update', this.updated_value );
            console.log( payload );
        },

        addNewActiveFieldSection() {
            this.groups.push( { label: 'New Section', fields: [] } );

            this.$emit( 'update', this.updated_value );
        },

        reorderActiveFieldsItems( payload ) {
            const origin_value = this.groups[ payload.group_index ].fields[ payload.origin_field_index ];

            this.groups[ payload.group_index ].fields.splice( payload.origin_field_index, 1 );
            const des_ind = ( payload.origin_field_index === 0 ) ? payload.destination_field_index : payload.destination_field_index + 1 ;
            this.groups[ payload.group_index ].fields.splice( des_ind, 0, origin_value );
        },

        moveActiveFieldsItems( payload ) {
            const origin_value = this.groups[ payload.origin_group_index ].fields[ payload.origin_field_index ];

            // Remove from origin group
            this.groups[ payload.origin_group_index ].fields.splice( payload.origin_field_index, 1 );

            // Insert to destination group
            // const des_ind = ( payload.origin_field_index === 0 ) ? payload.destination_field_index : payload.destination_field_index + 1 ;
            const des_ind = payload.destination_field_index + 1 ;
            this.groups[ payload.destination_group_index ].fields.splice( des_ind, 0, origin_value );
        },


        insertActiveFieldsItem( payload ) {
            let inserting_field_key = payload.inserting_field_key;

            if ( typeof this.active_fields_ref[ payload.inserting_field_key ] === 'undefined' ) {
                this.active_fields_ref[ payload.inserting_field_key ] = [];
            }

            if ( ! this.active_fields_ref[ payload.inserting_field_key ].length ) {
                this.active_fields_ref[ payload.inserting_field_key ].push( payload.inserting_field_key );
            } else {
                let new_key = payload.inserting_field_key + '_' + ( this.active_fields_ref[ payload.inserting_field_key ].length + 1 );
                this.active_fields_ref[ inserting_field_key ].push( new_key );
                inserting_field_key = new_key;
            }

            const field_data = this.widgets[ payload.inserting_from ][ payload.inserting_field_key ];
            
            let field_data_options = {};

            for ( let option_key in field_data.options ) {
                field_data_options[ option_key ] = ( typeof field_data.options[ option_key ].value !== 'undefined' ) ? field_data.options[ option_key ].value : '';
            }
            
            this.active_fields[ inserting_field_key ] = field_data_options;

            const widget_type = this.active_fields[ inserting_field_key ].widget_type;
            const widget_name = this.active_fields[ inserting_field_key ].widget_name;

            if ( typeof payload.destination_field_index !== 'undefined' ) {
                this.groups[ payload.destination_group_index ].fields.splice( payload.destination_field_index + 1, 0 , inserting_field_key );
            } else {
                this.groups[ payload.destination_group_index ].fields.push( inserting_field_key );
            }
        },



        groupHasKey( field_key ) {
            let match_found = false;

            for ( let i = 0; i < this.groups.length; i++ ) {
                if ( this.groups[ i ].fields.indexOf( field_key ) > -1 ) {
                    match_found = true;
                    break;
                }
            }

            return match_found;
        }
    },
}
</script>