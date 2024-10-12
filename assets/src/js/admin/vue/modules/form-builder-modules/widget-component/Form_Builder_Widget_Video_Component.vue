<template>
  <div
      v-if="videoOpened"
      class="video-popup-overlay"
      @click.prevent="$emit('close-video')"
  >
      <div class="video-popup-content" @click.stop>
          <div class="video-container">
              <iframe
                  width="560"
                  height="315"
                  :src="video.url"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
                  :title="video.title"
              ></iframe>
          </div>
      </div>
      <button 
        class="close-btn" 
        @click.prevent="$emit('close-video')"
      >
          <span class="la la-close"></span>
      </button>
  </div>
</template>

<script>
export default {
  name: "form-builder-widget-video-component",
  props: {
    videoOpened: {
      default: false,
    },
    video: {
      type: Object,
    },
  },
};
</script>
