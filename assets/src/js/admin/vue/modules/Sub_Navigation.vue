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
          <span
            class="cptm-sub-nav__item-icon"
            v-if="nav.icon && nav.icon_type == 'svg'"
            v-html="nav.icon"
          ></span>
          <span
            class="cptm-sub-nav__item-icon"
            :class="nav.icon"
            v-if="nav.icon && nav.icon_type !== 'svg'"
          ></span>
          {{ nav.label }}
          <span
            class="directorist-row-tooltip cptm-sub-nav__item-tooltip"
            v-if="nav.learn_more"
            :data-tooltip="nav?.learn_more?.description"
            data-flow="bottom-right"
            @click.prevent="openModal(nav.learn_more)"
          >
            <svg
              width="14"
              height="14"
              viewBox="0 0 14 14"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g clip-path="url(#clip0_8183_2901)">
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M7.00004 1.75004C4.10055 1.75004 1.75004 4.10055 1.75004 7.00004C1.75004 9.89954 4.10055 12.25 7.00004 12.25C9.89954 12.25 12.25 9.89954 12.25 7.00004C12.25 4.10055 9.89954 1.75004 7.00004 1.75004ZM0.583374 7.00004C0.583374 3.45621 3.45621 0.583374 7.00004 0.583374C10.5439 0.583374 13.4167 3.45621 13.4167 7.00004C13.4167 10.5439 10.5439 13.4167 7.00004 13.4167C3.45621 13.4167 0.583374 10.5439 0.583374 7.00004ZM6.41671 4.66671C6.41671 4.34454 6.67787 4.08337 7.00004 4.08337H7.00587C7.32804 4.08337 7.58921 4.34454 7.58921 4.66671C7.58921 4.98887 7.32804 5.25004 7.00587 5.25004H7.00004C6.67787 5.25004 6.41671 4.98887 6.41671 4.66671ZM7.00004 6.41671C7.32221 6.41671 7.58337 6.67787 7.58337 7.00004V9.33337C7.58337 9.65554 7.32221 9.91671 7.00004 9.91671C6.67787 9.91671 6.41671 9.65554 6.41671 9.33337V7.00004C6.41671 6.67787 6.67787 6.41671 7.00004 6.41671Z"
                  fill="#747C89"
                />
              </g>
              <defs>
                <clipPath id="clip0_8183_2901">
                  <rect width="14" height="14" fill="white" />
                </clipPath>
              </defs>
            </svg>
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
