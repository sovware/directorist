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
                                    <div class="cptm-form-builder-group-field-item" v-for="( group_field, group_field_key ) in group.fields" :key="group_field_key">
                                        <div class="cptm-form-builder-group-field-item-actions">
                                            <a href="#" class="cptm-form-builder-group-field-item-action-link action-trash">
                                                <span class="fa fa-trash-o" aria-hidden="true"></span>
                                            </a>
                                        </div>
                                        
                                        <div class="cptm-form-builder-group-field-item-header">
                                            <h4 class="cptm-title-3">{{ form_active_fields[ group_field ].label }}</h4>
                                            <div class="cptm-form-builder-group-field-item-header-actions">
                                                <a href="#" class="cptm-form-builder-header-action-link action-collapse-up" @click.prevent="toggleActiveFieldCollapseState( group_field )">
                                                    <span class="fa fa-angle-up" aria-hidden="true"></span>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <slide-up-down :active="getToggleState( group_field )" :duration="1000">
                                            <div class="cptm-form-builder-group-field-item-body">
                                                <template v-for="( option, option_key ) in form_active_fields[ group_field ].options">
                                                    <component 
                                                        :is="field_widgets[ option.type ]" 
                                                        :key="option_key"
                                                        v-bind="option"
                                                    >
                                                    </component>
                                                </template>
                                            </div>
                                        </slide-up-down>
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
                            <li class="cptm-form-builder-field-list-item" v-for="( field, field_key ) in form_fields.preset" :key="field_key">
                                <span class="cptm-form-builder-field-list-icon">
                                    <span v-if="(field.icon && field.icon.length )" :class="field.icon"></span>
                                </span>
                                <span class="cptm-form-builder-field-list-label">{{ field.label }}</span>
                            </li>
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
            field_widgets
        }
    },

    methods: {
        // ...mapMutations([
        //     'toggleActiveFieldCollapseState',
        // ]),

        toggleActiveFieldCollapseState( active_field_key ) {
            console.log( this.form_active_fields[active_field_key].show );
            this.$store.commit( 'toggleActiveFieldCollapseState', active_field_key );
        },

        getToggleState( active_field_key ) {
            console.log( this.form_active_fields[active_field_key].show );
            return this.form_active_fields[active_field_key].show;
        }
    },
}
</script>