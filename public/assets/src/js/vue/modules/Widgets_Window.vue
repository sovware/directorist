<template>
    <div class="cptm-option-card" :class="mainWrapperClass">
        <div class="cptm-option-card-header">
            <div class="cptm-option-card-header-title-section">
            <h3 class="cptm-option-card-header-title">Insert Element</h3>

            <div class="cptm-header-action-area">
                <a
                href="#"
                class="cptm-header-action-link cptm-header-action-close"
                @click.prevent="$emit( 'close' )"
                >
                <span class="fa fa-times"></span>
                </a>
            </div>
            </div>

            <div class="cptm-option-card-header-nav-section">
            <ul class="cptm-option-card-header-nav">
                <li class="cptm-option-card-header-nav-item active">Preset Field</li>
                <li class="cptm-option-card-header-nav-item">Custom Field</li>
            </ul>
            </div>
        </div>

        <div class="cptm-option-card-body">
            Options
        </div>

        <span class="cptm-anchor-down" v-if="bottomAchhor"></span>
    </div>
</template>

<script>
export default {
    name: 'widgets-window',

    model: {
        prop: 'active',
        event: 'update'
    },

    props: {
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
        availableWidgets: {
            type: Object,
        },
        accepted_widgets: {
            type: Array,
        },
    },

    mounted() {
        
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
            
        }
    },

    methods: {

    },
}
</script>