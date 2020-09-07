<template>
    <div class="atbdp-cpt-manager">
        <!-- atbdp-cptm-header -->
        <div class="atbdp-cptm-header">
            <headerNavigation />
        </div>

        <!-- atbdp-cptm-body -->
        <div class="atbdp-cptm-body">
            <tabContents />
        </div>

        <div class="atbdp-cptm-footer">
            <hiddenInputFields />
        </div>
    </div>
</template>


<script>
// Headers
import headerNavigation from './Header_Navigation.vue';
import hiddenInputFields from './HiddenInputFields.vue';
import tabContents from './tabs/TabContents.vue';

export default {
    name: 'cpt-manager',
    components: {
        headerNavigation,
        tabContents,
        hiddenInputFields,
    },
    data() {
        return {
            
        }
    },

    methods: {
        
    }
}
</script>