<template>
  <div class="cptm-sub-navigation-wrapper">
    <ul class="cptm-sub-navigation">
      <li
        class="cptm-sub-nav__item"
        v-for="(nav, index) in navLists"
        :key="index"
      >
        <a
          href="#"
          class="cptm-sub-nav__item-link"
          :class="getActiveClass(index, active_nav)"
          @click.prevent="swichNav(index)"
        >
          {{ nav.label }}
          <span
            class="directorist-row-tooltip cptm-sub-nav__item-tooltip"
            v-if="nav.learn_more"
            :data-tooltip="nav?.learn_more?.description"
            data-flow="bottom-right"
            @click.prevent="openModal(nav.learn_more)"
          >
            ?
          </span>
        </a>
      </li>
    </ul>

    <!-- Video Popup Modal -->
    <form-builder-widget-modal-component
      v-if="modalContent"
      :modalOpened="showModal"
      :content="modalContent"
      :type="modalContent.type"
      @close-modal="closeModal"
    />
  </div>
</template>

<script>
import helpers from "./../mixins/helpers";

export default {
  name: "sub-navigation",
  props: ["navLists", "active"],
  props: {
    navLists: Array,
    active: {
      type: Number,
      required: false,
    },
  },
  mixins: [helpers],
  model: {
    prop: "active",
    event: "change",
  },

  data() {
    return {
      active_nav: 0,
      showModal: false,
      modalContent: null,
    };
  },

  methods: {
    swichNav(index) {
      this.active_nav = index;
      this.$emit("change", index);
    },
    openModal(content) {
      if (!content) return; // Prevent setting invalid content
      this.modalContent = content;
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      this.modalContent = null; // Reset content after closing
    },
  },
};
</script>
