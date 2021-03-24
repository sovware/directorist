<template>
    <div class="cptm-form-builder-group-field-item-header">
        <h4 class="cptm-title-3">
            <span v-html="label"></span>
            <span v-if="sublabel.length" class="cptm-text-gray cptm-px-5" v-html="sublabel"></span>
        </h4>
        
        <div class="cptm-form-builder-group-field-item-header-actions">
            <a href="#" class="cptm-form-builder-header-action-link" 
                :class="( expanded ) ? 'action-collapse-up' : 'action-collapse-down'"
                @click.prevent="$emit( 'toggle-expand' )"
            >
                <span aria-hidden="true" class="fa fa-angle-up"></span>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    name: 'form-builder-widget-titlebar-component',
    props: {
        label: {
            default: ''
        },
        sublabel: {
            default: ''
        },
        expanded: {
            default: false,
        }
    }
}
</script>