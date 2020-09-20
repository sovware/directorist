<template>
    <div class="atbdp-cptm-tab-item submission-form cptm-tab-content tab-full-width" :class="getActiveClass(index, active_nav_index)">
        <div class="cptm-section" v-for="( section, section_key ) in submission_form_sections" :key="section_key">
            <div class="cptm-title-area">
                <h3 v-if="section.title.length" class="cptm-title" v-html="section.title"></h3>
                <p v-if="section.description" v-html="section.description"></p>
            </div>
            
            <div class="cptm-form-fields">
                <template v-for="( field, field_key ) in section.fields">
                    <component 
                        :is="field_widgets[ fields[ field ].type ]" 
                        :key="field_key"
                        v-bind="fields[ field ]"
                        @update="updateFieldValue( field, $event )">
                    </component>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import { mapMutations } from 'vuex';

import helpers from './../../../mixins/helpers';
import field_widgets from './../../../mixins/form-fields';
import form_builder from './../../../modules/Form_Builder.vue';


export default {
    name: 'submission-form',
    props: ['index'],
    mixins: [ helpers ],

    // computed
    computed: {
        ...mapState({
            active_nav_index: 'active_nav_index',
            submission_form_sections: state => state.settings.submission_form.sections,
            form_groups: state => state.settings.submission_form.sections.form_fields.fields[0],
            form_groups: state => state.fields.submission_form_fields.value.groups,
            form_active_fields: state => state.fields.submission_form_fields.value.fields,
            form_fields: 'form_fields',
            // form_fields: 'form_fields',
            fields: 'fields',
        }),

        form_groups() {
            return fields[ submission_form_sections.form_fields.fields[0] ].value.groups
        }
    },

    data() {
        return {
            field_widgets,
            form_builder,
            
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