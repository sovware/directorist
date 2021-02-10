<template>
    <div class="cptm-option-card" :class="mainWrapperClass">
        <div class="cptm-option-card-header">
            <div class="cptm-option-card-header-title-section">
                <h3 class="cptm-option-card-header-title">{{ title }}</h3>

                <div class="cptm-header-action-area">
                    <a href="#" class="cptm-header-action-link cptm-header-action-close" @click.prevent="$emit( 'close' )">
                        <span class="fa fa-times"></span>
                    </a>
                </div>
            </div>
        </div>

        <div class="cptm-option-card-body">
            <template v-if="local_fields">
                <template v-for="( field, field_key ) in local_fields">
                    <component 
                        :is="field.type + '-field'" 
                        v-bind="field" 
                        :key="field_key"
                        @update="updateFieldData( $event, field_key )">
                    </component>
                </template>
            </template>
            
        </div>

        <span class="cptm-anchor-down" v-if="bottomAchhor"></span>
    </div>
</template>

<script>
export default {
    name: 'options-window',

    model: {
        prop: 'fields',
        event: 'update'
    },

    props: {
        id: {
            type: [ String, Number],
            default: '',
        },
        title: {
            type: String,
            default: 'Edit',
        },
        fields: {
            type: Object,
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
        
    },

    mounted() {
        this.init();
    },

    watch: {
        fields() {
            if ( this.fields ) {
                this.local_fields = this.fields;
                this.$emit( 'update', this.local_fields );
            }
        }
    },

    computed: {
        mainWrapperClass() {
            return {
                active: this.active,
                [ this.animation ]: true
            }
        }
    },

    data() {
        return {
            local_fields: null,
        }
    },

    methods: {
        init() {
            if ( this.fields ) {
                this.local_fields = this.fields;
            }
        },

        updateFieldData( value, field_key ) {
            this.local_fields[ field_key ].value = value;
            this.$emit( 'update', this.local_fields );
        }
    },
}
</script>