<template>
  <div
    v-if="modalOpened"
    class="cptm-modal-overlay"
    @click.prevent="$emit('close-modal')"
  >
    <div class="cptm-modal-content" @click.stop>
      <div class="cptm-modal-container">
        <iframe
          v-if="content.type === 'video'"
          width="560"
          height="315"
          :src="content.url"
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen
          :title="content.title"
          class="cptm-modal-video"
        ></iframe>
        <div v-if="content.type === 'image'" class="cptm-modal-image">
          <img
            :src="content.url"
            :alt="content.title"
            class="cptm-modal-image__img"
          />
          <button
            class="cptm-modal-content__close-btn"
            @click.prevent="$emit('close-modal')"
          >
            <span class="la la-close"></span>
          </button>
        </div>
        <div v-if="content.type === 'preview'" class="cptm-modal-preview">
          <!-- Render Placeholder Groups -->
          <div
            v-for="(placeholderItem, index) in placeholders"
            :key="index"
            v-if="placeholderItem.type === 'placeholder_group'"
            class="cptm-modal-preview__group cptm-modal-preview__group--top"
          >
            <div
              v-for="(
                subPlaceholderItem, index
              ) in placeholderItem.placeholders"
              class="cptm-modal-preview__item"
              :class="subPlaceholderItem.placeholder_key"
            >
              <div
                v-for="(
                  selectedWidget, index
                ) in subPlaceholderItem.selectedWidgets"
                :key="`item_${index}`"
                class="cptm-modal-preview__btn"
                :class="selectedWidget.widget_key"
              >
                <span
                  v-if="selectedWidget.icon"
                  :class="selectedWidget.icon"
                ></span>
                {{ selectedWidget.label }}
              </div>
            </div>
          </div>

          <!-- Render Standalone Placeholder Items -->
          <div
            v-for="(placeholderItem, index) in placeholders"
            :key="`standalone_${index}`"
            v-if="placeholderItem.type === 'placeholder_item'"
            class="cptm-modal-preview__item"
            :class="placeholderItem.placeholder_key"
          >
            <div
              v-for="(selectedWidget, index) in placeholderItem.selectedWidgets"
              :key="`group_${index}`"
              class="cptm-modal-preview__btn"
              :class="selectedWidget.widget_key"
            >
              <span
                v-if="selectedWidget.icon"
                class="cptm-modal-preview__btn__icon"
                :class="selectedWidget.icon"
              ></span>
              {{ selectedWidget.label }}
            </div>
          </div>
          <button
            class="cptm-modal-content__close-btn"
            @click.prevent="$emit('close-modal')"
          >
            <span class="la la-close"></span>
          </button>
        </div>
      </div>
    </div>
    <button
      class="close-btn"
      v-if="content.type === 'video'"
      @click.prevent="$emit('close-modal')"
    >
      <span class="la la-close"></span>
    </button>
  </div>
</template>

<script>
export default {
  name: "form-builder-widget-modal-component",
  props: {
    modalOpened: {
      type: Boolean,
      default: false,
    },
    content: {
      type: [Object, Array],
      default: () => [], // Default is an empty array
    },
  },
  computed: {
    placeholders() {
      return this.content || [];
    },
  },
  mounted() {
    // Move modal to body to avoid z-index issues
    this.moveModalToBody();
  },
  updated() {
    // Re-move modal to body when updated
    this.moveModalToBody();
  },
  beforeDestroy() {
    // Clean up when component is destroyed
    this.cleanupModal();
  },
  methods: {
    moveModalToBody() {
      if (this.modalOpened && this.$el) {
        // Check if modal is already in body
        if (this.$el.parentNode !== document.body) {
          document.body.appendChild(this.$el);
        }
      }
    },
    cleanupModal() {
      // Remove modal from body if it exists
      if (this.$el && this.$el.parentNode === document.body) {
        document.body.removeChild(this.$el);
      }
    },
  },
  watch: {
    modalOpened(newVal) {
      if (newVal) {
        // When modal opens, move it to body
        this.$nextTick(() => {
          this.moveModalToBody();
        });
      } else {
        // When modal closes, cleanup
        this.cleanupModal();
      }
    },
  },
};
</script>
