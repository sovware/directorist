<template>
    <div class="cptm-tab-content" :class="containerClass">
        <div class="cptm-section" :class="sectionClass( section )" v-for="( section, section_key ) in theSections" :key="section_key">
            <div class="cptm-title-area" :class="sectionTitleAreaClass( section )">
                <h3 v-if="typeof section.title === 'string'" class="cptm-title" v-html="section.title"></h3>
                <p v-if="typeof section.description === 'string'" v-html="section.description"></p>
            </div>
            
            <div class="cptm-form-fields" v-if="sectionFields( section )">
                <template v-for="( field, field_key ) in sectionFields( section )">
                    <component
                        v-if="fields[ field ]"
                        :is="getFormFieldName( fields[ field ].type )" 
                        :field-id="field"
                        :id="menuKey + '__' + section_key + '__' + field"
                        :ref="field"
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
import Vue from 'vue';
import helpers from './../mixins/helpers';
import { mapState } from 'vuex';

export default {
    name: 'sections-module',
    mixins: [ helpers ],

    props: {
        sections: {
            type: Object
        },
        container: {
            type: String,
            default: '',
        },
        menuKey: {
            type: String,
            default: ''
        },
    },

    mounted() {
        // let element = this.$refs['new_listing_status'];

        // var top = element[0].$el.offsetTop;
        // window.scrollTo(0, 700);
        
        // console.log( { element, top } );

        // console.log( { menuKey: this.menuKey } );
    },

    computed: {
        ...mapState([
            'metaKeys'
        ]),
        theSections() {
            let the_sections = JSON.parse( JSON.stringify( this.sections ) );
            
            for ( let section in the_sections ) {
                if ( ! Array.isArray( the_sections[ section ].fields )) { continue; }
                
                for ( let field of the_sections[ section ].fields ) {

                    let field_index = the_sections[ section ].fields.indexOf( field );
                    
                    
                    if ( typeof this.fields[ field ] === 'undefined' ) {
                        the_sections[ section ].fields.splice( field_index, 1 );
                        continue;
                    }

                    Vue.set( this.fields[ field ], 'hidden', false );

                    if ( ! this.isObject( this.fields[ field ].show_if )  ) {
                        continue;
                    }

                    let show_if_cond = this.checkShowIfCondition({
                        condition: this.fields[ field ].show_if
                    });
                
                    if ( ! show_if_cond.status ) {
                        Vue.set( this.fields[ field ], 'hidden', true );

                        the_sections[ section ].fields.splice( field_index, 1 );
                    }
                }   
            }

            return the_sections;
        },

        containerClass() {
            return {
                'tab-wide': ( 'wide' === this.container ) ? true : false,
                'tab-full-width': ( 'full-width' === this.container ) ? true : false,
            }
        },
    },

    methods: {
        sectionFields( section ) {
            if ( ! this.isObject( section )) { return false; }
            if ( ! Array.isArray( section.fields )) { return false; }

            return section.fields;
        },

        sectionClass( section ) {
            return {
                'cptm-short-wide': ( 'short-width' === section.container ) ? true : false,
            }
        },

        sectionTitleAreaClass( section ) {
            return {
                'cptm-text-center': ( 'center' === section.title_align ) ? true : false,
            }
        }
    },
}
</script>