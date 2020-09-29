<template>
    <div class="cptm-form-builder cptm-row">
        <div class="cptm-col-6">
            <div class="cptm-form-builder-active-fields">
                <h3 class="cptm-title-3">Active Fields</h3>
                <p class="cptm-description-text">Click on a field to edit, Drag & Drop to reorder </p>

                <div class="cptm-form-builder-active-fields-container" v-if="groups.length">
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
                                    <div class="cptm-form-builder-group-field-item-body" v-if="getActiveFieldsSettings( field_key, 'options' )">
                                        <template v-for="( option, option_key ) in getActiveFieldsSettings( field_key, 'options' )">
                                                {{ active_fields[ field_key ][ option_key ] }}
                                                <component 
                                                    :is="option.type + '-field'" 
                                                    :key="option_key"
                                                    v-if="activeIsFieldVisible( option_key, field_key )"
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
                        
                        <br>       
                        
                    </div>


                    <div class="cptm-form-builder-active-fields-footer">
                        <button type="button" class="cptm-btn cptm-btn-secondery" v-if="allow_add_new_section" @click="addNewActiveFieldSection()">
                            Add new section
                        </button>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="cptm-col-6" v-if="widget_groups">
            <div class="cptm-form-builder-preset-fields" v-for="( widget_group, group_key ) in widget_groups" :key="group_key">
                <h3 class="cptm-title-3">{{ widget_group.title }}</h3>
                <p class="cptm-description-text">{{ widget_group.description }}</p>

                <ul class="cptm-form-builder-field-list">
                    <template v-for="( field, field_key ) in widget_group.widgets">
                        <li class="cptm-form-builder-field-list-item" draggable="true" v-if="widgetCanShow( widget_group, field_key )" :key="field_key"
                            @drag="widgetItemOnDrag( group_key, field_key )">
                                <span class="cptm-form-builder-field-list-icon">
                                    <span v-if="(field.icon && field.icon.length )" :class="field.icon"></span>
                                </span>
                                <span class="cptm-form-builder-field-list-label">{{ field.label }}</span>
                        </li>
                    </template>
                </ul>

            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'form-builder',
    props: {
        widgets: {
            required: false,
            default: false,
        },
        value: {
            required: false,
            default: '',
        },
        dependency: {
            required: false,
            default: '',
        },
        allow_add_new_section: {
            type: Boolean,
            default: true,
            required: false,
        },
    },

    created() {
        if ( this.value.groups ) {
            this.groups = this.value.groups;
        }

        if (  typeof this.value.fields === 'object' ) {
            this.active_fields = this.value.fields;
        }
        
        this.parseLocalWidgets();
        this.$emit( 'update', this.updated_value );
    },

    computed: {
        ...mapState({
            fields: 'fields',
        }),

        updated_value() {
            // return JSON.stringify( { fields: this.active_fields, groups: this.groups } );
            return { fields: this.active_fields, groups: this.groups };
        },

        widget_groups_with_states() {
            let widget_groups = this.widget_groups;

            for ( let field_key in this.active_fields ) {
                const widget_group = this.active_fields[ field_key ].widget_group;
                const widget_name = this.active_fields[ field_key ].widget_name;

                let field_options = widget_groups[ widget_group ].widgets[ widget_name ].options;

                for ( let field in field_options ) {
                    field_options[ field ][ 'show' ] = this.checkShowIfCondition( field_options[ field ], field_key );
                }

            }

            return widget_groups;
        },

        widget_groups() {

            if ( this.has_dependency && ! this.dependency_widgets) {
                return {};
            }

            if (  this.dependency_widgets ) {
                return this.dependency_widgets;
            }

            return this.local_widgets;

        },

        has_dependency() {
            if ( typeof this.dependency !== 'string' ) { return false; }
            if ( ! this.dependency.length ) { return false; }

            return true;
        },

        dependency_data() {
            if ( ! this.has_dependency ) { return null; }

            if ( typeof this.fields[ this.dependency ] === 'undefined' ) { return null; }
            if ( typeof this.fields[ this.dependency ].value === 'undefined' ) { return null; }
            
            return this.fields[ this.dependency ].value;
        },

        dependency_widgets() {
            if ( ! this.dependency_data ) { return null; }
            if ( ! this.local_widgets ) { return null; }

            let dependency_fields = this.dependency_data.fields;

            if ( this.dependency_fields ) {
                dependency_fields = this.dependency_fields;
            }
           
            let dependency_widgets = JSON.parse(JSON.stringify( this.local_widgets ));

            for ( let widget_group in dependency_widgets ) {
                let new_widget_list = {};

                 for ( let field in dependency_fields ) {
                    let widget_name = dependency_fields[ field ].widget_name;

                    if ( typeof dependency_widgets[ widget_group ].widgets[ widget_name ] !== 'undefined' ) {
                        // let dep_field_widget_name = ( dependency_fields[ field ].label && dependency_fields[ field ].label.length ) ? true : false;
                        let dep_field_has_label = ( dependency_fields[ field ].label && dependency_fields[ field ].label.length ) ? true : false;
                        let dep_widget_has_label = ( dependency_widgets[ widget_group ].widgets[ widget_name ].options.label ) ? true : false;
                        
                        // console.log( dependency_widgets[ widget_group ].widgets[ widget_name ].options );
                        // console.log( { dep_field_has_label, dep_widget_has_label } );

                        if ( dep_field_has_label && ! dep_widget_has_label ) {
                            // console.log( 'dep_field_has_label && ! dep_widget_has_label' );
                            dependency_widgets[ widget_group ].widgets[ widget_name ].options.label = {
                                type: 'hidden',
                                value: dependency_fields[ field ].label
                            }
                        }

                        if ( dep_field_has_label && dep_widget_has_label ) {
                            // console.log( 'dep_field_has_label && dep_widget_has_label' );
                            dependency_widgets[ widget_group ].widgets[ widget_name ].options.label.value = dependency_fields[ field ].label;
                        }

                        dependency_widgets[ widget_group ].widgets[ widget_name ].options.root_data = {
                            type: 'array',
                            value: dependency_fields[ field ],
                        }

                        new_widget_list[ widget_name ] = dependency_widgets[ widget_group ].widgets[ widget_name ];
                    }
                }

                dependency_widgets[ widget_group ].widgets = new_widget_list;
            }

            return dependency_widgets;
        }
    },

    data() {
        return {
            local_widgets: {},
            groups: [
                {
                    label: 'General',
                    fields: [],
                }
            ],
            active_fields: {},
            state: {},

            active_fields_ref: {},

            active_field_drop_area: '',
            active_group_drop_area: '',
            current_dragging_item: {},
            ative_field_collapse_states: {}
        }
    },

    methods: {
        activeIsFieldVisible( option_key, field_key ) {
            let options = this.getActiveFieldsSettings( field_key, 'options' );

            const widget_group = this.active_fields[ field_key ].widget_group;
            const widget_name = this.active_fields[ field_key ].widget_name;

            if ( 'pricing' === field_key ) {
                let widget_options = this.widget_groups_with_states[ widget_group ].widgets[ widget_name ].options[ option_key ];
                // console.log( { widget_group, widget_name, widget_options } );
                // console.log( {options, option_key, field_key } );
            }


            if ( typeof options[ option_key ] === 'undefined' ) {
                return true;
            }

            if ( typeof options[ option_key ].show === 'undefined' ) {
                return true;
            }

            // console.log( {option_key} );
            return options[ option_key ].show;

        },

        checkShowIfCondition( options, field_key  ) {
            if ( typeof options === 'undefined' ) { return true; }
            if ( typeof options.show_if === 'undefined' ) { return true; }

            let faild_condition_count = 0;
            let self = this;
        
            options.show_if.forEach(element => {
                let terget_field = null;
                let conditions = null;
                let where_field_is = 'self';
                let where_widget_is = 'self';
                let compare = 'or';

                if ( typeof element.where !== 'undefined' && typeof element.where.field !== 'undefined') {
                    where_field_is = element.where.field;
                }

                if ( typeof element.where !== 'undefined' && typeof element.where.widget !== 'undefined') {
                    where_widget_is = element.where.widget;
                }

                if ( typeof element.compare !== 'undefined' ) {
                    compare = element.compare;
                }
                
                if ( where_field_is !== 'self' ) {
                    terget_field = self.fields[ where_field_is ];
                }

                if ( where_field_is === 'self' ) {
                    terget_field = self.active_fields;
                }
                    
                if ( terget_field && where_widget_is === 'self' ) {
                    terget_field = terget_field[ field_key ];
                }

                if ( terget_field && where_widget_is !== 'self' ) {
                    terget_field = terget_field[ where_widget_is ];
                }


                if ( typeof terget_field !== 'undefined' ) {
                    conditions = element.conditions;
                }

                if ( conditions && typeof conditions === 'object' ) {
                    let missmatch_count = 0;
                    let match_count = 0;

                    conditions.forEach( item => {
                        let terget_value = terget_field[ item.key ];
                        let compare_value = item.value;

                        if ( terget_value !== compare_value ) {
                            missmatch_count++;
                        } else {
                            match_count++;
                        }
                    }); 

                    if ( 'and' === compare && missmatch_count ) {
                        faild_condition_count++;
                    } else if ( 'or' === compare && ! match_count ) {
                        faild_condition_count++;
                    }
                }


                // console.log({ field_key, faild_condition_count } );
            });

            if ( faild_condition_count ) {
                return false;
            }
            
            return true;
        },

        parseLocalWidgets() {
            if ( ! this.widgets && typeof this.widgets !== 'object' ) {
                this.local_widgets = null;
            }

            let widgets = JSON.parse(JSON.stringify( this.widgets ));
            
            for ( let widget_group in widgets ) {
                for ( let widget in widgets[ widget_group ].widgets )  {
                    widgets[ widget_group ].widgets[widget].options.widget_group = { type: 'hidden', value: widget_group };
                    widgets[ widget_group ].widgets[widget].options.widget_name = { type: 'hidden', value: widget };
                }
            }
            
            this.local_widgets = widgets;
        },

        getActiveFieldsOption( field_key, data_key ) {
            return this.active_fields[ field_key ][ data_key ];
        },

        getActiveFieldsSettings( field_key, data_key ) {
            const widget_group = this.active_fields[ field_key ].widget_group;
            const widget_name = this.active_fields[ field_key ].widget_name;
            

            // console.log( this.local_widgets, this.active_fields, widget_group );
            // console.log( { widget_group, widget_name } );

            if ( typeof widget_group === 'undefined' ) {
                return false;
            }

            if ( typeof widget_name === 'undefined' ) {
                return false;
            }
            
            if ( typeof this.widget_groups_with_states[ widget_group ] === 'undefined' ) {
                return false;
            }

            if ( typeof this.widget_groups_with_states[ widget_group ].widgets === 'undefined' ) {
                return false;
            }

            if ( typeof this.widget_groups_with_states[ widget_group ].widgets[ widget_name ] === 'undefined' ) {
                return false;
            }

            if ( typeof this.widget_groups_with_states[ widget_group ].widgets[ widget_name ][ data_key] === 'undefined' ) {
                return false;
            }

            return this.widget_groups_with_states[ widget_group ].widgets[ widget_name ][ data_key ];
        },

        getActiveFieldsOptions( widget_options ) {
            if ( typeof widget_options !== 'object'  ) {
                return {};
            }
            
            let options = JSON.parse(JSON.stringify( widget_options ));
            delete options.value;

            return options;
        },

        getActiveFieldsHeaderTitle( field_key ) {
            const settings_label = this.getActiveFieldsSettings( field_key, 'label' );
            const option_label = this.active_fields[ field_key ]['label'];

            // console.log( {settings_label, option_label} );
            
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

        widgetItemOnDrag( group_key, field_key ) {
            this.current_dragging_item = { inserting_field_key: field_key, inserting_from: group_key };
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

            // console.log( payload );
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

            const field_data = this.widget_groups[ payload.inserting_from ].widgets[ payload.inserting_field_key ];
            let field_data_options = {};

            for ( let option_key in field_data.options ) {
                field_data_options[ option_key ] = ( typeof field_data.options[ option_key ].value !== 'undefined' ) ? field_data.options[ option_key ].value : '';
            }
            
            this.active_fields[ inserting_field_key ] = field_data_options;


            const widget_group = this.active_fields[ inserting_field_key ].widget_group;
            const widget_name = this.active_fields[ inserting_field_key ].widget_name;

            if ( typeof payload.destination_field_index !== 'undefined' ) {
                this.groups[ payload.destination_group_index ].fields.splice( payload.destination_field_index + 1, 0 , inserting_field_key );
            } else {
                this.groups[ payload.destination_group_index ].fields.push( inserting_field_key );
            }
        },

        widgetCanShow( widget_group, field_key ) {

            if ( typeof widget_group.allow_multiple === 'undefined' ) {
                widget_group.allow_multiple = false;
            }
            
            if ( ! widget_group.allow_multiple && this.groupHasKey( field_key ) ) {
                return false;
            }

            return true;
        },

        groupHasKey( field_key ) {
            let match_found = false;
            if ( ! this.groups ) { return match_found; }

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