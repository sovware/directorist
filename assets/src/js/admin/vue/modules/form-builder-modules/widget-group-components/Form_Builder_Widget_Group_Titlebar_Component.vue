<template>
    <div class="cptm-form-builder-group-title-area">
        <h3 class="cptm-form-builder-group-title">
            {{ label }}
            <a href="#" class="cptm-form-builder-header-action-link cptm-ml-5 cptm-link-light"
                v-if="groupFields && typeof groupFields === 'object'"
                @click.prevent="$emit('toggle-expand-group')"
            >
                <span class="fa fa-pen" aria-hidden="true"></span>
            </a>
        </h3>

        <div class="cptm-form-builder-group-title-actions">
            <a href="#" class="cptm-form-builder-header-action-link"
                v-if="groupData && groupData.fields && groupData.fields.length"
                :class="( widgetsExpanded ) ? 'action-collapse-up' : 'action-collapse-down'"
                @click.prevent="$emit('toggle-expand-widgets')"
            >
                <span aria-hidden="true" class="fa fa-angle-up"></span>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    name: 'form-builder-widget-group-titlebar-component',
    props: {
        groupData: {
            default: '',
        },
        groupSettings: {
            default: '',
        },
        groupFields: {
            default: '',
        },
        widgetsExpanded: {
            default: false,
        },
    },

    created() {
        // this.setup()
    },

    computed: {

        label() {
            let label = '';

            if ( ! this.groupData.defaultGroupLabel ) {
                label = this.groupData.defaultGroupLabel;
            }

            if ( ! this.groupSettings.label ) {
                label = this.groupData.label;
            }

            return label;
        }
        
    },

    data() {
        return {
            // label: ''
        }
    }
}
</script>