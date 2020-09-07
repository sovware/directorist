<template>
    <div class="atbdp-cptm-tab-contents">
        <template v-for="( tab, index ) in tabs">
            <component :is="tab" :key="index" :index="index"></component>
        </template>
    </div>
</template>

<script>
// Tabs
import genaral_tab from './general/General_Contents.vue';
import listings_card_layout_tab from './Listings_Card_Layout.vue';
import search_forms_tab from './Search_Forms.vue';
import single_page_layout_tab from './Single_Page_Layout.vue';
import submission_form_tab from './Submission_Form.vue';

export default {
    name: 'tab-area',

    data() {
        return {
            tabs: [
                genaral_tab,
                submission_form_tab,
                single_page_layout_tab,
                listings_card_layout_tab,
                search_forms_tab,
            ]
        }
    },
}
</script>