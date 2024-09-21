<template>
  <div class="atbdp-cpt-manager cptm-p-20">
    <!-- atbdp-cptm-header -->
    <div class="atbdp-cptm-header">
      <component
        v-if="options.name && options.name.type"
        :is="options.name.type + '-field'"
        v-bind="options.name"
        @update="updateOptionsField({ field: 'name', value: $event })"
      />

      <headerNavigation />
    </div>

    <!-- atbdp-cptm-body -->
    <div class="atbdp-cptm-body">
      <tabContents />
    </div>
  </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import headerNavigation from "./Header_Navigation.vue";
import tabContents from "./TabContents.vue";

export default {
  name: "cpt-manager",

  components: {
    headerNavigation,
    tabContents,
  },

  computed: {
    ...mapState({
      options: "options",
    }),
  },

  created() {
    if (this.$root.fields) {
      this.$store.commit("updateFields", this.$root.fields);
    }

    if (this.$root.layouts) {
      this.$store.commit("updatelayouts", this.$root.layouts);
    }

    if (this.$root.options) {
      this.$store.commit("updateOptions", this.$root.options);
    }

    if (this.$root.config) {
      this.$store.commit("updateConfig", this.$root.config);
    }
  },

  data() {
    return {
      listing_type_id: null,
    };
  },

  methods: {
    ...mapGetters(["getFieldsValue"]),

    updateOptionsField(payload) {
      if (!payload.field) {
        return;
      }
      if (typeof payload.value === "undefined") {
        return;
      }

      this.$store.commit("updateOptionsField", payload);
    },

    saveData() {
      let options = this.$store.state.options;

      // Get Options Fields Data
      let options_field_list = [];
      for (let field in options) {
        let value = this.maybeJSON(options[field].value);

        form_data.append(field, value);
        options_field_list.push(field);
      }
    },
  },
};
</script>
